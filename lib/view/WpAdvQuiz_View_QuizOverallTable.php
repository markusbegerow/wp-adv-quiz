<?php

class WpAdvQuiz_View_QuizOverallTable extends WP_List_Table
{
    /** @var  WpAdvQuiz_Model_Quiz[] */
    private $quizItems;

    private $quizCount;
    private $perPage;

    /** @var  WpAdvQuiz_Model_Category[] */
    private $categoryItems;

    public static function getColumnDefs()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name', 'wp-adv-quiz'),
            'category' => __('Category', 'wp-adv-quiz'),
            'shortcode' => __('Shortcode', 'wp-adv-quiz'),
            'shortcode_leaderboard' => __('Shortcode-Leaderboard', 'wp-adv-quiz')
        );

        return $columns;
    }

    function __construct($quizItems, $quizCount, $categoryItems, $perPage)
    {
        parent::__construct(array(
            'singular' => __('Quiz', 'wp-adv-quiz'),
            'plural' => __('Quiz', 'wp-adv-quiz'),
            'ajax' => false,
            'screen' => 'toplevel_page_wpproquiz'
        ));

        $this->quizItems = $quizItems;
        $this->quizCount = $quizCount;
        $this->categoryItems = $categoryItems;
        $this->perPage = $perPage;
    }

    function no_items()
    {
        _e('No data available', 'wp-adv-quiz');
    }

    function column_default($item, $column_name)
    {
        return isset($item[$column_name]) ? $item[$column_name] : '';
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'name' => array('name', false),
            'category' => array('category', false),
        );

        return $sortable_columns;
    }

    function get_columns()
    {
        return get_column_headers(get_current_screen());
    }

    function column_name($item)
    {
        $actions = array(
            'wpAdvQuiz_questions' => sprintf('<a href="?page=wpAdvQuiz&module=question&quiz_id=%s">' . __('Questions',
                    'wp-adv-quiz') . '</a>', $item['ID']),
        );

        if (current_user_can('wpAdvQuiz_edit_quiz')) {
            $actions['wpAdvQuiz_edit'] = sprintf('<a href="?page=wpAdvQuiz&action=addEdit&quizId=%s">' . __('Edit',
                    'wp-adv-quiz') . '</a>', $item['ID']);
        }

        if (current_user_can('wpAdvQuiz_delete_quiz')) {
            $actions['wpAdvQuiz_delete'] = sprintf('<a style="color: red;" href="?page=wpAdvQuiz&action=delete&id=%s">' . __('Delete',
                    'wp-adv-quiz') . '</a>', $item['ID']);
        }

        $actions['wpAdvQuiz_preview'] = sprintf('<a href="?page=wpAdvQuiz&module=preview&id=%s">' . __('Preview',
                'wp-adv-quiz') . '</a>', $item['ID']);

        if (current_user_can('wpAdvQuiz_show_statistics')) {
            $actions['wpAdvQuiz_statistics'] = sprintf('<a href="?page=wpAdvQuiz&module=statistics&id=%s">' . __('Statistics',
                    'wp-adv-quiz') . '</a>', $item['ID']);
        }

        if (current_user_can('wpAdvQuiz_toplist_edit')) {
            $actions['wpAdvQuiz_leaderboard'] = sprintf('<a href="?page=wpAdvQuiz&module=toplist&id=%s">' . __('Leaderboard',
                    'wp-adv-quiz') . '</a>', $item['ID']);
        }

        return sprintf('<a class="row-title" href="?page=wpAdvQuiz&module=question&quiz_id=%1$s">%2$s</a> %3$s',
            $item['ID'], $item['name'], $this->row_actions($actions));
    }

    function get_bulk_actions()
    {
        $actions = array();

        if (current_user_can('wpAdvQuiz_delete_quiz')) {
            $actions['delete'] = __('Delete', 'wp-adv-quiz');
        }

        if (current_user_can('wpAdvQuiz_export')) {
            $actions['export'] = __('Export', 'wp-adv-quiz');
        }

        if (current_user_can('wpAdvQuiz_edit_quiz')) {
            $actions['set_category'] = __('Set Category', 'wp-adv-quiz');
        }

        return $actions;
    }

    function extra_tablenav($which)
    {
        if ($which != 'top') {
            return;
        }
        ?>

        <div class="alignleft actions">
            <label class="screen-reader-text" for="cat"><?php _e('Filter by category'); ?></label>
            <select name="cat" id="cat" class="postform">
                <option value="0"><?php _e('All categories'); ?> </option>
                <?php
                foreach ($this->categoryItems as $c) {
					
										
					$cat = filter_input(INPUT_GET,'cat',FILTER_SANITIZE_STRING);
					$cat = $cat ? : '';
					
                    $isSet = $cat == $c->getCategoryId();
                    echo '<option class="level-0" value="' . esc_attr($c->getCategoryId()) . '" ' . ($isSet ? 'selected' : '') . '>' . esc_html($c->getCategoryName()) . '</option>';
                }
                ?>
            </select>
            <?php submit_button(__('Filter'), 'button', 'filter_action', false, array('id' => 'post-query-submit')); ?>
        </div>

        <?php
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="quiz[]" value="%s" />', $item['ID']
        );
    }

    function prepare_items()
    {
        $this->set_pagination_args(array(
            'total_items' => $this->quizCount,
            'per_page' => $this->perPage
        ));

        $items = array();

        foreach ($this->quizItems as $q) {
            $items[] = array(
                'ID' => $q->getId(),
                'name' => $q->getName(),
                'category' => $q->getCategoryName(),
                'shortcode' => '[WpAdvQuiz ' . $q->getId() . ']',
                'shortcode_leaderboard' => $q->isToplistActivated() ? '[WpAdvQuiz_toplist ' . $q->getId() . ']' : ''
            );
        }

        $this->items = $items;
    }
}