<?php

class WpAdvQuiz_Controller_GlobalSettings extends WpAdvQuiz_Controller_Controller
{

    public function route()
    {
        $this->edit();
    }

    private function edit()
    {

        if (!current_user_can('wpAdvQuiz_change_settings')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $mapper = new WpAdvQuiz_Model_GlobalSettingsMapper();
        $categoryMapper = new WpAdvQuiz_Model_CategoryMapper();
        $templateMapper = new WpAdvQuiz_Model_TemplateMapper();

        $view = new WpAdvQuiz_View_GobalSettings();

        if (isset($this->_post['submit'])) {
            $mapper->save(new WpAdvQuiz_Model_GlobalSettings($this->_post));
            WpAdvQuiz_View_View::admin_notices(__('Settings saved', 'wp-adv-quiz'), 'info');

            $toplistDateFormat = $this->_post['toplist_date_format'];

            if ($toplistDateFormat == 'custom') {
                $toplistDateFormat = trim($this->_post['toplist_date_format_custom']);
            }

            $statisticTimeFormat = $this->_post['statisticTimeFormat'];

            if (add_option('wpAdvQuiz_toplistDataFormat', $toplistDateFormat) === false) {
                update_option('wpAdvQuiz_toplistDataFormat', $toplistDateFormat);
            }

            if (add_option('wpAdvQuiz_statisticTimeFormat', $statisticTimeFormat, '', 'no') === false) {
                update_option('wpAdvQuiz_statisticTimeFormat', $statisticTimeFormat);
            }
			
		//Start	
			$start_btn_width = $this->_post['start_btn_width'];
			$start_btn_width = $mapper->CheckForButtonDefaults('start_btn_width',$start_btn_width);
			
			if (add_option('wpAdvQuiz_start_btn_width', $start_btn_width, '', 'no') === false) {
                update_option('wpAdvQuiz_start_btn_width', $start_btn_width);
            }
			
			$start_btn_height = $this->_post['start_btn_height'];
			$start_btn_height = $mapper->CheckForButtonDefaults('start_btn_height',$start_btn_height);
			
			if (add_option('wpAdvQuiz_start_btn_height', $start_btn_height, '', 'no') === false) {
                update_option('wpAdvQuiz_start_btn_height', $start_btn_height);
            }
			
			
			$start_btn_color = $this->_post['start_btn_color'];
			$start_btn_color = $mapper->CheckForButtonDefaults('start_btn_color',$start_btn_color);
			
			if (add_option('wpAdvQuiz_start_btn_color', $start_btn_color, '', 'no') === false) {
                update_option('wpAdvQuiz_start_btn_color', $start_btn_color);
            }
		//Restart
		
			$restart_btn_width = $this->_post['restart_btn_width'];
			$restart_btn_width = $mapper->CheckForButtonDefaults('restart_btn_width',$restart_btn_width);
			
			if (add_option('wpAdvQuiz_restart_btn_width', $restart_btn_width, '', 'no') === false) {
                update_option('wpAdvQuiz_restart_btn_width', $restart_btn_width);
            }
			
			$restart_btn_height = $this->_post['restart_btn_height'];
			$restart_btn_height = $mapper->CheckForButtonDefaults('restart_btn_height',$restart_btn_height);
			
			if (add_option('wpAdvQuiz_restart_btn_height', $restart_btn_height, '', 'no') === false) {
                update_option('wpAdvQuiz_restart_btn_height', $restart_btn_height);
            }
			
			$restart_btn_color = $this->_post['restart_btn_color'];
			$restart_btn_color = $mapper->CheckForButtonDefaults('restart_btn_color',$restart_btn_color);
			
			if (add_option('wpAdvQuiz_restart_btn_color', $restart_btn_color, '', 'no') === false) {
                update_option('wpAdvQuiz_restart_btn_color', $restart_btn_color);
            }
		//review
		
			$review_btn_width = $this->_post['review_btn_width'];
			$review_btn_width = $mapper->CheckForButtonDefaults('review_btn_width',$review_btn_width);
			
			if (add_option('wpAdvQuiz_review_btn_width', $review_btn_width, '', 'no') === false) {
                update_option('wpAdvQuiz_review_btn_width', $review_btn_width);
            }
			
			$review_btn_height = $this->_post['review_btn_height'];
			$review_btn_height = $mapper->CheckForButtonDefaults('review_btn_height',$review_btn_height);
			
			if (add_option('wpAdvQuiz_review_btn_height', $review_btn_height, '', 'no') === false) {
                update_option('wpAdvQuiz_review_btn_height', $review_btn_height);
            }
			
			$review_btn_color = $this->_post['review_btn_color'];
			$review_btn_color = $mapper->CheckForButtonDefaults('review_btn_color',$review_btn_color);
			
			if (add_option('wpAdvQuiz_review_btn_color', $review_btn_color, '', 'no') === false) {
                update_option('wpAdvQuiz_review_btn_color', $review_btn_color);
            }
			
		//Summary
		
			$summary_btn_width = $this->_post['summary_btn_width'];
			$summary_btn_width = $mapper->CheckForButtonDefaults('summary_btn_width',$summary_btn_width);
			
			if (add_option('wpAdvQuiz_summary_btn_width', $summary_btn_width, '', 'no') === false) {
                update_option('wpAdvQuiz_summary_btn_width', $summary_btn_width);
            }
			
			$summary_btn_height = $this->_post['summary_btn_height'];
			$summary_btn_height = $mapper->CheckForButtonDefaults('summary_btn_height',$summary_btn_height);
			
			if (add_option('wpAdvQuiz_summary_btn_height', $summary_btn_height, '', 'no') === false) {
                update_option('wpAdvQuiz_summary_btn_height', $summary_btn_height);
            }
			
			$summary_btn_color = $this->_post['summary_btn_color'];
			$summary_btn_color = $mapper->CheckForButtonDefaults('summary_btn_color',$summary_btn_color);
			
			if (add_option('wpAdvQuiz_summary_btn_color', $summary_btn_color, '', 'no') === false) {
                update_option('wpAdvQuiz_summary_btn_color', $summary_btn_color);
            }
		
			//finish
		
			$finish_btn_width = $this->_post['finish_btn_width'];
			$finish_btn_width = $mapper->CheckForButtonDefaults('finish_btn_width',$finish_btn_width);
			
			if (add_option('wpAdvQuiz_finish_btn_width', $finish_btn_width, '', 'no') === false) {
                update_option('wpAdvQuiz_finish_btn_width', $finish_btn_width);
            }
			
			$finish_btn_height = $this->_post['finish_btn_height'];
			$finish_btn_height = $mapper->CheckForButtonDefaults('finish_btn_height',$finish_btn_height);
			
			if (add_option('wpAdvQuiz_finish_btn_height', $finish_btn_height, '', 'no') === false) {
                update_option('wpAdvQuiz_finish_btn_height', $finish_btn_height);
            }
			
			$finish_btn_color = $this->_post['finish_btn_color'];
			$finish_btn_color = $mapper->CheckForButtonDefaults('finish_btn_color',$finish_btn_color);
			
			if (add_option('wpAdvQuiz_finish_btn_color', $finish_btn_color, '', 'no') === false) {
                update_option('wpAdvQuiz_finish_btn_color', $finish_btn_color);
            }
					
			//hint
		
			$hint_btn_width = $this->_post['hint_btn_width'];
			$hint_btn_width = $mapper->CheckForButtonDefaults('hint_btn_width',$hint_btn_width);
			
			if (add_option('wpAdvQuiz_hint_btn_width', $hint_btn_width, '', 'no') === false) {
                update_option('wpAdvQuiz_hint_btn_width', $hint_btn_width);
            }
			
			$hint_btn_height = $this->_post['hint_btn_height'];
			$hint_btn_height = $mapper->CheckForButtonDefaults('hint_btn_height',$hint_btn_height);
			
			if (add_option('wpAdvQuiz_hint_btn_height', $hint_btn_height, '', 'no') === false) {
                update_option('wpAdvQuiz_hint_btn_height', $hint_btn_height);
            }
			
			$hint_btn_color = $this->_post['hint_btn_color'];
			$hint_btn_color = $mapper->CheckForButtonDefaults('hint_btn_color',$hint_btn_color);
			
			if (add_option('wpAdvQuiz_hint_btn_color', $hint_btn_color, '', 'no') === false) {
                update_option('wpAdvQuiz_hint_btn_color', $hint_btn_color);
            }
			
			//back
		
			$back_btn_width = $this->_post['back_btn_width'];
			$back_btn_width = $mapper->CheckForButtonDefaults('back_btn_width',$back_btn_width);
			
			if (add_option('wpAdvQuiz_back_btn_width', $back_btn_width, '', 'no') === false) {
                update_option('wpAdvQuiz_back_btn_width', $back_btn_width);
            }
			
			$back_btn_height = $this->_post['back_btn_height'];
			$back_btn_height = $mapper->CheckForButtonDefaults('back_btn_height',$back_btn_height);
			
			if (add_option('wpAdvQuiz_back_btn_height', $back_btn_height, '', 'no') === false) {
                update_option('wpAdvQuiz_back_btn_height', $back_btn_height);
            }
			
			$back_btn_color = $this->_post['back_btn_color'];
			$back_btn_color = $mapper->CheckForButtonDefaults('back_btn_color',$back_btn_color);
			
			if (add_option('wpAdvQuiz_back_btn_color', $back_btn_color, '', 'no') === false) {
                update_option('wpAdvQuiz_back_btn_color', $back_btn_color);
            }
			
			//check
		
			$check_btn_width = $this->_post['check_btn_width'];
			$check_btn_width = $mapper->CheckForButtonDefaults('check_btn_width',$check_btn_width);
			
			if (add_option('wpAdvQuiz_check_btn_width', $check_btn_width, '', 'no') === false) {
                update_option('wpAdvQuiz_check_btn_width', $check_btn_width);
            }
			
			$check_btn_height = $this->_post['check_btn_height'];
			$check_btn_height = $mapper->CheckForButtonDefaults('check_btn_height',$check_btn_height);
			
			if (add_option('wpAdvQuiz_check_btn_height', $check_btn_height, '', 'no') === false) {
                update_option('wpAdvQuiz_check_btn_height', $check_btn_height);
            }
			
			$check_btn_color = $this->_post['check_btn_color'];
			$check_btn_color = $mapper->CheckForButtonDefaults('check_btn_color',$check_btn_color);
			
			if (add_option('wpAdvQuiz_check_btn_color', $check_btn_color, '', 'no') === false) {
                update_option('wpAdvQuiz_check_btn_color', $check_btn_color);
            }
		
			//next
		
			$next_btn_width = $this->_post['next_btn_width'];
			$next_btn_width = $mapper->CheckForButtonDefaults('next_btn_width',$next_btn_width);
			
			if (add_option('wpAdvQuiz_next_btn_width', $next_btn_width, '', 'no') === false) {
                update_option('wpAdvQuiz_next_btn_width', $next_btn_width);
            }
			
			$next_btn_height = $this->_post['next_btn_height'];
			$next_btn_height = $mapper->CheckForButtonDefaults('next_btn_height',$next_btn_height);
			
			if (add_option('wpAdvQuiz_next_btn_height', $next_btn_height, '', 'no') === false) {
                update_option('wpAdvQuiz_next_btn_height', $next_btn_height);
            }
			
			$next_btn_color = $this->_post['next_btn_color'];
			$next_btn_color = $mapper->CheckForButtonDefaults('next_btn_color',$next_btn_color);
			
			if (add_option('wpAdvQuiz_next_btn_color', $next_btn_color, '', 'no') === false) {
                update_option('wpAdvQuiz_next_btn_color', $next_btn_color);
            }
			
			//skip
		
			$skip_btn_width = $this->_post['skip_btn_width'];
			$skip_btn_width = $mapper->CheckForButtonDefaults('skip_btn_width',$skip_btn_width);
			
			if (add_option('wpAdvQuiz_skip_btn_width', $skip_btn_width, '', 'no') === false) {
                update_option('wpAdvQuiz_skip_btn_width', $skip_btn_width);
            }
			
			$skip_btn_height = $this->_post['skip_btn_height'];
			$skip_btn_height = $mapper->CheckForButtonDefaults('skip_btn_height',$skip_btn_height);
			
			if (add_option('wpAdvQuiz_skip_btn_height', $skip_btn_height, '', 'no') === false) {
                update_option('wpAdvQuiz_skip_btn_height', $skip_btn_height);
            }
			
			$skip_btn_color = $this->_post['skip_btn_color'];
			$skip_btn_color = $mapper->CheckForButtonDefaults('skip_btn_color',$skip_btn_color);
			
			if (add_option('wpAdvQuiz_skip_btn_color', $skip_btn_color, '', 'no') === false) {
                update_option('wpAdvQuiz_skip_btn_color', $skip_btn_color);
            }
		
			//view
		
			$view_btn_width = $this->_post['view_btn_width'];
			$view_btn_width = $mapper->CheckForButtonDefaults('view_btn_width',$view_btn_width);
			
			if (add_option('wpAdvQuiz_view_btn_width', $view_btn_width, '', 'no') === false) {
                update_option('wpAdvQuiz_view_btn_width', $view_btn_width);
            }
			
			$view_btn_height = $this->_post['view_btn_height'];
			$view_btn_height = $mapper->CheckForButtonDefaults('view_btn_height',$view_btn_height);
			
			if (add_option('wpAdvQuiz_view_btn_height', $view_btn_height, '', 'no') === false) {
                update_option('wpAdvQuiz_view_btn_height', $view_btn_height);
            }
			
			$view_btn_color = $this->_post['view_btn_color'];
			$view_btn_color = $mapper->CheckForButtonDefaults('view_btn_color',$view_btn_color);
			
			if (add_option('wpAdvQuiz_view_btn_color', $view_btn_color, '', 'no') === false) {
                update_option('wpAdvQuiz_view_btn_color', $view_btn_color);
            }
			
			//leaderboard
		
			$leaderboard_btn_width = $this->_post['leaderboard_btn_width'];
			$leaderboard_btn_width = $mapper->CheckForButtonDefaults('leaderboard_btn_width',$leaderboard_btn_width);
			
			if (add_option('wpAdvQuiz_leaderboard_btn_width', $leaderboard_btn_width, '', 'no') === false) {
                update_option('wpAdvQuiz_leaderboard_btn_width', $leaderboard_btn_width);
            }
			
			$leaderboard_btn_height = $this->_post['leaderboard_btn_height'];
			$leaderboard_btn_height = $mapper->CheckForButtonDefaults('leaderboard_btn_height',$leaderboard_btn_height);
			
			if (add_option('wpAdvQuiz_leaderboard_btn_height', $leaderboard_btn_height, '', 'no') === false) {
                update_option('wpAdvQuiz_leaderboard_btn_height', $leaderboard_btn_height);
            }
			
			$leaderboard_btn_color = $this->_post['leaderboard_btn_color'];
			$leaderboard_btn_color = $mapper->CheckForButtonDefaults('leaderboard_btn_color',$leaderboard_btn_color);
			
			if (add_option('wpAdvQuiz_leaderboard_btn_color', $leaderboard_btn_color, '', 'no') === false) {
                update_option('wpAdvQuiz_leaderboard_btn_color', $leaderboard_btn_color);
            }
			
        } else {
            if (isset($this->_post['databaseFix'])) {
                WpAdvQuiz_View_View::admin_notices(__('Database repaired', 'wp-adv-quiz'), 'info');

                $DbUpgradeHelper = new WpAdvQuiz_Helper_DbUpgrade();
                $DbUpgradeHelper->databaseDelta();
            }
        }

        $view->settings = $mapper->fetchAll();
        $view->isRaw = !preg_match('[raw]', apply_filters('the_content', '[raw]a[/raw]'));
        $view->category = $categoryMapper->fetchAll();
        $view->categoryQuiz = $categoryMapper->fetchAll(WpAdvQuiz_Model_Category::CATEGORY_TYPE_QUIZ);
        $view->email = $mapper->getEmailSettings();
        $view->userEmail = $mapper->getUserEmailSettings();
        $view->templateQuiz = $templateMapper->fetchAll(WpAdvQuiz_Model_Template::TEMPLATE_TYPE_QUIZ, false);
        $view->templateQuestion = $templateMapper->fetchAll(WpAdvQuiz_Model_Template::TEMPLATE_TYPE_QUESTION, false);

        $view->toplistDataFormat = get_option('wpAdvQuiz_toplistDataFormat', 'Y/m/d g:i A');
        $view->statisticTimeFormat = get_option('wpAdvQuiz_statisticTimeFormat', 'Y/m/d g:i A');

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
		
        $view->show();
    }
}