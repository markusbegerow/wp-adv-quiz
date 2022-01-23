<?php

/**
 * @since 0.23
 */
class WpAdvQuiz_Controller_Ajax
{

    private $_adminCallbacks = array();
    private $_frontCallbacks = array();

    public function init()
    {
        $this->initCallbacks();

        add_action('wp_ajax_wp_adv_quiz_admin_ajax', array($this, 'adminAjaxCallback'));
        add_action('wp_ajax_nopriv_wp_adv_quiz_admin_ajax', array($this, 'frontAjaxCallback'));
    }

    public function adminAjaxCallback()
    {
        $this->ajaxCallbackHandler(true);
    }

    public function frontAjaxCallback()
    {
        $this->ajaxCallbackHandler(false);
    }

    private function ajaxCallbackHandler($admin)
    {
		$func = filter_input(INPUT_POST,'func',FILTER_SANITIZE_STRING);
		$func = $func ? : '';
		
		$data = filter_input(INPUT_POST,'data',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
		
        $calls = $admin ? $this->_adminCallbacks : $this->_frontCallbacks;

        if (isset($calls[$func])) {
            $r = call_user_func($calls[$func], $data, $func);

            if ($r !== null) {
               echo filter_var($r,FILTER_UNSAFE_RAW,FILTER_FLAG_ENCODE_AMP);
            }
        }

        exit;
    }

    private function initCallbacks()
    {
        $this->_adminCallbacks = array(
            'categoryAdd' => array('WpAdvQuiz_Controller_Category', 'ajaxAddCategory'),
            'categoryDelete' => array('WpAdvQuiz_Controller_Category', 'ajaxDeleteCategory'),
            'categoryEdit' => array('WpAdvQuiz_Controller_Category', 'ajaxEditCategory'),
            'statisticLoadHistory' => array('WpAdvQuiz_Controller_Statistics', 'ajaxLoadHistory'),
            'statisticLoadUser' => array('WpAdvQuiz_Controller_Statistics', 'ajaxLoadStatisticUser'),
            'statisticResetNew' => array('WpAdvQuiz_Controller_Statistics', 'ajaxRestStatistic'),
            'statisticLoadOverviewNew' => array('WpAdvQuiz_Controller_Statistics', 'ajaxLoadStatsticOverviewNew'),
            'templateEdit' => array('WpAdvQuiz_Controller_Template', 'ajaxEditTemplate'),
            'templateDelete' => array('WpAdvQuiz_Controller_Template', 'ajaxDeleteTemplate'),
            'quizLoadData' => array('WpAdvQuiz_Controller_Front', 'ajaxQuizLoadData'),
            'setQuizMultipleCategories' => array('WpAdvQuiz_Controller_Quiz', 'ajaxSetQuizMultipleCategories'),
            'setQuestionMultipleCategories' => array(
                'WpAdvQuiz_Controller_Question',
                'ajaxSetQuestionMultipleCategories'
            ),
            'loadQuestionsSort' => array('WpAdvQuiz_Controller_Question', 'ajaxLoadQuestionsSort'),
            'questionSaveSort' => array('WpAdvQuiz_Controller_Question', 'ajaxSaveSort'),
            'questionaLoadCopyQuestion' => array('WpAdvQuiz_Controller_Question', 'ajaxLoadCopyQuestion'),
            'loadQuizData' => array('WpAdvQuiz_Controller_Quiz', 'ajaxLoadQuizData'),
            'resetLock' => array('WpAdvQuiz_Controller_Quiz', 'ajaxResetLock'),
            'adminToplist' => array('WpAdvQuiz_Controller_Toplist', 'ajaxAdminToplist'),
            'completedQuiz' => array('WpAdvQuiz_Controller_Quiz', 'ajaxCompletedQuiz'),
            'quizCheckLock' => array('WpAdvQuiz_Controller_Quiz', 'ajaxQuizCheckLock'),
            'addInToplist' => array('WpAdvQuiz_Controller_Toplist', 'ajaxAddInToplist'),
            'showFrontToplist' => array('WpAdvQuiz_Controller_Toplist', 'ajaxShowFrontToplist')
        );

        //nopriv
        $this->_frontCallbacks = array(
            'quizLoadData' => array('WpAdvQuiz_Controller_Front', 'ajaxQuizLoadData'),
            'loadQuizData' => array('WpAdvQuiz_Controller_Quiz', 'ajaxLoadQuizData'),
            'completedQuiz' => array('WpAdvQuiz_Controller_Quiz', 'ajaxCompletedQuiz'),
            'quizCheckLock' => array('WpAdvQuiz_Controller_Quiz', 'ajaxQuizCheckLock'),
            'addInToplist' => array('WpAdvQuiz_Controller_Toplist', 'ajaxAddInToplist'),
            'showFrontToplist' => array('WpAdvQuiz_Controller_Toplist', 'ajaxShowFrontToplist')
        );
    }
}