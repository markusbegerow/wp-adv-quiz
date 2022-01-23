<?php

class WpAdvQuiz_Helper_QuizExport
{
    /**
     * @return array
     */
    public function getSupportedExportFormats()
    {
        $formats = [
            'waq' => 'WAQ',
            'xml' => 'XML'
        ];

        return apply_filters('wpAdvQuiz_filter_quizExport_supportedExportFormats', $formats);
    }

    public function getExternalFactories()
    {
        $factories = apply_filters('wpAdvQuiz_filter_quizExport_factory', []);

        return is_array($factories) ? $factories : [];
    }

    /**
     * @param int[] $ids
     * @param string $type
     *
     * @return WpAdvQuiz_Helper_QuizExporterInterface|null
     */
    public function factory($ids, $type)
    {
        $exporter = null;

        switch ($type) {
            case 'waq':
                $exporter = $this->createWaqExporter($ids);
                break;
            case 'xml':
                $exporter = $this->createXmlExporter($ids);
                break;
            default:
                $exporter = $this->handleExternalFactories($ids, $type);
                break;
        }

        return $exporter;
    }

    /**
     * @param $ids
     *
     * @return WpAdvQuiz_Helper_QuizExporterInterface
     */
    protected function createWaqExporter($ids)
    {
        return new WpAdvQuiz_Helper_WaqQuizExporter($ids);
    }

    /**
     * @param $ids
     *
     * @return WpAdvQuiz_Helper_QuizExporterInterface
     */
    protected function createXmlExporter($ids)
    {
        return new WpAdvQuiz_Helper_XmlQuizExporter($ids);
    }

    /**
     * @param $ids
     * @param $type
     * @return WpAdvQuiz_Helper_QuizExporterInterface|null
     */
    protected function handleExternalFactories(array $ids, $type)
    {
        $exporter = null;
        $factories = $this->getExternalFactories();

        if (isset($factories[$type])) {
            $factory = $factories[$type];

            if (is_callable($factory)) {
                $exporter = call_user_func($factory, $ids, $type);
            }
        }

        return $exporter instanceof WpAdvQuiz_Helper_QuizExporterInterface ? $exporter : null;
    }
}
