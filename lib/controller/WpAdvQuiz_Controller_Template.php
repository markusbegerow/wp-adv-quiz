<?php

class WpAdvQuiz_Controller_Template
{
    public static function ajaxEditTemplate($data)
    {
        if (!current_user_can('wpAdvQuiz_edit_quiz')) {
            return json_encode(array());
        }

        $templateMapper = new WpAdvQuiz_Model_TemplateMapper();

        $template = new WpAdvQuiz_Model_Template($data);

        $templateMapper->updateName($template->getTemplateId(), $template->getName());

        return json_encode(array());
    }

    public static function ajaxDeleteTemplate($data)
    {
        if (!current_user_can('wpAdvQuiz_edit_quiz')) {
            return json_encode(array());
        }

        $templateMapper = new WpAdvQuiz_Model_TemplateMapper();

        $template = new WpAdvQuiz_Model_Template($data);

        $templateMapper->delete($template->getTemplateId());

        return json_encode(array());
    }
} 