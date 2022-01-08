<?php

class WpAdvQuiz_Controller_InfoAdaptation extends WpAdvQuiz_Controller_Controller
{

    public function route()
    {
        $this->showAction();
    }

    private function showAction()
    {
        $view = new WpAdvQuiz_View_InfoAdaptation();

        $view->show();
    }
}