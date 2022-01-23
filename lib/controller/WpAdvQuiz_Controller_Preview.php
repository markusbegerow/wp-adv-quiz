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

        $view->show(true);
    }
}