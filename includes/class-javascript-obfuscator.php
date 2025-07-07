<?php
/**
 * This file contains the definition of the Javascript_Obfuscator class, which
 * is used to begin the plugin's functionality.
 *
 * @package       Javascript_Obfuscator
 * @subpackage    Javascript_Obfuscator/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The core plugin class.
 *
 * This is used to define admin-specific hooks and public-facing hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since    2.0.0
 */
class Javascript_Obfuscator {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       Javascript_Obfuscator_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function __construct() {
		$this->version     = defined( 'JAVASCRIPT_OBFUSCATOR_PLUGIN_VERSION' ) ? JAVASCRIPT_OBFUSCATOR_PLUGIN_VERSION : '1.0.0';
		$this->plugin_name = 'javascript-obfuscator';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Javascript_Obfuscator_Loader. Orchestrates the hooks of the plugin.
	 * - Sajjad_Dev_Settings_API.      Provides an interface for interacting with the WordPress Options API.
	 * - Javascript_Obfuscator_Admin.  Defines all hooks for the admin area.
	 * - Javascript_Obfuscator_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once JAVASCRIPT_OBFUSCATOR_PLUGIN_PATH . 'includes/class-javascript-obfuscator-loader.php';

		/**
		 * The class responsible for defining an interface for interacting with the WordPress Options API.
		 */
		require_once JAVASCRIPT_OBFUSCATOR_PLUGIN_PATH . 'includes/class-sajjad-dev-settings-api.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once JAVASCRIPT_OBFUSCATOR_PLUGIN_PATH . 'admin/class-javascript-obfuscator-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once JAVASCRIPT_OBFUSCATOR_PLUGIN_PATH . 'public/class-javascript-obfuscator-public.php';

		$this->loader = new Javascript_Obfuscator_Loader();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Javascript_Obfuscator_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'plugin_action_links_' . JAVASCRIPT_OBFUSCATOR_PLUGIN_BASENAME, $plugin_admin, 'add_plugin_action_links' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'admin_notices' );
		$this->loader->add_action( 'admin_bar_menu', $plugin_admin, 'admin_bar_menu', 99 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function define_public_hooks() {
		$plugin_public = new Javascript_Obfuscator_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts', 9999 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of WordPress.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    string The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    Javascript_Obfuscator_Loader Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieves the value of a specific settings field.
	 *
	 * This method fetches the value of a settings field from the WordPress options database.
	 * It retrieves the entire option group for the given section and then extracts the
	 * value for the specified field.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @param     string $option        The name of the settings field.
	 * @param     string $section       The name of the section this field belongs to. This corresponds
	 *                                  to the option name used in `register_setting()`.
	 * @param     string $default_value Optional. The default value to return if the field's value
	 *                                  is not found in the database. Default is an empty string.
	 * @return    string|mixed          The value of the settings field, or the default value if not found.
	 */
	public static function get_option( $option, $section, $default_value = '' ) {
		$options = get_option( $section ); // Get all options for the section.

		// Check if the option exists within the section's options array.
		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ]; // Return the option value.
		}

		return $default_value; // Return the default value if the option is not found.
	}

	/**
	 * Obfuscate the provided source code.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @param     string $source_code The original JS source code.
	 * @return    string              Obfuscated source code.
	 */
	public static function compile( $source_code ) {
		// add the obfuscating library [https://github.com/tholu/php-packer/].
		require JAVASCRIPT_OBFUSCATOR_PLUGIN_PATH . 'vendor/autoload.php';

		if ( empty( $source_code ) ) {
			return '';
		}

		$mode = self::get_option( 'mode', 'jsobfuscate_basic_settings', 'Normal' );

		/*
		 * params of the constructor :
		 * $script:           the JavaScript to pack, string.
		 * $encoding:         level of encoding, int or string :
		 *                    0,10,62,95 or 'None', 'Numeric', 'Normal', 'High ASCII'.
		 *                    default: 62 ('Normal').
		 * $fastDecode:       include the fast decoder in the packed result, boolean.
		 *                    default: true.
		 * $specialChars:     if you have flagged your private and local variables
		 *                    in the script, boolean.
		 *                    default: false.
		 * $removeSemicolons: whether to remove semicolons from the source script.
		 *                    default: true.
		 */
		$obfuscate = new Tholu\Packer\Packer( $source_code, $mode, true, false, true );

		return $obfuscate->pack();
	}

	/**
	 * Saves Obsfucated JS code to a file in the cache directory.
	 *
	 * This function writes the provided JS code to a file with the specified
	 * filename in the cache directory. It first checks if the upload directory
	 * is writable before attempting to save the file.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @param     string $code     The Obsfucated JS code to be saved.
	 * @param     string $filename The name of the file to save the JS code to.
	 * @return    bool|void        Returns true if the save was successful, or void if the upload directory is not writable.
	 */
	public static function save( $code, $filename ) {
		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem();

		if ( ! self::is_upload_dir_writable() ) {
			return;
		}

		$wp_filesystem->put_contents( $filename, $code, FS_CHMOD_FILE );
	}

