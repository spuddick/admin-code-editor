<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://webrockstar.net
 * @since      1.0.0
 *
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/includes
 * @author     Steve Puddick <steve@webrockstar.net>
 */
class Admin_Code_Editor_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'admin-code-editor',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
