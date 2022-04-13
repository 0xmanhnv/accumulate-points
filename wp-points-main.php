<?php
/**
 * WPPOINTS plugin file.
 *
 * @package WPPOINTS\Main
 */

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * {@internal Nobody should be able to overrule the real version number as this can cause
 *            serious issues with the options, so no if ( ! defined() ).}}
 */
define( 'WPSEO_VERSION', '1.0.1' );

require_once ( WPPOINTS_CORE_INC . '/constants.php' );
require_once ( WPPOINTS_CORE_INC . '/class.wp-points.php');
require_once ( WPPOINTS_CORE_INC . '/class.wp-points-database.php' );
require_once ( WPPOINTS_CORE_INC . '/class.wp-points-shortcodes.php' );
require_once ( WPPOINTS_ADMIN_DIR . '/class.wp-points-admin.php' );

class WPPOINTS_Plugin {
    private static $notices = array();

    public static function init() {

		register_activation_hook( WPPOINTS_FILE, array( __CLASS__, 'activate' ) );
		register_deactivation_hook( WPPOINTS_FILE, array( __CLASS__, 'deactivate' ) );
		register_uninstall_hook( WPPOINTS_FILE, array( __CLASS__, 'uninstall' ) );

		add_action( 'init', array( __CLASS__, 'wp_init' ) );

	}

    public static function wp_init() {

		WPPoints_Admin::init();

	}


    /**
	 * Plugin activation work.
	 * 
	 */
	public static function activate() {
		global $wpdb;

		$charset_collate = '';
		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}

		// create codes tables
		$wppoints_codes_table = WPPoints_Database::wppoints_get_table("codes");
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$wppoints_codes_table'" ) != $wppoints_codes_table ) {
			$queries[] = "CREATE TABLE $wppoints_codes_table (
			code_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            code varchar(100) NOT NULL,
            phone_number varchar(100) default NULL,
            status       varchar(10) NOT NULL DEFAULT '" . WPPOINTS_STATUS_PENDING . "',
			point BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
			PRIMARY KEY   (code_id)
			) $charset_collate;";
		}

        $wppoints_users_table = WPPoints_Database::wppoints_get_table("users");
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$wppoints_users_table'" ) != $wppoints_users_table ) {
			$queries[] = "CREATE TABLE $wppoints_users_table (
			user_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            point BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
            phone_number varchar(100) default NULL,
			status       INT(10) NOT NULL DEFAULT 1,
			PRIMARY KEY   (user_id)
			) $charset_collate;";
		}

		$wppoints_gifts_table = WPPoints_Database::wppoints_get_table("gifts");
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$wppoints_gifts_table'" ) != $wppoints_gifts_table ) {
			$queries[] = "CREATE TABLE $wppoints_gifts_table (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            point BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
            gift varchar(255) default NULL,
			status       INT(4) NOT NULL DEFAULT 1,
			PRIMARY KEY   (id)
			) $charset_collate;";
		}

		if ( !empty( $queries ) ) {
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $queries );
		}
	}

    /**
	 * Plugin deactivation.
	 *
	 */
	public static function deactivate() {
        //TODO: handle deactivate method
	}

	/**
	 * Plugin uninstall. Delete database table.
	 *
	 */
	public static function uninstall() {

		global $wpdb;
		$wpdb->query('DROP TABLE IF EXISTS ' . WPPoints_Database::wppoints_get_table("codes") );
        $wpdb->query('DROP TABLE IF EXISTS ' . WPPoints_Database::wppoints_get_table("users") );
		$wpdb->query('DROP TABLE IF EXISTS ' . WPPoints_Database::wppoints_get_table("gifts") );
	}
}
