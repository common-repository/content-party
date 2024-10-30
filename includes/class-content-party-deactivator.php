<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since 1.0.0
 * @package Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author Your Name <email@example.com>
 */
class Content_Party_Deactivator {
	
	/**
	 * Short Description.
	 * (use period)
	 *
	 * Long Description.
	 *
	 * @since 1.0.0
	 */
	public static function deactivate() {
		global $wpdb;
		$truncate = $wpdb->query ( "TRUNCATE TABLE `content_party_token`" );
		$delete = $wpdb->query ( "DROP TABLE `content_party_token`" );
	}
}
