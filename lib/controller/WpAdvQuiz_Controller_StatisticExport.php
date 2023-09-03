<?php

class WpAdvQuiz_Controller_StatisticExport extends WpAdvQuiz_Controller_Controller
{

    public function route()
    {
        if (!current_user_can('wpAdvQuiz_show_statistics')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
		
		$action = filter_input(INPUT_GET,'action',FILTER_SANITIZE_STRING);
		$action = $action ? : 'show';
		
		$quizId = filter_input(INPUT_GET,'quiz_id',FILTER_VALIDATE_INT);
		$quizId = $quizId ? : 0;
		
		$refid = filter_input(INPUT_GET,'ref_id',FILTER_VALIDATE_INT);
		$refid = $refid ? : 0;
		
		$userid = filter_input(INPUT_GET,'user_id',FILTER_VALIDATE_INT);
		$userid = $userid ? : 0;
		
		$avg = filter_input(INPUT_GET,'avg',FILTER_VALIDATE_FLOAT);
		$avg = $avg ? : 0;
		
		$exportType = filter_input(INPUT_POST,'exportType',FILTER_SANITIZE_STRING);
		$exportType = $exportType ? : '';

        switch ($action) {
            case 'user_export':
                try {
                    $this->exportUser($quizId, $refid, $userid, $avg,$exportType);
                } catch (Exception $e) {
                    wp_die(__('An error has occurred.', 'wp-adv-quiz'));
                }
                break;
            case 'history_export':
                try {
                    $this->exportHistory($exportType);
                } catch (Exception $e) {
                    wp_die(__('An error has occurred.', 'wp-adv-quiz'));
                }
                break;
            case 'overview_export':
                try {
                    $this->overviewExport($exportType);
                } catch (Exception $e) {
                    wp_die(__('An error has occurred.', 'wp-adv-quiz'));
                }
                break;
        }
    }

    protected function exportUser($quizId, $refId, $userId, $avg,$exportType)
    {
        $refIdUserId = $avg ? $userId : $refId;

        $statisticRefMapper = new WpAdvQuiz_Model_StatisticRefMapper();
        $statisticUserMapper = new WpAdvQuiz_Model_StatisticUserMapper();
        $formMapper = new WpAdvQuiz_Model_FormMapper();

        $statisticUsers = $statisticUserMapper->fetchUserStatistic($refIdUserId, $quizId, $avg);
        $statisticModel = $statisticRefMapper->fetchByRefId($refIdUserId, $quizId, $avg);
        $forms = $formMapper->fetch($quizId);
		
		//$exportType = filter_input(INPUT_POST,'exportType',FILTER_SANITIZE_STRING);
		//$exportType = $exportType ? : '';

        $expoter = $this->getExpoter($exportType);
		
        if ($expoter === null) {
            wp_die(__('Unsupported exporter'));
        }

        $response = $expoter->exportUser($statisticUsers, $statisticModel, !$avg ? $forms : []);

        $this->handleResponse($response);

        exit;
    }

    protected function exportHistory($exportType)
    {
		
		$page = filter_input(INPUT_GET,'_page',FILTER_VALIDATE_INT);
		$page = $page ? : 0;
		
		$quizId = filter_input(INPUT_GET,'quiz_id',FILTER_VALIDATE_INT);
		$quizId = $quizId ? : 0;
		
		$users = filter_input(INPUT_GET,'users',FILTER_VALIDATE_INT);
		$users = $users ? : 0;
		
		$limit = filter_input(INPUT_GET,'page_limit',FILTER_VALIDATE_INT);
		$limit = $limit ? : 0;
		
		$startTime = filter_input(INPUT_GET,'data_from',FILTER_VALIDATE_INT);
		$startTime = $startTime ? : 0;
		
		$endTime = filter_input(INPUT_GET,'date_to',FILTER_VALIDATE_INT);
		$endTime = $endTime ? : 0;

        $page = $page > 0 ? $page : 1;
        $start = $limit * ($page - 1);
        $endTime = $endTime ? $endTime + 86400 : 0;

        $statisticRefMapper = new WpAdvQuiz_Model_StatisticRefMapper();
        $formMapper = new WpAdvQuiz_Model_FormMapper();

        $forms = $formMapper->fetch($quizId);
        $statisticModel = $statisticRefMapper->fetchHistory($quizId, $start, $limit, $users, $startTime, $endTime);

		//$exportType = filter_input(INPUT_GET,'exportType',FILTER_SANITIZE_STRING);
		//$exportType = $exportType ? : '';
		
        $expoter = $this->getExpoter($exportType);

        if ($expoter === null) {
            wp_die(__('Unsupported exporter'));
        }

        $response = $expoter->exportHistory($statisticModel, $forms);

        $this->handleResponse($response);

        exit;
    }

    protected function overviewExport($exportType)
    {
		$page = filter_input(INPUT_GET,'_page',FILTER_VALIDATE_INT);
		$page = $page ? : 0;
		
		$quizId = filter_input(INPUT_GET,'quiz_id',FILTER_VALIDATE_INT);
		$quizId = $quizId ? : 0;

		$limit = filter_input(INPUT_GET,'page_limit',FILTER_VALIDATE_INT);
		$limit = $limit ? : 0;
		
		$onlyCompleted = filter_input(INPUT_GET,'only_completed',FILTER_VALIDATE_BOOLEAN);
		$onlyCompleted = $onlyCompleted ? : false;

        $page = $page > 0 ? $page : 1;
        $start = $limit * ($page - 1);

        $statisticRefMapper = new WpAdvQuiz_Model_StatisticRefMapper();

        $statisticModel = $statisticRefMapper->fetchStatisticOverview($quizId, $onlyCompleted, $start, $limit);

		//$exportType = filter_input(INPUT_GET,'exportType',FILTER_SANITIZE_STRING);
		//$exportType = $exportType ? : '';
		
        $expoter = $this->getExpoter($exportType);

        if ($expoter === null) {
            wp_die(__('Unsupported exporter'));
        }

        $response = $expoter->exportOverview($statisticModel);

        $this->handleResponse($response);

        exit;
    }

    protected function getExpoter($type)
    {
        $helper = new WpAdvQuiz_Helper_StatisticExport();

        return $helper->factory($type);
    }

    /**
     * @param string|null|WP_Error $response
     */
    protected function handleResponse($response)
    {
        if ($response instanceof WP_Error) {
            wp_die($response);
        } else if ($response !== null) {
            echo filter_var($response,FILTER_UNSAFE_RAW);
        }
    }
}
