<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://webrockstar.net
 * @since             1.0.0
 * @package           Admin_Code_Editor
 *
 * @wordpress-plugin
 * Plugin Name:       Admin Code Editor
 * Plugin URI:        
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Your Name or Your Company
 * Author URI:        http://webrockstar.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       admin-code-editor
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-admin-code-editor-activator.php
 */
function activate_admin_code_editor() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-admin-code-editor-activator.php';
	Admin_Code_Editor_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-admin-code-editor-deactivator.php
 */
function deactivate_admin_code_editor() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-admin-code-editor-deactivator.php';
	Admin_Code_Editor_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_admin_code_editor' );
register_deactivation_hook( __FILE__, 'deactivate_admin_code_editor' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-admin-code-editor.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_admin_code_editor() {

	$plugin = new Admin_Code_Editor();
	$plugin->run();

}
run_admin_code_editor();
