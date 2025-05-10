<?php

if (!class_exists('SKWPALC_Captcha')) {

    class SKWPALC_Captcha
    {

        public static $captcha_error = false;
        public static $default_captcha = 'math';
        public static $captcha_settings = array();

        public static function init()
        {
            if (self::is_plugin_active()) {
                //start session if math captcha is enabled
                if (self::get_current_captcha() == 'math') {
                    if (!session_id()) {
                        session_start();
                    }
                }
                //save settings 
                $captcha_settings['math'] = get_option(SKWPALC_PREFIX . '_math_captcha');
                $captcha_settings['image'] = get_option(SKWPALC_PREFIX . '_image_captcha');
                $captcha_settings['google'] = get_option(SKWPALC_PREFIX . '_google_captcha');

                $captcha_settings['google_captcha_v3'] = get_option(SKWPALC_PREFIX . '_google_captcha_v3');

                self::$captcha_settings = $captcha_settings;
            }
        }

        public static function login_enqueue_scripts()
        {

            if (self::is_plugin_active()) {
                //include script if google captcha is enabled
                if (self::get_current_captcha() == 'google') {
                    if (self::is_google_recaptcha_settings_exists()) {
                        wp_enqueue_script(SKWPALC_NAME . '-google-captcha', 'https://www.google.com/recaptcha/api.js?onload=skwpalcGoogleReCaptchaonloadCallback&render=explicit', [], SKWPALC_VERSION, true);
                    }
                } elseif (self::get_current_captcha() == 'google_captcha_v3') {
                    if (self::is_google_recaptcha_v3_settings_exists()) {
                        wp_enqueue_script(SKWPALC_PREFIX . '-google-captcha-v3-custom', plugin_dir_url(SKWPALC_FILE_PATH) . 'public/js/google-captcha-v3-custom.js', array('jquery'), SKWPALC_VERSION, false);

                        wp_add_inline_script(SKWPALC_PREFIX . '-google-captcha-v3-custom', "var google_site_key_v3 = '" . self::$captcha_settings['google_captcha_v3']['google_site_key_v3'] . "';", 'after');


                        // wp_enqueue_script(SKWPALC_NAME . '-google-captcha-v3', 'https://www.google.com/recaptcha/api.js?render=' . self::$captcha_settings['google_captcha_v3']['google_site_key_v3'], [], SKWPALC_VERSION, true);
                        wp_enqueue_script(SKWPALC_NAME . '-google-captcha-v3', 'https://www.google.com/recaptcha/api.js?onload=skwpalcGoogleReCaptchaV3onloadCallback&render=explicit', [], SKWPALC_VERSION, true);
                    }
                }
            }
        }

        public static function login_footer()
        {
            if (self::is_plugin_active()) {
                if (self::get_current_captcha() == 'google') {
                    if (self::is_google_recaptcha_settings_exists()) {
                        SKWPALC_Template::include_template('skwpalc-public-captcha-google-login-footer', 'public', self::$captcha_settings['google']);
                    }
                } elseif (self::get_current_captcha() == 'google_captcha_v3') {
                    if (self::is_google_recaptcha_settings_exists()) {
                        SKWPALC_Template::include_template('skwpalc-public-captcha-google-v3-login-footer', 'public', self::$captcha_settings['google_captcha_v3']);
                    }
                }
            }
        }

        public static function login_enqueue_styles()
        {
            if (self::is_plugin_active()) {
                wp_enqueue_style(SKWPALC_NAME . '-login-page-css', plugin_dir_url(SKWPALC_FILE_PATH) . 'public/css/' . SKWPALC_PREFIX . '-public-login.css', false);
            }
        }

        public static function authenticate($data)
        {


            if (self::is_plugin_active()) {
                if (isset($_POST['wp-submit']) || !empty($_POST)) {
                    if (self::get_current_captcha() == 'math') {
                        if (isset($_SESSION['skwpalc_settings']['captcha_math']['answer'])) {
                            if (empty($_POST['skwpalc_math_captcha']) || $_POST['skwpalc_math_captcha'] != $_SESSION['skwpalc_settings']['captcha_math']['answer']) {
                                remove_action('authenticate', 'wp_authenticate_username_password', 20);
                                remove_action('authenticate', 'wp_authenticate_email_password', 20);
                                $error = new WP_Error('skwpalc_invalid_captcha', __('<strong>ERROR</strong>: Please resolve the captcha', 'sk-wp-admin-login-captcha'));
                                return $error;
                            }
                        }
                    } else {

                     
                       

                        if (self::get_current_captcha() == 'google' || self::get_current_captcha() == 'google_captcha_v3') {

                            if ((self::is_google_recaptcha_settings_exists() && self::get_current_captcha() == 'google') || (self::get_current_captcha() == 'google_captcha_v3' && self::is_google_recaptcha_v3_settings_exists())) {
                                if (empty($_POST['g-recaptcha-response'])) {
                                    remove_action('authenticate', 'wp_authenticate_username_password', 20);
                                    remove_action('authenticate', 'wp_authenticate_email_password', 20);
                                    $error = new WP_Error('skwpalc_invalid_captcha', __('<strong>ERROR</strong>: Please resolve the captcha', 'sk-wp-admin-login-captcha'));
                                    return $error;
                                }



                                // Storing google recaptcha response
                                // in $recaptcha variable

                                $recaptcha = $_POST['g-recaptcha-response'];

                                // Put secret key here, which we get
                                // from google console

                                if (self::get_current_captcha() == 'google') {
                                    //validate captcha
                                    $captcha_v2_config = self::get_google_recaptcha_v2_config();

                                    $secret_key = $captcha_v2_config['google_secret_key'];
                                } else {
                                    $captcha_v3_config = self::get_google_recaptcha_v3_config();
                                    $secret_key = $captcha_v3_config['google_secret_key_v3'];
                                }


                                // Hitting request to the URL, Google will
                                // respond with success or error scenario

                                $url = 'https://www.google.com/recaptcha/api/siteverify?secret='
                                    . $secret_key . '&response=' . $recaptcha;

                                try {

                                    // Making request to verify captcha

                                    $google_captcha_response = file_get_contents($url);

                                    // Response return by google is in
                                    // JSON format, so we have to parse
                                    // that json

                                    $google_captcha_response = json_decode($google_captcha_response);
                                } catch (\Exception $exc) {

                                    $google_captcha_response = [];
                                }


                                // Checking, if response is true or not

                                if (isset($google_captcha_response->success) && $google_captcha_response->success === true) {
                                } else {
                                    remove_action('authenticate', 'wp_authenticate_username_password', 20);
                                    remove_action('authenticate', 'wp_authenticate_email_password', 20);
                                    return new WP_Error('skwpalc_invalid_captcha', __('<strong>ERROR</strong>: Invalid captcha', 'sk-wp-admin-login-captcha'));
                                }
                            }
                        }
                    }
                }
            }
            return $data;
        }

        public static function is_plugin_active()
        {
            return true;
        }

        public static function is_google_recaptcha_settings_exists()
        {
            $captcha_settings = get_option(SKWPALC_PREFIX . '_google_captcha');
            if (isset($captcha_settings['google_site_key']) && !empty($captcha_settings['google_site_key'])) {
                if (isset($captcha_settings['google_secret_key']) && !empty($captcha_settings['google_secret_key'])) {
                    return true;
                }
            }
            return false;
        }
        public static function is_google_recaptcha_v3_settings_exists()
        {
            $captcha_settings = get_option(SKWPALC_PREFIX . '_google_captcha_v3');
            if (isset($captcha_settings['google_site_key_v3']) && !empty($captcha_settings['google_site_key_v3'])) {
                if (isset($captcha_settings['google_secret_key_v3']) && !empty($captcha_settings['google_secret_key_v3'])) {
                    return true;
                }
            }
            return false;
        }

        public static function get_google_recaptcha_v2_config()
        {
            return  get_option(SKWPALC_PREFIX . '_google_captcha');
        }

        public static function get_google_recaptcha_v3_config()
        {
            return  get_option(SKWPALC_PREFIX . '_google_captcha_v3');
        }

        public static function get_current_captcha()
        {
            $current_captcha_selected = get_option(SKWPALC_PREFIX . '_general_config_captcha');

            if (!empty($current_captcha_selected) && isset($current_captcha_selected['captcha_type_to_use']) && !empty($current_captcha_selected['captcha_type_to_use'])) {

                return $current_captcha_selected['captcha_type_to_use'];
            }
            $captcha_settings = self::$captcha_settings;
            $captcha_timestamps = array();
            foreach ($captcha_settings as $captcha_type => $captcha) {
                if (!isset($captcha['captcha_timestamp'])) {
                    continue;
                }
                $captcha_timestamps[$captcha_type] = $captcha['captcha_timestamp'];
            }
            if (empty($captcha_timestamps)) {
                return self::$default_captcha;
            } else {
                return array_search(max($captcha_timestamps), $captcha_timestamps);
            }
        }

        public static function generate_math_captcha()
        {
            $captcha = self::$captcha_settings;
            $captcha = $captcha['math'];
            $math_expression = '';
            $random_numbers_range_min = 1;
            $random_numbers_range_max = 10;
            //get math operators
            if (!isset($captcha['captcha_operators']) || empty($captcha['captcha_operators'])) {
                $math_operator = 'plus';
            } else {
                $math_operators = $captcha['captcha_operators'];
                $math_operator = $math_operators[array_rand($math_operators)];
            }
            switch ($math_operator) {
                case 'plus':
                    $num1 = rand($random_numbers_range_min, $random_numbers_range_max);
                    $num2 = rand($random_numbers_range_min, $random_numbers_range_max);
                    $math_expression = $num1 . '+' . $num2;
                    $_SESSION['skwpalc_settings']['captcha_math']['expression'] = $num1 . '+' . $num2;
                    $_SESSION['skwpalc_settings']['captcha_math']['answer'] = $num1 + $num2;
                    break;
                case 'multiplication':
                    $num1 = rand($random_numbers_range_min, $random_numbers_range_max);
                    $num2 = rand($random_numbers_range_min, $random_numbers_range_max);
                    $math_expression = $num1 . '*' . $num2;
                    $_SESSION['skwpalc_settings']['captcha_math']['expression'] = $num1 . '*' . $num2;
                    $_SESSION['skwpalc_settings']['captcha_math']['answer'] = $num1 * $num2;

                    break;
            }
            if (!isset($captcha['captcha_question']) || empty($captcha['captcha_question'])) {
                $captcha_question = sprintf(__('What is %1$s?', 'sk-wp-admin-login-captcha'), '<b>' . $math_expression . '</b>');
            } else {
                $captcha_question = str_replace('_CAPTCHA_', '<b>' . $math_expression . '</b>', $captcha['captcha_question']);
            }
            SKWPALC_Template::include_template('skwpalc-public-captcha-math', 'public', array('captcha_question' => $captcha_question));
        }

        public static function generate_google_captcha()
        {
            SKWPALC_Template::include_template('skwpalc-public-captcha-google', 'public', self::$captcha_settings['google']);
        }
        public static function generate_google_captcha_v3()
        {
            SKWPALC_Template::include_template('skwpalc-public-captcha-google-v3', 'public', self::$captcha_settings['google_captcha_v3']);
        }

        public static function display_captcha()
        {

            if (self::is_plugin_active()) {
                switch (self::get_current_captcha()) {
                    case 'math':
                        self::generate_math_captcha();
                        break;
                    case 'google':
                        self::generate_google_captcha();
                        break;
                    case 'google_captcha_v3':
                        self::generate_google_captcha_v3();
                        break;
                }
            }
        }
    }
}
