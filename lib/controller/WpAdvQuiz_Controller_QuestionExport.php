<?php

class WpAdvQuiz_Controller_QuestionExport extends WpAdvQuiz_Controller_Controller
{
    public function route()
    {
        $this->handleExport();
    }

    protected function handleExport()
    {
        if (!current_user_can('wpAdvQuiz_export')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
		
		
		$exportIds = filter_input(INPUT_POST,'exportIds',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
		$exportIds = !empty($exportIds) ? $exportIds : null;
		
		$exportType = filter_input(INPUT_POST,'exportType',FILTER_SANITIZE_STRING);
		$exportType = $exportType ? : null;
		
		
        $exportIds = $this->prepareExportIds($exportIds);
		
        if (empty($exportIds) || empty($exportType)) {
            wp_die(__('Invalid arguments'));
        }

        $questionExport = new WpAdvQuiz_Helper_QuestionExport();
        $exporter = $questionExport->factory($exportIds, $exportType);

        if ($exporter === null) {
            wp_die(__('Unsupported exporter'));
        }

        $response = $exporter->response();

        if($response instanceof WP_Error) {
            wp_die($response);
        } else if ($response !== null) {
            echo filter_var($response,FILTER_UNSAFE_RAW,FILTER_FLAG_ENCODE_AMP);
        }

        exit;
    }

    /**
     * @param array $ids
     * @return array
     */
    protected function prepareExportIds($ids)
    {
        return array_map('intval', array_filter($ids, 'is_numeric'));
    }
}
