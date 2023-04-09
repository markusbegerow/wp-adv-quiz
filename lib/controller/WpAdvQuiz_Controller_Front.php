<?php

class WpAdvQuiz_Controller_Front
{

    /**
     * @var WpAdvQuiz_Model_GlobalSettings
     */
    private $_settings = null;

    public function __construct()
    {
        $this->loadSettings();

        add_action('wp_enqueue_scripts', array($this, 'loadDefaultScripts'));
        add_shortcode('WpAdvQuiz', array($this, 'shortcode'));
        add_shortcode('WpAdvQuiz_toplist', array($this, 'shortcodeToplist'));
    }

    public function loadDefaultScripts()
    {
        wp_enqueue_script('jquery');

        $data = array(
            'src' => plugins_url('css/wpAdvQuiz_front' . (WPADVQUIZ_DEV ? '' : '.min') . '.css', WPADVQUIZ_FILE),
            'deps' => array(),
            'ver' => WPADVQUIZ_VERSION,
        );

        $data = apply_filters('wpAdvQuiz_front_style', $data);

        wp_enqueue_style('wpAdvQuiz_front_style', $data['src'], $data['deps'], $data['ver']);

        if ($this->_settings->isJsLoadInHead()) {
            $this->loadJsScripts(false, true, true);
        }
    }

    private function loadJsScripts($footer = true, $quiz = true, $toplist = false)
    {
        if ($quiz) {
            wp_enqueue_script(
                'wpAdvQuiz_front_javascript',
                plugins_url('js/wpAdvQuiz_front' . (WPADVQUIZ_DEV ? '' : '.min') . '.js', WPADVQUIZ_FILE),
                array('jquery-ui-sortable'),
                WPADVQUIZ_VERSION,
                $footer
            );

            wp_localize_script('wpAdvQuiz_front_javascript', 'WpAdvQuizGlobal', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'loadData' => __('Loading', 'wp-adv-quiz'),
                'questionNotSolved' => __('You must answer this question.', 'wp-adv-quiz'),
                'questionsNotSolved' => __('You must answer all questions before you can completed the quiz.',
                    'wp-adv-quiz'),
                'fieldsNotFilled' => __('All fields have to be filled.', 'wp-adv-quiz')
            ));
        }

