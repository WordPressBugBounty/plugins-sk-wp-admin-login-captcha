<?php

/**
 * Plugin Name:       SK WP Admin Login Captcha
 * Plugin URI:        https://wordpress.org/plugins/sk-wp-admin-login-captcha/
 * Description:       Simple and Friendly plugin to secure your wordpress admin panel by adding captcha on the login page.
 * Version:           1.0.3
 * Author:            Sandeep Kumar
 * Author URI:        https://profiles.wordpress.org/sk335577
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sk-wp-admin-login-captcha
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * define plugin setting constants 
 */
define('SKWPALC_NAME', 'SK WP Admin Login Captcha');
define('SKWPALC_COMMERCIAL_NAME', 'SK WP Admin Login Captcha 1.0.0');
define('SKWPALC_SLUG', 'sk-wp-admin-login-captcha'); 
define('SKWPALC_PREFIX', 'skwpalc');
define('SKWPALC_VERSION', '1.0.0');

define('SKWPALC_FILE_PATH', __FILE__);
define('SKWPALC_DIRECTORY_PATH', plugin_dir_path(__FILE__));

require SKWPALC_DIRECTORY_PATH . 'includes/class-skwpalc-core.php';

function run_activate_skwpalc() {
    $skwpalc = new SKWPALC_Core();
    $skwpalc->run();
}

run_activate_skwpalc();
