<?php

/**
 * @property array $questionNames
 * @property int $quizId
 * @property string $name
 * @property string $type
 * @property string $data
 */
class WpAdvQuiz_View_QuestionImportPreview extends WpAdvQuiz_View_View
{

    public function show()
    {
        ?>
        <style>
            .wpAdvQuiz_importList {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .wpAdvQuiz_importList li {
                float: left;
                padding: 5px;
                border: 1px solid #B3B3B3;
                margin-right: 5px;
                background-color: #DAECFF;
            }
        </style>
        <div class="wrap wpAdvQuiz_importOverall">
            <h2><?php _e('Import', 'wp-adv-quiz'); ?></h2>

            <p>
                <a class="button-secondary" href="<?php admin_url('admin.php?page=wpAdvQuiz&module=question&quiz_id='.$this->quizId); ?>"><?php _e('back to overview', 'wp-adv-quiz'); ?></a>
            </p>

            <form method="post" action="<?php echo admin_url('admin.php?page=wpAdvQuiz&module=questionImport&action=import&quizId='.$this->quizId); ?>">
                <table class="wp-list-table widefat">
                    <thead>
                    <tr>
                        <th scope="col"><?php _e('Questions', 'wp-adv-quiz'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>
                                <ul class="wpAdvQuiz_importList">
                                    <?php foreach ($this->questionNames as $name) { ?>
                                        <li><?php echo esc_html($name); ?></li>
                                    <?php } ?>
                                </ul>
                                <div style="clear: both;"></div>
                            </th>
                        </tr>
                    </tbody>
                </table>

                <input name="name" value="<?php echo esc_attr($this->name); ?>" type="hidden">
                <input name="type" value="<?php echo esc_attr($this->type); ?>" type="hidden">
                <input name="data" value="<?php echo esc_attr($this->data); ?>" type="hidden">
                <input style="margin-top: 20px;" class="button-primary" name="importSave" value="<?php echo __('Start import', 'wp-adv-quiz'); ?>" type="submit">
            </form>
        </div>

        <?php
    }
}
