<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.cubetech.ch
 * @since      1.0.0
 *
 * @package    Simple_Emoji_Reactions
 * @subpackage Simple_Emoji_Reactions/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Simple_Emoji_Reactions
 * @subpackage Simple_Emoji_Reactions/includes
 * @author     cubetech GmbH <info@cubetech.ch>
 */
class Simple_Emoji_Reactions_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'simple-emoji-reactions',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
