<?php

class WpAdvQuiz_Helper_WaqQuizExporter implements WpAdvQuiz_Helper_QuizExporterInterface
{
    const WPPROQUIZ_EXPORT_VERSION = 4;

    /**
     * @var int[]
     */
    private $ids;

    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    public function response()
    {
        $export = $this->getArrayHeader();
        $export['master'] = $this->getQuizMaster();

        foreach ($export['master'] as $master) {
            $export['question'][$master->getId()] = $this->getQuestion($master->getId());
            $export['forms'][$master->getId()] = $this->getForms($master->getId());
        }

        $this->printHeader($this->getFilename());

        return $this->buildReturnValue($export);
    }

    protected function buildReturnValue($export)
    {
        $code = $this->getFileSuffix();

        return $code . base64_encode(serialize($export));
    }

    protected function getFilename()
    {
        return 'WpAdvQuiz_export_' . time() . '.waq';
    }

    protected function printHeader($filename)
    {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
    }

    protected function getArrayHeader()
    {
        $export = [];

        $export['version'] = WPADVQUIZ_VERSION;
        $export['exportVersion'] = static::WPPROQUIZ_EXPORT_VERSION;
        $export['date'] = time();

        return $export;
    }

    protected function getFileSuffix()
    {
        $v = str_pad(WPADVQUIZ_VERSION, 5, '0', STR_PAD_LEFT);
        $v .= str_pad(static::WPPROQUIZ_EXPORT_VERSION, 5, '0', STR_PAD_LEFT);

        return 'WAQ' . $v;
    }

    /**
     * @return WpAdvQuiz_Model_Quiz[]
     */
    protected function getQuizMaster()
    {
        $m = new WpAdvQuiz_Model_QuizMapper();

        $r = [];

        foreach ($this->ids as $id) {
            $master = $m->fetch($id);

            if ($master->getId() > 0) {
                $r[] = $master;
            }
        }

        return $r;
    }

    /**
     * @param $quizId
     * @return WpAdvQuiz_Model_Question[]
     */
    protected function getQuestion($quizId)
    {
        $m = new WpAdvQuiz_Model_QuestionMapper();

        return $m->fetchAll($quizId);
    }

    /**
     * @param $quizId
     * @return WpAdvQuiz_Model_Form[]
     */
    protected function getForms($quizId)
    {
        $formMapper = new WpAdvQuiz_Model_FormMapper();

        return $formMapper->fetch($quizId);
    }
}
