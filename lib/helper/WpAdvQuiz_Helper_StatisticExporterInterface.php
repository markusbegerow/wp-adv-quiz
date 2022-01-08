<?php

interface WpAdvQuiz_Helper_StatisticExporterInterface
{

    /**
     * @param WpAdvQuiz_Model_StatisticUser[] $users
     * @param WpAdvQuiz_Model_StatisticRefModel $ref
     * @param WpAdvQuiz_Model_Form[] $forms
     *
     * @return string|WP_Error
     */
    public function exportUser($users, $ref, $forms);

    /**
     * @param WpAdvQuiz_Model_StatisticOverview[] $overviews
     *
     * @return string|WP_Error
     */
    public function exportOverview($overviews);

    /**
     * @param WpAdvQuiz_Model_StatisticHistory[] $histories
     * @param WpAdvQuiz_Model_Form[] $forms
     * @return string|WP_Error
     */
    public function exportHistory($histories, $forms);
}
