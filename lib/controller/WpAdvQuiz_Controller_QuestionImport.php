<?php

class WpAdvQuiz_Controller_QuestionImport extends WpAdvQuiz_Controller_Controller
{
    public function route()
    {
		$action = filter_input(INPUT_GET,'action',FILTER_SANITIZE_STRING);
		$action = $action ? : 'show';

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
		
		$MyArray = !empty($_FILES);
		
		If ($MyArray) {
			$myimport = isset($_FILES);
			$import = isset($_FILES['import']);

			$filepath = $_FILES['import']['tmp_name'];
			$size = filesize($filepath);
			$error = $_FILES['import']['error'];
			
		}
		else
		{
			$myimport = false;
			$import = false;
			$size = 0;
			$error = 1;
		}
		
		$name = $_FILES['import']['name'];
		$type = $_FILES['import']['type'];
        $data = file_get_contents($_FILES['import']['tmp_name']);

        if ( !$myimport || !$import || $size <= 0 || $error != 0) {
            wp_die(__('Import failed'));
        }
		 
		$fileInfo = wp_check_filetype(basename($name), array('json' => 'application/json'));
		if ($fileInfo['ext'] != 'json' || $fileInfo['type'] != 'application/json' ) {
			wp_die(__('Unsupport import'));
		}

        $questionImport = new WpAdvQuiz_Helper_QuestionImport();
        
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
		$quizId = filter_input(INPUT_GET,'quizId',FILTER_VALIDATE_INT);
		$quizId = $quizId ? : 0;
		
        return $quizId;
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
		
		$name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
		$name = !empty($name) ? $name : null;
		
		$type = filter_input(INPUT_POST,'type',FILTER_SANITIZE_STRING);
		$type = !empty($type) ? $type : null;
		
		$data = filter_input(INPUT_POST,'data',FILTER_DEFAULT);
		$data = !empty($data) ? $data : null;
		

        if(empty($name) || empty($type) || empty($data)) {
            WpAdvQuiz_View_View::admin_notices(__('Quiz Import failed', 'wp-adv-quiz'), 'error');

            return;
        }

        $questionImport = new WpAdvQuiz_Helper_QuestionImport();
        $data = base64_decode($data);

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
