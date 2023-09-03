<?php

class WpAdvQuiz_Helper_GutenbergBlock
{
    public function __construct()
    {
        $this->addHooks();
    }

    protected function addHooks()
    {
        if (function_exists('register_block_type')) {
            add_action('init', [$this, 'initHook']);
        }
    }

    public function initHook() {
        $this->registerScripts();
        $this->registerBlock();
    }

    protected function registerScripts()
    {
        $data = array(
            'src' => plugins_url('css/wpAdvQuiz_front' . (WPADVQUIZ_DEV ? '' : '.min') . '.css', WPADVQUIZ_FILE),
            'deps' => array(),
            'ver' => WPADVQUIZ_VERSION,
        );

        wp_register_style('wpAdvQuiz_block-style', $data['src'], $data['deps'], $data['ver']);

        wp_register_script(
            'wpAdvQuiz-block-js',
            plugins_url('js/wpAdvQuiz_block.js', WPADVQUIZ_FILE),
            ['jquery', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor'],
            WPADVQUIZ_VERSION, true
        );
    }

    protected function registerBlock()
    {
        $mapper = new WpAdvQuiz_Model_QuizMapper();
        $results = [];

        foreach ($mapper->fetchAll() as $quiz) {
            $results[] = [
                'id' => $quiz->getId(),
                'title' => $quiz->getName(),
            ];
        }

         register_block_type('wp-adv-quiz/quiz', [
            'editor_script' => 'wpAdvQuiz-block-js',
            'editor_style' => 'wpAdvQuiz_block-style',
            'render_callback'   => [$this, 'renderRequest'],
            'attributes'	    => array(
                'idner' => $results,
                'metaFieldValue' => array(
                    'type'  => 'integer',
                ),
                'shortcode' => array(
                    'type'  => 'string',
                ),
                'className' => array(
                    'type'  => 'string',
                ),
            ),
        ]); 
    }

    public function renderRequest($attributes)
    {
        $html = '<p style="text-align:center;">' . __('Please select quiz') . '</p>';

         if(isset($attributes['shortcode']) && $attributes['shortcode'] != '') {
            $html = do_shortcode( $attributes['shortcode'] );
        } 

        return $html;
    }

    public static function init()
    {
        return new self();
    } 
} 
