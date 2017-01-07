<?php

/**
 * Fired during plugin activation
 *
 * @link       http://webrockstar.net
 * @since      1.0.0
 *
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Admin_Code_Editor
 * @subpackage Admin_Code_Editor/includes
 * @author     Steve Puddick <steve@webrockstar.net>
 */
class Admin_Code_Editor_Activator {

	/**
	 * Set initial default values
	 * @since    1.0.0
	 */
	public static function activate() {
		add_option( 'wp_ace_enabled_post_type', array('post', 'page'));
		add_option( 'wp_ace_default_conditional_display', array('inside-the-loop', 'in-main-query'));
		add_option( 'wp_ace_default_include_jquery', 1);
		add_option( 'wp_ace_default_html_position', 'before');
		add_option( 'wp_ace_default_html_preprocessor', 'none');
		add_option( 'wp_ace_default_css_preprocessor', 'scss');
		add_option( 'wp_ace_default_js_preprocessor', 'none');
	}

}
