<?php

class WpAdvQuiz_Controller_Admin
{

    protected $_ajax;

    public function __construct()
    {
		
		$this->_sec = new WpAdvQuiz_Helper_Security();
		$this->_sec->init();

        $this->_ajax = new WpAdvQuiz_Controller_Ajax();
        $this->_ajax->init();
		
        add_action('admin_menu', array($this, 'register_page'));

        add_filter('set-screen-option', array($this, 'setScreenOption'), 10, 3);

        WpAdvQuiz_Helper_TinyMcePlugin::init();
    }

    public function setScreenOption($status, $option, $value)
    {
        if (in_array($option, array('wp_adv_quiz_quiz_overview_per_page', 'wp_adv_quiz_question_overview_per_page'))) {
            return $value;
        }

        return $status;
    }

    private function localizeScript()
    {
        global $wp_locale;

        $isRtl = isset($wp_locale->is_rtl) ? $wp_locale->is_rtl : false;

        $translation_array = array(
            'delete_msg' => __('Do you really want to delete the quiz/question?', 'wp-adv-quiz'),
            'no_title_msg' => __('Title is not filled!', 'wp-adv-quiz'),
            'no_question_msg' => __('No question deposited!', 'wp-adv-quiz'),
            'no_correct_msg' => __('Correct answer was not selected!', 'wp-adv-quiz'),
            'no_answer_msg' => __('No answer deposited!', 'wp-adv-quiz'),
            'no_quiz_start_msg' => __('No quiz description filled!', 'wp-adv-quiz'),
            'fail_grade_result' => __('The percent values in result text are incorrect.', 'wp-adv-quiz'),
            'no_nummber_points' => __('No number in the field "Points" or less than 1', 'wp-adv-quiz'),
            'no_nummber_points_new' => __('No number in the field "Points" or less than 0', 'wp-adv-quiz'),
            'no_selected_quiz' => __('No quiz selected', 'wp-adv-quiz'),
            'reset_statistics_msg' => __('Do you really want to reset the statistic?', 'wp-adv-quiz'),
            'no_data_available' => __('No data available', 'wp-adv-quiz'),
            'no_sort_element_criterion' => __('No sort element in the criterion', 'wp-adv-quiz'),
            'dif_points' => __('"Different points for every answer" is not possible at "Free" choice', 'wp-adv-quiz'),
            'category_no_name' => __('You must specify a name.', 'wp-adv-quiz'),
            'confirm_delete_entry' => __('This entry should really be deleted?', 'wp-adv-quiz'),
            'not_all_fields_completed' => __('Not all fields completed.', 'wp-adv-quiz'),
            'temploate_no_name' => __('You must specify a template name.', 'wp-adv-quiz'),
            'closeText' => __('Close', 'wp-adv-quiz'),
            'currentText' => __('Today', 'wp-adv-quiz'),
            'monthNames' => array_values($wp_locale->month),
            'monthNamesShort' => array_values($wp_locale->month_abbrev),
            'dayNames' => array_values($wp_locale->weekday),
            'dayNamesShort' => array_values($wp_locale->weekday_abbrev),
            'dayNamesMin' => array_values($wp_locale->weekday_initial),
//			'dateFormat'        => WpAdvQuiz_Helper_Until::convertPHPDateFormatToJS(get_option('date_format', 'm/d/Y')),
            //e.g. "9 de setembro de 2014" -> change to "hard" dateformat
            'dateFormat' => 'mm/dd/yy',
            'firstDay' => get_option('start_of_week'),
            'isRTL' => $isRtl
        );

        wp_localize_script('wpAdvQuiz_admin_javascript', 'wpAdvQuizLocalize', $translation_array);
    }

