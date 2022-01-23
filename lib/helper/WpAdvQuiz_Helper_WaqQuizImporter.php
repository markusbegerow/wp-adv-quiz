<?php

class WpAdvQuiz_Helper_WaqQuizImporter implements WpAdvQuiz_Helper_QuizImporterInterface
{

    /**
     * @var string
     */
    private $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    protected function validate()
    {
        $code = substr($this->content, 0, 13);

        $c = substr($code, 0, 3);
        $v2 = substr($code, 8, 5);

        if ($c !== 'WAQ') {
            return false;
        }

        if ($v2 < 3) {
            return false;
        }

        return true;
    }

    public function getImport()
    {
        if (!$this->validate()) {
            return new WP_Error(__('File cannot be processed', 'wp-adv-quiz'));
        }

        $data = substr($this->content, 13);
        $b = base64_decode($data);

        if ($b === null) {
            return new WP_Error(__('File cannot be processed', 'wp-adv-quiz'));
        }

        $check = $this->saveUnserialize($b, $o);

        if ($check === false || !is_array($o)) {
            return new WP_Error(__('File cannot be processed', 'wp-adv-quiz'));
        }

        return $o;
    }

    public function import($ids = false)
    {
        $data = $this->getImport();

        if ($data === false) {
            return false;
        }

        switch ($data['exportVersion']) {
            case '3':
            case '4':
                return $this->saveToDatabase($data, $ids, $data['exportVersion']);
                break;
        }

        return false;
    }

    protected function saveUnserialize($str, &$into)
    {
        $into = @unserialize($str);

        return $into !== false || rtrim($str) === serialize(false);
    }

    /**
     * @param resource|string $res
     *
     * @return self|null
     */
    public static function factory($res)
    {
        $importer = null;

        if (is_resource($res)) {
            $res = stream_get_contents($res);
        }

        if (is_string($res) && !empty($res)) {
            $importer = new self($res);
        }

        return $importer;
    }

    private function saveToDatabase($o, $ids = false, $version = '1')
    {
        $quizMapper = new WpAdvQuiz_Model_QuizMapper();
        $questionMapper = new WpAdvQuiz_Model_QuestionMapper();
        $categoryMapper = new WpAdvQuiz_Model_CategoryMapper();
        $formMapper = new WpAdvQuiz_Model_FormMapper();

        $categoryArray = $categoryMapper->getCategoryArrayForImport();
        $categoryArrayQuiz = $categoryMapper->getCategoryArrayForImport(WpAdvQuiz_Model_Category::CATEGORY_TYPE_QUIZ);

        foreach ($o['master'] as $master) {
            /** @var WpAdvQuiz_Model_Quiz $master */

            if (get_class($master) !== 'WpAdvQuiz_Model_Quiz') {
                continue;
            }

            $oldId = $master->getId();

            if ($ids !== false) {
                if (!in_array($oldId, $ids)) {
                    continue;
                }
            }

            $master->setId(0);

            if ($version == 3) {
                if ($master->isQuestionOnSinglePage()) {
                    $master->setQuizModus(WpAdvQuiz_Model_Quiz::QUIZ_MODUS_SINGLE);
                } else {
                    if ($master->isCheckAnswer()) {
                        $master->setQuizModus(WpAdvQuiz_Model_Quiz::QUIZ_MODUS_CHECK);
                    } else {
                        if ($master->isBackButton()) {
                            $master->setQuizModus(WpAdvQuiz_Model_Quiz::QUIZ_MODUS_BACK_BUTTON);
                        } else {
                            $master->setQuizModus(WpAdvQuiz_Model_Quiz::QUIZ_MODUS_NORMAL);
                        }
                    }
                }
            }

            $master->setCategoryId(0);

            if (trim($master->getCategoryName()) != '') {
                if (isset($categoryArrayQuiz[strtolower($master->getCategoryName())])) {
                    $master->setCategoryId($categoryArrayQuiz[strtolower($master->getCategoryName())]);
                } else {
                    $categoryModel = new WpAdvQuiz_Model_Category();
                    $categoryModel->setCategoryName($master->getCategoryName());
                    $categoryModel->setType(WpAdvQuiz_Model_Category::CATEGORY_TYPE_QUIZ);

                    $categoryMapper->save($categoryModel);

                    $master->setCategoryId($categoryModel->getCategoryId());

                    $categoryArrayQuiz[strtolower($master->getCategoryName())] = $categoryModel->getCategoryId();
                }
            }

            $quizMapper->save($master);

            if (isset($o['forms']) && isset($o['forms'][$oldId])) {
                foreach ($o['forms'][$oldId] as $form) {
                    /** @var WpAdvQuiz_Model_Form $form * */

                    $form->setFormId(0);
                    $form->setQuizId($master->getId());
                }

                $formMapper->update($o['forms'][$oldId]);
            }

            $sort = 0;

            foreach ($o['question'][$oldId] as $question) {
                /** @var WpAdvQuiz_Model_Question $question */

                if (get_class($question) !== 'WpAdvQuiz_Model_Question') {
                    continue;
                }

                $question->setQuizId($master->getId());
                $question->setId(0);
                $question->setSort($sort++);
                $question->setCategoryId(0);

                if (trim($question->getCategoryName()) != '') {
                    if (isset($categoryArray[strtolower($question->getCategoryName())])) {
                        $question->setCategoryId($categoryArray[strtolower($question->getCategoryName())]);
                    } else {
                        $categoryModel = new WpAdvQuiz_Model_Category();
                        $categoryModel->setCategoryName($question->getCategoryName());
                        $categoryMapper->save($categoryModel);

                        $question->setCategoryId($categoryModel->getCategoryId());

                        $categoryArray[strtolower($question->getCategoryName())] = $categoryModel->getCategoryId();
                    }
                }

                $questionMapper->save($question);
            }
        }

        return true;
    }
}
