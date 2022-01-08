<?php

class WpAdvQuiz_Helper_TinyMcePlugin
{

    public function __construct()
    {
        $this->addHooks();
    }

    protected function addHooks()
    {
        add_filter('mce_external_plugins', [$this, 'registerTinyMcePlugin']);
        add_filter('mce_buttons', [$this, 'addTinymceButton']);
        add_action('wp_ajax_wpAdvQuiz_generate_mce_shortcode', [$this, 'generateTinyMceShortcodeView']);
    }

    public function addTinymceButton($buttons)
    {
        $buttons[] = 'wp_adv_quiz_button_mce';

        return $buttons;
    }

    public function registerTinyMcePlugin($plugin_array)
    {
        $plugin_array['wp_adv_quiz_button_mce'] = plugins_url('js/wpAdvQuiz_mce_shortcode.js', WPADVQUIZ_FILE);

        return $plugin_array;
    }

    public function generateTinyMceShortcodeView()
    {
        $mapper = new WpAdvQuiz_Model_QuizMapper();

        $view = new WpAdvQuiz_View_TinyMceShortcodeWindow();
        $view->quizzes = $mapper->fetchAll();
        $view->show();

        die();
    }

    public static function init()
    {
        return new self();
    }
}
