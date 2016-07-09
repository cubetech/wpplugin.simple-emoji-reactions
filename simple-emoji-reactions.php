<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.cubetech.ch
 * @since             1.0.0
 * @package           Simple_Emoji_Reactions
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Emoji Reactions
 * Plugin URI:        https://github.com/cubetech/wpplugin.simple-emoji-reactions
 * Description:       Adding simple emoji reactions to your blog posts.
 * Version:           1.0.0
 * Author:            cubetech GmbH
 * Author URI:        https://www.cubetech.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-emoji-reactions
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-simple-emoji-reactions-activator.php
 */
function activate_simple_emoji_reactions() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-emoji-reactions-activator.php';
	Simple_Emoji_Reactions_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-simple-emoji-reactions-deactivator.php
 */
function deactivate_simple_emoji_reactions() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-emoji-reactions-deactivator.php';
	Simple_Emoji_Reactions_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_simple_emoji_reactions' );
register_deactivation_hook( __FILE__, 'deactivate_simple_emoji_reactions' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-simple-emoji-reactions.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_simple_emoji_reactions() {

	$plugin = new Simple_Emoji_Reactions();
	$plugin->run();

}
run_simple_emoji_reactions();
