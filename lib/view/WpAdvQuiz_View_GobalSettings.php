<?php

/**
 * @property WpAdvQuiz_Model_GlobalSettings settings
 * @property bool isRaw
 * @property WpAdvQuiz_Model_Category[] category
 * @property WpAdvQuiz_Model_Category[] categoryQuiz
 * @property array email
 * @property array userEmail
 * @property WpAdvQuiz_Model_Template[] templateQuiz
 * @property WpAdvQuiz_Model_Template[] templateQuestion
 * @property string toplistDataFormat
 * @property string statisticTimeFormat
 */
class WpAdvQuiz_View_GobalSettings extends WpAdvQuiz_View_View
{

    public function show()
    {
        ?>
        <style>
            .wpAdvQuiz-tab-content:not(.wpAdvQuiz-tab-content-active) {
                display: none;
            }
        </style>

        <div class="wrap wpAdvQuiz_globalSettings">
            <h2 style="margin-bottom: 10px;"><?php _e('Global settings', 'wp-adv-quiz'); ?></h2>

            <div class="nav-tab-wrapper wpAdvQuiz-top-tab-wrapper">
                <a href="#globalContent" data-tab="globalContent" class="nav-tab nav-tab-active"><?php _e('Global settings', 'wp-adv-quiz'); ?></a>
				<a href="#ColorContent" data-tab="colorContent" class="nav-tab"><?php _e('Color settings', 'wp-adv-quiz'); ?></a>
                <a href="#problemContent" data-tab="problemContent" class="nav-tab "><?php _e('Settings in case of problems', 'wp-adv-quiz'); ?></a>
            </div>

            <form method="post">
                <div id="poststuff">
                    <div id="globalContent" class="wpAdvQuiz-tab-content wpAdvQuiz-tab-content-active">

                        <?php $this->globalSettings(); ?>

                    </div>
					
					<div id="colorContent" class="wpAdvQuiz-tab-content">

                        <?php $this->colorSettings(); ?>

                    </div>
					

                    <div id="problemContent" class="wpAdvQuiz-tab-content">
                        <div class="postbox">
                            <?php $this->problemSettings(); ?>
                        </div>
                    </div>

                    <input type="submit" name="submit" class="button-primary" id="wpAdvQuiz_save"
                           value="<?php _e('Save', 'wp-adv-quiz'); ?>">
                </div>
            </form>
        </div>

        <?php
    }

