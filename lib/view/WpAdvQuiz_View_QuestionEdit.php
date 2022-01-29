<?php

/**
 * @property WpAdvQuiz_Model_Category[] categories
 * @property WpAdvQuiz_Model_Quiz quiz
 * @property WpAdvQuiz_Model_Template[] templates
 * @property WpAdvQuiz_Model_Question question
 * @property string header
 * @property array answerData
 */
class WpAdvQuiz_View_QuestionEdit extends WpAdvQuiz_View_View
{
    public function show()
    {

        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');

        ?>
        <div class="wrap wpAdvQuiz_questionEdit">
            <h2 style="margin-bottom: 10px;"><?php echo esc_html($this->header); ?></h2>
            <!-- <form action="admin.php?page=wpAdvQuiz&module=question&action=show&quiz_id=<?php echo esc_attr($this->quiz->getId()); ?>" method="POST"> -->
            <form
                action="admin.php?page=wpAdvQuiz&module=question&action=addEdit&quiz_id=<?php echo esc_attr($this->quiz->getId()); ?>&questionId=<?php echo esc_attr($this->question->getId()); ?>"
                method="POST">
                <a style="float: left;" class="button-secondary"
                   href="admin.php?page=wpAdvQuiz&module=question&action=show&quiz_id=<?php echo esc_attr($this->quiz->getId()); ?>"><?php _e('back to overview',
                        'wp-adv-quiz'); ?></a>

                <div style="float: right;">
                    <select name="templateLoadId">
                        <?php
                        foreach ($this->templates as $template) {
                            echo '<option value="', esc_attr($template->getTemplateId()), '">', esc_html($template->getName()), '</option>';
                        }
                        ?>
                    </select>
                    <input type="submit" name="templateLoad" value="<?php _e('load template', 'wp-adv-quiz'); ?>"
                           class="button-primary">
                </div>
                <div style="clear: both;"></div>
	
                <!-- <input type="hidden" value="edit" name="hidden_action">
		<input type="hidden" value="<?php echo esc_html($this->question->getId()); ?>" name="questionId">-->
                <div id="poststuff">
                    <div class="postbox">
                        <h3 class="hndle"><?php _e('Title', 'wp-adv-quiz'); ?><?php _e('(optional)',
                                'wp-adv-quiz'); ?></h3>

                        <div class="inside">
                            <p class="description">
                                <?php _e('The title is used for overview, it is not visible in quiz. If you leave the title field empty, a title will be generated.',
                                    'wp-adv-quiz'); ?>
                            </p>
                            <input name="title" class="regular-text" value="<?php echo esc_attr($this->question->getTitle()); ?>"
                                   type="text">
                        </div>
                    </div>
                    <div class="postbox">
                        <h3 class="hndle"><?php _e('Points', 'wp-adv-quiz'); ?><?php _e('(required)',
                                'wp-adv-quiz'); ?></h3>

                        <div class="inside">
                            <div>
                                <p class="description">
                                    <?php _e('Points for this question (Standard is 1 point)', 'wp-adv-quiz'); ?>
                                </p>
                                <label>
                                    <input name="points" class="small-text"
                                           value="<?php echo esc_attr($this->question->getPoints()); ?>" type="number"
                                           min="1"> <?php _e('Points', 'wp-adv-quiz'); ?>
                                </label>

                                <p class="description">
                                    <?php _e('This points will be rewarded, only if the user closes the question correctly.',
                                        'wp-adv-quiz'); ?>
                                </p>
                            </div>
                            <div style="margin-top: 10px;">
                                <label>
                                    <input name="answerPointsActivated" type="checkbox"
                                           value="1" <?php echo esc_attr($this->question->isAnswerPointsActivated()) ? 'checked="checked"' : '' ?>>
                                    <?php _e('Different points for each answer', 'wp-adv-quiz'); ?>
                                </label>

                                <p class="description">
                                    <?php _e('If you enable this option, you can enter different points for every answer.',
                                        'wp-adv-quiz'); ?>
                                </p>
                            </div>
                            <div style="margin-top: 10px; display: none;" id="wpAdvQuiz_showPointsBox">
                                <label>
                                    <input name="showPointsInBox" value="1"
                                           type="checkbox" <?php echo esc_attr($this->question->isShowPointsInBox()) ? 'checked="checked"' : '' ?>>
                                    <?php _e('Show reached points in the correct- and incorrect message?',
                                        'wp-adv-quiz'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="postbox">
                        <h3 class="hndle"><?php _e('Category', 'wp-adv-quiz'); ?><?php _e('(optional)',
                                'wp-adv-quiz'); ?></h3>

                        <div class="inside">
                            <p class="description">
                                <?php _e('You can assign classify category for a question. Categories are e.g. visible in statistics function.',
                                    'wp-adv-quiz'); ?>
                            </p>

                            <p class="description">
                                <?php _e('You can manage categories in global settings.', 'wp-adv-quiz'); ?>
                            </p>

                            <div>
                                <select name="category">
                                    <option value="-1">--- <?php _e('Create new category', 'wp-adv-quiz'); ?>----
                                    </option>
                                    <option
                                        value="0" <?php echo esc_attr($this->question->getCategoryId()) == 0 ? 'selected="selected"' : ''; ?>>
                                        --- <?php _e('No category', 'wp-adv-quiz'); ?> ---
                                    </option>
                                    <?php
                                    foreach ($this->categories as $cat) {
                                        echo '<option ' . ($this->question->getCategoryId() == $cat->getCategoryId() ? 'selected="selected"' : '') . ' value="' . esc_attr($cat->getCategoryId()) . '">' . esc_html($cat->getCategoryName()) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div style="display: none;" id="categoryAddBox">
                                <h4><?php _e('Create new category', 'wp-adv-quiz'); ?></h4>
                                <input type="text" name="categoryAdd" value="">
                                <input type="button" class="button-secondary" name="" id="categoryAddBtn"
                                       value="<?php _e('Create', 'wp-adv-quiz'); ?>">
                            </div>
                            <div id="categoryMsgBox"
                                 style="display:none; padding: 5px; border: 1px solid rgb(160, 160, 160); background-color: rgb(255, 255, 168); font-weight: bold; margin: 5px; ">
                                Kategorie gespeichert
                            </div>
                        </div>
                    </div>
                    <div class="postbox">
                        <h3 class="hndle"><?php _e('Question', 'wp-adv-quiz'); ?><?php _e('(required)',
                                'wp-adv-quiz'); ?></h3>

                        <div class="inside">
                            <?php
                            wp_editor($this->question->getQuestion(), "question", array('textarea_rows' => 5));
                            ?>
                        </div>
                    </div>
                    <div class="postbox"
                         style="<?php echo esc_attr($this->quiz->isHideAnswerMessageBox()) ? '' : 'display: none;'; ?>">
                        <h3 class="hndle"><?php _e('Message with the correct / incorrect answer',
                                'wp-adv-quiz'); ?></h3>

                        <div class="inside">
                            <?php _e('Deactivated in quiz settings.', 'wp-adv-quiz'); ?>
                        </div>
                    </div>
                    <div style="<?php echo esc_attr($this->quiz->isHideAnswerMessageBox()) ? 'display: none;' : ''; ?>">
                        <div class="postbox">
                            <h3 class="hndle"><?php _e('Message with the correct answer',
                                    'wp-adv-quiz'); ?><?php _e('(optional)', 'wp-adv-quiz'); ?></h3>

                            <div class="inside">
                                <p class="description">
                                    <?php _e('This text will be visible if answered correctly. It can be used as explanation for complex questions. The message "Right" or "Wrong" is always displayed automatically.',
                                        'wp-adv-quiz'); ?>
                                </p>

                                <div style="padding-top: 10px; padding-bottom: 10px;">
                                    <label for="wpAdvQuiz_correctSameText">
                                        <?php _e('Same text for correct- and incorrect-message?', 'wp-adv-quiz'); ?>
                                        <input type="checkbox" name="correctSameText" id="wpAdvQuiz_correctSameText"
                                               value="1" <?php echo esc_attr($this->question->isCorrectSameText()) ? 'checked="checked"' : '' ?>>
                                    </label>
                                </div>
                                <?php
                                wp_editor($this->question->getCorrectMsg(), "correctMsg", array('textarea_rows' => 3));
                                ?>
                            </div>
                        </div>
                        <div class="postbox" id="wpAdvQuiz_incorrectMassageBox">
                            <h3 class="hndle"><?php _e('Message with the incorrect answer',
                                    'wp-adv-quiz'); ?><?php _e('(optional)', 'wp-adv-quiz'); ?></h3>

                            <div class="inside">
                                <p class="description">
                                    <?php _e('This text will be visible if answered incorrectly. It can be used as explanation for complex questions. The message "Right" or "Wrong" is always displayed automatically.',
                                        'wp-adv-quiz'); ?>
                                </p>
                                <?php
                                wp_editor($this->question->getIncorrectMsg(), "incorrectMsg",
                                    array('textarea_rows' => 3));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="postbox">
                        <h3 class="hndle"><?php _e('Hint', 'wp-adv-quiz'); ?><?php _e('(optional)',
                                'wp-adv-quiz'); ?></h3>

                        <div class="inside">
                            <p class="description">
                                <?php _e('Here you can enter solution hint.', 'wp-adv-quiz'); ?>
                            </p>

                            <div style="padding-top: 10px; padding-bottom: 10px;">
                                <label for="wpAdvQuiz_tip">
                                    <?php _e('Activate hint for this question?', 'wp-adv-quiz'); ?>
                                    <input type="checkbox" name="tipEnabled" id="wpAdvQuiz_tip"
                                           value="1" <?php echo esc_attr($this->question->isTipEnabled()) ? 'checked="checked"' : '' ?>>
                                </label>
                            </div>
                            <div id="wpAdvQuiz_tipBox">
                                <?php
                                wp_editor($this->question->getTipMsg(), 'tipMsg', array('textarea_rows' => 3));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="postbox">
                        <h3 class="hndle"><?php _e('Answer type', 'wp-adv-quiz'); ?></h3>

                        <div class="inside">
                            <?php
                            $type = $this->question->getAnswerType();
                            $type = $type === null ? 'single' : $type;
                            ?>
                            <label style="padding-right: 10px;">
                                <input type="radio" name="answerType"
                                       value="single" <?php echo ($type === 'single') ? 'checked="checked"' : ''; ?>>
                                <?php _e('Single choice', 'wp-adv-quiz'); ?>
                            </label>
                            <label style="padding-right: 10px;">
                                <input type="radio" name="answerType"
                                       value="multiple" <?php echo ($type === 'multiple') ? 'checked="checked"' : ''; ?>>
                                <?php _e('Multiple choice', 'wp-adv-quiz'); ?>
                            </label>
                            <label style="padding-right: 10px;">
                                <input type="radio" name="answerType"
                                       value="free_answer" <?php echo ($type === 'free_answer') ? 'checked="checked"' : ''; ?>>
                                <?php _e('"Free" choice', 'wp-adv-quiz'); ?>
                            </label>
                            <label style="padding-right: 10px;">
                                <input type="radio" name="answerType"
                                       value="sort_answer" <?php echo ($type === 'sort_answer') ? 'checked="checked"' : ''; ?>>
                                <?php _e('"Sorting" choice', 'wp-adv-quiz'); ?>
                            </label>
                            <label style="padding-right: 10px;">
                                <input type="radio" name="answerType"
                                       value="matrix_sort_answer" <?php echo ($type === 'matrix_sort_answer') ? 'checked="checked"' : ''; ?>>
                                <?php _e('"Matrix Sorting" choice', 'wp-adv-quiz'); ?>
                            </label>
                            <label style="padding-right: 10px;">
                                <input type="radio" name="answerType"
                                       value="cloze_answer" <?php echo ($type === 'cloze_answer') ? 'checked="checked"' : ''; ?>>
                                <?php _e('Cloze', 'wp-adv-quiz'); ?>
                            </label>
                            <label style="padding-right: 10px;">
                                <input type="radio" name="answerType"
                                       value="assessment_answer" <?php echo ($type === 'assessment_answer') ? 'checked="checked"' : ''; ?>>
                                <?php _e('Assessment', 'wp-adv-quiz'); ?>
                            </label>
                        </div>
                    </div>
                    <?php $this->singleChoiceOptions(); ?>
                    <div class="postbox">
                        <h3 class="hndle"><?php _e('Answers', 'wp-adv-quiz'); ?><?php _e('(required)',
                                'wp-adv-quiz'); ?></h3>

                        <div class="inside answer_felder">
                            <div class="free_answer">
                                <?php $this->freeChoice($this->answerData['free_answer']); ?>
                            </div>
                            <div class="sort_answer">
                                <p class="description">
                                    <?php _e('Please sort the answers in right order with the "Move" - Button. The answers will be displayed randomly.',
                                        'wp-adv-quiz'); ?>
                                </p>
                                <ul class="answerList">
                                    <?php $this->sortingChoice($this->answerData['sort_answer']); ?>
                                </ul>
                                <input type="button" class="button-primary addAnswer"
                                       value="<?php _e('Add new answer', 'wp-adv-quiz'); ?>">
                            </div>
                            <div class="classic_answer">
                                <ul class="answerList">
                                    <?php $this->singleMultiCoice($this->answerData['classic_answer']); ?>
                                </ul>
                                <input type="button" class="button-primary addAnswer"
                                       value="<?php _e('Add new answer', 'wp-adv-quiz'); ?>">
                            </div>
                            <div class="matrix_sort_answer">
                                <p class="description">
                                    <?php _e('In this mode, not a list have to be sorted, but elements must be assigned to matching criterion.',
                                        'wp-adv-quiz'); ?>
                                </p>

                                <p class="description">
                                    <?php _e('You can create sort elements with empty criteria, which can\'t be assigned by user.',
                                        'wp-adv-quiz'); ?>
                                </p>
                                <br>
                                <label>
                                    <?php _e('Percentage width of criteria table column:', 'wp-adv-quiz'); ?>
                                    <?php $msacwValue = $this->question->getMatrixSortAnswerCriteriaWidth() > 0 ? $this->question->getMatrixSortAnswerCriteriaWidth() : 20; ?>
                                    <input type="number" min="1" max="99" step="1" name="matrixSortAnswerCriteriaWidth"
                                           value="<?php echo esc_attr($msacwValue); ?>">%
                                </label>

                                <p class="description">
                                    <?php _e('Allows adjustment of the left column\'s width, and the right column will auto-fill the rest of the available space. Increase this to allow accommodate longer criterion text. Defaults to 20%.',
                                        'wp-adv-quiz'); ?>
                                </p>
                                <br>
                                <ul class="answerList">
                                    <?php $this->matrixSortingChoice($this->answerData['matrix_sort_answer']); ?>
                                </ul>
                                <input type="button" class="button-primary addAnswer"
                                       value="<?php _e('Add new answer', 'wp-adv-quiz'); ?>">
                            </div>
                            <div class="cloze_answer">
                                <?php $this->clozeChoice($this->answerData['cloze_answer']); ?>
                            </div>
                            <div class="assessment_answer">
                                <?php $this->assessmentChoice($this->answerData['assessment_answer']); ?>
                            </div>
                        </div>
                    </div>

                    <?php do_action('wpAdvQuiz_view_questionEdit_after_postbox', $this) ?>

                    <div style="float: left;">
                        <input type="submit" name="submit" id="saveQuestion" class="button-primary"
                               value="<?php _e('Save', 'wp-adv-quiz'); ?>">
                    </div>
                    <div style="float: right;">
                        <input type="text" placeholder="<?php _e('template name', 'wp-adv-quiz'); ?>"
                               class="regular-text" name="templateName" style="border: 1px solid rgb(255, 134, 134);">
                        <select name="templateSaveList">
                            <option value="0">=== <?php _e('Create new template', 'wp-adv-quiz'); ?> ===</option>
                            <?php
                            foreach ($this->templates as $template) {
                                echo '<option value="', esc_attr($template->getTemplateId()), '">', esc_html($template->getName()), '</option>';
                            }
                            ?>
                        </select>

                        <input type="submit" name="template" class="button-primary" id="wpAdvQuiz_saveTemplate"
                               value="<?php _e('Save as template', 'wp-adv-quiz'); ?>">
                    </div>
                    <div style="clear: both;"></div>

                </div>
            </form>
        </div>

        <?php
    }

    /**
     * @param WpAdvQuiz_Model_AnswerTypes[] $data
     */
    private function singleMultiCoice($data)
    {
        foreach ($data as $d) {
            ?>

            <li style="border-bottom:1px dotted #ccc; padding-bottom: 5px; background-color: whiteSmoke;" id="TEST">
                <table style="width: 100%;border: 1px solid #9E9E9E;border-collapse: collapse; margin-bottom: 20px;">
                    <thead>
                    <tr>
                        <th width="160px" style=" border-right: 1px solid #9E9E9E; padding: 5px; "><?php _e('Options',
                                'wp-adv-quiz'); ?></th>
                        <th style="padding: 5px;"><?php _e('Answer', 'wp-adv-quiz'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="border-right: 1px solid #9E9E9E; padding: 5px; vertical-align: top;">
                            <div>
                                <label>
                                    <input type="checkbox" class="wpAdvQuiz_classCorrect wpAdvQuiz_checkbox"
                                           name="answerData[][correct]"
                                           value="1" <?php $this->checked($d->isCorrect()); ?>>
                                    <?php _e('Correct', 'wp-adv-quiz'); ?>
                                </label>
                            </div>
                            <div style="padding-top: 5px;">
                                <label>
                                    <input type="checkbox" class="wpAdvQuiz_checkbox" name="answerData[][html]"
                                           value="1" <?php $this->checked($d->isHtml()); ?>>
                                    <?php _e('Allow HTML', 'wp-adv-quiz'); ?>
                                </label>
                            </div>
                            <div style="padding-top: 5px;" class="wpAdvQuiz_answerPoints">
                                <label>
                                    <input type="number" min="0" class="small-text wpAdvQuiz_points"
                                           name="answerData[][points]" value="<?php echo esc_attr($d->getPoints()); ?>">
                                    <?php _e('Points', 'wp-adv-quiz'); ?>
                                </label>
                            </div>
                        </td>
                        <td style="padding: 5px; vertical-align: top;">
                            <textarea rows="2" cols="50" class="large-text wpAdvQuiz_text" name="answerData[][answer]"
                                      style="resize:vertical;"><?php echo esc_textarea($d->getAnswer()); ?></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <input type="button" name="submit" class="button-primary deleteAnswer"
                       value="<?php _e('Delete answer', 'wp-adv-quiz'); ?>">
                <input type="button" class="button-secondary addMedia" value="<?php _e('Add Media'); ?>">
                <a href="#" class="button-secondary wpAdvQuiz_move" style="cursor: move;"><?php _e('Move',
                        'wp-adv-quiz'); ?></a>

            </li>

            <?php
        }
    }

    /**
     * @param WpAdvQuiz_Model_AnswerTypes[] $data
     */
    private function matrixSortingChoice($data)
    {
        foreach ($data as $d) {
            ?>
            <li style="border-bottom:1px dotted #ccc; padding-bottom: 5px; background-color: whiteSmoke;">
                <table style="width: 100%;border: 1px solid #9E9E9E;border-collapse: collapse; margin-bottom: 20px;">
                    <thead>
                    <tr>
                        <th width="130px" style=" border-right: 1px solid #9E9E9E; padding: 5px; "><?php _e('Options',
                                'wp-adv-quiz'); ?></th>
                        <th style=" border-right: 1px solid #9E9E9E; padding: 5px; "><?php _e('Criterion',
                                'wp-adv-quiz'); ?></th>
                        <th style="padding: 5px;"><?php _e('Sort elements', 'wp-adv-quiz'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="border-right: 1px solid #9E9E9E; padding: 5px; vertical-align: top;">
                            <label class="wpAdvQuiz_answerPoints">
                                <input type="number" min="0" class="small-text wpAdvQuiz_points"
                                       name="answerData[][points]" value="<?php echo esc_attr($d->getPoints()); ?>">
                                <?php _e('Points', 'wp-adv-quiz'); ?>
                            </label>
                        </td>
                        <td style="border-right: 1px solid #9E9E9E; padding: 5px; vertical-align: top;">
                            <textarea rows="4" name="answerData[][answer]" class="wpAdvQuiz_text"
                                      style="width: 100%; resize:vertical;"><?php echo esc_textarea($d->getAnswer()); ?></textarea>
                        </td>
                        <td style="padding: 5px; vertical-align: top;">
                            <textarea rows="4" name="answerData[][sort_string]" class="wpAdvQuiz_text"
                                      style="width: 100%; resize:vertical;"><?php echo esc_textarea($d->getSortString()); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #9E9E9E; padding: 5px; vertical-align: top;"></td>
                        <td style="border-right: 1px solid #9E9E9E; padding: 5px; vertical-align: top;">
                            <label>
                                <input type="checkbox" class="wpAdvQuiz_checkbox" name="answerData[][html]"
                                       value="1" <?php $this->checked($d->isHtml()); ?>>
                                <?php _e('Allow HTML', 'wp-adv-quiz'); ?>
                            </label>
                        </td>
                        <td style="padding: 5px; vertical-align: top;">
                            <label>
                                <input type="checkbox" class="wpAdvQuiz_checkbox" name="answerData[][sort_string_html]"
                                       value="1" <?php $this->checked($d->isSortStringHtml()); ?>>
                                <?php _e('Allow HTML', 'wp-adv-quiz'); ?>
                            </label>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <input type="button" name="submit" class="button-primary deleteAnswer"
                       value="<?php _e('Delete answer', 'wp-adv-quiz'); ?>">
                <input type="button" class="button-secondary addMedia" value="<?php _e('Add Media'); ?>">
                <a href="#" class="button-secondary wpAdvQuiz_move" style="cursor: move;"><?php _e('Move',
                        'wp-adv-quiz'); ?></a>
            </li>
            <?php
        }
    }

    /**
     * @param WpAdvQuiz_Model_AnswerTypes[] $data
     */
    private function sortingChoice($data)
    {
        foreach ($data as $d) {
            ?>
            <li style="border-bottom:1px dotted #ccc; padding-bottom: 5px; background-color: whiteSmoke;">
                <table style="width: 100%;border: 1px solid #9E9E9E;border-collapse: collapse;margin-bottom: 20px;">
                    <thead>
                    <tr>
                        <th width="160px" style=" border-right: 1px solid #9E9E9E; padding: 5px; "><?php _e('Options',
                                'wp-adv-quiz'); ?></th>
                        <th style="padding: 5px;"><?php _e('Answer', 'wp-adv-quiz'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="border-right: 1px solid #9E9E9E; padding: 5px; vertical-align: top;">
                            <div>
                                <label>
                                    <input type="checkbox" class="wpAdvQuiz_checkbox" name="answerData[][html]"
                                           value="1" <?php $this->checked($d->isHtml()); ?>>
                                    <?php _e('Allow HTML', 'wp-adv-quiz'); ?>
                                </label>
                            </div>
                            <div style="padding-top: 5px;" class="wpAdvQuiz_answerPoints">
                                <label>
                                    <input type="number" min="0" class="small-text wpAdvQuiz_points"
                                           name="answerData[][points]" value="<?php echo esc_attr($d->getPoints()); ?>">
                                    <?php _e('Points', 'wp-adv-quiz'); ?>
                                </label>
                            </div>
                        </td>
                        <td style="padding: 5px; vertical-align: top;">
                            <textarea rows="2" cols="100" class="large-text wpAdvQuiz_text" name="answerData[][answer]"
                                      style="resize:vertical;"><?php echo esc_textarea($d->getAnswer()); ?></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <input type="button" name="submit" class="button-primary deleteAnswer"
                       value="<?php _e('Delete answer', 'wp-adv-quiz'); ?>">
                <input type="button" class="button-secondary addMedia" value="<?php _e('Add Media'); ?>">
                <a href="#" class="button-secondary wpAdvQuiz_move" style="cursor: move;"><?php _e('Move',
                        'wp-adv-quiz'); ?></a>
            </li>
            <?php
        }
    }

    /**
     * @param WpAdvQuiz_Model_AnswerTypes[] $data
     */
    private function freeChoice($data)
    {
        $single = $data[0];
        ?>
        <div class="answerList">
            <p class="description">
                <?php _e('correct answers (one per line) (answers will be converted to lower case)', 'wp-adv-quiz'); ?>
            </p>

            <p style="border-bottom:1px dotted #ccc;">
                <textarea rows="6" cols="100" class="large-text"
                          name="answerData[][answer]"><?php echo esc_textarea($single->getAnswer()); ?></textarea>
            </p>
        </div>
        <?php
    }

    /**
     * @param WpAdvQuiz_Model_AnswerTypes[] $data
     */
    private function clozeChoice($data)
    {
        $single = $data[0];
        ?>
        <p class="description">
            <?php _e('Enclose the searched words with { } e.g. "I {play} soccer". Capital and small letters will be ignored.',
                'wp-adv-quiz'); ?>
        </p>
        <p class="description">
            <?php _e('You can specify multiple options for a search word. Enclose the word with [ ] e.g. <span style="font-style: normal; letter-spacing: 2px;"> "I {[play][love][hate]} soccer" </span>. In this case answers play, love OR hate are correct.',
                'wp-adv-quiz'); ?>
        </p>
        <p class="description" style="margin-top: 10px;">
            <?php _e('If mode "Different points for every answer" is activated, you can assign points with |POINTS. Otherwise 1 point will be awarded for every answer.',
                'wp-adv-quiz'); ?>
        </p>
        <p class="description">
            <?php _e('e.g. "I {play} soccer, with a {ball|3}" - "play" gives 1 point and "ball" 3 points.',
                'wp-adv-quiz'); ?>
        </p>
        <?php
        wp_editor($single->getAnswer(), 'cloze',
            array('textarea_rows' => 10, 'textarea_name' => 'answerData[cloze][answer]'));
        ?>
        <?php
    }

    /**
     * @param WpAdvQuiz_Model_AnswerTypes[] $data
     */
    private function assessmentChoice($data)
    {
        $single = $data[0];
        ?>
        <p class="description">
            <?php _e('Here you can create an assessment question.', 'wp-adv-quiz'); ?>
        </p>
        <p class="description">
            <?php _e('Enclose a assesment with {}. The individual assessments are marked with [].', 'wp-adv-quiz'); ?>
            <br>
            <?php _e('The number of options in the maximum score.', 'wp-adv-quiz'); ?>
        </p>
        <p>
            <?php _e('Examples:', 'wp-adv-quiz'); ?>
            <br>
            * <?php _e('less true { [1] [2] [3] [4] [5] } more true', 'wp-adv-quiz'); ?>
        </p>
        <div class="wpAdvQuiz_demoImgBox">
            <a href="#"><?php _e('Demo', 'wp-adv-quiz'); ?></a>

            <div
                style="z-index: 9999999; position: absolute; background-color: #E9E9E9; padding: 10px; box-shadow: 0 0 10px 4px rgb(44, 44, 44); display: none; ">
                <img alt="" src="<?php echo esc_attr(WPADVQUIZ_URL) . '/img/assessmentDemo1.png'; ?> ">
            </div>
        </div>
        <p>
            * <?php _e('less true { [a] [b] [c] } more true', 'wp-adv-quiz'); ?>
        </p>
        <div class="wpAdvQuiz_demoImgBox">
            <a href="#"><?php _e('Demo', 'wp-adv-quiz'); ?></a>

            <div
                style="z-index: 9999999; position: absolute; background-color: #E9E9E9; padding: 10px; box-shadow: 0 0 10px 4px rgb(44, 44, 44); display: none; ">
                <img alt="" src="<?php echo esc_attr(WPADVQUIZ_URL) . '/img/assessmentDemo2.png'; ?> ">
            </div>
        </div>
        <p></p>

        <?php
        wp_editor($single->getAnswer(), 'assessment',
            array('textarea_rows' => 10, 'textarea_name' => 'answerData[assessment][answer]'));
        ?>
        <?php
    }

    private function singleChoiceOptions()
    {
        ?>
        <div class="postbox" id="singleChoiceOptions">
            <h3 class="hndle"><?php _e('Single choice options', 'wp-adv-quiz'); ?></h3>

            <div class="inside">
                <p class="description">
                    <?php _e('If "Different points for each answer" is activated, you can activate a special mode.<br> This changes the calculation of the points',
                        'wp-adv-quiz'); ?>
                </p>
                <label>
                    <input type="checkbox" name="answerPointsDiffModusActivated"
                           value="1" <?php $this->checked($this->question->isAnswerPointsDiffModusActivated()); ?>>
                    <?php _e('Different points - modus 2 activate', 'wp-adv-quiz'); ?>
                </label>
                <br><br>

                <p class="description">
                    <?php _e('Disables the distinction between correct and incorrect.', 'wp-adv-quiz'); ?><br>
                </p>
                <label>
                    <input type="checkbox" name=disableCorrect
                           value="1" <?php $this->checked($this->question->isDisableCorrect()); ?>>
                    <?php _e('disable correct and incorrent', 'wp-adv-quiz'); ?>
                </label>

                <div style="padding-top: 20px;">
                    <a href="#" id="clickPointDia"><?php _e('Explanation of points calculation', 'wp-adv-quiz'); ?></a>
                    <?php $this->answerPointDia(); ?>
                </div>
            </div>
        </div>

        <?php
    }

    private function answerPointDia()
    {
        ?>
        <style>
            .pointDia td {
                border: 1px solid #9E9E9E;
                padding: 8px;
            }
        </style>
        <table style="border-collapse: collapse; display: none; margin-top: 10px;" class="pointDia">
            <tr>
                <th>
                    <?php _e('"Different points for each answer" enabled'); ?>
                    <br>
                    <?php _e('"Different points - mode 2" disable', 'wp-adv-quiz'); ?>
                </th>
                <th>
                    <?php _e('"Different points for each answer" enabled'); ?>
                    <br>
                    <?php _e('"Different points - mode 2" enabled', 'wp-adv-quiz'); ?>
                </th>
            </tr>
            <tr>
                <td>
                    <?php
                    echo nl2br('Question - Single Choice - 3 Answers - Diff points mode

			A=3 Points [correct]
			B=2 Points [incorrect]
			C=1 Point [incorrect]
			
			= 6 Points
			'); ?>

                </td>
                <td>
                    <?php
                    echo nl2br('Question - Single Choice - 3 Answers - Modus 2

			A=3 Points [correct]
			B=2 Points [incorrect]
			C=1 Point [incorrect]
			
			= 3 Points
			'); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                    echo nl2br('~~~ User 1: ~~~
			
			A=checked
			B=unchecked
			C=unchecked
			
			Result:
			A=correct and checked (correct) = 3 Points
			B=incorrect and unchecked (correct) = 2 Points
			C=incorrect and unchecked (correct) = 1 Points
			
			= 6 / 6 Points 100%
			'); ?>

                </td>
                <td>
                    <?php
                    echo nl2br('~~~ User 1: ~~~
			
			A=checked
			B=unchecked
			C=unchecked
			
			Result:
			A=checked = 3 Points
			B=unchecked = 0 Points
			C=unchecked = 0 Points
			
			= 3 / 3 Points 100%'); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                    echo nl2br('~~~ User 2: ~~~
			
			A=unchecked
			B=checked
			C=unchecked
			
			Result:
			A=correct and unchecked (incorrect) = 0 Points
			B=incorrect and checked (incorrect) = 0 Points
			C=incorrect and uncecked (correct) = 1 Points
			
			= 1 / 6 Points 16.67%
			'); ?>

                </td>
                <td>
                    <?php
                    echo nl2br('~~~ User 2: ~~~
			
			A=unchecked
			B=checked
			C=unchecked
			
			Result:
			A=unchecked = 0 Points
			B=checked = 2 Points
			C=uncecked = 0 Points
			
			= 2 / 3 Points 66,67%'); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                    echo nl2br('~~~ User 3: ~~~
			
			A=unchecked
			B=unchecked
			C=checked
			
			Result:
			A=correct and unchecked (incorrect) = 0 Points
			B=incorrect and unchecked (correct) = 2 Points
			C=incorrect and checked (incorrect) = 0 Points
			
			= 2 / 6 Points 33.33%
			'); ?>

                </td>
                <td>
                    <?php
                    echo nl2br('~~~ User 3: ~~~
			
			A=unchecked
			B=unchecked
			C=checked
			
			Result:
			A=unchecked = 0 Points
			B=unchecked = 0 Points
			C=checked = 1 Points
			
			= 1 / 3 Points 33,33%'); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                    echo nl2br('~~~ User 4: ~~~
			
			A=unchecked
			B=unchecked
			C=unchecked
			
			Result:
			A=correct and unchecked (incorrect) = 0 Points
			B=incorrect and unchecked (correct) = 2 Points
			C=incorrect and unchecked (correct) = 1 Points
			
			= 3 / 6 Points 50%
			'); ?>

                </td>
                <td>
                    <?php
                    echo nl2br('~~~ User 4: ~~~
			
			A=unchecked
			B=unchecked
			C=unchecked
			
			Result:
			A=unchecked = 0 Points
			B=unchecked = 0 Points
			C=unchecked = 0 Points
			
			= 0 / 3 Points 0%'); ?>
                </td>
            </tr>
        </table>
        <?php
    }
}
