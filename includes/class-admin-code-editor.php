<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://webrockstar.net
 * @since      1.0.0
 *
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/includes
 * @author     Steve Puddick <steve@webrockstar.net>
 */
class Admin_Code_Editor {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Admin_Code_Editor_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $admin_code_editor    The string used to uniquely identify this plugin.
	 */
	protected $admin_code_editor;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->admin_code_editor = 'admin-code-editor';
		$this->version = '1.3.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Admin_Code_Editor_Loader. Orchestrates the hooks of the plugin.
	 * - Admin_Code_Editor_i18n. Defines internationalization functionality.
	 * - Admin_Code_Editor_Admin. Defines all hooks for the admin area.
	 * - Admin_Code_Editor_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-admin-code-editor-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-admin-code-editor-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-code-editor-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-admin-code-editor-public.php';

		$this->loader = new Admin_Code_Editor_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Admin_Code_Editor_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Admin_Code_Editor_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Admin_Code_Editor_Admin( $this->get_admin_code_editor(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init',                  $plugin_admin, 'wp_ace_post_type_init');
		$this->loader->add_action( 'add_meta_boxes', 				$plugin_admin, 'code_editor_add_meta_box' );
		$this->loader->add_action( 'save_post', 						$plugin_admin, 'code_editor_save' );
		$this->loader->add_action( 'admin_notices', 				$plugin_admin, 'admin_post_error_notice' );
		$this->loader->add_action( 'admin_menu', 						$plugin_admin, 'options_menu' );
		$this->loader->add_action( 'admin_init', 						$plugin_admin, 'display_theme_panel_fields' );
		$this->loader->add_action( 'admin_init', 						$plugin_admin, 'init_option_filtering' );
		
		$this->loader->add_action( 'plugins_loaded', 				$plugin_admin, 'plugin_update_check' );
		$this->loader->add_action( 'before_delete_post', 		$plugin_admin, 'delete_code_posts' );

		$this->loader->add_filter( 'option_wp_ace_default_disabled_template', 	$plugin_admin, 'filterDefaultHideonTemplates' );
		$this->loader->add_filter( 'option_wp_ace_default_disabled_code', 			$plugin_admin, 'filterDefaultHideCodeEditorTypes' );
		$this->loader->add_filter( 'option_wp_ace_default_conditional_display', $plugin_admin, 'filterDefaultConditionalDisplay' );
		$this->loader->add_filter( 'option_wp_ace_enabled_post_type', 					$plugin_admin, 'filterEnabledPostType' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Admin_Code_Editor_Public( $this->get_admin_code_editor(), $this->get_version() );

		// $this->loader->add_action( 'wp_enqueue_scripts', 	$plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', 	$plugin_public, 'enqueue_scripts' );
		remove_filter('the_content','wpautop');
		$this->loader->add_filter( 'the_content', 				$plugin_public, 'wp_ace_wpautop' );
		$this->loader->add_action( 'the_content', 				$plugin_public, 'insert_ace_code_in_page' );
		$this->loader->add_action( 'wp_footer', 					$plugin_public, 'insert_script_in_footer', 999 );
	
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_admin_code_editor() {
		return $this->admin_code_editor;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Admin_Code_Editor_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
