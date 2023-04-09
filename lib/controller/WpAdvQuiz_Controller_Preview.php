<?php

class WpAdvQuiz_Controller_Preview extends WpAdvQuiz_Controller_Controller
{

    public function route()
    {

        wp_enqueue_script(
            'wpAdvQuiz_front_javascript',
            plugins_url('js/wpAdvQuiz_front' . (WPADVQUIZ_DEV ? '' : '.min') . '.js', WPADVQUIZ_FILE),
            array('jquery', 'jquery-ui-sortable'),
            WPADVQUIZ_VERSION
        );

        wp_localize_script('wpAdvQuiz_front_javascript', 'WpAdvQuizGlobal', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'loadData' => __('Loading', 'wp-adv-quiz'),
            'questionNotSolved' => __('You must answer this question.', 'wp-adv-quiz'),
            'questionsNotSolved' => __('You must answer all questions before you can completed the quiz.',
                'wp-adv-quiz'),
            'fieldsNotFilled' => __('All fields have to be filled.', 'wp-adv-quiz')
        ));

        wp_enqueue_style(
            'wpAdvQuiz_front_style',
            plugins_url('css/wpAdvQuiz_front' . (WPADVQUIZ_DEV ? '' : '.min') . '.css', WPADVQUIZ_FILE),
            array(),
            WPADVQUIZ_VERSION
        );
		
		$quizId = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
		$quizId = $quizId ? : 0;

        $this->showAction($quizId);
    }

    public function showAction($id)
    {
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
		
        $view->show(true);
    }
}