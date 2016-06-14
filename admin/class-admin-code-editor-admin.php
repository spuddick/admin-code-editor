<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/admin
 * @author     Your Name <email@example.com>
 */
class Admin_Code_Editor_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $admin_code_editor    The ID of this plugin.
	 */
	private $admin_code_editor;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $admin_code_editor       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $admin_code_editor, $version ) {

		$this->admin_code_editor = $admin_code_editor;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Admin_Code_Editor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Admin_Code_Editor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->admin_code_editor, plugin_dir_url( __FILE__ ) . 'css/admin-code-editor-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Admin_Code_Editor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Admin_Code_Editor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->admin_code_editor, plugin_dir_url( __FILE__ ) . 'js/admin-code-editor-admin.js', array( 'jquery' ), $this->version, false );

	}

}