        if ($toplist) {
            wp_enqueue_script(
                'wpAdvQuiz_front_javascript_toplist',
                plugins_url('js/wpAdvQuiz_toplist' . (WPADVQUIZ_DEV ? '' : '.min') . '.js', WPADVQUIZ_FILE),
                array('jquery-ui-sortable'),
                WPADVQUIZ_VERSION,
                $footer
            );

            if (!wp_script_is('wpAdvQuiz_front_javascript')) {
                wp_localize_script('wpAdvQuiz_front_javascript_toplist', 'WpAdvQuizGlobal', array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'loadData' => __('Loading', 'wp-adv-quiz'),
                    'questionNotSolved' => __('You must answer this question.', 'wp-adv-quiz'),
                    'questionsNotSolved' => __('You must answer all questions before you can completed the quiz.',
                        'wp-adv-quiz'),
                    'fieldsNotFilled' => __('All fields have to be filled.', 'wp-adv-quiz')
                ));
            }
        }

        if (!$this->_settings->isTouchLibraryDeactivate()) {
            wp_enqueue_script(
                'jquery-ui-touch-punch',
                plugins_url('js/jquery.ui.touch-punch.min.js', WPADVQUIZ_FILE),
                array('jquery-ui-sortable'),
                '0.2.2',
                $footer
            );
        }
    }

    public function shortcode($attr)
    {
        $id = $attr[0];
        $content = '';

        if (!$this->_settings->isJsLoadInHead()) {
            $this->loadJsScripts();
        }

        if (is_numeric($id)) {
            ob_start();

            $this->handleShortCode($id);

            $content = ob_get_contents();

            ob_end_clean();
        }

        if ($this->_settings->isAddRawShortcode()) {
            return '[raw]' . $content . '[/raw]';
        }

        return $content;
    }

    public function handleShortCode($id)
    {
        $view = new WpAdvQuiz_View_FrontQuiz();

        $quizMapper = new WpAdvQuiz_Model_QuizMapper();
        $questionMapper = new WpAdvQuiz_Model_QuestionMapper();
        $categoryMapper = new WpAdvQuiz_Model_CategoryMapper();
        $formMapper = new WpAdvQuiz_Model_FormMapper();

        $quiz = $quizMapper->fetch($id);

        $maxQuestion = false;

        if ($quiz->isShowMaxQuestion() && $quiz->getShowMaxQuestionValue() > 0) {

            $value = $quiz->getShowMaxQuestionValue();

            if ($quiz->isShowMaxQuestionPercent()) {
                $count = $questionMapper->count($id);

                $value = ceil($count * $value / 100);
            }

            $question = $questionMapper->fetchAll($id, true, $value);
            $maxQuestion = true;

        } else {
            $question = $questionMapper->fetchAll($id);
        }
		
        if (empty($quiz) || empty($question)) {
			//echo '';
            return;
        }

        $view->quiz = $quiz;
        $view->question = $question;
        $view->category = $categoryMapper->fetchByQuiz($quiz->getId());
        $view->forms = $formMapper->fetch($quiz->getId());
		
		$mapper = new WpAdvQuiz_Model_GlobalSettingsMapper();
		$view->start_btn_width = $mapper->getButtonProperty('wpAdvQuiz_start_btn_width');
		$view->start_btn_height = $mapper->getButtonProperty('wpAdvQuiz_start_btn_height');
		$view->start_btn_color = $mapper->getButtonProperty('wpAdvQuiz_start_btn_color');
		
		$view->restart_btn_width = $mapper->getButtonProperty('wpAdvQuiz_restart_btn_width');
		$view->restart_btn_height = $mapper->getButtonProperty('wpAdvQuiz_restart_btn_height');
		$view->restart_btn_color = $mapper->getButtonProperty('wpAdvQuiz_restart_btn_color');
		
		$view->review_btn_width = $mapper->getButtonProperty('wpAdvQuiz_review_btn_width');
		$view->review_btn_height = $mapper->getButtonProperty('wpAdvQuiz_review_btn_height');
		$view->review_btn_color = $mapper->getButtonProperty('wpAdvQuiz_review_btn_color');

		$view->summary_btn_width = $mapper->getButtonProperty('wpAdvQuiz_summary_btn_width');
		$view->summary_btn_height = $mapper->getButtonProperty('wpAdvQuiz_summary_btn_height');
		$view->summary_btn_color = $mapper->getButtonProperty('wpAdvQuiz_summary_btn_color');

		$view->finish_btn_width = $mapper->getButtonProperty('wpAdvQuiz_finish_btn_width');
		$view->finish_btn_height = $mapper->getButtonProperty('wpAdvQuiz_finish_btn_height');
		$view->finish_btn_color = $mapper->getButtonProperty('wpAdvQuiz_finish_btn_color');
		
		$view->hint_btn_width = $mapper->getButtonProperty('wpAdvQuiz_hint_btn_width');
		$view->hint_btn_height = $mapper->getButtonProperty('wpAdvQuiz_hint_btn_height');
		$view->hint_btn_color = $mapper->getButtonProperty('wpAdvQuiz_hint_btn_color');
		
		$view->back_btn_width = $mapper->getButtonProperty('wpAdvQuiz_back_btn_width');
		$view->back_btn_height = $mapper->getButtonProperty('wpAdvQuiz_back_btn_height');
		$view->back_btn_color = $mapper->getButtonProperty('wpAdvQuiz_back_btn_color');

		$view->check_btn_width = $mapper->getButtonProperty('wpAdvQuiz_check_btn_width');
		$view->check_btn_height = $mapper->getButtonProperty('wpAdvQuiz_check_btn_height');
		$view->check_btn_color = $mapper->getButtonProperty('wpAdvQuiz_check_btn_color');
	
		$view->next_btn_width = $mapper->getButtonProperty('wpAdvQuiz_next_btn_width');
		$view->next_btn_height = $mapper->getButtonProperty('wpAdvQuiz_next_btn_height');
		$view->next_btn_color = $mapper->getButtonProperty('wpAdvQuiz_next_btn_color');
		
			$view->skip_btn_width = $mapper->getButtonProperty('wpAdvQuiz_skip_btn_width');
		$view->skip_btn_height = $mapper->getButtonProperty('wpAdvQuiz_skip_btn_height');
		$view->skip_btn_color = $mapper->getButtonProperty('wpAdvQuiz_skip_btn_color');
		
		$view->view_btn_width = $mapper->getButtonProperty('wpAdvQuiz_view_btn_width');
		$view->view_btn_height = $mapper->getButtonProperty('wpAdvQuiz_view_btn_height');
		$view->view_btn_color = $mapper->getButtonProperty('wpAdvQuiz_view_btn_color');

			$view->leaderboard_btn_width = $mapper->getButtonProperty('wpAdvQuiz_leaderboard_btn_width');
		$view->leaderboard_btn_height = $mapper->getButtonProperty('wpAdvQuiz_leaderboard_btn_height');
		$view->leaderboard_btn_color = $mapper->getButtonProperty('wpAdvQuiz_leaderboard_btn_color');
		
        if ($maxQuestion) {
            $view->showMaxQuestion();
        } else {
            $view->show();
        }
    }

    public function shortcodeToplist($attr)
    {
        $id = $attr[0];
        $content = '';

        if (!$this->_settings->isJsLoadInHead()) {
            $this->loadJsScripts(true, false, true);
        }

        if (is_numeric($id)) {
            ob_start();

            $this->handleShortCodeToplist($id, isset($attr['q']));

            $content = ob_get_contents();

            ob_end_clean();
        }

        if ($this->_settings->isAddRawShortcode() && !isset($attr['q'])) {
            return '[raw]' . $content . '[/raw]';
        }

        return $content;
    }

    private function handleShortCodeToplist($quizId, $inQuiz = false)
    {
        $quizMapper = new WpAdvQuiz_Model_QuizMapper();
        $view = new WpAdvQuiz_View_FrontToplist();

        $quiz = $quizMapper->fetch($quizId);

        if ($quiz->getId() <= 0 || !$quiz->isToplistActivated()) {
            //echo '';

            return;
        }

        $view->quiz = $quiz;
        $view->points = $quizMapper->sumQuestionPoints($quizId);
        $view->inQuiz = $inQuiz;
        $view->show();
    }

    private function loadSettings()
    {
        $mapper = new WpAdvQuiz_Model_GlobalSettingsMapper();

        $this->_settings = $mapper->fetchAll();

    }

    public static function ajaxQuizLoadData($data)
    {
        $id = $data['quizId'];

        $view = new WpAdvQuiz_View_FrontQuiz();

        $quizMapper = new WpAdvQuiz_Model_QuizMapper();
        $questionMapper = new WpAdvQuiz_Model_QuestionMapper();
        $categoryMapper = new WpAdvQuiz_Model_CategoryMapper();
        $formMapper = new WpAdvQuiz_Model_FormMapper();

        $quiz = $quizMapper->fetch($id);

        if ($quiz->isShowMaxQuestion() && $quiz->getShowMaxQuestionValue() > 0) {

            $value = $quiz->getShowMaxQuestionValue();

            if ($quiz->isShowMaxQuestionPercent()) {
                $count = $questionMapper->count($id);

                $value = ceil($count * $value / 100);
            }

            $question = $questionMapper->fetchAll($id, true, $value);

        } else {
            $question = $questionMapper->fetchAll($id);
        }

        if (empty($quiz) || empty($question)) {
            return null;
        }

        $view->quiz = $quiz;
        $view->question = $question;
        $view->category = $categoryMapper->fetchByQuiz($quiz->getId());
        $view->forms = $formMapper->fetch($quiz->getId());
		
		$mapper = new WpAdvQuiz_Model_GlobalSettingsMapper();
		$view->start_btn_width = $mapper->getButtonProperty('wpAdvQuiz_start_btn_width');
		$view->start_btn_height = $mapper->getButtonProperty('wpAdvQuiz_start_btn_height');
		$view->start_btn_color = $mapper->getButtonProperty('wpAdvQuiz_start_btn_color');
		
		$view->restart_btn_width = $mapper->getButtonProperty('wpAdvQuiz_restart_btn_width');
		$view->restart_btn_height = $mapper->getButtonProperty('wpAdvQuiz_restart_btn_height');
		$view->restart_btn_color = $mapper->getButtonProperty('wpAdvQuiz_restart_btn_color');
		
		$view->review_btn_width = $mapper->getButtonProperty('wpAdvQuiz_review_btn_width');
		$view->review_btn_height = $mapper->getButtonProperty('wpAdvQuiz_review_btn_height');
		$view->review_btn_color = $mapper->getButtonProperty('wpAdvQuiz_review_btn_color');

		$view->summary_btn_width = $mapper->getButtonProperty('wpAdvQuiz_summary_btn_width');
		$view->summary_btn_height = $mapper->getButtonProperty('wpAdvQuiz_summary_btn_height');
		$view->summary_btn_color = $mapper->getButtonProperty('wpAdvQuiz_summary_btn_color');

		$view->finish_btn_width = $mapper->getButtonProperty('wpAdvQuiz_finish_btn_width');
		$view->finish_btn_height = $mapper->getButtonProperty('wpAdvQuiz_finish_btn_height');
		$view->finish_btn_color = $mapper->getButtonProperty('wpAdvQuiz_finish_btn_color');
		
		$view->hint_btn_width = $mapper->getButtonProperty('wpAdvQuiz_hint_btn_width');
		$view->hint_btn_height = $mapper->getButtonProperty('wpAdvQuiz_hint_btn_height');
		$view->hint_btn_color = $mapper->getButtonProperty('wpAdvQuiz_hint_btn_color');
		
		$view->back_btn_width = $mapper->getButtonProperty('wpAdvQuiz_back_btn_width');
		$view->back_btn_height = $mapper->getButtonProperty('wpAdvQuiz_back_btn_height');
		$view->back_btn_color = $mapper->getButtonProperty('wpAdvQuiz_back_btn_color');

		$view->check_btn_width = $mapper->getButtonProperty('wpAdvQuiz_check_btn_width');
		$view->check_btn_height = $mapper->getButtonProperty('wpAdvQuiz_check_btn_height');
		$view->check_btn_color = $mapper->getButtonProperty('wpAdvQuiz_check_btn_color');

		$view->next_btn_width = $mapper->getButtonProperty('wpAdvQuiz_next_btn_width');
		$view->next_btn_height = $mapper->getButtonProperty('wpAdvQuiz_next_btn_height');
		$view->next_btn_color = $mapper->getButtonProperty('wpAdvQuiz_next_btn_color');
		
			$view->skip_btn_width = $mapper->getButtonProperty('wpAdvQuiz_skip_btn_width');
		$view->skip_btn_height = $mapper->getButtonProperty('wpAdvQuiz_skip_btn_height');
		$view->skip_btn_color = $mapper->getButtonProperty('wpAdvQuiz_skip_btn_color');
		
		$view->view_btn_width = $mapper->getButtonProperty('wpAdvQuiz_view_btn_width');
		$view->view_btn_height = $mapper->getButtonProperty('wpAdvQuiz_view_btn_height');
		$view->view_btn_color = $mapper->getButtonProperty('wpAdvQuiz_view_btn_color');
		
			$view->leaderboard_btn_width = $mapper->getButtonProperty('wpAdvQuiz_leaderboard_btn_width');
		$view->leaderboard_btn_height = $mapper->getButtonProperty('wpAdvQuiz_leaderboard_btn_height');
		$view->leaderboard_btn_color = $mapper->getButtonProperty('wpAdvQuiz_leaderboard_btn_color');
		
        return json_encode($view->getQuizData());
    }
}