	/**
	 * Purges all cache files from the cache directory.
	 *
	 * This function deletes all files within the cache directory, effectively
	 * purging the cache. It first checks if the upload directory is writable
	 * before attempting to delete any files.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @return    bool|void Returns true if the purge was successful, or void if the upload directory is not writable.
	 */
	public static function purge() {
		if ( ! self::is_upload_dir_writable() ) {
			return;
		}

		// get cache folder.
		$cache_folder = self::get_cache_dir();

		self::delete_folders_recursively( $cache_folder );
	}

	/**
	 * Deletes a folder and all its contents recursively.
	 *
	 * This function deletes a specified folder and all files and subfolders within it.
	 * It recursively traverses subfolders to ensure all content is removed.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @param     string $folder The path to the folder to be deleted.
	 * @return    bool           True if the folder and its contents were successfully deleted, false otherwise.
	 */
	public static function delete_folders_recursively( $folder ) {
		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem();

		// Ensure the folder exists and is a directory.
		if ( ! $wp_filesystem->is_dir( $folder ) ) {
			return false;
		}

		// Get the list of files and folders, excluding '.' and '..'.
		$files = array_diff( $wp_filesystem->dirlist( $folder ), array( '.', '..' ) );

		foreach ( $files as $file => $file_info ) {
			$path = trailingslashit( $folder ) . $file;

			if ( 'd' === $file_info['type'] ) {
				// Recursively delete subfolder.
				self::delete_folders_recursively( $path );
			}

			// Delete file or folder.
			$wp_filesystem->delete( $path, true );
		}

		// Finally, delete the main folder.
		return $wp_filesystem->delete( $folder, true );
	}

	/**
	 * Create a directory recursively using WP_Filesystem.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @param     string $path The directory path.
	 * @return    bool         True on success, false on failure.
	 */
	public static function create_folders_recursively( $path ) {
		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem();

		$path = wp_normalize_path( $path );

		// If the directory already exists, return true.
		if ( $wp_filesystem->is_dir( $path ) ) {
			return true;
		}

		// Get the parent directory.
		$parent_dir = dirname( $path );

		// Recursively create parent directories first.
		if ( ! $wp_filesystem->is_dir( $parent_dir ) ) {
			self::create_folders_recursively( $parent_dir );
		}

		// Create the directory.
		return $wp_filesystem->mkdir( $path, FS_CHMOD_DIR );
	}

	/**
	 * Creates the cache directory if it does not exist.
	 *
	 * This function checks if the cache directory, as determined by
	 * `self::get_cache_dir()`, exists. If it does not, it creates the directory
	 * with permissions 0700 (owner read, write, execute).
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @return    void
	 */
	public static function create_cache_dir() {
		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem();

		$cache_dir = self::get_cache_dir();

		if ( ! $cache_dir ) {
			return;
		}

		// Check if the directory already exists.
		if ( ! $wp_filesystem->is_dir( $cache_dir ) ) {
			$wp_filesystem->mkdir( $cache_dir, FS_CHMOD_DIR );
		}
	}

	/**
	 * Checks if the WordPress upload directory is writable.
	 *
	 * This function determines if the WordPress upload directory is writable by
	 * checking the directory returned by `self::get_cache_dir(true)`.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @return    bool True if the upload directory is writable, false otherwise.
	 */
	public static function is_upload_dir_writable() {
		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem();

		$cache_dir = self::get_cache_dir( true );

		if ( ! $cache_dir ) {
			return false;
		}

		return $wp_filesystem->is_writable( $cache_dir );
	}

	/**
	 * Gets the compiled file storage cache directory.
	 *
	 * This function retrieves the cache directory path for storing compiled files.
	 * It uses WordPress's `wp_upload_dir()` function to determine the base upload
	 * directory and appends '/obfuscated_scripts' to it, unless `$base_dir_only` is set to true.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 * @param     bool   $base_dir_only Optional. If true, returns only the base upload directory.
	 * @param     string $dir           Optional. The directory within the uploads array to use. Defaults to 'basedir'.
	 * @return    string $dir           The cache directory path.
	 */
	public static function get_cache_dir( $base_dir_only = false, $dir = 'basedir' ) {
		$upload     = wp_upload_dir();
		$upload_dir = $upload[ $dir ];

		if ( $base_dir_only ) {
			return $upload_dir;
		}

		return $upload_dir . '/obfuscated_scripts';
	}
}
