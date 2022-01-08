<?php

class WpAdvQuiz_Helper_JsonQuestionExporter implements WpAdvQuiz_Helper_QuestionExporterInterface
{
    const VERSION = 1;

    /**
     * @var int[]
     */
    protected $ids;

    /**
     * @var WpAdvQuiz_Model_QuestionMapper
     */
    protected $questionMapper;

    /**
     * WpAdvQuiz_Helper_JsonQuestionExporter constructor.
     *
     * @param $ids
     */
    public function __construct($ids)
    {
        $this->ids = $ids;
        $this->questionMapper = new WpAdvQuiz_Model_QuestionMapper();
    }

    public function response()
    {
        $questions = $this->convertQuestionToArray($this->getQuestion($this->ids));
        $response = $this->buildJson($questions);
        $filename = $this->getFilename();

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        return $response;
    }

    protected function getFilename()
    {
        return 'export_question_' . time() . '.json';
    }

    protected function buildJson($questions)
    {
        $array = [
            'version' => WPADVQUIZ_VERSION,
            'export_version' => self::VERSION,
            'date' => time(),
            'questions' => $questions,
        ];

        return json_encode($array);
    }

    /**
     * @param $ids
     * @return WpAdvQuiz_Model_Question|WpAdvQuiz_Model_Question[]|null
     */
    protected function getQuestion($ids)
    {
        return $this->questionMapper->fetchById($ids);
    }

    /**
     * @param WpAdvQuiz_Model_Question[] $questions
     *
     * @return array
     */
    protected function convertQuestionToArray($questions)
    {
        if ($questions === null || empty($questions)) {
            return [];
        }

        $a = [];

        foreach ($questions as $question) {
            $a[] = [
                'sort' => $question->getSort(),
                'title' => $question->getTitle(),
                'question' => $question->getQuestion(),
                'correct_msg' => $question->getCorrectMsg(),
                'incorrect_msg' => $question->getIncorrectMsg(),
                'answer_type' => $question->getAnswerType(),
                'correct_same_text' => $question->isCorrectSameText(),
                'tip_enabled' => $question->isTipEnabled(),
                'tip_msg' => $question->getTipMsg(),
                'points' => $question->getPoints(),
                'show_points_in_box' => $question->isShowPointsInBox(),
                'answer_points_activated' => $question->isAnswerPointsActivated(),
                'category_name' => $question->getCategoryName(),
                'answer_points_diff_modus_activated' => $question->isAnswerPointsDiffModusActivated(),
                'disable_correct' => $question->isDisableCorrect(),
                'matrix_sort_answer_criteria_width' => $question->getMatrixSortAnswerCriteriaWidth(),
                'answer_data' => $this->convertAnswerDataToArray($question->getAnswerData()),
            ];
        }

        return $a;
    }

    /**
     * @param WpAdvQuiz_Model_AnswerTypes[]|null $answerData
     *
     * @return array
     */
    protected function convertAnswerDataToArray($answerData)
    {
        if ($answerData === null || empty($answerData)) {
            return [];
        }

        $a = [];

        foreach ($answerData as $answer) {
            $a[] = [
                'answer' => $answer->getAnswer(),
                'html' => $answer->isHtml(),
                'points' => $answer->getPoints(),
                'correct' => $answer->isCorrect(),
                'sort_string' => $answer->getSortString(),
                'sort_string_html' => $answer->isSortStringHtml(),
            ];
        }

        return $a;
    }
}
