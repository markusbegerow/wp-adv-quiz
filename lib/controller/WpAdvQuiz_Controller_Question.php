<?php

class WpAdvQuiz_Controller_Question extends WpAdvQuiz_Controller_Controller
{
    private $_quizId;

    public function route()
    {
		
		$action = filter_input(INPUT_GET,'action',FILTER_SANITIZE_STRING);
		$action = $action ? : 'show';
		
		$quizId = filter_input(INPUT_GET,'quiz_id',FILTER_VALIDATE_INT);
		$quizId = $quizId ? : 0;
		
		$questid = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
		$questid = $questid ? : 0;
		
		if ($quizId == 0) {
            WpAdvQuiz_View_View::admin_notices(__('Quiz not found', 'wp-adv-quiz'), 'error');

            return;
        }

        $this->_quizId = $quizId;


        $m = new WpAdvQuiz_Model_QuizMapper();

        if ($m->exists($this->_quizId) == 0) {
            WpAdvQuiz_View_View::admin_notices(__('Quiz not found', 'wp-adv-quiz'), 'error');

            return;
        }

        switch ($action) {
            case 'show':
                $this->showAction();
                break;
            case 'addEdit':
                $this->addEditQuestion($this->_quizId);
                break;
            case 'delete':
                $this->deleteAction($questid);
                break;
            case 'delete_multi':
                $this->deleteMultiAction();
                break;
            case 'save_sort':
                $this->saveSort();
                break;
            case 'load_question':
                $this->loadQuestion($this->_quizId);
                break;
            case 'copy_question':
                $this->copyQuestion($this->_quizId);
                break;
            default:
                $this->showAction();
                break;
        }
    }

    public function routeAction()
    {
		$action = filter_input(INPUT_GET,'action',FILTER_SANITIZE_STRING);
		$action = $action ? : 'show';

        switch ($action) {
            default:
                $this->showActionHook();
                break;
        }
    }

    private function showActionHook()
    {
		$_wp_http_referer = filter_input(INPUT_GET,'_wp_http_referer',FILTER_SANITIZE_STRING);
		$_wp_http_referer = $_wp_http_referer ? : '';
		
        if ($_wp_http_referer != '') {
            wp_redirect(remove_query_arg(array('_wp_http_referer', '_wpnonce'), wp_unslash($_SERVER['REQUEST_URI'])));
            exit;
        }

        if (!class_exists('WP_List_Table')) {
            require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
        }

        add_filter('manage_' . get_current_screen()->id . '_columns',
            array('WpAdvQuiz_View_QuestionOverallTable', 'getColumnDefs'));

        add_screen_option('per_page', array(
            'label' => __('Questions', 'wp-adv-quiz'),
            'default' => 20,
            'option' => 'wp_adv_quiz_question_overview_per_page'
        ));
    }