    public function enqueueScript()
    {
        wp_enqueue_script(
            'wpAdvQuiz_admin_javascript',
            plugins_url('js/wpAdvQuiz_admin' . (WPADVQUIZ_DEV ? '' : '.min') . '.js', WPADVQUIZ_FILE),
            array('jquery', 'jquery-ui-sortable', 'jquery-ui-datepicker'),
            WPADVQUIZ_VERSION
        );


        wp_enqueue_style(
            'jquery-ui',
            plugins_url('css/jquery-ui.min.css', WPADVQUIZ_FILE),
            array(),
            '1.11.4'
        );

        $this->localizeScript();
    }

    public function register_page()
    {
        $pages = array();

        $pages[] = add_menu_page(
            'WP-Adv-Quiz',
            'WP-Adv-Quiz',
            'wpAdvQuiz_show',
            'wpAdvQuiz',
            array($this, 'route'));

        $pages[] = add_submenu_page(
            'wpAdvQuiz',
            __('Global settings', 'wp-adv-quiz'),
            __('Global settings', 'wp-adv-quiz'),
            'wpAdvQuiz_change_settings',
            'wpAdvQuiz_glSettings',
            array($this, 'route'));

        $pages[] = add_submenu_page(
            'wpAdvQuiz',
            __('Support & More', 'wp-adv-quiz'),
            __('Support & More', 'wp-adv-quiz'),
            'wpAdvQuiz_show',
            'wpAdvQuiz_waq_support',
            array($this, 'route'));

        foreach ($pages as $p) {
            add_action('admin_print_scripts-' . $p, array($this, 'enqueueScript'));
            add_action('load-' . $p, array($this, 'routeLoadAction'));
        }
    }

    public function routeLoadAction()
    {
        $screen = get_current_screen();

        if (!empty($screen)) {
            // Workaround for wp_ajax_hidden_columns() with sanitize_key()
            $name = strtolower($screen->id);

			$module = filter_input(INPUT_GET,'module',FILTER_SANITIZE_STRING);
			$module = $module ? : '';
		
            if ($module != '') {
                $name .= '_' . strtolower($module);
            }

            set_current_screen($name);

            $screen = get_current_screen();
        }

        $this->_route(true);
    }

    public function route()
    {
        $this->_route();
    }

    private function _route($routeAction = false)
    {
		
		$module = filter_input(INPUT_GET,'module',FILTER_SANITIZE_STRING);
		$module = $module ? : 'overallView';
		
		$page = filter_input(INPUT_GET,'page',FILTER_SANITIZE_STRING);
		$page = $page ? : 'wpAdvQuiz';

        if (isset($page)) {
            if (preg_match('#wpAdvQuiz_(.+)#', trim($page), $matches)) {
                $module = $matches[1];
            }
        }

        $c = null;

        switch ($module) {
            case 'overallView':
                $c = new WpAdvQuiz_Controller_Quiz();
                break;
            case 'question':
                $c = new WpAdvQuiz_Controller_Question();
                break;
            case 'preview':
                $c = new WpAdvQuiz_Controller_Preview();
                break;
            case 'statistics':
                $c = new WpAdvQuiz_Controller_Statistics();
                break;
            case 'importExport':
                $c = new WpAdvQuiz_Controller_ImportExport();
                break;
            case 'glSettings':
                $c = new WpAdvQuiz_Controller_GlobalSettings();
                break;
            case 'styleManager':
                $c = new WpAdvQuiz_Controller_StyleManager();
                break;
            case 'toplist':
                $c = new WpAdvQuiz_Controller_Toplist();
                break;
            case 'waq_support':
                $c = new WpAdvQuiz_Controller_WaqSupport();
                break;
            case 'info_adaptation':
                $c = new WpAdvQuiz_Controller_InfoAdaptation();
                break;
            case 'questionExport':
                $c = new WpAdvQuiz_Controller_QuestionExport();
                break;
            case 'questionImport':
                $c = new WpAdvQuiz_Controller_QuestionImport();
                break;
            case 'statistic_export':
                $c = new WpAdvQuiz_Controller_StatisticExport();
                break;
        }

        if ($c !== null) {
            if ($routeAction) {
                if (method_exists($c, 'routeAction')) {
                    $c->routeAction();
                }
            } else {
                $c->route();
            }
        }
    }
}
