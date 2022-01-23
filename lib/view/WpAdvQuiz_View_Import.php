<?php

/**
 * @property bool error
 * @property string importType
 * @property bool finish
 * @property array import
 * @property string importData
 * @property string name
 */
class WpAdvQuiz_View_Import extends WpAdvQuiz_View_View
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

            <p><a class="button-secondary" href="admin.php?page=wpAdvQuiz"><?php _e('back to overview',
                        'wp-adv-quiz'); ?></a></p>
            <?php if ($this->error) { ?>
                <div style="padding: 10px; background-color: rgb(255, 199, 199); margin-top: 20px; border: 1px dotted;">
                    <h3 style="margin-top: 0;"><?php _e('Error', 'wp-adv-quiz'); ?></h3>

                    <div>
                        <?php echo esc_html($this->error); ?>
                    </div>
                </div>
            <?php } else {
                if ($this->finish) { ?>
                    <div style="padding: 10px; background-color: #C7E4FF; margin-top: 20px; border: 1px dotted;">
                        <h3 style="margin-top: 0;"><?php _e('Successfully', 'wp-adv-quiz'); ?></h3>

                        <div>
                            <?php _e('Import completed successfully', 'wp-adv-quiz'); ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <form method="post">
                        <table class="wp-list-table widefat">
                            <thead>
                            <tr>
                                <th scope="col" width="30px"></th>
                                <th scope="col" width="40%"><?php _e('Quiz name', 'wp-adv-quiz'); ?></th>
                                <th scope="col"><?php _e('Questions', 'wp-adv-quiz'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($this->import['master'] as $master) { ?>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="importItems[]"
                                               value="<?php echo esc_attr($master->getId()); ?>" checked="checked">
                                    </th>
                                    <th><?php echo esc_html($master->getName()); ?></th>
                                    <th>
                                        <ul class="wpAdvQuiz_importList">
                                            <?php if (isset($this->import['question'][$master->getId()])) { ?>
                                                <?php foreach ($this->import['question'][$master->getId()] as $question) { ?>
                                                    <li><?php echo esc_html($question->getTitle()); ?></li>
                                                <?php }
                                            } ?>
                                        </ul>
                                        <div style="clear: both;"></div>
                                    </th>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <input name="name" value="<?php echo esc_attr($this->name); ?>" type="hidden">
                        <input name="importData" value="<?php echo esc_attr($this->importData); ?>" type="hidden">
                        <input name="importType" value="<?php echo esc_attr($this->importType); ?>" type="hidden">
                        <input style="margin-top: 20px;" class="button-primary" name="importSave"
                               value="<?php echo __('Start import', 'wp-adv-quiz'); ?>" type="submit">
                    </form>
                <?php }
            } ?>
        </div>

        <?php
    }
}
