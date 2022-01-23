<?php

/**
 * @property WpAdvQuiz_Model_Quiz[] quizzes
 */
class WpAdvQuiz_View_TinyMceShortcodeWindow extends WpAdvQuiz_View_View
{
    public function show()
    {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Wp-Adv-Quiz</title>
            <script type="text/javascript" src="<?php echo site_url('/wp-includes/js/tinymce/tiny_mce_popup.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo site_url('/wp-includes/js/tinymce/utils/mctabs.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo site_url('/wp-includes/js/tinymce/utils/form_utils.js'); ?>"></script>
            <?php
            wp_print_scripts('jquery');
            ?>
        </head>
        <body id="link" onLoad="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';" dir="ltr" class="forceColors">
        <div class="select-sb">

            <select id="wpAdvQuiz" style="padding: 2px; height: 25px; font-size: 16px;width:100%;">
                <option>--Select Quiz--</option>
                <?php foreach ($this->quizzes as $quiz) {
                    echo '<option id="' . esc_attr($quiz->getId()) . '">' . esc_html($quiz->getName()) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="mceActionPanel">
            <input type="submit" id="insert" name="insert" value="Insert" onClick="quiz_insert_shortcode();"/>
        </div>
        <script>

        </script>
        <script type="text/javascript">
            function quiz_insert_shortcode() {
                var selectedIndex = document.getElementById('wpAdvQuiz').selectedIndex;

                if (selectedIndex) {
                    var id = document.getElementById('wpAdvQuiz')[selectedIndex].id;

                    var tagtext = '[WpAdvQuiz ' + id + ']';
                    window.tinyMCE.execCommand('mceInsertContent', false, tagtext);
                }

                tinyMCEPopup.close();
            }
        </script>
        </body>
        </html>


        <?php
    }

}
