<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://vishalpadhariya.github.io/
 * @since      1.0.0
 *
 * @package    Pb_Bruteforce_Prevent
 * @subpackage Pb_Bruteforce_Prevent/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Pb_Bruteforce_Prevent
 * @subpackage Pb_Bruteforce_Prevent/includes
 * @author     Pbytes Hub <pbytes.hub@gmail.com>
 */
class Pb_Bruteforce_Prevent_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pb-bruteforce-prevent',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
