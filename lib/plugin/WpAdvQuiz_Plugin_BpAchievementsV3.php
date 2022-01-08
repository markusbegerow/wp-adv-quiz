<?php

class WpAdvQuiz_Plugin_BpAchievementsV3 extends DPA_Extension
{

    public function __construct()
    {
        $this->actions = array(
            'wp_adv_quiz_completed_quiz' => __('The user completed a quiz.', 'wp-adv-quiz'),
            'wp_adv_quiz_completed_quiz_100_percent' => __('The user completed a quiz with 100 percent.', 'wp-adv-quiz')
        );

        $this->contributors = array(
            array(
                'name' => 'Markus Begerow',
                'gravatar_url' => '',
                'profile_url' => '',
            )
        );

        $this->description = __('A powerful and beautiful quiz plugin for WordPress.', 'wp-adv-quiz');
        $this->id = 'wp-adv-quiz';
        $this->image_url = WPADVQUIZ_URL . '/img/wp_adv_quiz.jpg';
        $this->name = __('WP-Adv-Quiz', 'wp-adv-quiz');
        //$this->rss_url         = '';
        $this->small_image_url = WPADVQUIZ_URL . '/img/wp_adv_quiz_small.jpg';
        $this->version = 5;
        $this->wporg_url = 'http://wordpress.org/extend/plugins/wp-adv-quiz/';
    }

    public function do_update()
    {
        $this->insertTerm();
    }

    public function insertTerm()
    {
        if (function_exists('dpa_get_event_tax_id')) {
            $taxId = dpa_get_event_tax_id();

            foreach ($this->actions as $actionName => $desc) {
                $e = term_exists($actionName, $taxId);

                if ($e === 0 || $e === null) {
                    wp_insert_term($actionName, $taxId, array('description' => $desc));
                }
            }

        }

    }
}