<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Plugin_Name
 *
 * @wordpress-plugin
 * Plugin Name:       Content Party
 * Plugin URI:        http://contentparty.org/
 * Description:       Content party plugin for Wordpress
 * Version:           1.0.7
 * Author:            Content Party
 * Author URI:        http://contentparty.org/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       content-party
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-content-party-activator.php
 */
function activate_content_party() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-content-party-activator.php';
	Content_Party_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-content-party-deactivator.php
 */
function deactivate_content_party() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-content-party-deactivator.php';
	Content_Party_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_content_party' );
register_deactivation_hook( __FILE__, 'deactivate_content_party' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-content-party.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_content_party() {

	$plugin = new Content_Party();
	$plugin->run();

}
run_content_party();
