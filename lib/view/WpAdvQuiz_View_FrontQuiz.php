<?php

/**
 * @property WpAdvQuiz_Model_Quiz quiz
 * @property WpAdvQuiz_Model_Question[] question
 * @property WpAdvQuiz_Model_Category[] category
 * @property WpAdvQuiz_Model_Form[] forms
 */
class WpAdvQuiz_View_FrontQuiz extends WpAdvQuiz_View_View
{
    private $_clozeTemp = array();
    private $_assessmetTemp = array();

    private $_buttonNames = array();


    private function loadButtonNames()
    {
        if (!empty($this->_buttonNames)) {
            return;
        }

        $names = array(
            'start_quiz' => __('Start quiz', 'wp-adv-quiz'),
            'restart_quiz' => __('Restart quiz', 'wp-adv-quiz'),
            'quiz_summary' => __('Quiz-summary', 'wp-adv-quiz'),
            'finish_quiz' => __('Finish quiz', 'wp-adv-quiz'),
            'quiz_is_loading' => __('Quiz is loading...', 'wp-adv-quiz'),
            'lock_box_msg' => __('You have already completed the quiz before. Hence you can not start it again.',
                'wp-adv-quiz'),
            'only_registered_user_msg' => __('You must sign in or sign up to start the quiz.', 'wp-adv-quiz'),
            'prerequisite_msg' => __('You have to finish following quiz, to start this quiz:', 'wp-adv-quiz')
        );

        $this->_buttonNames = ((array)apply_filters('wpAdvQuiz_filter_frontButtonNames', $names, $this)) + $names;
		
    }

    /**
     * @param $data WpAdvQuiz_Model_AnswerTypes
     *
     * @return array
     */
    private function getFreeCorrect($data)
    {
        $t = str_replace("\r\n", "\n", strtolower($data->getAnswer()));
        $t = str_replace("\r", "\n", $t);
        $t = explode("\n", $t);

        return array_values(array_filter(array_map('trim', $t), array($this, 'removeEmptyElements')));
    }

    private function removeEmptyElements($v)
    {
        return !empty($v) || $v === '0';
    }

    public function show($preview = false)
    {
        $this->loadButtonNames();

        $question_count = count($this->question);

        $result = $this->quiz->getResultText();

        if (!$this->quiz->isResultGradeEnabled()) {
            $result = array(
                'text' => array($result),
                'prozent' => array(0)
            );
        }

        $resultsProzent = json_encode($result['prozent']);

        $resultReplace = array();

        foreach ($this->forms as $form) {
            /* @var $form WpAdvQuiz_Model_Form */

            $resultReplace['$form{' . $form->getSort() . '}'] = '<span class="wpAdvQuiz_resultForm" data-form_id="' . $form->getFormId() . '"></span>';
        }

        foreach ($result['text'] as &$text) {
            $text = str_replace(array_keys($resultReplace), $resultReplace, $text);
        }

        ?>
        <div class="wpAdvQuiz_content" id="wpAdvQuiz_<?php echo esc_attr($this->quiz->getId()); ?>">
            <?php

            if (!$this->quiz->isTitleHidden()) {
                echo '<h2>' . esc_html($this->quiz->getName()) . '</h2>';
            }

            $this->showTimeLimitBox();
            $this->showCheckPageBox($question_count);
            $this->showInfoPageBox();
            $this->showStartQuizBox();
            $this->showLockBox();
            $this->showLoadQuizBox();
            $this->showStartOnlyRegisteredUserBox();
            $this->showPrerequisiteBox();
            $this->showResultBox($result, $question_count);

            if ($this->quiz->getToplistDataShowIn() == WpAdvQuiz_Model_Quiz::QUIZ_TOPLIST_SHOW_IN_BUTTON) {
                $this->showToplistInButtonBox();
            }

            $this->showReviewBox($question_count);
            $this->showQuizAnker();

            $quizData = $this->showQuizBox($question_count);

            ?>
        </div>
        <?php

        $bo = $this->createOption($preview);

        ?>
        <script type="text/javascript">
            window.wpAdvQuizInitList = window.wpAdvQuizInitList || [];

            window.wpAdvQuizInitList.push({
                id: '#wpAdvQuiz_<?php echo $this->quiz->getId(); ?>',
                init: {
                    quizId: <?php echo esc_attr((int)$this->quiz->getId()); ?>,
                    mode: <?php echo esc_attr((int)$this->quiz->getQuizModus()); ?>,
                    globalPoints: <?php echo esc_attr((int)$quizData['globalPoints']); ?>,
                    timelimit: <?php echo esc_attr((int)$this->quiz->getTimeLimit()); ?>,
                    resultsGrade: <?php echo esc_attr($resultsProzent); ?>,
                    bo: <?php echo esc_attr($bo); ?>,
                    qpp: <?php echo esc_attr($this->quiz->getQuestionsPerPage()); ?>,
                    catPoints: <?php echo json_encode($quizData['catPoints']); ?>,
                    formPos: <?php echo (int)$this->quiz->getFormShowPosition(); ?>,
                    lbn: <?php echo json_encode(($this->quiz->isShowReviewQuestion() && !$this->quiz->isQuizSummaryHide()) ? $this->_buttonNames['quiz_summary'] : $this->_buttonNames['finish_quiz']); ?>,
                    json: <?php echo json_encode($quizData['json']); ?>
                }
            });
        </script>
        <?php
    }

    private function createOption($preview)
    {
        $bo = 0;

        $bo |= ((int)$this->quiz->isAnswerRandom()) << 0;
        $bo |= ((int)$this->quiz->isQuestionRandom()) << 1;
        $bo |= ((int)$this->quiz->isDisabledAnswerMark()) << 2;
        $bo |= ((int)($this->quiz->isQuizRunOnce() || $this->quiz->isPrerequisite() || $this->quiz->isStartOnlyRegisteredUser())) << 3;
        $bo |= ((int)$preview) << 4;
        $bo |= ((int)get_option('wpAdvQuiz_corsActivated')) << 5;
        $bo |= ((int)$this->quiz->isToplistDataAddAutomatic()) << 6;
        $bo |= ((int)$this->quiz->isShowReviewQuestion()) << 7;
        $bo |= ((int)$this->quiz->isQuizSummaryHide()) << 8;
        $bo |= ((int)(!$this->quiz->isSkipQuestionDisabled() && $this->quiz->isShowReviewQuestion())) << 9;
        $bo |= ((int)$this->quiz->isAutostart()) << 10;
        $bo |= ((int)$this->quiz->isForcingQuestionSolve()) << 11;
        $bo |= ((int)$this->quiz->isHideQuestionPositionOverview()) << 12;
        $bo |= ((int)$this->quiz->isFormActivated()) << 13;
        $bo |= ((int)$this->quiz->isShowMaxQuestion()) << 14;
        $bo |= ((int)$this->quiz->isSortCategories()) << 15;

        return $bo;
    }

