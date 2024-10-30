<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since 1.0.0
 * @package Content_Party
 * @subpackage Content_Party/includes
 * @author Your Name <email@example.com>
 */
class Content_Party_Activator {

	/**
	 * Short Description.
	 * (use period)
	 *
	 * Long Description.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {
		Content_Party_Activator::create_db ();
	}
	private static function create_db() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate ();

		$sql = "CREATE TABLE `content_party_token` (`id`  int(10) NOT NULL AUTO_INCREMENT ,`user_key`  varchar(255) NOT NULL ,`timestamp`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,PRIMARY KEY (`id`),INDEX `timestamp` (`timestamp`) USING BTREE)ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;";
		$wpdb->query ( $sql );
	}
}
