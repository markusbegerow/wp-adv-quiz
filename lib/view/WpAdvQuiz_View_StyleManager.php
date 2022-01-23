<?php

class WpAdvQuiz_View_StyleManager extends WpAdvQuiz_View_View
{

    public function show()
    {

        ?>


        <div class="wrap">
            <h2 style="margin-bottom: 10px;"><?php echo esc_html($this->header); ?></h2>
            <a class="button-secondary" href="admin.php?page=wpAdvQuiz"><?php _e('back to overview',
                    'wp-adv-quiz'); ?></a>

            <form method="post">
                <div id="poststuff">
                    <div class="postbox">
                        <h3 class="hndle"><?php _e('Front', 'wp-adv-quiz'); ?></h3>

                        <div class="wrap wpAdvQuiz_quizEdit">
                            <table class="form-table">
                                <tbody>
                                <tr>
                                    <td width="50%">


                                    </td>
                                    <td>


                                        <div style="" class="wpAdvQuiz_quiz">
                                            <ol class="wpAdvQuiz_list">


                                                <li class="wpAdvQuiz_listItem" style="display: list-item;">
                                                    <div class="wpAdvQuiz_question_page">
                                                        Frage <span>4</span> von <span>7</span>
                                                        <span style="float:right;">1 Punkte</span>

                                                        <div style="clear: right;"></div>
                                                    </div>
                                                    <h3><span>4</span>. Frage</h3>

                                                    <div class="wpAdvQuiz_question" style="margin: 10px 0px 0px 0px;">
                                                        <div class="wpAdvQuiz_question_text">
                                                            <p>Frage3</p>
                                                        </div>
                                                        <ul class="wpAdvQuiz_questionList">


                                                            <li class="wpAdvQuiz_questionListItem" style="">
                                                                <label>
                                                                    <input class="wpAdvQuiz_questionInput"
                                                                           type="checkbox" name="question_5_26"
                                                                           value="2"> Test </label>
                                                            </li>
                                                            <li class="wpAdvQuiz_questionListItem" style="">
                                                                <label>
                                                                    <input class="wpAdvQuiz_questionInput"
                                                                           type="checkbox" name="question_5_26"
                                                                           value="1"> Test </label>
                                                            </li>
                                                            <li class="wpAdvQuiz_questionListItem" style="">
                                                                <label>
                                                                    <input class="wpAdvQuiz_questionInput"
                                                                           type="checkbox" name="question_5_26"
                                                                           value="3"> Test </label>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="wpAdvQuiz_response" style="">
                                                        <div style="" class="wpAdvQuiz_correct">
						<span>
							Korrekt						</span>

                                                            <p>
                                                            </p>
                                                        </div>

                                                    </div>
                                                    <div class="wpAdvQuiz_tipp" style="display: none;">
                                                        <h3>Tipp</h3>
                                                    </div>
                                                    <input type="button" name="check" value="Prüfen"
                                                           class="wpAdvQuiz_QuestionButton"
                                                           style="float: left !important; margin-right: 10px !important;">
                                                    <input type="button" name="back" value="Zurück"
                                                           class="wpAdvQuiz_QuestionButton"
                                                           style="float: left !important; margin-right: 10px !important; ">
                                                    <input type="button" name="next" value="Nächste Frage"
                                                           class="wpAdvQuiz_QuestionButton" style="float: right; ">

                                                    <div style="clear: both;"></div>
                                                </li>
                                            </ol>
                                        </div>


                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <?php
    }
}