<?php

class WpAdvQuiz_Controller_QuestionImport extends WpAdvQuiz_Controller_Controller
{
    public function route()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'preview':
                $this->showPreview();
                break;
            case 'import':
                $this->handleImport();
                break;
        }
    }

    protected function showPreview()
    {
        if (!current_user_can('wpAdvQuiz_import')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $quizId = $this->getQuizId();

        if (!$this->validateQuizId($quizId)) {
            WpAdvQuiz_View_View::admin_notices(__('Quiz not found', 'wp-adv-quiz'), 'error');

            return;
        }

        if (!isset($_FILES) || !isset($_FILES['import']) || $_FILES['import']['size'] <= 0 || $_FILES['import']['error'] != 0) {
            wp_die(__('Import failed'));
        }

        $questionImport = new WpAdvQuiz_Helper_QuestionImport();
        $name = $_FILES['import']['name'];
        $type = $_FILES['import']['type'];
        $data = file_get_contents($_FILES['import']['tmp_name']);

        if (!$questionImport->canHandle($name, $type)) {
            wp_die(__('Unsupport import'));
        }

        $importer = $questionImport->factory($name, $type, $data);

        if ($importer === null) {
            wp_die(__('Unsupport import'));
        }

        $view = new WpAdvQuiz_View_QuestionImportPreview();
        $view->questionNames = $importer->getQuestionPreview();
        $view->quizId = $quizId;
        $view->name = $name;
        $view->type = $type;
        $view->data = base64_encode($data);

        $view->show();
    }

    protected function getQuizId()
    {
        return isset($_GET['quizId']) ? (int)$_GET['quizId'] : 0;
    }

    protected function validateQuizId($quizId)
    {
        $m = new WpAdvQuiz_Model_QuizMapper();

        return (bool)$m->exists($quizId);
    }

    protected function handleImport()
    {
        if (!current_user_can('wpAdvQuiz_import')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $quizId = $this->getQuizId();

        if (!$this->validateQuizId($quizId)) {
            WpAdvQuiz_View_View::admin_notices(__('Quiz not found', 'wp-adv-quiz'), 'error');

            return;
        }

        if(empty($_POST['name']) || empty($_POST['type']) || empty($_POST['data'])) {
            WpAdvQuiz_View_View::admin_notices(__('QuImport failed', 'wp-adv-quiz'), 'error');

            return;
        }

        $questionImport = new WpAdvQuiz_Helper_QuestionImport();
        $name = $_POST['name'];
        $type = $_POST['type'];
        $data = base64_decode($_POST['data']);

        if (!$questionImport->canHandle($name, $type)) {
            wp_die(__('Unsupport import'));
        }

        $importer = $questionImport->factory($name, $type, $data);

        if ($importer === null) {
            wp_die(__('Unsupport import'));
        }

        $importer->import($quizId);

        wp_redirect(admin_url('admin.php?page=wpAdvQuiz&module=question&quiz_id='. $quizId));

        exit;
    }

}
