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

        $view->show();
    }
}