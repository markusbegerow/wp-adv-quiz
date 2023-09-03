<?php
/**
 * WP-Adv-Quiz
 *
 * @wordpress-plugin
 * Plugin Name: WP-Adv-Quiz
 * Plugin URI: http://wordpress.org/extend/plugins/wp-adv-quiz
 * Description: A powerful and beautiful quiz plugin for WordPress.
 * Version: 1.0.2
 * Requires at least: 4.6
 * Requires PHP: 5.6
 * Author: Markus Begerow
 * Author URI: https://github.com/markusbegerow
 * Text Domain: wp-adv-quiz
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

define('WPADVQUIZ_VERSION', '1.0.1');

define('WPADVQUIZ_DEV', false);

define('WPADVQUIZ_PATH', dirname(__FILE__));
define('WPADVQUIZ_URL', plugins_url('', __FILE__));
define('WPADVQUIZ_FILE', __FILE__);
define('WPADVQUIZ_PPATH', dirname(plugin_basename(__FILE__)));
define('WPADVQUIZ_PLUGIN_PATH', WPADVQUIZ_PATH . '/plugin');

$uploadDir = wp_upload_dir();

define('WPADVQUIZ_CAPTCHA_DIR', $uploadDir['basedir'] . '/wp_adv_quiz_captcha');
define('WPADVQUIZ_CAPTCHA_URL', $uploadDir['baseurl'] . '/wp_adv_quiz_captcha');

spl_autoload_register('wpAdvQuiz_autoload');

register_activation_hook(__FILE__, ['WpAdvQuiz_Helper_Upgrade', 'upgrade']);

add_action('plugins_loaded', 'wpAdvQuiz_pluginLoaded');

WpAdvQuiz_Helper_GutenbergBlock::init();

if (is_admin()) {
    new WpAdvQuiz_Controller_Admin();
} else {
    new WpAdvQuiz_Controller_Front();
}

function wpAdvQuiz_autoload($class)
{
    $c = explode('_', $class);

    if ($c === false || count($c) != 3 || $c[0] !== 'WpAdvQuiz') {
        return;
    }

    switch ($c[1]) {
        case 'View':
            $dir = 'view';
            break;
        case 'Model':
            $dir = 'model';
            break;
        case 'Helper':
            $dir = 'helper';
            break;
        case 'Controller':
            $dir = 'controller';
            break;
        case 'Plugin':
            $dir = 'plugin';
            break;
        default:
            return;
    }

    $classPath = WPADVQUIZ_PATH . '/lib/' . $dir . '/' . $class . '.php';

    if (file_exists($classPath)) {
        /** @noinspection PhpIncludeInspection */
        include_once $classPath;
    }
}

function wpAdvQuiz_pluginLoaded()
{
	load_plugin_textdomain('wp-adv-quiz', false, WPADVQUIZ_PPATH . '/languages');

    if (get_option('wpAdvQuiz_version') !== WPADVQUIZ_VERSION) {
        WpAdvQuiz_Helper_Upgrade::upgrade();
    }
}

function wpAdvQuiz_achievementsV3()
{
    if (function_exists('achievements')) {
        achievements()->extensions->wp_adv_quiz = new WpAdvQuiz_Plugin_BpAchievementsV3();

        do_action('wpAdvQuiz_achievementsV3');
    }
}

add_action('dpa_ready', 'wpAdvQuiz_achievementsV3');