    public function showMaxQuestion()
    {
        $this->loadButtonNames();

        $question_count = count($this->question);

        $result = $this->quiz->getResultText();

        if (!$this->quiz->isResultGradeEnabled()) {
            $result = array(
                'text' => array($result),
                'prozent' => array(0)
            );
        }

        $resultsProzent = json_encode($result['prozent']);

        ?>
        <div class="wpAdvQuiz_content" id="wpAdvQuiz_<?php echo esc_attr($this->quiz->getId()); ?>">
            <?php

            if (!$this->quiz->isTitleHidden()) {
                echo '<h2>' . esc_html($this->quiz->getName()) . '</h2>';
            }

            $this->showTimeLimitBox();
            $this->showCheckPageBox($question_count);
            $this->showInfoPageBox();
            $this->showStartQuizBox();
            $this->showLockBox();
            $this->showLoadQuizBox();
            $this->showStartOnlyRegisteredUserBox();
            $this->showPrerequisiteBox();
            $this->showResultBox($result, $question_count);

            if ($this->quiz->getToplistDataShowIn() == WpAdvQuiz_Model_Quiz::QUIZ_TOPLIST_SHOW_IN_BUTTON) {
                $this->showToplistInButtonBox();
            }

            $this->showReviewBox($question_count);
            $this->showQuizAnker();
            ?>
        </div>
        <?php

        $bo = $this->createOption(false);

        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#wpAdvQuiz_<?php echo $this->quiz->getId(); ?>').wpAdvQuizFront({
                    quizId: <?php echo esc_attr((int)$this->quiz->getId()); ?>,
                    mode: <?php echo esc_attr((int)$this->quiz->getQuizModus()); ?>,
                    timelimit: <?php echo esc_attr((int)$this->quiz->getTimeLimit()); ?>,
                    resultsGrade: <?php echo esc_attr($resultsProzent); ?>,
                    bo: <?php echo esc_attr($bo); ?>,
                    qpp: <?php echo esc_attr($this->quiz->getQuestionsPerPage()); ?>,
                    formPos: <?php echo (int)$this->quiz->getFormShowPosition(); ?>,
                    lbn: <?php echo json_encode(($this->quiz->isShowReviewQuestion() && !$this->quiz->isQuizSummaryHide()) ? $this->_buttonNames['quiz_summary'] : $this->_buttonNames['finish_quiz']); ?>
                });
            });
        </script>
        <?php
    }

    public function getQuizData()
    {
        ob_start();

        $this->loadButtonNames();

        $quizData = $this->showQuizBox(count($this->question));

        $quizData['content'] = ob_get_contents();

        ob_end_clean();

        return $quizData;
    }

    private function showQuizAnker()
    {
        ?>
        <div class="wpAdvQuiz_quizAnker" style="display: none;"></div>
        <?php
    }

    private function showAddToplist()
    {
        ?>
        <div class="wpAdvQuiz_addToplist" style="display: none;">
            <span style="font-weight: bold;"><?php _e('Your result has been entered into leaderboard',
                    'wp-adv-quiz'); ?></span>

            <div style="margin-top: 6px;">
                <div class="wpAdvQuiz_addToplistMessage" style="display: none;"><?php _e('Loading',
                        'wp-adv-quiz'); ?></div>
                <div class="wpAdvQuiz_addBox">
                    <div>
						<span>
							<label>
                                <?php _e('Name', 'wp-adv-quiz'); ?>: <input type="text" placeholder="<?php _e('Name',
                                    'wp-adv-quiz'); ?>" name="wpAdvQuiz_toplistName" maxlength="15" size="16"
                                                                            style="width: 150px;">
                            </label>
							<label>
                                <?php _e('E-Mail', 'wp-adv-quiz'); ?>: <input type="email"
                                                                              placeholder="<?php _e('E-Mail',
                                                                                  'wp-adv-quiz'); ?>"
                                                                              name="wpAdvQuiz_toplistEmail" size="20"
                                                                              style="width: 150px;">
                            </label>
						</span>

                        <div style="margin-top: 5px;">
                            <label>
                                <?php _e('Captcha', 'wp-adv-quiz'); ?>: <input type="text" name="wpAdvQuiz_captcha"
                                                                               size="8" style="width: 50px;">
                            </label>
                            <input type="hidden" name="wpAdvQuiz_captchaPrefix" value="0">
                            <img alt="captcha" src="" class="wpAdvQuiz_captchaImg" style="vertical-align: middle;">
                        </div>
                    </div>
                    <input class="wpAdvQuiz_button2" type="submit" value="<?php _e('Send', 'wp-adv-quiz'); ?>"
                           name="wpAdvQuiz_toplistAdd">
                </div>
            </div>
        </div>
        <?php
    }

    private function fetchCloze($answer_text)
    {
        preg_match_all('#\{(.*?)(?:\|(\d+))?(?:[\s]+)?\}#im', $answer_text, $matches, PREG_SET_ORDER);

        $data = array();

        foreach ($matches as $k => $v) {
            $text = $v[1];
            $points = !empty($v[2]) ? (int)$v[2] : 1;
            $rowText = $multiTextData = array();
            $len = array();

            if (preg_match_all('#\[(.*?)\]#im', $text, $multiTextMatches)) {
                foreach ($multiTextMatches[1] as $multiText) {
                    $x = mb_strtolower(trim(html_entity_decode($multiText, ENT_QUOTES)));

                    $len[] = strlen($x);
                    $multiTextData[] = $x;
                    $rowText[] = $multiText;
                }
            } else {
                $x = mb_strtolower(trim(html_entity_decode($text, ENT_QUOTES)));

                $len[] = strlen($x);
                $multiTextData[] = $x;
                $rowText[] = $text;
            }

            $a = '<span class="wpAdvQuiz_cloze"><input data-wordlen="' . max($len) . '" type="text" value=""> ';
            $a .= '<span class="wpAdvQuiz_clozeCorrect" style="display: none;">(' . implode(', ',
                    $rowText) . ')</span></span>';

            $data['correct'][] = $multiTextData;
            $data['points'][] = $points;
            $data['data'][] = $a;
        }

        $data['replace'] = preg_replace('#\{(.*?)(?:\|(\d+))?(?:[\s]+)?\}#im', '@@wpAdvQuizCloze@@', $answer_text);

        return $data;
    }

    private function clozeCallback($t)
    {
        $a = array_shift($this->_clozeTemp);

        return $a === null ? '' : $a;
    }

    private function fetchAssessment($answerText, $quizId, $questionId)
    {
        preg_match_all('#\{(.*?)\}#im', $answerText, $matches);

        $this->_assessmetTemp = array();
        $data = array();

        for ($i = 0, $ci = count($matches[1]); $i < $ci; $i++) {
            $match = $matches[1][$i];

            preg_match_all('#\[([^\|\]]+)(?:\|(\d+))?\]#im', $match, $ms);

            $a = '';

            for ($j = 0, $cj = count($ms[1]); $j < $cj; $j++) {
                $v = $ms[1][$j];

                $a .= '<label>
					<input type="radio" value="' . ($j + 1) . '" name="question_' . $quizId . '_' . $questionId . '_' . $i . '" class="wpAdvQuiz_questionInput" data-index="' . $i . '">
					' . $v . '
				</label>';

            }

            $this->_assessmetTemp[] = $a;
        }

        $data['replace'] = preg_replace('#\{(.*?)\}#im', '@@wpAdvQuizAssessment@@', $answerText);

        return $data;
    }

    private function assessmentCallback($t)
    {
        $a = array_shift($this->_assessmetTemp);

        return $a === null ? '' : $a;
    }

    private function showFormBox()
    {
        $info = '<div class="wpAdvQuiz_invalidate">' . __('You must fill out this field.', 'wp-adv-quiz') . '</div>';

        $validateText = array(
            WpAdvQuiz_Model_Form::FORM_TYPE_NUMBER => __('You must specify a number.', 'wp-adv-quiz'),
            WpAdvQuiz_Model_Form::FORM_TYPE_TEXT => __('You must specify a text.', 'wp-adv-quiz'),
            WpAdvQuiz_Model_Form::FORM_TYPE_EMAIL => __('You must specify an email address.', 'wp-adv-quiz'),
            WpAdvQuiz_Model_Form::FORM_TYPE_DATE => __('You must specify a date.', 'wp-adv-quiz')
        );
        ?>
        <div class="wpAdvQuiz_forms">
            <table>
                <tbody>

                <?php
                $index = 0;
                foreach ($this->forms as $form) {
                    /* @var $form WpAdvQuiz_Model_Form */

                    $id = 'forms_' . $this->quiz->getId() . '_' . $index++;
                    $name = 'wpAdvQuiz_field_' . $form->getFormId();
                    ?>
                    <tr>
                        <td>
                            <?php
                            echo '<label for="' . esc_html($id) . '">';
                            echo esc_html($form->getFieldname());
                            echo esc_attr($form->isRequired()) ? '<span class="wpAdvQuiz_required">*</span>' : '';
                            echo '</label>';
                            ?>
                        </td>
                        <td>

                            <?php
                            switch ($form->getType()) {
                                case WpAdvQuiz_Model_Form::FORM_TYPE_TEXT:
                                case WpAdvQuiz_Model_Form::FORM_TYPE_EMAIL:
                                case WpAdvQuiz_Model_Form::FORM_TYPE_NUMBER:
                                    echo '<input name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" type="text" ',
                                        'data-required="' . (int)$form->isRequired() . '" data-type="' . $form->getType() . '" data-form_id="' . $form->getFormId() . '">';
                                    break;
                                case WpAdvQuiz_Model_Form::FORM_TYPE_TEXTAREA:
                                    echo '<textarea rows="5" cols="20" name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" ',
                                        'data-required="' . (int)$form->isRequired() . '" data-type="' . $form->getType() . '" data-form_id="' . $form->getFormId() . '"></textarea>';
                                    break;
                                case WpAdvQuiz_Model_Form::FORM_TYPE_CHECKBOX:
                                    echo '<input name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" type="checkbox" value="1"',
                                        'data-required="' . (int)$form->isRequired() . '" data-type="' . $form->getType() . '" data-form_id="' . $form->getFormId() . '">';
                                    break;
                                case WpAdvQuiz_Model_Form::FORM_TYPE_DATE:
                                    echo '<div data-required="' . (int)$form->isRequired() . '" data-type="' . $form->getType() . '" class="wpAdvQuiz_formFields" data-form_id="' . $form->getFormId() . '">';
                                    echo WpAdvQuiz_Helper_Until::getDatePicker(get_option('date_format', 'j. F Y'),
                                        $name);
                                    echo '</div>';
                                    break;
                                case WpAdvQuiz_Model_Form::FORM_TYPE_RADIO:
                                    echo '<div data-required="' . (int)$form->isRequired() . '" data-type="' . $form->getType() . '" class="wpAdvQuiz_formFields" data-form_id="' . $form->getFormId() . '">';

                                    if ($form->getData() !== null) {
                                        foreach ($form->getData() as $data) {
                                            echo '<label>';
                                            echo '<input name="' . esc_attr($name) . '" type="radio" value="' . esc_attr($data) . '"> ',
                                            esc_html($data);
                                            echo '</label> ';
                                        }
                                    }

                                    echo '</div>';

                                    break;
                                case WpAdvQuiz_Model_Form::FORM_TYPE_SELECT:
                                    if ($form->getData() !== null) {
                                        echo '<select name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" ',
                                            'data-required="' . (int)$form->isRequired() . '" data-type="' . $form->getType() . '" data-form_id="' . $form->getFormId() . '">';
                                        echo '<option value=""></option>';

                                        foreach ($form->getData() as $data) {
                                            echo '<option value="' . esc_attr($data) . '">', esc_html($data), '</option>';
                                        }

                                        echo '</select>';
                                    }
                                    break;
                                case WpAdvQuiz_Model_Form::FORM_TYPE_YES_NO:
                                    echo '<div data-required="' . (int)$form->isRequired() . '" data-type="' . $form->getType() . '" class="wpAdvQuiz_formFields" data-form_id="' . $form->getFormId() . '">';
                                    echo '<label>';
                                    echo '<input name="' . esc_attr($name) . '" type="radio" value="1"> ',
                                    __('Yes', 'wp-adv-quiz');
                                    echo '</label> ';

                                    echo '<label>';
                                    echo '<input name="' . esc_attr($name) . '" type="radio" value="0"> ',
                                    __('No', 'wp-adv-quiz');
                                    echo '</label> ';
                                    echo '</div>';
                                    break;
                            }

                            if (isset($validateText[$form->getType()])) {
                                echo '<div class="wpAdvQuiz_invalidate">' . $validateText[$form->getType()] . '</div>';
                            } else {
                                echo '<div class="wpAdvQuiz_invalidate">' . __('You must fill out this field.',
                                        'wp-adv-quiz') . '</div>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>

        <?php
    }

    private function showLockBox()
    {
        ?>
        <div style="display: none;" class="wpAdvQuiz_lock">
            <p>
                <?php echo esc_html($this->_buttonNames['lock_box_msg']); ?>
            </p>
        </div>
        <?php
    }

    private function showStartOnlyRegisteredUserBox()
    {
        ?>
        <div style="display: none;" class="wpAdvQuiz_startOnlyRegisteredUser">
            <p>
                <?php echo esc_html($this->_buttonNames['only_registered_user_msg']); ?>
            </p>
        </div>
        <?php
    }

    private function showPrerequisiteBox()
    {
        ?>
        <div style="display: none;" class="wpAdvQuiz_prerequisite">
            <p>
                <?php echo esc_html($this->_buttonNames['prerequisite_msg']); ?>
                <span></span>
            </p>
        </div>
        <?php
    }

    private function showCheckPageBox($questionCount)
    {
        ?>
        <div class="wpAdvQuiz_checkPage" style="display: none;">
            <h4 class="wpAdvQuiz_header"><?php echo esc_html($this->_buttonNames['quiz_summary']); ?></h4>

            <p>
                <?php printf(__('%s of %s questions completed', 'wp-adv-quiz'), '<span>0</span>', $questionCount); ?>
            </p>

            <p><?php _e('Questions', 'wp-adv-quiz'); ?>:</p>

            <div style="margin-bottom: 20px;" class="wpAdvQuiz_box">
                <ol>
                    <?php for ($xy = 1; $xy <= $questionCount; $xy++) { ?>
                        <li><?php echo esc_html($xy); ?></li>
                    <?php } ?>
                </ol>
                <div style="clear: both;"></div>
            </div>

            <?php
            if ($this->quiz->isFormActivated() && $this->quiz->getFormShowPosition() == WpAdvQuiz_Model_Quiz::QUIZ_FORM_POSITION_END
                && ($this->quiz->isShowReviewQuestion() && !$this->quiz->isQuizSummaryHide())
            ) {

                ?>
                <h4 class="wpAdvQuiz_header"><?php _e('Information', 'wp-adv-quiz'); ?></h4>
                <?php
                $this->showFormBox();
            }

            ?>

            <input type="button" name="endQuizSummary" value="<?php echo esc_attr($this->_buttonNames['finish_quiz']); ?>"
                   class="wpAdvQuiz_button" 
				    style="width:<?php echo esc_attr($this->finish_btn_width); ?>px !Important; height:<?php echo esc_attr($this->finish_btn_height); ?>px !Important; background-color: <?php echo esc_attr($this->finish_btn_color); ?> !important">
        </div>
        <?php
    }

    private function showInfoPageBox()
    {
        ?>
        <div class="wpAdvQuiz_infopage" style="display: none;">
            <h4><?php _e('Information', 'wp-adv-quiz'); ?></h4>

            <?php
            if ($this->quiz->isFormActivated() && $this->quiz->getFormShowPosition() == WpAdvQuiz_Model_Quiz::QUIZ_FORM_POSITION_END
                && (!$this->quiz->isShowReviewQuestion() || $this->quiz->isQuizSummaryHide())
            ) {
                $this->showFormBox();
            }

            ?>

            <input type="button" name="endInfopage" value="<?php echo esc_attr($this->_buttonNames['finish_quiz']); ?>"
                   class="wpAdvQuiz_button"
				   style="width:<?php echo esc_attr($this->finish_btn_width); ?>px !Important; height:<?php echo esc_attr($this->finish_btn_height); ?>px !Important; background-color: <?php echo esc_attr($this->finish_btn_color); ?> !important">
        </div>
        <?php
    }

    private function showStartQuizBox()
    {
        ?>
        <div class="wpAdvQuiz_text">
            <p>
                <?php echo do_shortcode(apply_filters('comment_text', $this->quiz->getText())); ?>
            </p>

            <?php
            if ($this->quiz->isFormActivated() && $this->quiz->getFormShowPosition() == WpAdvQuiz_Model_Quiz::QUIZ_FORM_POSITION_START) {
                $this->showFormBox();
            }
            ?>

            <div>
                <input class="wpAdvQuiz_button" type="button" value="<?php echo esc_attr($this->_buttonNames['start_quiz']); ?>"
                       name="startQuiz" style="width:<?php echo esc_attr($this->start_btn_width); ?>px !Important; height:<?php echo esc_attr($this->start_btn_height); ?>px !Important; background-color: <?php echo esc_attr($this->start_btn_color); ?> !important">
            </div>
        </div>
        <?php
    }

    private function showTimeLimitBox()
    {
        ?>
        <div style="display: none;" class="wpAdvQuiz_time_limit">
            <div class="time"><?php _e('Time limit', 'wp-adv-quiz'); ?>: <span>0</span></div>
            <div class="wpAdvQuiz_progress"></div>
        </div>
        <?php
    }

    private function showReviewBox($questionCount)
    {
        ?>
        <div class="wpAdvQuiz_reviewDiv" style="display: none;">
            <div class="wpAdvQuiz_reviewQuestion">
                <ol>
                    <?php for ($xy = 1; $xy <= $questionCount; $xy++) { ?>
                        <li><?php echo esc_html($xy); ?></li>
                    <?php } ?>
                </ol>
                <div style="display: none;"></div>
            </div>
            <div class="wpAdvQuiz_reviewLegend">
                <ol>
                    <li>
                        <span class="wpAdvQuiz_reviewColor" style="background-color: #6CA54C;"></span>
                        <span class="wpAdvQuiz_reviewText"><?php _e('Answered', 'wp-adv-quiz'); ?></span>
                    </li>
                    <li>
                        <span class="wpAdvQuiz_reviewColor" style="background-color: #FFB800;"></span>
                        <span class="wpAdvQuiz_reviewText"><?php _e('Review', 'wp-adv-quiz'); ?></span>
                    </li>
                </ol>
                <div style="clear: both;"></div>
            </div>
            <div>
                <?php if ($this->quiz->getQuizModus() != WpAdvQuiz_Model_Quiz::QUIZ_MODUS_SINGLE) { ?>
                    <input type="button" name="review" value="<?php _e('Review question', 'wp-adv-quiz'); ?>"
                           class="wpAdvQuiz_button2" style="float: left; display: block;
						   width:<?php echo esc_attr($this->review_btn_width); ?>px !Important; height:<?php echo esc_attr($this->review_btn_height); ?>px !Important; background-color: <?php echo esc_attr($this->review_btn_color); ?> !important">
						   
                    <?php if (!$this->quiz->isQuizSummaryHide()) { ?>
                        <input type="button" name="quizSummary"
                               value="<?php echo esc_attr($this->_buttonNames['quiz_summary']); ?>" class="wpAdvQuiz_button2"
                               style="float: right;
							   	width:<?php echo esc_attr($this->summary_btn_width); ?>px !Important; height:<?php echo esc_attr($this->summary_btn_height); ?>px !Important; background-color: <?php echo esc_attr($this->summary_btn_color); ?> !important">
                    <?php } ?>
                    <div style="clear: both;"></div>
                <?php } ?>
            </div>
        </div>
        <?php
    }

    private function showResultBox($result, $questionCount)
    {
        ?>
        <div style="display: none;" class="wpAdvQuiz_results">
            <h4 class="wpAdvQuiz_header"><?php _e('Results', 'wp-adv-quiz'); ?></h4>
            <?php if (!$this->quiz->isHideResultCorrectQuestion()) { ?>
                <p>
                    <?php printf(__('%s of %s questions answered correctly', 'wp-adv-quiz'),
                        '<span class="wpAdvQuiz_correct_answer">0</span>', '<span>' . $questionCount . '</span>'); ?>
                </p>
            <?php }
            if (!$this->quiz->isHideResultQuizTime()) { ?>
                <p class="wpAdvQuiz_quiz_time">
                    <?php _e('Your time: <span></span>', 'wp-adv-quiz'); ?>
                </p>
            <?php } ?>
            <p class="wpAdvQuiz_time_limit_expired" style="display: none;">
                <?php _e('Time has elapsed', 'wp-adv-quiz'); ?>
            </p>
            <?php if (!$this->quiz->isHideResultPoints()) { ?>
                <p class="wpAdvQuiz_points">
                    <?php printf(__('You have reached %s of %s points, (%s)', 'wp-adv-quiz'), '<span>0</span>',
                        '<span>0</span>', '<span>0</span>'); ?>
                </p>
            <?php } ?>
            <?php if ($this->quiz->isShowAverageResult()) { ?>
                <div class="wpAdvQuiz_resultTable">
                    <table>
                        <tbody>
                        <tr>
                            <td class="wpAdvQuiz_resultName"><?php _e('Average score', 'wp-adv-quiz'); ?></td>
                            <td class="wpAdvQuiz_resultValue">
                                <div style="background-color: #6CA54C;">&nbsp;</div>
                                <span>&nbsp;</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="wpAdvQuiz_resultName"><?php _e('Your score', 'wp-adv-quiz'); ?></td>
                            <td class="wpAdvQuiz_resultValue">
                                <div style="background-color: #F79646;">&nbsp;</div>
                                <span>&nbsp;</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
            <div class="wpAdvQuiz_catOverview" <?php $this->isDisplayNone($this->quiz->isShowCategoryScore()); ?>>
                <h4><?php _e('Categories', 'wp-adv-quiz'); ?></h4>

                <div style="margin-top: 10px;">
                    <ol>
                        <?php foreach ($this->category as $cat) {
                            if (!$cat->getCategoryId()) {
                                $cat->setCategoryName(__('Not categorized', 'wp-adv-quiz'));
                            }
                            ?>
                            <li data-category_id="<?php echo esc_attr($cat->getCategoryId()); ?>">
                                <span class="wpAdvQuiz_catName"><?php echo esc_html($cat->getCategoryName()); ?></span>
                                <span class="wpAdvQuiz_catPercent">0%</span>
                            </li>
                        <?php } ?>
                    </ol>
                </div>
            </div>
            <div>
                <ul class="wpAdvQuiz_resultsList">
                    <?php foreach ($result['text'] as $resultText) { ?>
                        <li style="display: none;">
                            <div>
                                <?php echo do_shortcode(apply_filters('comment_text', $resultText)); ?>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <?php
            if ($this->quiz->isToplistActivated()) {
                if ($this->quiz->getToplistDataShowIn() == WpAdvQuiz_Model_Quiz::QUIZ_TOPLIST_SHOW_IN_NORMAL) {
                    echo do_shortcode('[WpAdvQuiz_toplist ' . $this->quiz->getId() . ' q="true"]');
                }

                $this->showAddToplist();
            }
            ?>
            <div style="margin: 10px 0px;">
                <?php if (!$this->quiz->isBtnRestartQuizHidden()) { ?>
                    <input class="wpAdvQuiz_button" type="button" name="restartQuiz"
                           value="<?php echo esc_attr($this->_buttonNames['restart_quiz']); ?>" 
						   style="width:<?php echo esc_attr($this->restart_btn_width); ?>px !Important; height:<?php echo esc_attr($this->restart_btn_height); ?>px !Important; background-color: <?php echo esc_attr($this->restart_btn_color); ?> !important"
						   ">
                <?php }
                if (!$this->quiz->isBtnViewQuestionHidden()) { ?>
                    <input class="wpAdvQuiz_button" type="button" name="reShowQuestion"
                           value="<?php _e('View questions', 'wp-adv-quiz'); ?>" 
						   style="width:<?php echo esc_attr($this->view_btn_width); ?>px !Important; height:<?php echo esc_attr($this->view_btn_height); ?>px !Important; background-color: <?php echo esc_attr($this->view_btn_color); ?> !important">
                <?php } ?>
                <?php if ($this->quiz->isToplistActivated() && $this->quiz->getToplistDataShowIn() == WpAdvQuiz_Model_Quiz::QUIZ_TOPLIST_SHOW_IN_BUTTON) { ?>
                    <input class="wpAdvQuiz_button" type="button" name="showToplist"
                           value="<?php _e('Show leaderboard', 'wp-adv-quiz'); ?>"
						   style="width:<?php echo esc_attr($this->leaderboard_btn_width); ?>px !Important; height:<?php echo esc_attr($this->leaderboard_btn_height); ?>px !Important; background-color: <?php echo esc_attr($this->leaderboard_btn_color); ?> !important">
                <?php } ?>
            </div>
        </div>
        <?php
    }

    private function showToplistInButtonBox()
    {
        ?>
        <div class="wpAdvQuiz_toplistShowInButton" style="display: none;">
            <?php echo do_shortcode('[WpAdvQuiz_toplist ' . $this->quiz->getId() . ' q="true"]'); ?>
        </div>
        <?php
    }

    private function showQuizBox($questionCount)
    {
        $globalPoints = 0;
        $json = array();
        $catPoints = array();
        ?>
        <div style="display: none;" class="wpAdvQuiz_quiz">
            <ol class="wpAdvQuiz_list">
                <?php
                $index = 0;
                foreach ($this->question as $question) {
                    $index++;

                    /* @var $answerArray WpAdvQuiz_Model_AnswerTypes[] */
                    $answerArray = $question->getAnswerData();

                    $globalPoints += $question->getPoints();

                    $json[$question->getId()]['type'] = $question->getAnswerType();
                    $json[$question->getId()]['id'] = (int)$question->getId();
                    $json[$question->getId()]['catId'] = (int)$question->getCategoryId();

                    if ($question->isAnswerPointsActivated() && $question->isAnswerPointsDiffModusActivated() && $question->isDisableCorrect()) {
                        $json[$question->getId()]['disCorrect'] = (int)$question->isDisableCorrect();
                    }

                    if (!isset($catPoints[$question->getCategoryId()])) {
                        $catPoints[$question->getCategoryId()] = 0;
                    }

                    $catPoints[$question->getCategoryId()] += $question->getPoints();

                    if (!$question->isAnswerPointsActivated()) {
                        $json[$question->getId()]['points'] = $question->getPoints();
                        // 					$catPoints[$question->getCategoryId()] += $question->getPoints();
                    }

                    if ($question->isAnswerPointsActivated() && $question->isAnswerPointsDiffModusActivated()) {
                        // 					$catPoints[$question->getCategoryId()] += $question->getPoints();
                        $json[$question->getId()]['diffMode'] = 1;
                    }

                    ?>
                    <li class="wpAdvQuiz_listItem" style="display: none;">
                        <div
                            class="wpAdvQuiz_question_page" <?php $this->isDisplayNone($this->quiz->getQuizModus() != WpAdvQuiz_Model_Quiz::QUIZ_MODUS_SINGLE && !$this->quiz->isHideQuestionPositionOverview()); ?> >
                            <?php printf(__('Question %s of %s', 'wp-adv-quiz'), '<span>' . $index . '</span>',
                                '<span>' . $questionCount . '</span>'); ?>
                        </div>
                        <h5 style="<?php echo esc_attr($this->quiz->isHideQuestionNumbering()) ? 'display: none;' : 'display: inline-block;' ?>"
                            class="wpAdvQuiz_header">
                            <span><?php echo esc_html($index); ?></span>. <?php _e('Question', 'wp-adv-quiz'); ?>
                        </h5>

                        <?php if ($this->quiz->isShowPoints()) { ?>
                            <span style="font-weight: bold; float: right;"><?php printf(__('%d points', 'wp-adv-quiz'),
                                    $question->getPoints()); ?></span>
                            <div style="clear: both;"></div>
                        <?php } ?>

                        <?php if ($question->getCategoryId() && $this->quiz->isShowCategory()) { ?>
                            <div style="font-weight: bold; padding-top: 5px;">
                                <?php printf(__('Category: %s', 'wp-adv-quiz'),
                                    esc_html($question->getCategoryName())); ?>
                            </div>
                        <?php } ?>
                        <div class="wpAdvQuiz_question" style="margin: 10px 0 0 0;">
                            <div class="wpAdvQuiz_question_text">
                                <?php echo do_shortcode(apply_filters('comment_text', $question->getQuestion())); ?>
                            </div>
                            <?php if ($question->getAnswerType() === 'matrix_sort_answer') { ?>
                                <div class="wpAdvQuiz_matrixSortString">
                                    <h5 class="wpAdvQuiz_header"><?php _e('Sort elements', 'wp-adv-quiz'); ?></h5>
                                    <ul class="wpAdvQuiz_sortStringList">
                                        <?php
                                        $matrix = array();
                                        foreach ($answerArray as $k => $v) {
                                            $matrix[$k][] = $k;

                                            foreach ($answerArray as $k2 => $v2) {
                                                if ($k != $k2) {
                                                    if ($v->getAnswer() == $v2->getAnswer()) {
                                                        $matrix[$k][] = $k2;
                                                    } else {
                                                        if ($v->getSortString() == $v2->getSortString()) {
                                                            $matrix[$k][] = $k2;
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        foreach ($answerArray as $k => $v) {
                                            ?>
                                            <li class="wpAdvQuiz_sortStringItem" data-pos="<?php echo esc_attr($k); ?>"
                                                data-correct="<?php echo implode(',', $matrix[$k]); ?>">
                                                <?php echo esc_attr($v->isSortStringHtml()) ? $v->getSortString() : esc_html($v->getSortString()); ?>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div style="clear: both;"></div>
                                </div>
                            <?php } ?>
                            <ul class="wpAdvQuiz_questionList" data-question_id="<?php echo esc_attr($question->getId()); ?>"
                                data-type="<?php echo esc_attr($question->getAnswerType()); ?>">
                                <?php
                                $answer_index = 0;

                                foreach ($answerArray as $v) {
                                    $answer_text = $v->isHtml() ? $v->getAnswer() : esc_html($v->getAnswer());

                                    if ($answer_text == '') {
                                        continue;
                                    }

                                    if ($question->isAnswerPointsActivated()) {
                                        $json[$question->getId()]['points'][] = $v->getPoints();

                                        // 								if(!$question->isAnswerPointsDiffModusActivated())
                                        // 									$catPoints[$question->getCategoryId()] += $question->getPoints();
                                    }

                                    ?>

                                    <li class="wpAdvQuiz_questionListItem" data-pos="<?php echo esc_attr($answer_index); ?>">

                                        <?php if ($question->getAnswerType() === 'single' || $question->getAnswerType() === 'multiple') { ?>
                                            <?php $json[$question->getId()]['correct'][] = (int)$v->isCorrect(); ?>
                                            <span <?php echo esc_attr($this->quiz->isNumberedAnswer()) ? '' : 'style="display:none;"' ?>></span>
                                            <label>
                                                <input class="wpAdvQuiz_questionInput"
                                                       type="<?php echo esc_attr($question->getAnswerType()) === 'single' ? 'radio' : 'checkbox'; ?>"
                                                       name="question_<?php echo esc_attr($this->quiz->getId()); ?>_<?php echo esc_attr($question->getId()); ?>"
                                                       value="<?php echo esc_attr(($answer_index + 1)); ?>"> <?php echo $answer_text; ?>
                                            </label>

                                        <?php } else {
                                            if ($question->getAnswerType() === 'sort_answer') { ?>
                                                <?php $json[$question->getId()]['correct'][] = (int)$answer_index; ?>
                                                <div class="wpAdvQuiz_sortable">
                                                    <?php echo $answer_text; ?>
                                                </div>
                                            <?php } else {
                                                if ($question->getAnswerType() === 'free_answer') { ?>
                                                    <?php $json[$question->getId()]['correct'] = $this->getFreeCorrect($v); ?>
                                                    <label>
                                                        <input class="wpAdvQuiz_questionInput" type="text"
                                                               name="question_<?php echo esc_attr($this->quiz->getId()); ?>_<?php echo esc_attr($question->getId()); ?>"
                                                               style="width: 300px;">
                                                    </label>
                                                <?php } else {
                                                    if ($question->getAnswerType() === 'matrix_sort_answer') { ?>
                                                        <?php
                                                        $json[$question->getId()]['correct'][] = (int)$answer_index;
                                                        $msacwValue = $question->getMatrixSortAnswerCriteriaWidth() > 0 ? $question->getMatrixSortAnswerCriteriaWidth() : 20;
                                                        ?>
                                                        <table>
                                                            <tbody>
                                                            <tr class="wpAdvQuiz_mextrixTr">
                                                                <td width="<?php echo esc_attr($msacwValue); ?>%">
                                                                    <div
                                                                        class="wpAdvQuiz_maxtrixSortText"><?php echo $answer_text; ?></div>
                                                                </td>
                                                                <td width="<?php echo esc_attr(100 - $msacwValue); ?>%">
                                                                    <ul class="wpAdvQuiz_maxtrixSortCriterion"></ul>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>

                                                    <?php } else {
                                                        if ($question->getAnswerType() === 'cloze_answer') {
                                                            $clozeData = $this->fetchCloze($v->getAnswer());

                                                            $this->_clozeTemp = $clozeData['data'];

                                                            $json[$question->getId()]['correct'] = $clozeData['correct'];

                                                            if ($question->isAnswerPointsActivated()) {
                                                                $json[$question->getId()]['points'] = $clozeData['points'];
                                                            }

                                                            $cloze = do_shortcode(apply_filters('comment_text',
                                                                $clozeData['replace']));
                                                            $cloze = $clozeData['replace'];

                                                            echo preg_replace_callback('#@@wpAdvQuizCloze@@#im',
                                                                array($this, 'clozeCallback'), $cloze);
                                                        } else {
                                                            if ($question->getAnswerType() === 'assessment_answer') {
                                                                $assessmentData = $this->fetchAssessment($v->getAnswer(),
                                                                    $this->quiz->getId(), $question->getId());

                                                                $assessment = do_shortcode(apply_filters('comment_text',
                                                                    $assessmentData['replace']));

                                                                echo preg_replace_callback('#@@wpAdvQuizAssessment@@#im',
                                                                    array($this, 'assessmentCallback'), $assessment);

                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        } ?>
                                    </li>
                                    <?php
                                    $answer_index++;
                                }
                                ?>
                            </ul>
                        </div>
                        <?php if (!$this->quiz->isHideAnswerMessageBox()) { ?>
                            <div class="wpAdvQuiz_response" style="display: none;">
                                <div style="display: none;" class="wpAdvQuiz_correct">
                                    <?php if ($question->isShowPointsInBox() && $question->isAnswerPointsActivated()) { ?>
                                        <div>
									<span style="float: left;" class="wpAdvQuiz_respone_span">
										<?php _e('Correct', 'wp-adv-quiz'); ?>
									</span>
                                            <span
                                                style="float: right;"><?php echo esc_html($question->getPoints()) . ' / ' . $question->getPoints(); ?> <?php _e('Points',
                                                    'wp-adv-quiz'); ?></span>

                                            <div style="clear: both;"></div>
                                        </div>
                                    <?php } else { ?>
                                        <span class="wpAdvQuiz_respone_span">
									<?php _e('Correct', 'wp-adv-quiz'); ?>
								</span><br>
                                    <?php }
                                    $_correctMsg = trim(do_shortcode(apply_filters('comment_text',
                                        $question->getCorrectMsg())));

									if (stripos(ltrim($_correctMsg), '<p') === 0 && preg_match('/^<p[^>]*>/i', $_correctMsg)) {
                                        echo $_correctMsg;
                                    } else {
                                        echo '<p>', htmlspecialchars($_correctMsg, ENT_QUOTES, 'UTF-8'), '</p>';
                                    }
                                    ?>
                                </div>
                                <div style="display: none;" class="wpAdvQuiz_incorrect">
                                    <?php if ($question->isShowPointsInBox() && $question->isAnswerPointsActivated()) { ?>
                                        <div>
									<span style="float: left;" class="wpAdvQuiz_respone_span">
										<?php _e('Incorrect', 'wp-adv-quiz'); ?>
									</span>
                                            <span style="float: right;"><span
                                                    class="wpAdvQuiz_responsePoints"></span> / <?php echo esc_html($question->getPoints()); ?> <?php _e('Points',
                                                    'wp-adv-quiz'); ?></span>

                                            <div style="clear: both;"></div>
                                        </div>
                                    <?php } else { ?>
                                        <span class="wpAdvQuiz_respone_span">
									<?php _e('Incorrect', 'wp-adv-quiz'); ?>
								</span><br>
                                    <?php }

                                    if ($question->isCorrectSameText()) {
                                        $_incorrectMsg = do_shortcode(apply_filters('comment_text',
                                            $question->getCorrectMsg()));
                                    } else {
                                        $_incorrectMsg = do_shortcode(apply_filters('comment_text',
                                            $question->getIncorrectMsg()));
                                    }

							
                                    if (stripos(ltrim($_incorrectMsg), '<p') === 0 && preg_match('/^<p[^>]*>/i', $_incorrectMsg)) {
                                        echo $_incorrectMsg;
                                    } else {
                                          echo '<p>', htmlspecialchars($_incorrectMsg, ENT_QUOTES, 'UTF-8'), '</p>';
                                    }
										
                                    ?>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($question->isTipEnabled()) { ?>
                            <div class="wpAdvQuiz_tipp" style="display: none; position: relative;">
                                <div>
                                    <h5 style="margin: 0 0 10px;" class="wpAdvQuiz_header"><?php _e('Hint',
                                            'wp-adv-quiz'); ?></h5>
                                    <?php echo do_shortcode(apply_filters('comment_text', $question->getTipMsg())); ?>
                                </div>
                            </div>
                        <?php } ?>
		
                        <?php if ($this->quiz->getQuizModus() == WpAdvQuiz_Model_Quiz::QUIZ_MODUS_CHECK && !$this->quiz->isSkipQuestionDisabled() && $this->quiz->isShowReviewQuestion()) { ?>
                            
							<input type="button" name="skip" value="<?php _e('Skip question', 'wp-adv-quiz'); ?>"
                                   class="wpAdvQuiz_button wpAdvQuiz_QuestionButton"
                                   style="float: left; margin-right: 10px !important;
									width:<?php echo esc_attr($this->skip_btn_width); ?>px !Important; height:<?php echo esc_attr($this->skip_btn_height); ?>px !Important; background-color: <?php echo esc_attr($this->skip_btn_color); ?> !important">
                        <?php } ?>
						
                        <input type="button" name="back" value="<?php _e('Back', 'wp-adv-quiz'); ?>"
                               class="wpAdvQuiz_button wpAdvQuiz_QuestionButton"
                               style="float: left !important; margin-right: 10px !important; display: none;
							   width:<?php echo esc_attr($this->back_btn_width); ?>px !Important; height:<?php echo esc_attr($this->back_btn_height); ?>px !Important; background-color: <?php echo esc_attr($this->back_btn_color); ?> !important">
                        <?php if ($question->isTipEnabled()) { ?>
                            <input type="button" name="tip" value="<?php _e('Hint', 'wp-adv-quiz'); ?>"
                                   class="wpAdvQuiz_button wpAdvQuiz_QuestionButton wpAdvQuiz_TipButton"
                                   style="float: left !important; display: inline-block; margin-right: 10px !important;
								   width:<?php echo esc_attr($this->hint_btn_width); ?>px !Important; height:<?php echo esc_attr($this->hint_btn_height); ?>px !Important; background-color: <?php echo esc_attr($this->hint_btn_color); ?> !important">
                        <?php } ?>
                        <input type="button" name="check" value="<?php _e('Check', 'wp-adv-quiz'); ?>"
                               class="wpAdvQuiz_button wpAdvQuiz_QuestionButton"
                               style="float: right !important; margin-right: 10px !important; display: none;
							   	width:<?php echo esc_attr($this->check_btn_width); ?>px !Important; height:<?php echo esc_attr($this->check_btn_height); ?>px !Important; background-color: <?php echo esc_attr($this->check_btn_color); ?> !important">
                        <input type="button" name="next" value="<?php _e('Next', 'wp-adv-quiz'); ?>"
                               class="wpAdvQuiz_button wpAdvQuiz_QuestionButton" style="float: right; display: none;
								width:<?php echo esc_attr($this->next_btn_width); ?>px !Important; height:<?php echo esc_attr($this->next_btn_height); ?>px !Important; background-color: <?php echo esc_attr($this->next_btn_color); ?> !important">

                        <div style="clear: both;"></div>

                        <?php if ($this->quiz->getQuizModus() == WpAdvQuiz_Model_Quiz::QUIZ_MODUS_SINGLE) { ?>
                            <div style="margin-bottom: 20px;"></div>
                        <?php } ?>

                    </li>

                <?php } ?>
            </ol>
            <?php if ($this->quiz->getQuizModus() == WpAdvQuiz_Model_Quiz::QUIZ_MODUS_SINGLE) { ?>
                <div>
                    <input type="button" name="wpAdvQuiz_pageLeft"
                           data-text="<?php echo esc_attr(__('Page %d', 'wp-adv-quiz')); ?>"
                           style="float: left; display: none;" class="wpAdvQuiz_button wpAdvQuiz_QuestionButton">
                    <input type="button" name="wpAdvQuiz_pageRight"
                           data-text="<?php echo esc_attr(__('Page %d', 'wp-adv-quiz')); ?>"
                           style="float: right; display: none;" class="wpAdvQuiz_button wpAdvQuiz_QuestionButton">

                    <?php if ($this->quiz->isShowReviewQuestion() && !$this->quiz->isQuizSummaryHide()) { ?>
                        <input type="button" name="checkSingle"
                               value="<?php echo esc_attr($this->_buttonNames['quiz_summary']); ?>"
                               class="wpAdvQuiz_button wpAdvQuiz_QuestionButton" style="float: right;
							   	width:<?php echo esc_attr($this->summary_btn_width); ?>px !Important; height:<?php echo esc_attr($this->summary_btn_height); ?>px !Important; background-color: <?php echo esc_attr($this->summary_btn_color); ?> !important">
                    <?php } else { ?>
                        <input type="button" name="checkSingle"
                               value="<?php echo esc_attr($this->_buttonNames['finish_quiz']); ?>"
                               class="wpAdvQuiz_button wpAdvQuiz_QuestionButton" style="float: right;
							   width:<?php echo esc_attr($this->finish_btn_width); ?>px !Important; height:<?php echo esc_attr($this->finish_btn_height); ?>px !Important; background-color: <?php echo esc_attr($this->finish_btn_color); ?> !important">
                    <?php } ?>

                    <div style="clear: both;"></div>
                </div>
            <?php } ?>
        </div>
        <?php

        return array('globalPoints' => $globalPoints, 'json' => $json, 'catPoints' => $catPoints);
    }

    private function showLoadQuizBox()
    {
        ?>
        <div style="display: none;" class="wpAdvQuiz_loadQuiz">
            <p>
                <?php echo esc_html($this->_buttonNames['quiz_is_loading']); ?>
            </p>
        </div>
        <?php
    }
}