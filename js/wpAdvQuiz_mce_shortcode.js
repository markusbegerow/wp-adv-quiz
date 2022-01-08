(function () {
    if (tinymce) {

        tinymce.create('tinymce.plugins.wp_adv_quiz_button_mce', {
            init: function (ed, url) {
                ed.addButton('wp_adv_quiz_button_mce', {
                    title: 'Add Quiz',
                    image: url + '/../css/images/icon-128x128.png',
                    cmd: 'wp_adv_quiz_button_mce_cmd'
                });

                ed.addCommand('wp_adv_quiz_button_mce_cmd', function () {
                    ed.windowManager.open(
                        {
                            title: 'Wp-Adv-Quiz',
                            file: ajaxurl + '?action=wpAdvQuiz_generate_mce_shortcode',
                        },
                        {
                            plugin_url: url
                        });

                });
            },
            createControl: function (n, cm) {
                return null;
            },
        });

        tinymce.PluginManager.add('wp_adv_quiz_button_mce', tinymce.plugins.wp_adv_quiz_button_mce);
    }
})();

