<?php

/**
 * @property WpAdvQuiz_Model_Quiz quiz
 * @property bool inQuiz
 * @property int points
 */
class WpAdvQuiz_View_FrontToplist extends WpAdvQuiz_View_View
{

    public function show()
    {
        ?>
        <div style="margin-bottom: 30px; margin-top: 10px;" class="wpAdvQuiz_toplist"
             data-quiz_id="<?php echo esc_attr($this->quiz->getId()); ?>">
            <?php if (!$this->inQuiz) { ?>
                <h2><?php _e('Leaderboard', 'wp-adv-quiz'); ?>: <?php echo esc_html($this->quiz->getName()); ?></h2>
            <?php } ?>
            <table class="wpAdvQuiz_toplistTable">
                <caption><?php printf(__('maximum of %s points', 'wp-adv-quiz'), $this->points); ?></caption>
                <thead>
                <tr>
                    <th style="width: 40px;"><?php _e('Pos.', 'wp-adv-quiz'); ?></th>
                    <th style="text-align: left !important;"><?php _e('Name', 'wp-adv-quiz'); ?></th>
                    <th style="width: 140px;"><?php _e('Entered on', 'wp-adv-quiz'); ?></th>
                    <th style="width: 60px;"><?php _e('Points', 'wp-adv-quiz'); ?></th>
                    <th style="width: 75px;"><?php _e('Result', 'wp-adv-quiz'); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="5"><?php _e('Table is loading', 'wp-adv-quiz'); ?></td>
                </tr>
                <tr style="display: none;">
                    <td colspan="5"><?php _e('No data available', 'wp-adv-quiz'); ?></td>
                </tr>
                <tr style="display: none;">
                    <td></td>
                    <td style="text-align: left !important;"></td>
                    <td style=" color: rgb(124, 124, 124); font-size: x-small;"></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>

        <?php
    }
}