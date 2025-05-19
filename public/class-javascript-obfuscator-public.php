<?php
/**
 * This file contains the definition of the Javascript_Obfuscator_Public class, which
 * is used to load the plugin's public-facing functionality.
 *
 * @package       Javascript_Obfuscator
 * @subpackage    Javascript_Obfuscator/public
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @since    2.0.0
 */
class Javascript_Obfuscator_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     string $plugin_name The name of the plugin.
	 * @param     string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function enqueue_scripts() {
		$compiler_enabled = Javascript_Obfuscator::get_option( 'enable', 'jsobfuscate_basic_settings', 'off' );

		// compiler is not enabled so nothing to do here...
		if ( 'off' === $compiler_enabled ) {
			return;
		}

		// get all comma separated file list.
		$included_scripts = array_map( 'trim', explode( ',', Javascript_Obfuscator::get_option( 'include', 'jsobfuscate_basic_settings', '' ) ) );

		// if no files were provided to obfucate bail early...
		if ( empty( $included_scripts ) ) {
			return;
		}

		// Get the site URL.
		$parsed_site_url = wp_parse_url( get_site_url() );

		global $wp_scripts;

		if ( false !== $wp_scripts->queue ) {
			foreach ( $wp_scripts->queue as $handle ) {
				// get the script version.
				$version = $wp_scripts->registered[ $handle ]->ver;

				// get script dependencies.
				$deps = $wp_scripts->registered[ $handle ]->deps;

				// get script extra args.
				$extra = $wp_scripts->registered[ $handle ]->extra;

				// get script src.
				$script_src = $wp_scripts->registered[ $handle ]->src;

				// if script is built in wp don't touch it.
				$built_in_script = preg_match_all( '/(\/wp-includes\/)|(\/wp-admin\/)/', $script_src, $matches );

				if ( 1 === $built_in_script ) {
					continue;
				}

				$parsed_src_url = wp_parse_url( $script_src );
				$pathinfo       = pathinfo( $parsed_src_url['path'] );
				$allowed_exts   = array( 'js' );

				if ( ! isset( $pathinfo['extension'] ) ) {
					continue;
				}

				// Check if it's a js file or not.
				if ( ! in_array( $pathinfo['extension'], $allowed_exts, true ) ) {
					continue;
				}

				// Check if the file is from an external domain or CDN or is a relative path (does not start with http or https).
				if ( ! isset( $parsed_src_url['host'] ) || ! isset( $parsed_src_url['scheme'] ) || $parsed_src_url['host'] !== $parsed_site_url['host'] ) {
					continue;
				}

				// check if any valid comma separated file exists.
				if ( ! in_array( $pathinfo['basename'], $included_scripts, true ) ) {
					// if not included don't continue.
					continue;
				}

				$relative_path         = $pathinfo['dirname'];
				$filename              = $pathinfo['basename'];
				$file_full_path        = rtrim( ABSPATH, '/' ) . $parsed_src_url['path'];
				$cache_target_dir_path = Javascript_Obfuscator::get_cache_dir() . $relative_path;
				$cache_target_dir_url  = Javascript_Obfuscator::get_cache_dir( false, 'baseurl' ) . $relative_path;

				global $wp_filesystem;

				if ( ! function_exists( 'WP_Filesystem' ) ) {
					require_once ABSPATH . 'wp-admin/includes/file.php';
				}

				WP_Filesystem();

				// Check if the directory already exists.
				if ( ! $wp_filesystem->is_dir( $cache_target_dir_path ) ) {
					Javascript_Obfuscator::create_folders_recursively( $cache_target_dir_path );
				}

				// check if file is already generated... if so load cache file.
				if ( ! file_exists( $cache_target_dir_path . DIRECTORY_SEPARATOR . $filename ) ) {
					// get js file content.
					$js_code = $wp_filesystem->get_contents( $file_full_path );

					// Compile the script.
					$compiled_content = Javascript_Obfuscator::compile( $js_code );

					if ( $compiled_content ) {
						Javascript_Obfuscator::save( $compiled_content, $cache_target_dir_path . DIRECTORY_SEPARATOR . $filename );
					}
				}

				// remove the script from loading.
				wp_dequeue_script( $handle );
				wp_deregister_script( $handle );

				// load onfuscated script from cache folder.
				wp_enqueue_script( $handle, $cache_target_dir_url . '/' . $filename, $deps, $version, $extra );
			}
		}
	}
}
