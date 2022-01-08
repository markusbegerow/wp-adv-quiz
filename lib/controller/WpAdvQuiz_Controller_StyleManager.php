<?php

class WpAdvQuiz_Controller_StyleManager extends WpAdvQuiz_Controller_Controller
{

    public function route()
    {
        $this->show();
    }

    private function show()
    {

        wp_enqueue_style(
            'wpAdvQuiz_front_style',
            plugins_url('css/wpAdvQuiz_front.min.css', WPADVQUIZ_FILE),
            array(),
            WPADVQUIZ_VERSION
        );

        $view = new WpAdvQuiz_View_StyleManager();

        $view->show();
    }
}