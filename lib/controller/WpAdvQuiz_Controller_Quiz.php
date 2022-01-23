<?php

class WpAdvQuiz_Controller_Quiz extends WpAdvQuiz_Controller_Controller
{
    public function route()
    {			
        $action = filter_input(INPUT_GET,'action',FILTER_SANITIZE_STRING);
		$action = $action ? : 'show';
		
		
		$quizId = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
		$quizId = $quizId ? : 0;

        switch ($action) {
            case 'show':
                $this->showAction();
                break;
            case 'addEdit':
                $this->addEditQuiz();
                break;
            case 'delete':
                if ($quizId > 0) {
                    $this->deleteAction($quizId);
                }
                break;
            case 'deleteMulti':
                $this->deleteMultiAction();
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
            array('WpAdvQuiz_View_QuizOverallTable', 'getColumnDefs'));

        add_screen_option('per_page', array(
            'label' => __('Quiz', 'wp-adv-quiz'),
            'default' => 20,
            'option' => 'wp_adv_quiz_quiz_overview_per_page'
        ));
    }

    private function addEditQuiz()
    {
		
		$quizId = filter_input(INPUT_GET,'quizId',FILTER_VALIDATE_INT);
		$quizId = $quizId ? : 0;

        if ($quizId) {
            if (!current_user_can('wpAdvQuiz_edit_quiz')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
        } else {
            if (!current_user_can('wpAdvQuiz_add_quiz')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
        }

        $prerequisiteMapper = new WpAdvQuiz_Model_PrerequisiteMapper();
        $quizMapper = new WpAdvQuiz_Model_QuizMapper();
        $formMapper = new WpAdvQuiz_Model_FormMapper();
        $templateMapper = new WpAdvQuiz_Model_TemplateMapper();
        $cateoryMapper = new WpAdvQuiz_Model_CategoryMapper();

        $quiz = new WpAdvQuiz_Model_Quiz();
        $forms = null;
        $prerequisiteQuizList = array();

        if ($quizId && $quizMapper->exists($quizId) == 0) {
            WpAdvQuiz_View_View::admin_notices(__('Quiz not found', 'wp-adv-quiz'), 'error');

            return;
        }

        if (isset($this->_post['template']) || (isset($this->_post['templateLoad']) && isset($this->_post['templateLoadId']))) {
            if (isset($this->_post['template'])) {
                $template = $this->saveTemplate();
            } else {
                $template = $templateMapper->fetchById($this->_post['templateLoadId']);
            }

            $data = $template->getData();

            if ($data !== null) {
                /** @var WpAdvQuiz_Model_Quiz $quiz */
                $quiz = $data['quiz'];
                $quiz->setId($quizId);

                $forms = $data['forms'];
                $prerequisiteQuizList = $data['prerequisiteQuizList'];
            }
        } else {
            if (isset($this->_post['submit'])) {

                if (isset($this->_post['resultGradeEnabled'])) {
                    $this->_post['result_text'] = $this->filterResultTextGrade($this->_post);
                }

                $this->_post['categoryId'] = $this->_post['category'] > 0 ? $this->_post['category'] : 0;

                $this->_post['adminEmail'] = new WpAdvQuiz_Model_Email($this->_post['adminEmail']);
                $this->_post['userEmail'] = new WpAdvQuiz_Model_Email($this->_post['userEmail']);

                $quiz = new WpAdvQuiz_Model_Quiz($this->_post);
                $quiz->setId($quizId);

                if (isset($this->_post['plugin'])) {
                    $quiz->getPluginContainer()->set($this->_post['plugin']);
                }

                if ($this->checkValidit($this->_post)) {
                    if ($quizId) {
                        WpAdvQuiz_View_View::admin_notices(__('Quiz edited', 'wp-adv-quiz'), 'info');
                    } else {
                        WpAdvQuiz_View_View::admin_notices(__('quiz created', 'wp-adv-quiz'), 'info');
                    }

                    $quizMapper->save($quiz);

                    $quizId = $quiz->getId();

                    $prerequisiteMapper->delete($quizId);

                    if ($quiz->isPrerequisite() && !empty($this->_post['prerequisiteList'])) {
                        $prerequisiteMapper->save($quizId, $this->_post['prerequisiteList']);
                        $quizMapper->activateStatitic($this->_post['prerequisiteList'], 1440);
                    }

                    if (!$this->formHandler($quiz->getId(), $this->_post)) {
                        $quiz->setFormActivated(false);
                        $quizMapper->save($quiz);
                    }

                    $forms = $formMapper->fetch($quizId);
                    $prerequisiteQuizList = $prerequisiteMapper->fetchQuizIds($quizId);

                } else {
                    WpAdvQuiz_View_View::admin_notices(__('Quiz title or quiz description are not filled',
                        'wp-adv-quiz'));
                }
            } else {
                if ($quizId) {
                    $quiz = $quizMapper->fetch($quizId);
                    $forms = $formMapper->fetch($quizId);
                    $prerequisiteQuizList = $prerequisiteMapper->fetchQuizIds($quizId);
                }
            }
        }

        $view = new WpAdvQuiz_View_QuizEdit();

        $view->quiz = $quiz;
        $view->forms = $forms;
        $view->prerequisiteQuizList = $prerequisiteQuizList;
        $view->templates = $templateMapper->fetchAll(WpAdvQuiz_Model_Template::TEMPLATE_TYPE_QUIZ, false);
        $view->quizList = $quizMapper->fetchAllAsArray(array('id', 'name'), $quizId ? array($quizId) : array());
        $view->captchaIsInstalled = class_exists('ReallySimpleCaptcha');
        $view->categories = $cateoryMapper->fetchAll(WpAdvQuiz_Model_Category::CATEGORY_TYPE_QUIZ);

        $view->header = $quizId ? __('Edit quiz', 'wp-adv-quiz') : __('Create quiz', 'wp-adv-quiz');

        $view->show();
    }

    public function isLockQuiz()
    {
        $quizId = (int)$this->_post['quizId'];
        $userId = get_current_user_id();
        $data = array();

        $lockMapper = new WpAdvQuiz_Model_LockMapper();
        $quizMapper = new WpAdvQuiz_Model_QuizMapper();
        $prerequisiteMapper = new WpAdvQuiz_Model_PrerequisiteMapper();

        $quiz = $quizMapper->fetch($this->_post['quizId']);

        if ($quiz === null || $quiz->getId() <= 0) {
            return null;
        }

        if ($this->isPreLockQuiz($quiz)) {
            $lockIp = $lockMapper->isLock($this->_post['quizId'], $this->getIp(), $userId,
                WpAdvQuiz_Model_Lock::TYPE_QUIZ);
            $lockCookie = false;
            $cookieTime = $quiz->getQuizRunOnceTime();

            if (isset($this->_cookie['wpAdvQuiz_lock']) && $userId == 0 && $quiz->isQuizRunOnceCookie()) {
                $cookieJson = json_decode($this->_cookie['wpAdvQuiz_lock'], true);

                if ($cookieJson !== false) {
                    if (isset($cookieJson[$this->_post['quizId']]) && $cookieJson[$this->_post['quizId']] == $cookieTime) {
                        $lockCookie = true;
                    }
                }
            }

            $data['lock'] = array(
                'is' => ($lockIp || $lockCookie),
                'pre' => true
            );
        }

        if ($quiz->isPrerequisite()) {
            $quizIds = array();

            if ($userId > 0) {
                $quizIds = $prerequisiteMapper->getNoPrerequisite($quizId, $userId);
            } else {
                $checkIds = $prerequisiteMapper->fetchQuizIds($quizId);

                if (isset($this->_cookie['wpAdvQuiz_result'])) {
                    $r = json_decode($this->_cookie['wpAdvQuiz_result'], true);

                    if ($r !== null && is_array($r)) {
                        foreach ($checkIds as $id) {
                            if (!isset($r[$id]) || !$r[$id]) {
                                $quizIds[] = $id;
                            }
                        }
                    }
                } else {
                    $quizIds = $checkIds;
                }
            }

            if (!empty($quizIds)) {
                $names = $quizMapper->fetchCol($quizIds, 'name');

                if (!empty($names)) {
                    $data['prerequisite'] = implode(', ', $names);
                }
            }

        }

        if ($quiz->isStartOnlyRegisteredUser()) {
            $data['startUserLock'] = (int)!is_user_logged_in();
        }

        return $data;
    }

    private function getCurrentPage()
    {
		
		$pagenum = filter_input(INPUT_GET,'paged',FILTER_VALIDATE_INT);
		$pagenum = absint($pagenum) ? : 0;

        return max(1, $pagenum);
    }


    protected function getSupportedExportFormats()
    {
        $helper = new WpAdvQuiz_Helper_QuizExport();

        return $helper->getSupportedExportFormats();
    }

    protected function getSupportedImportFormats()
    {
        $helper = new WpAdvQuiz_Helper_QuizImport();

        $extensions = [];
        $accept = [];

        foreach ($helper->getSupportedFileExtensions() as $extension) {
            $extensions[] = '*.' . $extension;
            $accept[] = '.'.$extension;
        }

        return ['extensions' => $extensions, 'accept' => $accept];
    }

    private function showAction()
    {
        if (!current_user_can('wpAdvQuiz_show')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $view = new WpAdvQuiz_View_QuizOverall();

        $m = new WpAdvQuiz_Model_QuizMapper();
        $categoryMapper = new WpAdvQuiz_Model_CategoryMapper();

        $per_page = (int)get_user_option('wp_adv_quiz_quiz_overview_per_page');
        if (empty($per_page) || $per_page < 1) {
            $per_page = 20;
        }

        $current_page = $this->getCurrentPage();

		$search = filter_input(INPUT_GET,'s',FILTER_SANITIZE_STRING);
		$search = $search ? : '';
		
		$orderBy = filter_input(INPUT_GET,'orderBy',FILTER_SANITIZE_STRING);
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

        $result = $m->fetchTable($orderBy, $order, $search, $limit, $offset, $filter);

        $view->quizItems = $result['quiz'];
        $view->quizCount = $result['count'];
        $view->categoryItems = $categoryMapper->fetchAll(WpAdvQuiz_Model_Category::CATEGORY_TYPE_QUIZ);;
        $view->perPage = $per_page;
        $view->supportedExportFormats = $this->getSupportedExportFormats();
        $view->supportedImportFormats = $this->getSupportedImportFormats();

        $view->show();
    }

    private function saveTemplate()
    {
        $templateMapper = new WpAdvQuiz_Model_TemplateMapper();

        if (isset($this->_post['resultGradeEnabled'])) {
            $this->_post['result_text'] = $this->filterResultTextGrade($this->_post);
        }

        $this->_post['categoryId'] = $this->_post['category'] > 0 ? $this->_post['category'] : 0;

        $this->_post['adminEmail'] = new WpAdvQuiz_Model_Email($this->_post['adminEmail']);
        $this->_post['userEmail'] = new WpAdvQuiz_Model_Email($this->_post['userEmail']);

        $quiz = new WpAdvQuiz_Model_Quiz($this->_post);

        if ($quiz->isPrerequisite() && !empty($this->_post['prerequisiteList']) && !$quiz->isStatisticsOn()) {
            $quiz->setStatisticsOn(true);
            $quiz->setStatisticsIpLock(1440);
        }

        $form = $this->_post['form'];

        unset($form[0]);

        $forms = array();

        foreach ($form as $f) {
            $f['fieldname'] = trim($f['fieldname']);

            if (empty($f['fieldname'])) {
                continue;
            }

            if ((int)$f['form_id'] && (int)$f['form_delete']) {
                continue;
            }

            if ($f['type'] == WpAdvQuiz_Model_Form::FORM_TYPE_SELECT || $f['type'] == WpAdvQuiz_Model_Form::FORM_TYPE_RADIO) {
                if (!empty($f['data'])) {
                    $items = explode("\n", $f['data']);
                    $f['data'] = array();

                    foreach ($items as $item) {
                        $item = trim($item);

                        if (!empty($item)) {
                            $f['data'][] = $item;
                        }
                    }
                }
            }

            if (empty($f['data']) || !is_array($f['data'])) {
                $f['data'] = null;
            }

            $forms[] = new WpAdvQuiz_Model_Form($f);
        }

        WpAdvQuiz_View_View::admin_notices(__('Template stored', 'wp-adv-quiz'), 'info');

        $data = array(
            'quiz' => $quiz,
            'forms' => $forms,
            'prerequisiteQuizList' => isset($this->_post['prerequisiteList']) ? $this->_post['prerequisiteList'] : array()
        );

        $template = new WpAdvQuiz_Model_Template();

        if ($this->_post['templateSaveList'] == '0') {
            $template->setName(trim($this->_post['templateName']));
        } else {
            $template = $templateMapper->fetchById($this->_post['templateSaveList'], false);
        }

        $template->setType(WpAdvQuiz_Model_Template::TEMPLATE_TYPE_QUIZ);
        $template->setData($data);

        $templateMapper->save($template);

        return $template;
    }

    private function formHandler($quizId, $post)
    {
        if (!isset($post['form'])) {
            return false;
        }

        $form = $post['form'];

        unset($form[0]);

        if (empty($form)) {
            return false;
        }

        $formMapper = new WpAdvQuiz_Model_FormMapper();

        $deleteIds = array();
        $forms = array();
        $sort = 0;

        foreach ($form as $f) {
            $f['fieldname'] = trim($f['fieldname']);

            if (empty($f['fieldname'])) {
                continue;
            }

            if ((int)$f['form_id'] && (int)$f['form_delete']) {
                $deleteIds[] = (int)$f['form_id'];
                continue;
            }

            $f['sort'] = $sort++;
            $f['quizId'] = $quizId;

            if ($f['type'] == WpAdvQuiz_Model_Form::FORM_TYPE_SELECT || $f['type'] == WpAdvQuiz_Model_Form::FORM_TYPE_RADIO) {
                if (!empty($f['data'])) {
                    $items = explode("\n", $f['data']);
                    $f['data'] = array();

                    foreach ($items as $item) {
                        $item = trim($item);

                        if (!empty($item)) {
                            $f['data'][] = $item;
                        }
                    }
                }
            }

            if (empty($f['data']) || !is_array($f['data'])) {
                $f['data'] = null;
            }

            $forms[] = new WpAdvQuiz_Model_Form($f);
        }

        if (!empty($deleteIds)) {
            $formMapper->deleteForm($deleteIds, $quizId);
        }

        $formMapper->update($forms);

        return !empty($forms);
    }

    private function deleteAction($id)
    {
		
        if (!current_user_can('wpAdvQuiz_delete_quiz') ) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $m = new WpAdvQuiz_Model_QuizMapper();

        $m->deleteAll($id);

        WpAdvQuiz_View_View::admin_notices(__('Quiz deleted', 'wp-adv-quiz'), 'info');

        $this->showAction();
    }

    private function deleteMultiAction()
    {
        if (!current_user_can('wpAdvQuiz_delete_quiz')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $m = new WpAdvQuiz_Model_QuizMapper();

		$ids = filter_input(INPUT_POST,'ids',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
		$ids = !empty($ids) ? $ids : null;
		
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $m->deleteAll($id);
            }
        }

        WpAdvQuiz_View_View::admin_notices(__('Quiz deleted', 'wp-adv-quiz'), 'info');

        $this->showAction();
    }

    private function checkValidit($post)
    {
        return (isset($post['name']) && !empty($post['name']) && isset($post['text']) && !empty($post['text']));
    }

    private function filterResultTextGrade($post)
    {
        $activ = array_keys($post['resultTextGrade']['activ'], '1');
        $result = array();

        foreach ($activ as $k) {
            $result['text'][] = $post['resultTextGrade']['text'][$k];
            $result['prozent'][] = (float)str_replace(',', '.', $post['resultTextGrade']['prozent'][$k]);
        }

        return $result;
    }

    private function setResultCookie(WpAdvQuiz_Model_Quiz $quiz)
    {
        $prerequisite = new WpAdvQuiz_Model_PrerequisiteMapper();

        if (get_current_user_id() == 0 && $prerequisite->isQuizId($quiz->getId())) {
            $cookieData = array();

            if (isset($this->_cookie['wpAdvQuiz_result'])) {
                $d = json_decode($this->_cookie['wpAdvQuiz_result'], true);

                if ($d !== null && is_array($d)) {
                    $cookieData = $d;
                }
            }

            $cookieData[$quiz->getId()] = 1;

            $url = parse_url(get_bloginfo('url'));

            setcookie('wpAdvQuiz_result', json_encode($cookieData), time() + 60 * 60 * 24 * 300,
                empty($url['path']) ? '/' : $url['path']);
        }
    }

    public function isPreLockQuiz(WpAdvQuiz_Model_Quiz $quiz)
    {
        $userId = get_current_user_id();

        if ($quiz->isQuizRunOnce()) {
            switch ($quiz->getQuizRunOnceType()) {
                case WpAdvQuiz_Model_Quiz::QUIZ_RUN_ONCE_TYPE_ALL:
                    return true;
                case WpAdvQuiz_Model_Quiz::QUIZ_RUN_ONCE_TYPE_ONLY_USER:
                    return $userId > 0;
                case WpAdvQuiz_Model_Quiz::QUIZ_RUN_ONCE_TYPE_ONLY_ANONYM:
                    return $userId == 0;
            }
        }

        return false;
    }

    private function getIp()
    {
        if (get_current_user_id() > 0) {
            return '0';
        } else {
            return filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
        }
    }

    /**
     * @param WpAdvQuiz_Model_Quiz $quiz
     * @param $result
     * @param WpAdvQuiz_Model_Category[] $categories
     * @param WpAdvQuiz_Model_Form[] $forms
     * @param $inputForms
     */
    private function emailNote(WpAdvQuiz_Model_Quiz $quiz, $result, $categories, $forms, $inputForms)
    {
        $user = wp_get_current_user();

        $r = array(
            '$userId' => $user->ID,
            '$username' => $user->display_name,
            '$quizname' => $quiz->getName(),
            '$result' => $result['result'] . '%',
            '$points' => $result['points'],
            '$ip' => filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP),
            '$categories' => empty($result['cats']) ? '' : $this->setCategoryOverview($result['cats'], $categories)
        );

        if ($quiz->isFormActivated() && $forms !== null) {
            foreach ($forms as $form) {
                $value = '';

                if ($form->getType() == WpAdvQuiz_Model_Form::FORM_TYPE_DATE) {
                    if (isset($inputForms[$form->getFormId()])) {
                        $value = $inputForms[$form->getFormId()]['day'] . '-' . $inputForms[$form->getFormId()]['month']
                            . '-' . $inputForms[$form->getFormId()]['year'];
                    }
                } else {
                    $value = isset($inputForms[$form->getFormId()]) ? $inputForms[$form->getFormId()] : '';
                }

                $r['$form{' . $form->getSort() . '}'] = esc_html($value);
            }
        }

        if ($user->ID == 0) {
            $r['$username'] = $r['$ip'];
        }

        if ($quiz->isUserEmailNotification()) {
            $userEmail = $quiz->getUserEmail();

            $userAdress = null;

            if ($userEmail->isToUser() && get_current_user_id() > 0) {
                $userAdress = $user->user_email;
            } else {
                if ($userEmail->isToForm() && $quiz->isFormActivated()) {
                    foreach ($forms as $form) {
                        if ($form->getSort() == $userEmail->getTo()) {
                            if (isset($inputForms[$form->getFormId()])) {
                                $userAdress = $inputForms[$form->getFormId()];
                            }

                            break;
                        }
                    }
                }
            }

            if (!empty($userAdress) && filter_var($userAdress, FILTER_VALIDATE_EMAIL) !== false) {
                $msg = str_replace(array_keys($r), $r, $userEmail->getMessage());

                $headers = '';
                $email = $userEmail->getFrom();

                if (!empty($email)) {
                    $headers = 'From: ' . $userEmail->getFrom();
                }

                if ($userEmail->isHtml()) {
                    add_filter('wp_mail_content_type', array($this, 'htmlEmailContent'));
                }

                wp_mail($userAdress, $userEmail->getSubject(), $msg, $headers);

                if ($userEmail->isHtml()) {
                    remove_filter('wp_mail_content_type', array($this, 'htmlEmailContent'));
                }
            }
        }

        if ($quiz->getEmailNotification() == WpAdvQuiz_Model_Quiz::QUIZ_EMAIL_NOTE_ALL
            || (get_current_user_id() > 0 && $quiz->getEmailNotification() == WpAdvQuiz_Model_Quiz::QUIZ_EMAIL_NOTE_REG_USER)
        ) {

            $adminEmail = $quiz->getAdminEmail();

            $msg = str_replace(array_keys($r), $r, $adminEmail->getMessage());

            $headers = '';
            $email = $adminEmail->getFrom();

            if (!empty($email)) {
                $headers = 'From: ' . $adminEmail->getFrom();
            }

            if ($adminEmail->isHtml()) {
                add_filter('wp_mail_content_type', array($this, 'htmlEmailContent'));
            }

            wp_mail($adminEmail->getTo(), $adminEmail->getSubject(), $msg, $headers);

            if ($adminEmail->isHtml()) {
                remove_filter('wp_mail_content_type', array($this, 'htmlEmailContent'));
            }
        }
    }

    public function htmlEmailContent()
    {
        return 'text/html';
    }

    private function setCategoryOverview($catArray, $categories)
    {
        $cats = array();

        foreach ($categories as $cat) {
            /* @var $cat WpAdvQuiz_Model_Category */

            if (!$cat->getCategoryId()) {
                $cat->setCategoryName(__('Not categorized', 'wp-adv-quiz'));
            }

            $cats[$cat->getCategoryId()] = $cat->getCategoryName();
        }

        $a = __('Categories', 'wp-adv-quiz') . ":\n";

        foreach ($catArray as $id => $value) {
            if (!isset($cats[$id])) {
                continue;
            }

            $a .= '* ' . str_pad($cats[$id], 35, '.') . ((float)$value) . "%\n";
        }

        return $a;
    }

    public static function ajaxSetQuizMultipleCategories($data)
    {
        if (!current_user_can('wpAdvQuiz_edit_quiz')) {
            return json_encode(array());
        }

        $quizMapper = new WpAdvQuiz_Model_QuizMapper();

        $quizMapper->setMultipeCategories($data['quizIds'], $data['categoryId']);

        return json_encode(array());
    }

    public static function ajaxLoadQuizData($data)
    {
        $quizId = (int)$data['quizId'];

        $quizMapper = new WpAdvQuiz_Model_QuizMapper();
        $toplistController = new WpAdvQuiz_Controller_Toplist();
        $statisticController = new WpAdvQuiz_Controller_Statistics();

        $quiz = $quizMapper->fetch($quizId);
        $data = array();

        if ($quiz === null || $quiz->getId() <= 0) {
            return json_encode(array());
        }

        $data['toplist'] = $toplistController->getAddToplist($quiz);
        $data['averageResult'] = $statisticController->getAverageResult($quizId);

        return json_encode($data);
    }

    public static function ajaxQuizCheckLock()
    {
        // workaround ...
		
		$data = filter_input(INPUT_POST,'data',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
		$data = !empty($data) ? $data : null;
		
        $_POST = $data;

        $quizController = new WpAdvQuiz_Controller_Quiz();

        return json_encode($quizController->isLockQuiz());
    }

    public static function ajaxResetLock($data)
    {
        if (!current_user_can('wpAdvQuiz_edit_quiz')) {
            return json_encode(array());
        }

        $quizId = (int)$data['quizId'];

        $lm = new WpAdvQuiz_Model_LockMapper();
        $qm = new WpAdvQuiz_Model_QuizMapper();

        $q = $qm->fetch($quizId);

        if ($q->getId() > 0) {
            $q->setQuizRunOnceTime(time());

            $qm->save($q);

            $lm->deleteByQuizId($quizId, WpAdvQuiz_Model_Lock::TYPE_QUIZ);
        }

        return json_encode(array());
    }

    public static function ajaxCompletedQuiz($data)
    {
        // workaround ...
		
		$data = filter_input(INPUT_POST,'data',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
		$data = !empty($data) ? $data : null;
		
        $_POST = $data;

        $ctr = new WpAdvQuiz_Controller_Quiz();

        $lockMapper = new WpAdvQuiz_Model_LockMapper();
        $quizMapper = new WpAdvQuiz_Model_QuizMapper();
        $categoryMapper = new WpAdvQuiz_Model_CategoryMapper();
        $formMapper = new WpAdvQuiz_Model_FormMapper();

        $is100P = $data['results']['comp']['result'] == 100;

        $quiz = $quizMapper->fetch($data['quizId']);

        if ($quiz === null || $quiz->getId() <= 0) {
            return json_encode(array());
        }

        $categories = $categoryMapper->fetchByQuiz($quiz->getId());
        $forms = $formMapper->fetch($quiz->getId());

        $ctr->setResultCookie($quiz);

        $ctr->emailNote($quiz, $data['results']['comp'], $categories, $forms,
            isset($data['forms']) ? $data['forms'] : array());

        if (!$ctr->isPreLockQuiz($quiz)) {
            $statistics = new WpAdvQuiz_Controller_Statistics();
            $statistics->save($quiz);

            do_action('wp_adv_quiz_completed_quiz');

            if ($is100P) {
                do_action('wp_adv_quiz_completed_quiz_100_percent');
            }

            return json_encode(array());
        }

        $lockMapper->deleteOldLock(60 * 60 * 24 * 7, $data['quizId'], time(), WpAdvQuiz_Model_Lock::TYPE_QUIZ,
            0);

        $lockIp = $lockMapper->isLock($data['quizId'], $ctr->getIp(), get_current_user_id(),
            WpAdvQuiz_Model_Lock::TYPE_QUIZ);
        $lockCookie = false;
        $cookieTime = $quiz->getQuizRunOnceTime();
        $cookieJson = null;

        if (isset($ctr->_cookie['wpAdvQuiz_lock']) && get_current_user_id() == 0 && $quiz->isQuizRunOnceCookie()) {
            $cookieJson = json_decode($ctr->_cookie['wpAdvQuiz_lock'], true);

            if ($cookieJson !== false) {
                if (isset($cookieJson[$data['quizId']]) && $cookieJson[$data['quizId']] == $cookieTime) {
                    $lockCookie = true;
                }
            }
        }

        if (!$lockIp && !$lockCookie) {
            $statistics = new WpAdvQuiz_Controller_Statistics();
            $statistics->save($quiz);

            do_action('wp_adv_quiz_completed_quiz');

            if ($is100P) {
                do_action('wp_adv_quiz_completed_quiz_100_percent');
            }

            if (get_current_user_id() == 0 && $quiz->isQuizRunOnceCookie()) {
                $cookieData = array();

                if ($cookieJson !== null || $cookieJson !== false) {
                    $cookieData = $cookieJson;
                }

                $cookieData[$data['quizId']] = $quiz->getQuizRunOnceTime();
                $url = parse_url(get_bloginfo('url'));

                setcookie('wpAdvQuiz_lock', json_encode($cookieData), time() + 60 * 60 * 24 * 60,
                    empty($url['path']) ? '/' : $url['path']);
            }

            $lock = new WpAdvQuiz_Model_Lock();

            $lock->setUserId(get_current_user_id());
            $lock->setQuizId($data['quizId']);
            $lock->setLockDate(time());
            $lock->setLockIp($ctr->getIp());
            $lock->setLockType(WpAdvQuiz_Model_Lock::TYPE_QUIZ);

            $lockMapper->insert($lock);
        }

        return json_encode(array());
    }
}
