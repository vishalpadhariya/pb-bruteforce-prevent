<?php

/**
 * Fired during plugin activation
 *
 * @link       https://vishalpadhariya.github.io/
 * @since      1.0.0
 *
 * @package    Pb_Bruteforce_Prevent
 * @subpackage Pb_Bruteforce_Prevent/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pb_Bruteforce_Prevent
 * @subpackage Pb_Bruteforce_Prevent/includes
 * @author     Pbytes Hub <pbytes.hub@gmail.com>
 */
class Pb_Bruteforce_Prevent_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		// set the default character set and collation for the table
		$charset_collate = $wpdb->get_charset_collate();
		// Check that the table does not already exist before continuing
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}pb_bruteforce_prevent` (
		id bigint(50) NOT NULL AUTO_INCREMENT,
		ip bigint(50) NOT NULL,
		PRIMARY KEY (id)
		) $charset_collate;";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
		$is_error = empty( $wpdb->last_error );
		return $is_error;
	}

}
