<?php

class WpAdvQuiz_View_InfoAdaptation extends WpAdvQuiz_View_View
{
    public function show()
    {
        ?>

        <div class="wrap">
            <h2><?php _e('WP-Pro-Quiz special modification', 'wp-adv-quiz'); ?></h2>

            <p><?php _e('You need special WP-Pro-Quiz modification for your website?', 'wp-adv-quiz'); ?></p>

            <h3><?php _e('We offer you:', 'wp-adv-quiz'); ?></h3>
            <ol style="list-style-type: disc;">
                <li><?php _e('Design adaption for your theme', 'wp-adv-quiz'); ?></li>
                <li><?php _e('Creation of additional modules for your needs', 'wp-adv-quiz'); ?></li>
                <li style="display: none;"><?php _e('Premium Support', 'wp-adv-quiz'); ?></li>
            </ol>

            <h3><?php _e('Contact us:', 'wp-adv-quiz'); ?></h3>
            <ol style="list-style-type: disc;">
                <li><?php _e('Send us an e-mail', 'wp-adv-quiz'); ?> <a href="mailto:wp-adv-quiz@it-gecko.de"
                                                                        style="font-weight: bold;">wp-adv-quiz@it-gecko.de</a>
                </li>
                <li><?php _e('The e-mail must be written in english or german', 'wp-adv-quiz'); ?></li>
                <li><?php _e('Explain your wish detailed and exactly as possible', 'wp-adv-quiz'); ?>
                    <ol style="list-style-type: disc;">
                        <li><?php _e('You can send us screenshots, sketches and attachments', 'wp-adv-quiz'); ?></li>
                    </ol>
                </li>
                <li><?php _e('Send us your full name and your web address (webpage-URL)', 'wp-adv-quiz'); ?></li>
                <li><?php _e('If you wish design adaption, we additionally need the name of your theme',
                        'wp-adv-quiz'); ?></li>
            </ol>

            <p>
                <?php _e('After receiving your e-mail we will verify your request on feasibility. After this you will receive e-mail from us with further details and offer.',
                    'wp-adv-quiz'); ?>
            </p>

            <p>
                <?php _e('Extended support in first 6 months. Reported bugs and updates of WP Pro Quiz are supported. Exception are major releases (update of main version)',
                    'wp-adv-quiz'); ?>
            </p>
        </div>

        <?php
    }
}