<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @since      2.0.0
 * @package    Javascript_Obfuscator
 * @author     Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

/**
 * Remove plugin options on uninstall/delete
 */
delete_option( 'jsobfuscate_basic_settings' );