    private function addEditQuestion($quizId)
    {
		
		$questionId = filter_input(INPUT_GET,'questionId',FILTER_VALIDATE_INT);
		$questionId = $questionId ? : 0;

        if ($questionId) {
            if (!current_user_can('wpAdvQuiz_edit_quiz')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
        } else {
            if (!current_user_can('wpAdvQuiz_add_quiz')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
        }

        $quizMapper = new WpAdvQuiz_Model_QuizMapper();
        $questionMapper = new WpAdvQuiz_Model_QuestionMapper();
        $cateoryMapper = new WpAdvQuiz_Model_CategoryMapper();
        $templateMapper = new WpAdvQuiz_Model_TemplateMapper();

        if ($questionId && $questionMapper->existsAndWritable($questionId) == 0) {
            WpAdvQuiz_View_View::admin_notices(__('Question not found', 'wp-adv-quiz'), 'error');

            return;
        }

        $question = new WpAdvQuiz_Model_Question();

        if (isset($this->_post['template']) || (isset($this->_post['templateLoad']) && isset($this->_post['templateLoadId']))) {
            if (isset($this->_post['template'])) {
                $template = $this->saveTemplate();
            } else {
                $template = $templateMapper->fetchById($this->_post['templateLoadId']);
            }

            $data = $template->getData();

            if ($data !== null) {
                /** @var WpAdvQuiz_Model_Question $question */
                $question = $data['question'];
                $question->setId($questionId);
                $question->setQuizId($quizId);
            }
        } else {
            if (isset($this->_post['submit'])) {
                if ($questionId) {
                    WpAdvQuiz_View_View::admin_notices(__('Question edited', 'wp-adv-quiz'), 'info');
                } else {
                    WpAdvQuiz_View_View::admin_notices(__('Question added', 'wp-adv-quiz'), 'info');
                }

                $question = $questionMapper->save($this->getPostQuestionModel($quizId, $questionId), true);
                $questionId = $question->getId();

            } else {
                if ($questionId) {
                    $question = $questionMapper->fetch($questionId);
                }
            }
        }

        $view = new WpAdvQuiz_View_QuestionEdit();
        $view->categories = $cateoryMapper->fetchAll();
        $view->quiz = $quizMapper->fetch($quizId);
        $view->templates = $templateMapper->fetchAll(WpAdvQuiz_Model_Template::TEMPLATE_TYPE_QUESTION, false);
        $view->question = $question;
        $view->answerData = $this->setAnswerObject($question);

        $view->header = $questionId ? __('Edit question', 'wp-adv-quiz') : __('New question', 'wp-adv-quiz');

        if ($view->question->isAnswerPointsActivated()) {
            $view->question->setPoints(1);
        }

        $view->show();
    }

    private function saveTemplate()
    {
        $questionModel = $this->getPostQuestionModel(0, 0);

        $templateMapper = new WpAdvQuiz_Model_TemplateMapper();
        $template = new WpAdvQuiz_Model_Template();

        if ($this->_post['templateSaveList'] == '0') {
            $template->setName(trim($this->_post['templateName']));
        } else {
            $template = $templateMapper->fetchById($this->_post['templateSaveList'], false);
        }

        $template->setType(WpAdvQuiz_Model_Template::TEMPLATE_TYPE_QUESTION);

        $template->setData(array(
            'question' => $questionModel
        ));

        return $templateMapper->save($template);
    }

    private function getPostQuestionModel($quizId, $questionId)
    {
        $questionMapper = new WpAdvQuiz_Model_QuestionMapper();

        $post = WpAdvQuiz_Controller_Request::getPost();

        $post['id'] = $questionId;
        $post['quizId'] = $quizId;
        $post['title'] = isset($post['title']) ? trim($post['title']) : '';

        $clearPost = $this->clearPost($post);

        $post['answerData'] = $clearPost['answerData'];

        if (empty($post['title'])) {
            $count = $questionMapper->count($quizId);

            $post['title'] = sprintf(__('Question: %d', 'wp-adv-quiz'), $count + 1);
        }

        if ($post['answerType'] === 'assessment_answer') {
            $post['answerPointsActivated'] = 1;
        }

        if (isset($post['answerPointsActivated'])) {
            if (isset($post['answerPointsDiffModusActivated'])) {
                $post['points'] = $clearPost['maxPoints'];
            } else {
                $post['points'] = $clearPost['points'];
            }
        }

        $post['categoryId'] = $post['category'] > 0 ? $post['category'] : 0;

        return new WpAdvQuiz_Model_Question($post);
    }

    public function copyQuestion($quizId)
    {

        if (!current_user_can('wpAdvQuiz_edit_quiz')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $m = new WpAdvQuiz_Model_QuestionMapper();

        $questions = $m->fetchById($this->_post['copyIds']);

        foreach ($questions as $question) {
            $question->setId(0);
            $question->setQuizId($quizId);

            $m->save($question);
        }

        WpAdvQuiz_View_View::admin_notices(__('questions copied', 'wp-adv-quiz'), 'info');

        $this->showAction();
    }

    public function loadQuestion($quizId)
    {

        if (!current_user_can('wpAdvQuiz_edit_quiz')) {
            echo json_encode(array());
            exit;
        }

        $quizMapper = new WpAdvQuiz_Model_QuizMapper();
        $questionMapper = new WpAdvQuiz_Model_QuestionMapper();
        $data = array();

        $quiz = $quizMapper->fetchAll();

        foreach ($quiz as $qz) {

            if ($qz->getId() == $quizId) {
                continue;
            }

            $question = $questionMapper->fetchAll($qz->getId());
            $questionArray = array();

            foreach ($question as $qu) {
                $questionArray[] = array(
                    'name' => $qu->getTitle(),
                    'id' => $qu->getId()
                );
            }

            $data[] = array(
                'name' => $qz->getName(),
                'id' => $qz->getId(),
                'question' => $questionArray
            );
        }

        echo json_encode($data);

        exit;
    }

    public function saveSort()
    {

        if (!current_user_can('wpAdvQuiz_edit_quiz')) {
            exit;
        }

        $mapper = new WpAdvQuiz_Model_QuestionMapper();
        $map = $this->_post['sort'];

        foreach ($map as $k => $v) {
            $mapper->updateSort($v, $k);
        }

        exit;
    }

    private function deleteAction($id)
    {

        if (!current_user_can('wpAdvQuiz_delete_quiz')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $mapper = new WpAdvQuiz_Model_QuestionMapper();
        $mapper->setOnlineOff($id);

        $this->showAction();
    }

    public function deleteMultiAction()
    {
        if (!current_user_can('wpAdvQuiz_delete_quiz')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $mapper = new WpAdvQuiz_Model_QuestionMapper();
	
		$ids = filter_input(INPUT_POST,'ids',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
		$ids = !empty($ids) ? $ids : null;

        if (!empty($ids)) {
            foreach ($ids as $id) {
                $mapper->setOnlineOff($id);
            }
        }

        $this->showAction();
    }

    private function setAnswerObject(WpAdvQuiz_Model_Question $question = null)
    {
        //Defaults
        $data = array(
            'sort_answer' => array(new WpAdvQuiz_Model_AnswerTypes()),
            'classic_answer' => array(new WpAdvQuiz_Model_AnswerTypes()),
            'matrix_sort_answer' => array(new WpAdvQuiz_Model_AnswerTypes()),
            'cloze_answer' => array(new WpAdvQuiz_Model_AnswerTypes()),
            'free_answer' => array(new WpAdvQuiz_Model_AnswerTypes()),
            'assessment_answer' => array(new WpAdvQuiz_Model_AnswerTypes())
        );

        if ($question !== null) {
            $type = $question->getAnswerType();
            $type = ($type == 'single' || $type == 'multiple') ? 'classic_answer' : $type;
            $answerData = $question->getAnswerData();

            if (isset($data[$type]) && $answerData !== null) {
                $data[$type] = $question->getAnswerData();
            }
        }

        return $data;
    }

    public function clearPost($post)
    {

        if ($post['answerType'] == 'cloze_answer' && isset($post['answerData']['cloze'])) {
            preg_match_all('#\{(.*?)(?:\|(\d+))?(?:[\s]+)?\}#im', $post['answerData']['cloze']['answer'], $matches);

            $points = 0;
            $maxPoints = 0;

            foreach ($matches[2] as $match) {
                if (empty($match)) {
                    $match = 1;
                }

                $points += $match;
                $maxPoints = max($maxPoints, $match);
            }

            return array(
                'points' => $points,
                'maxPoints' => $maxPoints,
                'answerData' => array(new WpAdvQuiz_Model_AnswerTypes($post['answerData']['cloze']))
            );
        }

        if ($post['answerType'] == 'assessment_answer' && isset($post['answerData']['assessment'])) {
            preg_match_all('#\{(.*?)\}#im', $post['answerData']['assessment']['answer'], $matches);

            $points = 0;
            $maxPoints = 0;

            foreach ($matches[1] as $match) {
                preg_match_all('#\[([^\|\]]+)(?:\|(\d+))?\]#im', $match, $ms);

                $points += count($ms[1]);
                $maxPoints = max($maxPoints, count($ms[1]));
            }

            return array(
                'points' => $points,
                'maxPoints' => $maxPoints,
                'answerData' => array(new WpAdvQuiz_Model_AnswerTypes($post['answerData']['assessment']))
            );
        }

        unset($post['answerData']['cloze']);
        unset($post['answerData']['assessment']);

        if (isset($post['answerData']['none'])) {
            unset($post['answerData']['none']);
        }

        $answerData = array();
        $points = 0;
        $maxPoints = 0;

        foreach ($post['answerData'] as $k => $v) {
            if (trim($v['answer']) == '') {
                if ($post['answerType'] != 'matrix_sort_answer') {
                    continue;
                } else {
                    if (trim($v['sort_string']) == '') {
                        continue;
                    }
                }
            }

            $answerType = new WpAdvQuiz_Model_AnswerTypes($v);
            $points += $answerType->getPoints();

            $maxPoints = max($maxPoints, $answerType->getPoints());

            $answerData[] = $answerType;
        }

        return array('points' => $points, 'maxPoints' => $maxPoints, 'answerData' => $answerData);
    }

    public function clear($a)
    {
        foreach ($a as $k => $v) {
            if (is_array($v)) {
                $a[$k] = $this->clear($a[$k]);
            }

            if (is_string($a[$k])) {
                $a[$k] = trim($a[$k]);

                if ($a[$k] != '') {
                    continue;
                }
            }

            if (empty($a[$k])) {
                unset($a[$k]);
            }
        }

        return $a;
    }

    private function getCurrentPage()
    {
		$pagenum = filter_input(INPUT_GET,'paged',FILTER_VALIDATE_INT);
		$pagenum = absint($pagenum) ? : 0;

        return max(1, $pagenum);
    }

    protected function getExportFormats()
    {
        $helper = new WpAdvQuiz_Helper_QuestionExport();

        return $helper->getSupportedExportFormats();
    }

    protected function getImportFormats()
    {
        $helper = new WpAdvQuiz_Helper_QuestionImport();
        $helper->getSupportedFileExtensions();

        $extensions = [];
        $accept = [];

        foreach ($helper->getSupportedFileExtensions() as $extension) {
            $extensions[] = '*.' . $extension;
            $accept[] = '.'.$extension;
        }

//        foreach ($helper->getSupportedTypes() as $type) {
//            $accept[] = $type;
//        }

        return ['extensions' => $extensions, 'accept' => $accept];
    }

    public function showAction()
    {
        if (!current_user_can('wpAdvQuiz_show')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $m = new WpAdvQuiz_Model_QuizMapper();
        $mm = new WpAdvQuiz_Model_QuestionMapper();
        $categoryMapper = new WpAdvQuiz_Model_CategoryMapper();

        $view = new WpAdvQuiz_View_QuestionOverall();
        $view->quiz = $m->fetch($this->_quizId);

        $per_page = (int)get_user_option('wp_adv_quiz_question_overview_per_page');
        if (empty($per_page) || $per_page < 1) {
            $per_page = 20;
        }

        $current_page = $this->getCurrentPage();
 
		$search = filter_input(INPUT_GET,'s',FILTER_SANITIZE_STRING);
		$search = $search ? : '';
		
		$orderBy = filter_input(INPUT_GET,'orderby',FILTER_SANITIZE_STRING);
		$orderBy = $orderBy ? : '';
		
		$order = filter_input(INPUT_GET,'order',FILTER_SANITIZE_STRING);
		$order = $order ? : '';
		
		$cat = filter_input(INPUT_GET,'cat',FILTER_SANITIZE_STRING);
		$cat = $cat ? : '';
		
        $offset = ($current_page - 1) * $per_page;
        $limit = $per_page;
        $filter = array();

        if ($cat != '') {
            $filter['cat'] = $cat;
        }
	

        $result = $mm->fetchTable($this->_quizId, $orderBy, $order, $search, $limit, $offset, $filter);

        $view->questionItems = $result['questions'];
        $view->questionCount = $result['count'];
        $view->categoryItems = $categoryMapper->fetchAll(WpAdvQuiz_Model_Category::CATEGORY_TYPE_QUESTION);
        $view->perPage = $per_page;
        $view->exportFormats = $this->getExportFormats();
        $view->importFormats = $this->getImportFormats();

        $view->show();
    }

    public static function ajaxSetQuestionMultipleCategories($data)
    {
        if (!current_user_can('wpAdvQuiz_edit_quiz')) {
            return json_encode(array());
        }

        $quizMapper = new WpAdvQuiz_Model_QuestionMapper();

        $quizMapper->setMultipeCategories($data['questionIds'], $data['categoryId']);

        return json_encode(array());
    }

    public static function ajaxLoadQuestionsSort($data)
    {
        if (!current_user_can('wpAdvQuiz_edit_quiz')) {
            return json_encode(array());
        }

        $quizMapper = new WpAdvQuiz_Model_QuestionMapper();

        $questions = $quizMapper->fetchAllList($data['quizId'], array('id', 'title'), true);

        return json_encode($questions);
    }

    public static function ajaxSaveSort($data)
    {
        if (!current_user_can('wpAdvQuiz_edit_quiz')) {
            return json_encode(array());
        }

        $mapper = new WpAdvQuiz_Model_QuestionMapper();

        foreach ($data['sort'] as $k => $v) {
            $mapper->updateSort($v, $k);
        }

        return json_encode(array());
    }

    public static function ajaxLoadCopyQuestion($data)
    {
        if (!current_user_can('wpAdvQuiz_edit_quiz')) {
            echo json_encode(array());
            exit;
        }

        $quizId = $data['quizId'];
        $quizMapper = new WpAdvQuiz_Model_QuizMapper();
        $questionMapper = new WpAdvQuiz_Model_QuestionMapper();
        $data = array();

        $quiz = $quizMapper->fetchAll();

        foreach ($quiz as $qz) {

            if ($qz->getId() == $quizId) {
                continue;
            }

            $question = $questionMapper->fetchAll($qz->getId());
            $questionArray = array();

            foreach ($question as $qu) {
                $questionArray[] = array(
                    'name' => $qu->getTitle(),
                    'id' => $qu->getId()
                );
            }

            $data[] = array(
                'name' => $qz->getName(),
                'id' => $qz->getId(),
                'question' => $questionArray
            );
        }

        return json_encode($data);
    }
}