    private function globalSettings()
    {

        ?>
        <div class="postbox">
            <h3 class="hndle"><?php _e('Global settings', 'wp-adv-quiz'); ?></h3>

            <div class="inside">
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th scope="row">
                            <?php _e('Leaderboard time format', 'wp-adv-quiz'); ?>
                        </th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text">
                                    <span><?php _e('Leaderboard time format', 'wp-adv-quiz'); ?></span>
                                </legend>
                                <label>
                                    <input type="radio" name="toplist_date_format"
                                           value="d.m.Y H:i" <?php $this->checked($this->toplistDataFormat,
                                        'd.m.Y H:i'); ?>> 06.11.2010 12:50
                                </label> <br>
                                <label>
                                    <input type="radio" name="toplist_date_format"
                                           value="Y/m/d g:i A" <?php $this->checked($this->toplistDataFormat,
                                        'Y/m/d g:i A'); ?>> 2010/11/06 12:50 AM
                                </label> <br>
                                <label>
                                    <input type="radio" name="toplist_date_format"
                                           value="Y/m/d \a\t g:i A" <?php $this->checked($this->toplistDataFormat,
                                        'Y/m/d \a\t g:i A'); ?>> 2010/11/06 at 12:50 AM
                                </label> <br>
                                <label>
                                    <input type="radio" name="toplist_date_format"
                                           value="Y/m/d \a\t g:ia" <?php $this->checked($this->toplistDataFormat,
                                        'Y/m/d \a\t g:ia'); ?>> 2010/11/06 at 12:50am
                                </label> <br>
                                <label>
                                    <input type="radio" name="toplist_date_format"
                                           value="F j, Y g:i a" <?php $this->checked($this->toplistDataFormat,
                                        'F j, Y g:i a'); ?>> November 6, 2010 12:50 am
                                </label> <br>
                                <label>
                                    <input type="radio" name="toplist_date_format"
                                           value="M j, Y @ G:i" <?php $this->checked($this->toplistDataFormat,
                                        'M j, Y @ G:i'); ?>> Nov 6, 2010 @ 0:50
                                </label> <br>
                                <label>
                                    <input type="radio" name="toplist_date_format"
                                           value="custom" <?php echo in_array($this->toplistDataFormat, array(
                                        'd.m.Y H:i',
                                        'Y/m/d g:i A',
                                        'Y/m/d \a\t g:i A',
                                        'Y/m/d \a\t g:ia',
                                        'F j, Y g:i a',
                                        'M j, Y @ G:i'
                                    )) ? '' : 'checked="checked"'; ?> >
                                    <?php _e('Custom', 'wp-adv-quiz'); ?>:
                                    <input class="medium-text" name="toplist_date_format_custom" style="width: 100px;"
                                           value="<?php echo esc_attr($this->toplistDataFormat); ?>">
                                </label>

                                <p>
                                    <a href="http://codex.wordpress.org/Formatting_Date_and_Time"
                                       target="_blank"><?php _e('Documentation on date and time formatting',
                                            'wp-adv-quiz'); ?></a>
                                </p>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <?php _e('Statistic time format', 'wp-adv-quiz'); ?>
                        </th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text">
                                    <span><?php _e('Statistic time format', 'wp-adv-quiz'); ?></span>
                                </legend>

                                <label>
                                    <?php _e('Select example:', 'wp-adv-quiz'); ?>
                                    <select id="statistic_time_format_select">
                                        <option value="0"></option>
                                        <option value="d.m.Y H:i"> 06.11.2010 12:50</option>
                                        <option value="Y/m/d g:i A"> 2010/11/06 12:50 AM</option>
                                        <option value="Y/m/d \a\t g:i A"> 2010/11/06 at 12:50 AM</option>
                                        <option value="Y/m/d \a\t g:ia"> 2010/11/06 at 12:50am</option>
                                        <option value="F j, Y g:i a"> November 6, 2010 12:50 am</option>
                                        <option value="M j, Y @ G:i"> Nov 6, 2010 @ 0:50</option>
                                    </select>
                                </label>

                                <div style="margin-top: 10px;">
                                    <label>
                                        <?php _e('Time format:', 'wp-adv-quiz'); ?>:
                                        <input class="medium-text" name="statisticTimeFormat"
                                               value="<?php echo esc_attr($this->statisticTimeFormat); ?>">
                                    </label>

                                    <p>
                                        <a href="http://codex.wordpress.org/Formatting_Date_and_Time"
                                           target="_blank"><?php _e('Documentation on date and time formatting',
                                                'wp-adv-quiz'); ?></a>
                                    </p>
                                </div>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <?php _e('Category management', 'wp-adv-quiz'); ?>
                        </th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text">
                                    <span><?php _e('Category management', 'wp-adv-quiz'); ?></span>
                                </legend>
                                <select name="category">
                                    <?php foreach ($this->category as $cat) {
                                        echo '<option value="' . esc_attr($cat->getCategoryId()) . '">' . esc_html($cat->getCategoryName()) . '</option>';

                                    } ?>
                                </select>

                                <div style="padding-top: 5px;">
                                    <input type="text" value="" name="categoryEditText">
                                </div>
                                <div style="padding-top: 5px;">
                                    <input type="button" value="<?php _e('Delete', 'wp-adv-quiz'); ?>"
                                           name="categoryDelete" class="button-secondary">
                                    <input type="button" value="<?php _e('Edit', 'wp-adv-quiz'); ?>" name="categoryEdit"
                                           class="button-secondary">
                                </div>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>

                        <th scope="row">
                            <?php _e('Quiz Category management', 'wp-adv-quiz'); ?>
                        </th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text">
                                    <span><?php _e('Quiz Category management', 'wp-adv-quiz'); ?></span>
                                </legend>
                                <select name="categoryQuiz">
                                    <?php foreach ($this->categoryQuiz as $cat) {
                                        echo '<option value="' . esc_attr($cat->getCategoryId()) . '">' . esc_html($cat->getCategoryName()) . '</option>';

                                    } ?>
                                </select>

                                <div style="padding-top: 5px;">
                                    <input type="text" value="" name="categoryQuizEditText">
                                </div>
                                <div style="padding-top: 5px;">
                                    <input type="button" value="<?php _e('Delete', 'wp-adv-quiz'); ?>"
                                           name="categoryQuizDelete" class="button-secondary">
                                    <input type="button" value="<?php _e('Edit', 'wp-adv-quiz'); ?>"
                                           name="categoryQuizEdit" class="button-secondary">
                                </div>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <?php _e('Quiz template management', 'wp-adv-quiz'); ?>
                        </th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text">
                                    <span><?php _e('Quiz template management', 'wp-adv-quiz'); ?></span>
                                </legend>
                                <select name="templateQuiz">
                                    <?php foreach ($this->templateQuiz as $templateQuiz) {
                                        echo '<option value="' . esc_attr($templateQuiz->getTemplateId()) . '">' . esc_html($templateQuiz->getName()) . '</option>';

                                    } ?>
                                </select>

                                <div style="padding-top: 5px;">
                                    <input type="text" value="" name="templateQuizEditText">
                                </div>
                                <div style="padding-top: 5px;">
                                    <input type="button" value="<?php _e('Delete', 'wp-adv-quiz'); ?>"
                                           name="templateQuizDelete" class="button-secondary">
                                    <input type="button" value="<?php _e('Edit', 'wp-adv-quiz'); ?>"
                                           name="templateQuizEdit" class="button-secondary">
                                </div>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php _e('Question template management', 'wp-adv-quiz'); ?>
                        </th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text">
                                    <span><?php _e('Question template management', 'wp-adv-quiz'); ?></span>
                                </legend>
                                <select name="templateQuestion">
                                    <?php foreach ($this->templateQuestion as $templateQuestion) {
                                        echo '<option value="' . esc_attr($templateQuestion->getTemplateId()) . '">' . esc_html($templateQuestion->getName()) . '</option>';

                                    } ?>
                                </select>

                                <div style="padding-top: 5px;">
                                    <input type="text" value="" name="templateQuestionEditText">
                                </div>
                                <div style="padding-top: 5px;">
                                    <input type="button" value="<?php _e('Delete', 'wp-adv-quiz'); ?>"
                                           name="templateQuestionDelete" class="button-secondary">
                                    <input type="button" value="<?php _e('Edit', 'wp-adv-quiz'); ?>"
                                           name="templateQuestionEdit" class="button-secondary">
                                </div>
                            </fieldset>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
    }
	
