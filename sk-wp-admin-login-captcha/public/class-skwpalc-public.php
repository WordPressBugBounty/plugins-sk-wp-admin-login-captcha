<?php

if (!class_exists('SKWPALC_Public')) {

    class SKWPALC_Public {

        public function __construct() {
            $this->load_dependencies();
        }

        public function load_dependencies() {
            require_once SKWPALC_DIRECTORY_PATH . 'public/includes/class-skwpalc-captcha.php';
        }

        public function login_enqueue_scripts() {
            SKWPALC_Captcha::login_enqueue_scripts();
        }

        public function login_enqueue_styles() {
            SKWPALC_Captcha::login_enqueue_styles();
        }

        public function login_form() {
            SKWPALC_Captcha::display_captcha();
        }

        public function login_footer() {
            SKWPALC_Captcha::login_footer();
        }

        public function init() {
            SKWPALC_Captcha::init();
        }

        public function authenticate($data) {
            return SKWPALC_Captcha::authenticate($data);
        }

    }

}
