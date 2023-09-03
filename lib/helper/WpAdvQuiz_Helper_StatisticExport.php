<?php

class WpAdvQuiz_Helper_StatisticExport
{
    /**
     * @return array
     */
    public function getSupportedExportFormats()
    {
        $formats = [
			'csv' => 'CSV',
			'xls' => 'XLS',
            'json' => 'JSON'
        ];

        return apply_filters('wpAdvQuiz_filter_statisticExport_supportedExportFormats', $formats);
    }

    public function getExternalFactories()
    {
        $factories = apply_filters('wpAdvQuiz_filter_statisticExport_factory', []);

        return is_array($factories) ? $factories : [];
    }

    /**
     * @param string $type
     *
     * @return WpAdvQuiz_Helper_StatisticExporterInterface|null
     */
    public function factory($type)
    {
        $exporter = null;

        switch ($type) {
		     case 'csv':
                $exporter = $this->createCsvExporter();
                break;
		     case 'xls':
                $exporter = $this->createExcelExporter();
                break;
            case 'json':
                $exporter = $this->createJsonExporter();
                break;
            default:
                $exporter = $this->handleExternalFactories($type);
                break;
        }

        return $exporter;
    }
	
	/**
     *
     * @return WpAdvQuiz_Helper_StatisticExporterInterface
     */
    protected function createCsvExporter()
    {
        return new WpAdvQuiz_Helper_CsvStatisticExporter();
    }
	
	/**
     *
     * @return WpAdvQuiz_Helper_StatisticExporterInterface
     */
    protected function createExcelExporter()
    {
        return new WpAdvQuiz_Helper_ExcelStatisticExporter();
    }

    /**
     *
     * @return WpAdvQuiz_Helper_StatisticExporterInterface
     */
    protected function createJsonExporter()
    {
        return new WpAdvQuiz_Helper_JsonStatisticExporter();
    }

    /**
     * @param $type
     * @return WpAdvQuiz_Helper_StatisticExporterInterface|null
     */
    protected function handleExternalFactories($type)
    {
        $exporter = null;
        $factories = $this->getExternalFactories();

        if (isset($factories[$type])) {
            $factory = $factories[$type];

            if (is_callable($factory)) {
                $exporter = call_user_func($factory, $type);
            }
        }

        return $exporter instanceof WpAdvQuiz_Helper_StatisticExporterInterface ? $exporter : null;
    }
}
