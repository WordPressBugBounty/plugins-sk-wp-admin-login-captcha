<?php

if (!class_exists('SKWPALC_Admin')) {

    class SKWPALC_Admin {

        private $settings_api;

        public function __construct() {
            $this->load_dependencies();
        }

        public function load_dependencies() {
            require_once SKWPALC_DIRECTORY_PATH . 'admin/includes/class-skwpalc-settings-api.php';
        }

        public function enqueue_styles() {
            wp_enqueue_style(SKWPALC_PREFIX . '-admin', plugin_dir_url(SKWPALC_FILE_PATH) . 'admin/css/' . SKWPALC_PREFIX . '-admin.css', array(), SKWPALC_VERSION, 'all');
            wp_enqueue_style(SKWPALC_PREFIX . '-admin-responsiveslides', plugin_dir_url(SKWPALC_FILE_PATH) . 'admin/css/' . SKWPALC_PREFIX . '-responsiveslides.css', array(), SKWPALC_VERSION, 'all');
        }

        public function enqueue_scripts() {
            wp_enqueue_script(SKWPALC_PREFIX . '-responsiveslides', plugin_dir_url(SKWPALC_FILE_PATH) . 'admin/js/' . SKWPALC_PREFIX . '-responsiveslides.min.js', array('jquery'), SKWPALC_VERSION, false);
            wp_enqueue_script(SKWPALC_PREFIX . '-admin', plugin_dir_url(SKWPALC_FILE_PATH) . 'admin/js/' . SKWPALC_PREFIX . '-admin.js', array('jquery'), SKWPALC_VERSION, true);
        }

        public function admin_init() {
            $this->settings_api = new SKWPALC_Settings_API();

            //set the settings
            $this->settings_api->set_sections(array(
                array(
                    'id' => SKWPALC_PREFIX . '_math_captcha',
                    'title' => __('Math Captcha', 'sk-wp-admin-login-captcha'),
                    'help_content' => '<ul class="skwpalc-slider">'
                    . '<li><img src="' . plugin_dir_url(SKWPALC_FILE_PATH) . 'admin/images/captcha-math-item-1.png' . '"/><li>'
                    . '<li><img src="' . plugin_dir_url(SKWPALC_FILE_PATH) . 'admin/images/captcha-math-item-2.png' . '"/><li>'
                    . '</ul>'
                ),
//            array(
//                'id' => SKWPALC_PREFIX . '_image_captcha',
//                'title' => __('Image Captcha', 'sk-wp-admin-login-captcha')
//            ),
                array(
                    'id' => SKWPALC_PREFIX . '_google_captcha',
                    'title' => __('Google Captcha', 'sk-wp-admin-login-captcha'),
                    'help_content' => '<ul class="skwpalc-slider">'
                    . '<li><img src="' . plugin_dir_url(SKWPALC_FILE_PATH) . 'admin/images/captcha-google-item-1.png' . '"/><li>'
                    . '<li><img src="' . plugin_dir_url(SKWPALC_FILE_PATH) . 'admin/images/captcha-google-item-2.png' . '"/><li>'
                    . '</ul>'
                ),
            ));
            $this->settings_api->set_fields(array(
                SKWPALC_PREFIX . '_math_captcha' => array(
                    array(
                        'name' => 'captcha_question',
                        'label' => __('Captcha Question', 'sk-wp-admin-login-captcha'),
                        'desc' => __('Use _CAPTCHA_ in your question.Ex. What is _CAPTCHA_?', 'sk-wp-admin-login-captcha'),
//                    'placeholder' => __('What is _CAPTCHA_?', 'sk-wp-admin-login-captcha'),
                        'type' => 'text',
                        'default' => '',
                        'sanitize_callback' => 'sanitize_text_field'
                    ),
                    array(
                        'name' => 'captcha_operators',
                        'label' => __('Select Operators', 'sk-wp-skeleton-plugin'),
                        'desc' => __('Select the math operators. ', 'sk-wp-skeleton-plugin'),
                        'type' => 'multicheck',
                        'options' => array(
                            'plus' => '+',
//                        'minus' => '-',
                            'multiplication' => '*',
                        )
                    ),
                    array(
                        'name' => 'captcha_timestamp',
                        'label' => __('Captcha Timestamp', 'sk-wp-admin-login-captcha'),
                        'type' => 'text',
                        'default' => '',
                        'class' => SKWPALC_PREFIX . '-captcha-type-input hidden',
                        'sanitize_callback' => array($this, 'captcha_timestamp_callback')
                    ),
                ),
//            SKWPALC_PREFIX . '_image_captcha' => array(
//                array(
//                    'name' => 'color',
//                    'label' => __('Color', 'sk-wp-admin-login-captcha'),
//                    'desc' => __('Color description', 'sk-wp-admin-login-captcha'),
//                    'type' => 'color',
//                    'default' => ''
//                ),
//                array(
//                    'name' => 'captcha_timestamp',
//                    'label' => __('Captcha Timestamp', 'sk-wp-admin-login-captcha'),
//                    'type' => 'text',
//                    'default' => '',
//                    'class' => SKWPALC_PREFIX . '-captcha-type-input  hidden',
//                    'sanitize_callback' => array($this, 'captcha_timestamp_callback')
//                ),
//            ),
                SKWPALC_PREFIX . '_google_captcha' => array(
                    array(
                        'name' => 'google_site_key',
                        'label' => __('Site key', 'sk-wp-admin-login-captcha'),
                        'desc' => __('Copy and Paste Site key and Secret key from <a href="https://www.google.com/recaptcha/intro/" target="_blank">Google</a>', 'sk-wp-admin-login-captcha'),
                        'placeholder' => __('', 'sk-wp-admin-login-captcha'),
                        'type' => 'text',
                        'default' => '',
                        'sanitize_callback' => 'sanitize_text_field'
                    ),
                    array(
                        'name' => 'google_secret_key',
                        'label' => __('Secret key', 'sk-wp-admin-login-captcha'),
                        'desc' => __('Copy and Paste Site key and Secret key from <a href="https://www.google.com/recaptcha/intro/" target="_blank">Google</a>', 'sk-wp-admin-login-captcha'),
                        'placeholder' => __('', 'sk-wp-admin-login-captcha'),
                        'type' => 'text',
                        'default' => '',
                        'sanitize_callback' => 'sanitize_text_field'
                    ),
                    array(
                        'name' => 'captcha_label',
                        'label' => __('Captcha Label', 'sk-wp-admin-login-captcha'),
//                    'desc' => __('Use _CAPTCHA_ in your question.Ex. What is _CAPTCHA_?', 'sk-wp-admin-login-captcha'),
//                    'placeholder' => __('What is _CAPTCHA_?', 'sk-wp-admin-login-captcha'),
                        'type' => 'text',
                        'default' => '',
                        'sanitize_callback' => 'sanitize_text_field'
                    ),
                    array(
                        'name' => 'captcha_timestamp',
                        'label' => __('Captcha Status', 'sk-wp-admin-login-captcha'),
                        'type' => 'text',
                        'default' => '',
                        'class' => SKWPALC_PREFIX . '-captcha-type-input hidden',
                        'sanitize_callback' => array($this, 'captcha_timestamp_callback')
                    ),
                ),
            ));

            //initialize settings
            $this->settings_api->admin_init();
        }

        public function captcha_timestamp_callback($val) {
            return time();
        }

        public function admin_menu() {
            add_options_page(SKWPALC_NAME, SKWPALC_NAME, 'manage_options', SKWPALC_PREFIX . '_page', function () {
                SKWPALC_Template::include_template('skwpalc-admin-page', 'admin', array('settings_api_obj' => $this->settings_api));
            });
        }

    }

}
