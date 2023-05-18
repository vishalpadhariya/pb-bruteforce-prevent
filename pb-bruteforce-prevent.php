<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://vishalpadhariya.github.io/
 * @since             1.0.0
 * @package           Pb_Bruteforce_Prevent
 *
 * @wordpress-plugin
 * Plugin Name:       PB BruteForce Prevent
 * Plugin URI:        https://github.com/vishalpadhariya/pb-bruteforce-prevent
 * Description:       To Prevent Bruteforce attack on the website
 * Version:           1.0.0
 * Author:            Vishal Padhariya
 * Author URI:        https://vishalpadhariya.github.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pb-bruteforce-prevent
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('PB_BRUTEFORCE_PREVENT_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pb-bruteforce-prevent-activator.php
 */
function activate_pb_bruteforce_prevent()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-pb-bruteforce-prevent-activator.php';
    Pb_Bruteforce_Prevent_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pb-bruteforce-prevent-deactivator.php
 */
function deactivate_pb_bruteforce_prevent()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-pb-bruteforce-prevent-deactivator.php';
    Pb_Bruteforce_Prevent_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_pb_bruteforce_prevent');
register_deactivation_hook(__FILE__, 'deactivate_pb_bruteforce_prevent');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-pb-bruteforce-prevent.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pb_bruteforce_prevent()
{

    $plugin = new Pb_Bruteforce_Prevent();
    $plugin->run();
}
run_pb_bruteforce_prevent();


function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }

    return $ip;
}

function check_attempted_login($user, $username, $password)
{
    if (get_transient('attempted_login')) {
        $datas = get_transient('attempted_login');

        if ($datas['tried'] >= 5) {
            // $until = get_option( '_transient_timeout_' . 'attempted_login' );
            $until = 5256000;

            $time = time_to_go($until);
            return new WP_Error('too_many_tried',  sprintf(__('<strong>ERROR</strong>: You have reached authentication limit, you are banned for %1$s.'), $time));
        }
    }

    return $user;
}
add_filter('authenticate', 'check_attempted_login', 30, 3);
function login_failed($username)
{
    if (get_transient('attempted_login')) {
        $datas = get_transient('attempted_login');
        $datas['tried']++;

        if ($datas['tried'] <= 5)
            set_transient('attempted_login', $datas, 300);
    } else {
        $datas = array(
            'tried'     => 1
        );
        set_transient('attempted_login', $datas, 300);
    }
}
add_action('wp_login_failed', 'login_failed', 10, 1);

function time_to_go($timestamp)
{

    // converting the mysql timestamp to php time
    $periods = array(
        "second",
        "minute",
        "hour",
        "day",
        "week",
        "month",
        "year"
    );
    $lengths = array(
        "60",
        "60",
        "24",
        "7",
        "4.35",
        "12"
    );
    $current_timestamp = time();
    $difference = abs($current_timestamp - $timestamp);
    for ($i = 0; $difference >= $lengths[$i] && $i < count($lengths) - 1; $i++) {
        $difference /= $lengths[$i];
    }
    $difference = round($difference);
    if (isset($difference)) {
        if ($difference != 1)
            $periods[$i] .= "s";
        $output = "$difference $periods[$i]";
        return $output;
    }
}
