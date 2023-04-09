<?php

class WpAdvQuiz_Model_GlobalSettingsMapper extends WpAdvQuiz_Model_Mapper
{

    public function fetchAll()
    {
        $s = new WpAdvQuiz_Model_GlobalSettings();

        $s->setAddRawShortcode(get_option('wpAdvQuiz_addRawShortcode'))
            ->setJsLoadInHead(get_option('wpAdvQuiz_jsLoadInHead'))
            ->setTouchLibraryDeactivate(get_option('wpAdvQuiz_touchLibraryDeactivate'))
            ->setCorsActivated(get_option('wpAdvQuiz_corsActivated'));

        return $s;
    }

    public function save(WpAdvQuiz_Model_GlobalSettings $settings)
    {

        if (add_option('wpAdvQuiz_addRawShortcode', $settings->isAddRawShortcode()) === false) {
            update_option('wpAdvQuiz_addRawShortcode', $settings->isAddRawShortcode());
        }

        if (add_option('wpAdvQuiz_jsLoadInHead', $settings->isJsLoadInHead()) === false) {
            update_option('wpAdvQuiz_jsLoadInHead', $settings->isJsLoadInHead());
        }

        if (add_option('wpAdvQuiz_touchLibraryDeactivate', $settings->isTouchLibraryDeactivate()) === false) {
            update_option('wpAdvQuiz_touchLibraryDeactivate', $settings->isTouchLibraryDeactivate());
        }

        if (add_option('wpAdvQuiz_corsActivated', $settings->isCorsActivated()) === false) {
            update_option('wpAdvQuiz_corsActivated', $settings->isCorsActivated());
        }
    }

    public function delete()
    {
        delete_option('wpAdvQuiz_addRawShortcode');
        delete_option('wpAdvQuiz_jsLoadInHead');
        delete_option('wpAdvQuiz_touchLibraryDeactivate');
        delete_option('wpAdvQuiz_corsActivated');
    }

    /**
     * @return array
     */
    public function getEmailSettings()
    {
        $e = get_option('wpAdvQuiz_emailSettings', null);

        if ($e === null) {
            $e['to'] = '';
            $e['from'] = '';
            $e['subject'] = __('Wp-Adv-Quiz: One user completed a quiz', 'wp-adv-quiz');#
            $e['html'] = false;
            $e['message'] = __('Wp-Adv-Quiz

The user "$username" has completed "$quizname" the quiz.

Points: $points
Result: $result

', 'wp-adv-quiz');

        }

        return $e;
    }

    public function saveEmailSettiongs($data)
    {
        if (isset($data['html']) && $data['html']) {
            $data['html'] = true;
        } else {
            $data['html'] = false;
        }

        if (add_option('wpAdvQuiz_emailSettings', $data, '', 'no') === false) {
            update_option('wpAdvQuiz_emailSettings', $data);
        }
    }

    /**
     * @return array
     */
    public function getUserEmailSettings()
    {
        $e = get_option('wpAdvQuiz_userEmailSettings', null);

        if ($e === null) {
            $e['from'] = '';
            $e['subject'] = __('Wp-Adv-Quiz: One user completed a quiz', 'wp-adv-quiz');
            $e['html'] = false;
            $e['message'] = __('Wp-Adv-Quiz

You have completed the quiz "$quizname".

Points: $points
Result: $result

', 'wp-adv-quiz');

        }

        return $e;

    }

    public function saveUserEmailSettiongs($data)
    {
        if (isset($data['html']) && $data['html']) {
            $data['html'] = true;
        } else {
            $data['html'] = false;
        }

        if (add_option('wpAdvQuiz_userEmailSettings', $data, '', 'no') === false) {
            update_option('wpAdvQuiz_userEmailSettings', $data);
        }
    }
	
	public function CheckForButtonDefaults($case,$data)
    {
		$width = '100';
		$height = '30';
		$background = '#13455B';
		if (str_contains($case,'width')) {
			$e = $width;
			if(is_numeric($data)) {
				$e = $data;
			}
		}			
        if (str_contains($case,'height')) {
			$e = $height;
			if(is_numeric($data)) {
				$e = $data;
			}
		}		
        if (str_contains($case,'color')) {
			$e = $background;
			if(preg_match('/^#[a-f0-9]{6}$/i', $data) ) {
				$e = $data;
			}
		}			
		return $e;
	}
	
	public function getButtonProperty($data)
    {
        $e = get_option($data, null);
		return $e;
	}
	
}