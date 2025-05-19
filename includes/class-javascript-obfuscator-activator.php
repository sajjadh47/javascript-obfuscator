<?php
/**
 * This file contains the definition of the Javascript_Obfuscator_Activator class, which
 * is used during plugin activation.
 *
 * @package       Javascript_Obfuscator
 * @subpackage    Javascript_Obfuscator/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin activation.
 *
 * @since    2.0.0
 */
class Javascript_Obfuscator_Activator {
	/**
	 * Activation hook.
	 *
	 * This function is called when the plugin is activated. It can be used to
	 * perform tasks such as creating database tables, setting up default options,
	 * or scheduling cron jobs.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 */
	public static function on_activate() {
		// check if upload directory is writable.
		if ( Javascript_Obfuscator::is_upload_dir_writable() ) {
			// create cache dir if not already there.
			Javascript_Obfuscator::create_cache_dir();
		}
	}
}