	  private function colorSettings()
    {

        ?>
        <div class="postbox">
            <h3 class="hndle"><?php _e('Color settings', 'wp-adv-quiz'); ?></h3>
			<div class="inside">
				<table class="form-table" style="max-width: 800px;">
					<tbody>
						<tr>
							<th scope="row">
								<?php _e('Button Properties', 'wp-adv-quiz'); ?>
							</th>
						</tr>
							<tr>
							<td>
								<p class="description"><strong><?php _e('Button Name', 'wp-adv-quiz'); ?></strong></p>
							</td>
							<td>
							    <p class="description"><strong><?php _e('Button Width', 'wp-adv-quiz'); ?></strong></p>
							</td>
							
							<td>
							   <p class="description"><strong><?php _e('Button Height', 'wp-adv-quiz'); ?></strong></p>
							</td>
							<td>
							    <p class="description"><strong><?php _e('Button Color', 'wp-adv-quiz'); ?></strong></p>
							</td>
						</tr>
						<tr>
							<td>
								<p class="description"><strong><?php _e('Start quiz', 'wp-adv-quiz'); ?></strong></p>
							</td>
							<td>
							    <input class="medium-text" name="start_btn_width" style="width: 100px; text-align:center;" value="<?php echo esc_attr($this->start_btn_width); ?>">
							</td>
							
							<td>
							    <input class="medium-text" name="start_btn_height" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->start_btn_height); ?>">
							</td >
							<td>
							    <input class="medium-text" name="start_btn_color" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->start_btn_color); ?>">
							</td>
						</tr>
						<tr>
							<td>
								<p class="description"><strong><?php _e('Restart quiz', 'wp-adv-quiz'); ?></strong></p>
							</td>
							<td>
							    <input class="medium-text" name="restart_btn_width" style="width: 100px; text-align:center;" value="<?php echo esc_attr($this->restart_btn_width); ?>">
							</td>
							
							<td>
							    <input class="medium-text" name="restart_btn_height" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->restart_btn_height); ?>">
							</td >
							<td>
							    <input class="medium-text" name="restart_btn_color" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->restart_btn_color); ?>">
							</td>
						</tr>
						<tr>
							<td>
								<p class="description"><strong><?php _e('Review question', 'wp-adv-quiz'); ?></strong></p>
							</td>
							<td>
							    <input class="medium-text" name="review_btn_width" style="width: 100px; text-align:center;" value="<?php echo esc_attr($this->review_btn_width); ?>">
							</td>
							
							<td>
							    <input class="medium-text" name="review_btn_height" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->review_btn_height); ?>">
							</td >
							<td>
							    <input class="medium-text" name="review_btn_color" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->review_btn_color); ?>">
							</td>
						</tr>
						<tr>
							<td>
								<p class="description"><strong><?php _e('Quiz-summary', 'wp-adv-quiz'); ?></strong></p>
							</td>
							<td>
							    <input class="medium-text" name="summary_btn_width" style="width: 100px; text-align:center;" value="<?php echo esc_attr($this->summary_btn_width); ?>">
							</td>
							
							<td>
							    <input class="medium-text" name="summary_btn_height" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->summary_btn_height); ?>">
							</td >
							<td>
							    <input class="medium-text" name="summary_btn_color" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->summary_btn_color); ?>">
							</td>
						</tr>
						<tr>
							<td>
								<p class="description"><strong><?php _e('Finish quiz', 'wp-adv-quiz'); ?></strong></p>
							</td>
							<td>
							    <input class="medium-text" name="finish_btn_width" style="width: 100px; text-align:center;" value="<?php echo esc_attr($this->finish_btn_width); ?>">
							</td>
							
							<td>
							    <input class="medium-text" name="finish_btn_height" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->finish_btn_height); ?>">
							</td >
							<td>
							    <input class="medium-text" name="finish_btn_color" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->finish_btn_color); ?>">
							</td>
						</tr>
						<tr>
							<td>
								<p class="description"><strong><?php _e('Hint', 'wp-adv-quiz'); ?></strong></p>
							</td>
							<td>
							    <input class="medium-text" name="hint_btn_width" style="width: 100px; text-align:center;" value="<?php echo esc_attr($this->hint_btn_width); ?>">
							</td>
							
							<td>
							    <input class="medium-text" name="hint_btn_height" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->hint_btn_height); ?>">
							</td >
							<td>
							    <input class="medium-text" name="hint_btn_color" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->hint_btn_color); ?>">
							</td>
						</tr>
						<tr>
							<td>
								<p class="description"><strong><?php _e('Back', 'wp-adv-quiz'); ?></strong></p>
							</td>
							<td>
							    <input class="medium-text" name="back_btn_width" style="width: 100px; text-align:center;" value="<?php echo esc_attr($this->back_btn_width); ?>">
							</td>
							
							<td>
							    <input class="medium-text" name="back_btn_height" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->back_btn_height); ?>">
							</td >
							<td>
							    <input class="medium-text" name="back_btn_color" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->back_btn_color); ?>">
							</td>
						</tr>
						<tr>
							<td>
								<p class="description"><strong><?php _e('Check', 'wp-adv-quiz'); ?></strong></p>
							</td>
							<td>
							    <input class="medium-text" name="check_btn_width" style="width: 100px; text-align:center;" value="<?php echo esc_attr($this->check_btn_width); ?>">
							</td>
							
							<td>
							    <input class="medium-text" name="check_btn_height" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->check_btn_height); ?>">
							</td >
							<td>
							    <input class="medium-text" name="check_btn_color" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->check_btn_color); ?>">
							</td>
						</tr>
						<tr>
							<td>
								<p class="description"><strong><?php _e('Next', 'wp-adv-quiz'); ?></strong></p>
							</td>
							<td>
							    <input class="medium-text" name="next_btn_width" style="width: 100px; text-align:center;" value="<?php echo esc_attr($this->next_btn_width); ?>">
							</td>
							
							<td>
							    <input class="medium-text" name="next_btn_height" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->next_btn_height); ?>">
							</td >
							<td>
							    <input class="medium-text" name="next_btn_color" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->next_btn_color); ?>">
							</td>
						</tr>
					<tr>
							<td>
								<p class="description"><strong><?php _e('Skip question', 'wp-adv-quiz'); ?></strong></p>
							</td>
							<td>
							    <input class="medium-text" name="skip_btn_width" style="width: 100px; text-align:center;" value="<?php echo esc_attr($this->skip_btn_width); ?>">
							</td>
							
							<td>
							    <input class="medium-text" name="skip_btn_height" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->skip_btn_height); ?>">
							</td >
							<td>
							    <input class="medium-text" name="skip_btn_color" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->skip_btn_color); ?>">
							</td>
						</tr>
						<tr>
							<td>
								<p class="description"><strong><?php _e('View questions', 'wp-adv-quiz'); ?></strong></p>
							</td>
							<td>
							    <input class="medium-text" name="view_btn_width" style="width: 100px; text-align:center;" value="<?php echo esc_attr($this->view_btn_width); ?>">
							</td>
							
							<td>
							    <input class="medium-text" name="view_btn_height" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->view_btn_height); ?>">
							</td >
							<td>
							    <input class="medium-text" name="view_btn_color" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->view_btn_color); ?>">
							</td>
						</tr>
						<tr>
							<td>
								<p class="description"><strong><?php _e('Show leaderboard', 'wp-adv-quiz'); ?></strong></p>
							</td>
							<td>
							    <input class="medium-text" name="leaderboard_btn_width" style="width: 100px; text-align:center;" value="<?php echo esc_attr($this->leaderboard_btn_width); ?>">
							</td>
							
							<td>
							    <input class="medium-text" name="leaderboard_btn_height" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->leaderboard_btn_height); ?>">
							</td >
							<td>
							    <input class="medium-text" name="leaderboard_btn_color" style="width: 100px; text-align:center;"value="<?php echo esc_attr($this->leaderboard_btn_color); ?>">
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<?php
    }
			

    private function problemSettings()
    {
        if ($this->isRaw) {
            $rawSystem = __('to activate', 'wp-adv-quiz');
        } else {
            $rawSystem = __('not to activate', 'wp-adv-quiz');
        }

        ?>

        <div class="updated" id="problemInfo" style="display: none;">
            <h3><?php _e('Please note', 'wp-adv-quiz'); ?></h3>

            <p>
                <?php _e('These settings should only be set in cases of problems with Wp-Adv-Quiz.', 'wp-adv-quiz'); ?>
            </p>
        </div>

        <h3 class="hndle"><?php _e('Settings in case of problems', 'wp-adv-quiz'); ?></h3>
        <div class="inside">
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">
                        <?php _e('Automatically add [raw] shortcode', 'wp-adv-quiz'); ?>
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e('Automatically add [raw] shortcode', 'wp-adv-quiz'); ?></span>
                            </legend>
                            <label>
                                <input type="checkbox" value="1"
                                       name="addRawShortcode" <?php echo esc_attr($this->settings->isAddRawShortcode()) ? 'checked="checked"' : '' ?> >
                                <?php _e('Activate', 'wp-adv-quiz'); ?> <span
                                    class="description">( <?php printf(__('It is recommended %s this option on your system.',
                                        'wp-adv-quiz'),
                                        '<span style=" font-weight: bold;">' . $rawSystem . '</span>'); ?> )</span>
                            </label>

                            <p class="description">
                                <?php _e('If this option is activated, a [raw] shortcode is automatically set around WpAdvQuiz shortcode ( [WpAdvQuiz X] ) into [raw] [WpAdvQuiz X] [/raw]',
                                    'wp-adv-quiz'); ?>
                            </p>

                            <p class="description">
                                <?php _e('Own themes changes internal  order of filters, what causes the problems. With additional shortcode [raw] this is prevented.',
                                    'wp-adv-quiz'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php _e('Do not load the Javascript-files in the footer', 'wp-adv-quiz'); ?>
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e('Do not load the Javascript-files in the footer',
                                        'wp-adv-quiz'); ?></span>
                            </legend>
                            <label>
                                <input type="checkbox" value="1"
                                       name="jsLoadInHead" <?php echo esc_attr($this->settings->isJsLoadInHead()) ? 'checked="checked"' : '' ?> >
                                <?php _e('Activate', 'wp-adv-quiz'); ?>
                            </label>

                            <p class="description">
                                <?php _e('Generally all WpAdvQuiz-Javascript files are loaded in the footer and only when they are really needed.',
                                    'wp-adv-quiz'); ?>
                            </p>

                            <p class="description">
                                <?php _e('In very old Wordpress themes this can lead to problems.', 'wp-adv-quiz'); ?>
                            </p>

                            <p class="description">
                                <?php _e('If you activate this option, all WpAdvQuiz-Javascript files are loaded in the header even if they are not needed.',
                                    'wp-adv-quiz'); ?>
                            </p>

                            <p class="description">
                                <?php printf(__('Anyone who wants to learn more about this topic should read through the following websites %s and %s.',
                                    'wp-adv-quiz'),
                                    '<a href="http://codex.wordpress.org/Theme_Development#Footer_.28footer.php.29" target="_blank">Theme Development</a>',
                                    '<a href="http://codex.wordpress.org/Function_Reference/wp_footer" target="_blank">Function Reference/wp footer</a>'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php _e('Touch Library', 'wp-adv-quiz'); ?>
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e('Touch Library', 'wp-adv-quiz'); ?></span>
                            </legend>
                            <label>
                                <input type="checkbox" value="1"
                                       name="touchLibraryDeactivate" <?php echo esc_attr($this->settings->isTouchLibraryDeactivate()) ? 'checked="checked"' : '' ?> >
                                <?php _e('Deactivate', 'wp-adv-quiz'); ?>
                            </label>

                            <p class="description">
                                <?php _e('In Version 0.13 a new Touch Library was added for mobile devices.',
                                    'wp-adv-quiz'); ?>
                            </p>

                            <p class="description">
                                <?php _e('If you have any problems with the Touch Library, please deactivate it.',
                                    'wp-adv-quiz'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php _e('jQuery support cors', 'wp-adv-quiz'); ?>
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e('jQuery support cors', 'wp-adv-quiz'); ?></span>
                            </legend>
                            <label>
                                <input type="checkbox" value="1"
                                       name="corsActivated" <?php echo esc_attr($this->settings->isCorsActivated()) ? 'checked="checked"' : '' ?> >
                                <?php _e('Activate', 'wp-adv-quiz'); ?>
                            </label>

                            <p class="description">
                                <?php _e('Is required only in rare cases.', 'wp-adv-quiz'); ?>
                            </p>

                            <p class="description">
                                <?php _e('If you have problems with the front ajax, please activate it.',
                                    'wp-adv-quiz'); ?>
                            </p>

                            <p class="description">
                                <?php _e('e.g. Domain with special characters in combination with IE',
                                    'wp-adv-quiz'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php _e('Repair database', 'wp-adv-quiz'); ?>
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e('Repair database', 'wp-adv-quiz'); ?></span>
                            </legend>
                            <input type="submit" name="databaseFix" class="button-primary"
                                   value="<?php _e('Repair database', 'wp-adv-quiz'); ?>">

                            <p class="description">
                                <?php _e('No date will be deleted. Only WP-Adv-Quiz tables will be repaired.',
                                    'wp-adv-quiz'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <?php
    }

}
