<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.wplauncher.com
 * @since      1.0.5
 *
 * @package    Binox_Wp
 * @subpackage Binox_Wp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.5
 * @package    Binox_Wp
 * @subpackage Binox_Wp/includes
 * @author     Ben Shadle <benshadle@gmail.com>
 */
class Binox_Wp_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.5
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'settings-page',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
