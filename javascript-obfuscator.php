<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           Javascript_Obfuscator
 * @author            Sajjad Hossain Sagor <sagorh672@gmail.com>
 *
 * Plugin Name:       JavaScript Obfuscator
 * Plugin URI:        https://wordpress.org/plugins/javascript-obfuscator/
 * Description:       Obfuscate your JavaScript Source Code to enable anti-theft protection by converting your js source code into completely unreadable form preventing it from analyzing and reusing.
 * Version:           2.0.4
 * Requires at least: 5.6
 * Requires PHP:      8.0
 * Author:            Sajjad Hossain Sagor
 * Author URI:        https://sajjadhsagor.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       javascript-obfuscator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'JAVASCRIPT_OBFUSCATOR_PLUGIN_VERSION', '2.0.4' );

/**
 * Define Plugin Folders Path
 */
define( 'JAVASCRIPT_OBFUSCATOR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

define( 'JAVASCRIPT_OBFUSCATOR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'JAVASCRIPT_OBFUSCATOR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-javascript-obfuscator-activator.php
 *
 * @since    2.0.0
 */
function on_activate_javascript_obfuscator() {
	require_once JAVASCRIPT_OBFUSCATOR_PLUGIN_PATH . 'includes/class-javascript-obfuscator-activator.php';

	Javascript_Obfuscator_Activator::on_activate();
}

register_activation_hook( __FILE__, 'on_activate_javascript_obfuscator' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-javascript-obfuscator-deactivator.php
 *
 * @since    2.0.0
 */
function on_deactivate_javascript_obfuscator() {
	require_once JAVASCRIPT_OBFUSCATOR_PLUGIN_PATH . 'includes/class-javascript-obfuscator-deactivator.php';

	Javascript_Obfuscator_Deactivator::on_deactivate();
}

register_deactivation_hook( __FILE__, 'on_deactivate_javascript_obfuscator' );

/**
 * The core plugin class that is used to define admin-specific and public-facing hooks.
 *
 * @since    2.0.0
 */
require JAVASCRIPT_OBFUSCATOR_PLUGIN_PATH . 'includes/class-javascript-obfuscator.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_javascript_obfuscator() {
	$plugin = new Javascript_Obfuscator();

	$plugin->run();
}

run_javascript_obfuscator();
