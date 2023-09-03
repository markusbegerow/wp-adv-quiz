<?php

class WpAdvQuiz_Helper_CsvStatisticExporter implements WpAdvQuiz_Helper_StatisticExporterInterface
{

    public function exportUser($users, $ref, $forms)
    {
        $build = $this->statisticRefToArray($ref);
        $build['forms'] = $this->formsToArray($forms, $ref->getFormData());
        $build['statistic'] = $this->userStatisticToArray($users);

        $this->sendHeaders($this->getFilename());

        return $this->toCsv($build);
    }

    public function exportOverview($overviews)
    {
        $build = $this->overviewStatisticToArray($overviews);

        $this->sendHeaders($this->getFilename());

        return $this->toCsv($build);
    }

    public function exportHistory($histories, $forms)
    {
        $build = $this->historyStatisticToArray($histories, $forms);

        $this->sendHeaders($this->getFilename());

        return $this->toCsv($build);
    }

    protected function toCsv($array)
    {
		//$array = json_decode($array,true);

		$csv = '';

		$header = false;
		foreach ($array as $line) {	
			if (empty($header)) {
				$header = array_keys($line);
				$csv .= implode(',', $header);
				$header = array_flip($header);		
			}
			
			$line_array = array();
			
			foreach($line as $value) {
				array_push($line_array, $value);
			}
			
			$csv .= "\n" . implode(',', $line_array);
		}

		//output as CSV string

		return $csv;
    }

    protected function getFilename()
    {
        return 'export_statistics_' . time() . '.csv';
    }

    protected function sendHeaders($filename)
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
    }

    /**
     * @param WpAdvQuiz_Model_Form[] $forms
     * @param array $formData
     * @param bool $onlyInStastic
     *
     * @return array
     */
    protected function formsToArray($forms, $formData, $onlyInStastic = false)
    {
        $result = [];

        foreach ($forms as $form) {
            if (!$formData || !isset($formData[$form->getFormId()])) {
                continue;
            }

            if($onlyInStastic && !$form->isShowInStatistic()) {
                continue;
            }

            $data = $formData[$form->getFormId()];

            $result[] = [
                'name' => $form->getFieldname(),
                'value' => WpAdvQuiz_Helper_Form::formToString($form, $data)
            ];
        }

        return $result;
    }

    /**
     * @param WpAdvQuiz_Model_StatisticRefModel $refs
     *
     * @return array
     */
    protected function statisticRefToArray($refs) {
        return [
            'user_id' => $refs->getUserId(),
            'user_name' => $this->getUsername($refs->getUserId()),
            'create_time' => $refs->getCreateTime(),
        ];
    }

    /**
     * @param int|null $userId
     * @return string
     */
    protected function getUsername($userId) {
        $userName = __('Anonymous', 'wp-adv-quiz');

        if ($userId) {
            $userInfo = get_userdata($userId);

            if ($userInfo !== false) {
                $userName = $userInfo->user_login . ' (' . $userInfo->display_name . ')';
            } else {
                $userName = __('Deleted user', 'wp-adv-quiz');
            }
        }

        return $userName;
    }

    /**
     * @param WpAdvQuiz_Model_StatisticUser[] $statistics
     *
     * @return array
     */
    protected function userStatisticToArray($statistics)
    {
        $result = [];

        foreach ($statistics as $statistic) {
            $result[] = [
                'name' => $statistic->getQuestionName(),
                'category' => $statistic->getCategoryId() ? $statistic->getCategoryName() : null,
                'correct_count' => $statistic->getCorrectCount(),
                'incorrect_count' => $statistic->getIncorrectCount(),
                'solved_count' => $statistic->getSolvedCount(),
                'points' => $statistic->getPoints(),
                'gpoints' => $statistic->getGPoints(),
                'solved_time' => $statistic->getQuestionTime(),
            ];
        }

        return $result;
    }

    /**
     * @param WpAdvQuiz_Model_StatisticHistory[] $histories
     * @param WpAdvQuiz_Model_Form[] $forms
     * @return array
     */
    protected function historyStatisticToArray($histories, $forms)
    {
        $result = [];

        foreach ($histories as $history) {
            $result[] = [
                'user_id' => $history->getUserId(),
                'user_name' => $history->getUserName(),
                'create_time' => $history->getCreateTime(),
                'correct_count' => $history->getCorrectCount(),
                'incorrect_count' => $history->getIncorrectCount(),
                'solved_count' => $history->getSolvedCount(),
                'points' => $history->getPoints(),
                'gpoints' => $history->getGPoints(),
                'solved_time' => $history->getQuestionTime(),
                'result' => $history->getGPoints() ? round(100 * $history->getPoints() / $history->getGPoints(), 2) : 0,
                //'forms' => $this->formsToArray($forms, $history->getFormData(), true),
            ];
        }

        return $result;
    }

    /**
     * @param WpAdvQuiz_Model_StatisticOverview[] $overviews
     * @return array
     */
    protected function overviewStatisticToArray($overviews)
    {
        $result = [];

        foreach ($overviews as $statistic) {
            $result[] = [
                'user_id' => $statistic->getUserId(),
                'user_name' => $this->getUsername($statistic->getUserId()),
                'correct_count' => $statistic->getCorrectCount(),
                'incorrect_count' => $statistic->getIncorrectCount(),
                'points' => $statistic->getPoints(),
                'gpoints' => $statistic->getGPoints(),
                'solved_time' => $statistic->getQuestionTime(),
                'result' => $statistic->getGPoints() ? round(100 * $statistic->getPoints() / $statistic->getGPoints(), 2) : 0,
            ];
        }

        return $result;
    }
}
