<?php

class WpAdvQuiz_Controller_ImportExport extends WpAdvQuiz_Controller_Controller
{

    public function route()
    {

        @set_time_limit(0);
        @ini_set('memory_limit', '128M');
		
		$action = filter_input(INPUT_GET,'action',FILTER_SANITIZE_STRING);
		$action = $action ? : 'show';

        if ($action == 'show') {
            wp_die("Error");
        }
		
		switch ($action) {
            case 'export':
                $this->handleExport();
                break;
            case 'import':
                $this->handleImport();
                break;
        }
    }

    private function handleExport()
    {

        if (!current_user_can('wpAdvQuiz_export')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
		
		$exportType = filter_input(INPUT_POST,'exportType',FILTER_SANITIZE_STRING);
		$exportType = $exportType ? : '';

        $helper = new WpAdvQuiz_Helper_QuizExport();
        $exporter = $helper->factory($this->_post['exportIds'], $exportType);

        if ($exporter === null) {
            wp_die(__('Unsupported expoter', 'wp-adv-quiz'));
        }

        $response = $exporter->response();
        if ($response instanceof WP_Error) {
            wp_die($response);
        } else if ($response !== null) {
            echo filter_var($response,FILTER_UNSAFE_RAW,FILTER_FLAG_ENCODE_AMP); 
        }

        exit;
    }

    private function handleImport()
    {
        if (!current_user_can('wpAdvQuiz_import')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
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

		if ($myimport && $import && $size > 0 && $error == 0) {
			$this->previewImport();
		} else {
			if(isset($this->_post, $this->_post['importSave'])) {
				$this->saveImport();
			} else {
				$view = new WpAdvQuiz_View_Import();
				$view->error = __('File cannot be processed', 'wp-adv-quiz');
				$view->show();
			}
		}
		
		

        return;
    }

    protected function previewImport()
    {
		$name = $_FILES['import']['name'];
		
		$fileInfo = wp_check_filetype(basename($name), array('waq' => 'application/waq', 'xml' => 'application/xml'));

		if (!in_array($fileInfo['ext'], array('waq', 'xml')) || !in_array($fileInfo['type'], array('application/waq', 'application/xml')) ) {
			wp_die(__('Unsupport import'));
		}
				

        $type = $_FILES['import']['type'];
        $data = file_get_contents($_FILES['import']['tmp_name']);
		
        $helper = new WpAdvQuiz_Helper_QuizImport();

        if (!$helper->canHandle($name, $type)) {
            wp_die(__('Unsupport import'));
        }

        $importer = $helper->factory($name, $type, $data);

        if ($importer === null) {
            wp_die(__('Unsupport import'));
        }

        $import = $importer->getImport();

        $view = new WpAdvQuiz_View_Import();
        $view->error = false;
        $view->importType = $type;
        $view->name = $name;
        $view->importData = base64_encode($data);

        if (is_wp_error($import)) {
            $view->error = $import->get_error_message();
        } else {
            $view->import = $import;
        }

        $view->show();
    }

    protected function saveImport()
    {
		$name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
		$name = !empty($name) ? $name : null;
		
		$type = filter_input(INPUT_POST,'importType',FILTER_DEFAULT);
		$type = !empty($type) ? $type : null;
	
	
		$importData = filter_input(INPUT_POST,'importData',FILTER_DEFAULT);
		$importData = !empty($importData) ? $importData : null;
		
        if(empty($name) || empty($type) || empty($importData)) {
            WpAdvQuiz_View_View::admin_notices(__('Import failed', 'wp-adv-quiz'), 'error');

            return;
        }

    
        $data = base64_decode($importData);
        $ids = isset($this->_post['importItems']) ? $this->_post['importItems'] : false;

        $helper = new WpAdvQuiz_Helper_QuizImport();

        if (!$helper->canHandle($name, $type)) {
            wp_die(__('Unsupport import'));
        }

        $importer = $helper->factory($name, $type, $data);

        if ($importer === null) {
            wp_die(__('Unsupport import'));
        }

        $result = $importer->import($ids);

        $view = new WpAdvQuiz_View_Import();

        if (is_wp_error($result)) {
            $view->error = false;
        } else {
            $view->finish = true;
        }

        $view->show();
    }
}
