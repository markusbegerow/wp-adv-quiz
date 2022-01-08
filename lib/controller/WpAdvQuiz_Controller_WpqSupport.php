<?php

class WpAdvQuiz_Controller_WpqSupport extends WpAdvQuiz_Controller_Controller
{

    public function route()
    {
        $this->showView();
    }

    private function showView()
    {
        $view = new WpAdvQuiz_View_WpqSupport();

        $view->show();
    }
}