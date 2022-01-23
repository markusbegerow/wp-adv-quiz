<?php

class WpAdvQuiz_Controller_Toplist extends WpAdvQuiz_Controller_Controller
{
    public function route()
    {

		$action = filter_input(INPUT_GET,'action',FILTER_SANITIZE_STRING);
		$action = $action ? : 'show';
		
		$quizId = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
		$quizId = $quizId ? : 0;

        switch ($action) {
            default:
                $this->showAdminToplist($quizId);
                break;
        }
    }

    private function showAdminToplist($quizId)
    {
        if (!current_user_can('wpAdvQuiz_toplist_edit')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $view = new WpAdvQuiz_View_AdminToplist();
        $quizMapper = new WpAdvQuiz_Model_QuizMapper();

        $quiz = $quizMapper->fetch($quizId);

        $view->quiz = $quiz;
        $view->show();
    }

    public function getAddToplist(WpAdvQuiz_Model_Quiz $quiz)
    {
        $userId = get_current_user_id();

        if (!$quiz->isToplistActivated()) {
            return null;
        }

        $data = array(
            'userId' => $userId,
            'token' => wp_create_nonce('wpAdvQuiz_toplist'),
            'canAdd' => $this->preCheck($quiz->getToplistDataAddPermissions(), $userId),
        );

        if ($quiz->isToplistDataCaptcha() && $userId == 0) {
            $captcha = WpAdvQuiz_Helper_Captcha::getInstance();

            if ($captcha->isSupported()) {
                $data['captcha']['img'] = WPADVQUIZ_CAPTCHA_URL . '/' . $captcha->createImage();
                $data['captcha']['code'] = $captcha->getPrefix();
            }
        }

        return $data;
    }

    private function handleAddInToplist(WpAdvQuiz_Model_Quiz $quiz)
    {
        if (!wp_verify_nonce($this->_post['token'], 'wpAdvQuiz_toplist')) {
            return array('text' => __('An error has occurred.', 'wp-adv-quiz'), 'clear' => true);
        }

        if (!isset($this->_post['points']) || !isset($this->_post['totalPoints'])) {
            return array('text' => __('An error has occurred.', 'wp-adv-quiz'), 'clear' => true);
        }

        $quizId = $quiz->getId();
        $userId = get_current_user_id();
        $points = (int)$this->_post['points'];
        $totalPoints = (int)$this->_post['totalPoints'];
        $name = !empty($this->_post['name']) ? trim($this->_post['name']) : '';
        $email = !empty($this->_post['email']) ? trim($this->_post['email']) : '';
        $ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
        $captchaAnswer = !empty($this->_post['captcha']) ? trim($this->_post['captcha']) : '';
        $prefix = !empty($this->_post['prefix']) ? trim($this->_post['prefix']) : '';

        $quizMapper = new WpAdvQuiz_Model_QuizMapper();
        $toplistMapper = new WpAdvQuiz_Model_ToplistMapper();

        if ($quiz == null || $quiz->getId() == 0 || !$quiz->isToplistActivated()) {
            return array('text' => __('An error has occurred.', 'wp-adv-quiz'), 'clear' => true);
        }

        if (!$this->preCheck($quiz->getToplistDataAddPermissions(), $userId)) {
            return array('text' => __('An error has occurred.', 'wp-adv-quiz'), 'clear' => true);
        }

        $numPoints = $quizMapper->sumQuestionPoints($quizId);

        if ($totalPoints > $numPoints || $points > $numPoints) {
            return array('text' => __('An error has occurred.', 'wp-adv-quiz'), 'clear' => true);
        }

        $clearTime = null;

        if ($quiz->isToplistDataAddMultiple()) {
            $clearTime = $quiz->getToplistDataAddBlock() * 60;
        }

        if ($userId > 0) {
            if ($toplistMapper->countUser($quizId, $userId, $clearTime)) {
                return array('text' => __('You can not enter again.', 'wp-adv-quiz'), 'clear' => true);
            }

            $user = wp_get_current_user();
            $email = $user->user_email;
            $name = $user->display_name;

        } else {
            if ($toplistMapper->countFree($quizId, $name, $email, $ip, $clearTime)) {
                return array('text' => __('You can not enter again.', 'wp-adv-quiz'), 'clear' => true);
            }

            if (empty($name) || empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                return array('text' => __('No name or e-mail entered.', 'wp-adv-quiz'), 'clear' => false);
            }

            if (strlen($name) > 15) {
                return array('text' => __('Your name can not exceed 15 characters.', 'wp-adv-quiz'), 'clear' => false);
            }

            if ($quiz->isToplistDataCaptcha()) {
                $captcha = WpAdvQuiz_Helper_Captcha::getInstance();

                if ($captcha->isSupported()) {
                    if (!$captcha->check($prefix, $captchaAnswer)) {
                        return array('text' => __('You entered wrong captcha code.', 'wp-adv-quiz'), 'clear' => false);
                    }
                }
            }
        }

        $toplist = new WpAdvQuiz_Model_Toplist();
        $toplist->setQuizId($quizId)
            ->setUserId($userId)
            ->setDate(time())
            ->setName($name)
            ->setEmail($email)
            ->setPoints($points)
            ->setResult(round($points / $totalPoints * 100, 2))
            ->setIp($ip);

        $toplistMapper->save($toplist);

        return true;
    }

    private function preCheck($type, $userId)
    {
        switch ($type) {
            case WpAdvQuiz_Model_Quiz::QUIZ_TOPLIST_TYPE_ALL:
                return true;
            case WpAdvQuiz_Model_Quiz::QUIZ_TOPLIST_TYPE_ONLY_ANONYM:
                return $userId == 0;
            case WpAdvQuiz_Model_Quiz::QUIZ_TOPLIST_TYPE_ONLY_USER:
                return $userId > 0;
        }

        return false;
    }

    public static function ajaxAdminToplist($data)
    {
        if (!current_user_can('wpAdvQuiz_toplist_edit')) {
            return json_encode(array());
        }

        $toplistMapper = new WpAdvQuiz_Model_ToplistMapper();

        $j = array('data' => array());
        $limit = (int)$data['limit'];
        $start = $limit * ($data['page'] - 1);
        $isNav = isset($data['nav']);
        $quizId = $data['quizId'];

        if (isset($data['a'])) {
            switch ($data['a']) {
                case 'deleteAll':
                    $toplistMapper->delete($quizId);
                    break;
                case 'delete':
                    if (!empty($data['toplistIds'])) {
                        $toplistMapper->delete($quizId, $data['toplistIds']);
                    }
                    break;
            }

            $start = 0;
            $isNav = true;
        }

        $toplist = $toplistMapper->fetch($quizId, $limit, $data['sort'], $start);

        foreach ($toplist as $tp) {
            $j['data'][] = array(
                'id' => $tp->getToplistId(),
                'name' => $tp->getName(),
                'email' => $tp->getEmail(),
                'type' => $tp->getUserId() ? 'R' : 'UR',
                'date' => WpAdvQuiz_Helper_Until::convertTime($tp->getDate(),
                    get_option('wpAdvQuiz_toplistDataFormat', 'Y/m/d g:i A')),
                'points' => $tp->getPoints(),
                'result' => $tp->getResult()
            );
        }

        if ($isNav) {

            $count = $toplistMapper->count($quizId);
            $pages = ceil($count / $limit);
            $j['nav'] = array(
                'count' => $count,
                'pages' => $pages ? $pages : 1
            );
        }

        return json_encode($j);
    }

    public static function ajaxAddInToplist($data)
    {
        // workaround ...
		
		$data = filter_input(INPUT_POST,'data',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
		$data = !empty($data) ? $data : null;
		
        $_POST = $data;

        $ctn = new WpAdvQuiz_Controller_Toplist();

        $quizId = isset($data['quizId']) ? $data['quizId'] : 0;
        $prefix = !empty($data['prefix']) ? trim($data['prefix']) : '';
        $quizMapper = new WpAdvQuiz_Model_QuizMapper();

        $quiz = $quizMapper->fetch($quizId);

        $r = $ctn->handleAddInToplist($quiz);

        if ($quiz->isToplistActivated() && $quiz->isToplistDataCaptcha() && get_current_user_id() == 0) {
            $captcha = WpAdvQuiz_Helper_Captcha::getInstance();

            if ($captcha->isSupported()) {
                $captcha->remove($prefix);
                $captcha->cleanup();

                if ($r !== true) {
                    $r['captcha']['img'] = WPADVQUIZ_CAPTCHA_URL . '/' . $captcha->createImage();
                    $r['captcha']['code'] = $captcha->getPrefix();
                }
            }
        }

        if ($r === true) {
            $r = array('text' => __('You signed up successfully.', 'wp-adv-quiz'), 'clear' => true);
        }

        return json_encode($r);
    }

    public static function ajaxShowFrontToplist($data)
    {
        // workaround ...
		$data = filter_input(INPUT_POST,'data',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
		$data = !empty($data) ? $data : null;
		
        $_POST = $data;
	

        $quizIds = empty($data['quizIds']) ? array() : array_unique((array)$data['quizIds']);
        $toplistMapper = new WpAdvQuiz_Model_ToplistMapper();
        $quizMapper = new WpAdvQuiz_Model_QuizMapper();
        $j = array();

        foreach ($quizIds as $quizId) {
            $quiz = $quizMapper->fetch($quizId);
            if ($quiz == null || $quiz->getId() == 0) {
                continue;
            }

            $toplist = $toplistMapper->fetch($quizId, $quiz->getToplistDataShowLimit(), $quiz->getToplistDataSort());

            foreach ($toplist as $tp) {
                $j[$quizId][] = array(
                    'name' => $tp->getName(),
                    'date' => WpAdvQuiz_Helper_Until::convertTime($tp->getDate(),
                        get_option('wpAdvQuiz_toplistDataFormat', 'Y/m/d g:i A')),
                    'points' => $tp->getPoints(),
                    'result' => $tp->getResult()
                );
            }
        }

        return json_encode($j);
    }
}