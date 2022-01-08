<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

include_once 'lib/helper/WpAdvQuiz_Helper_DbUpgrade.php';

$db = new WpAdvQuiz_Helper_DbUpgrade();
$db->delete();

delete_option('wpAdvQuiz_dbVersion');
delete_option('wpAdvQuiz_version');

delete_option('wpAdvQuiz_addRawShortcode');
delete_option('wpAdvQuiz_jsLoadInHead');
delete_option('wpAdvQuiz_touchLibraryDeactivate');
delete_option('wpAdvQuiz_corsActivated');
delete_option('wpAdvQuiz_toplistDataFormat');
delete_option('wpAdvQuiz_emailSettings');
delete_option('wpAdvQuiz_statisticTimeFormat');