<?php

if (!class_exists('SKWPALC_Core')) {

    class SKWPALC_Core {

        protected $loader;

        public function __construct() {
            $this->load_dependencies();
            $this->set_locale();
            $this->define_admin_hooks();
            $this->define_public_hooks();
        }

        private function load_dependencies() {
            require_once SKWPALC_DIRECTORY_PATH . 'includes/class-skwpalc-loader.php';
            require_once SKWPALC_DIRECTORY_PATH . 'includes/class-skwpalc-i18n.php';
            require_once SKWPALC_DIRECTORY_PATH . 'includes/class-skwpalc-template.php';
            require_once SKWPALC_DIRECTORY_PATH . 'admin/class-skwpalc-admin.php';
            require_once SKWPALC_DIRECTORY_PATH . 'public/class-skwpalc-public.php';
            $this->loader = new SKWPALC_Loader();
        }

        private function set_locale() {
            $plugin_i18n = new SKWPALC_i18n();
            $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
        }

        private function define_admin_hooks() {
            $plugin_admin = new SKWPALC_Admin();
            $this->loader->add_action('admin_init', $plugin_admin, 'admin_init');
            $this->loader->add_action('admin_menu', $plugin_admin, 'admin_menu');
            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        }

        private function define_public_hooks() {
            $plugin_public = new SKWPALC_Public();
            $this->loader->add_action('init', $plugin_public, 'init', 1);
            $this->loader->add_action('login_form', $plugin_public, 'login_form');
            $this->loader->add_action('authenticate', $plugin_public, 'authenticate', 1);
            $this->loader->add_action('login_enqueue_scripts', $plugin_public, 'login_enqueue_scripts', 1);
            $this->loader->add_action('login_enqueue_scripts', $plugin_public, 'login_enqueue_styles', 10);
            $this->loader->add_action('login_footer', $plugin_public, 'login_footer');
        }

        public function run() {
            $this->loader->run();
        }

        public function get_loader() {
            return $this->loader;
        }

    }

}
