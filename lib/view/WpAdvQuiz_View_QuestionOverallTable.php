<?php

class WpAdvQuiz_View_QuestionOverallTable extends WP_List_Table
{
    /** @var  WpAdvQuiz_Model_Question[] */
    private $questionItems;

    private $questionCount;
    private $perPage;

    /** @var  WpAdvQuiz_Model_Category[] */
    private $categoryItems;

    public static function getColumnDefs()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name', 'wp-adv-quiz'),
            'category' => __('Category', 'wp-adv-quiz'),
            'points' => __('Points', 'wp-adv-quiz'),
        );

        return $columns;
    }

    function __construct($questionItems, $questionCount, $categoryItems, $perPage)
    {
        parent::__construct(array(
            'singular' => __('Quiz', 'wp-adv-quiz'),
            'plural' => __('Quiz', 'wp-adv-quiz'),
            'ajax' => false,
            'screen' => get_current_screen()->id
        ));

        $this->questionItems = $questionItems;
        $this->questionCount = $questionCount;
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
        $actions = array();

        if (current_user_can('wpAdvQuiz_edit_quiz')) {
            $actions['wpAdvQuiz_edit'] = sprintf('<a href="?page=wpAdvQuiz&module=question&action=addEdit&quiz_id=%1$s&questionId=%2$s">' . __('Edit',
                    'wp-adv-quiz') . '</a>',
                $item['quizId'], $item['ID']);
        }

        if (current_user_can('wpAdvQuiz_delete_quiz')) {
            $actions['wpAdvQuiz_delete'] = sprintf('<a style="color: red;" value="' . wp_create_nonce('delete_question') .'"; href="?page=wpAdvQuiz&module=question&action=delete&quiz_id=%1$s&id=%2$s">' . __('Delete',
                    'wp-adv-quiz') . '</a>',
                $item['quizId'], $item['ID']);
        }

        if (current_user_can('wpAdvQuiz_edit_quiz')) {
            return sprintf('<a class="row-title" href="?page=wpAdvQuiz&module=question&action=addEdit&quiz_id=%1$s&questionId=%2$s">%3$s</a> %4$s',
                $item['quizId'], $item['ID'], $item['name'], $this->row_actions($actions));
        } else {
            return sprintf('<a class="row-title" href="#">%2$s</a> %3$s', $item['ID'], $item['name'],
                $this->row_actions($actions));
        }
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
            '<input type="checkbox" name="questions[]" value="%1$s" />',
            $item['ID']
        );
    }

    function prepare_items()
    {
        $this->set_pagination_args(array(
            'total_items' => $this->questionCount,
            'per_page' => $this->perPage
        ));

        $items = array();

        foreach ($this->questionItems as $i => $q) {
            $items[] = array(
                'ID' => $q->getId(),
                'quizId' => $q->getQuizId(),
                'name' => $q->getTitle(),
                'category' => $q->getCategoryName(),
                'points' => $q->getPoints(),
                'sort' => $q->getSort()
            );
        }

        $this->items = $items;
    }


}
