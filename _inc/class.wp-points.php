<?php
/**
 * class.wp-points.php
 *
 * Copyright (c) Manhnv
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author Manhnv
 * @package WPpoints
 * @since WPpoints 1.0.0
 */

/**
 * WPPoints class
 */
class WPPoints {
    /**
	 * Get a codes list.
	 * @param int $limit
	 * @param string $order_by
	 * @param string $order
	 * @return Ambigous <mixed, NULL, multitype:, multitype:multitype: , multitype:Ambigous <multitype:, NULL> >
	 */
	public static function get_used_codes ( $limit = null, $order_by = null, $order = null, $output = OBJECT ) {
		global $wpdb;
		
		$where_str = " WHERE status = '" . WPPOINTS_STATUS_USED . "'";
		
		$limit_str = "";
		if ( isset( $limit ) && ( $limit !== null ) ) {
			$limit_str = " LIMIT 0 ," . $limit;
		}
		$order_by_str = "";
		if ( isset( $order_by ) && ( $order_by !== null ) ) {
			$order_by_str = " ORDER BY " . $order_by;
		}
		$order_str = "";
		if ( isset( $order ) && ( $order !== null ) ) {
			$order_str = " " . $order;
		}

		$result = $wpdb->get_results("SELECT * FROM " . WPPoints_Database::wppoints_get_table( "codes" ) . $where_str . $order_by_str . $order_str . $limit_str, $output );

		return $result;
	}

	/**
	 * Get a pending codes list.
	 * @param int $limit
	 * @param string $order_by
	 * @param string $order
	 * @return Ambigous <mixed, NULL, multitype:, multitype:multitype: , multitype:Ambigous <multitype:, NULL> >
	 */
	public static function get_pending_codes ( $limit = null, $order_by = null, $order = null, $output = OBJECT ) {
		global $wpdb;
		
		$where_str = " WHERE status = '" . WPPOINTS_STATUS_PENDING . "'";
		
		$limit_str = "";
		if ( isset( $limit ) && ( $limit !== null ) ) {
			$limit_str = " LIMIT 0 ," . $limit;
		}
		$order_by_str = "";
		if ( isset( $order_by ) && ( $order_by !== null ) ) {
			$order_by_str = " ORDER BY " . $order_by;
		}
		$order_str = "";
		if ( isset( $order ) && ( $order !== null ) ) {
			$order_str = " " . $order;
		}

		$result = $wpdb->get_results("SELECT * FROM " . WPPoints_Database::wppoints_get_table( "codes" ) . $where_str . $order_by_str . $order_str . $limit_str, $output );

		return $result;
	}

	public static function get_users ( $limit = null, $order_by = null, $order = null, $output = OBJECT ) {
		global $wpdb;
		
		$where_str = " WHERE status = 1";
		
		$limit_str = "";
		if ( isset( $limit ) && ( $limit !== null ) ) {
			$limit_str = " LIMIT 0 ," . $limit;
		}
		$order_by_str = "";
		if ( isset( $order_by ) && ( $order_by !== null ) ) {
			$order_by_str = " ORDER BY " . $order_by;
		}
		$order_str = "";
		if ( isset( $order ) && ( $order !== null ) ) {
			$order_str = " " . $order;
		}

		$result = $wpdb->get_results("SELECT * FROM " . WPPoints_Database::wppoints_get_table( "users" ) . $where_str . $order_by_str . $order_str . $limit_str, $output );

		return $result;
	}

	public static function get_code ( $code = null ) {
		global $wpdb;

		$result = null;

		if ( isset( $code ) && ( $code !== null ) ) {

			$codes_str = " WHERE code = '" . $code. "'";
			$result = $wpdb->get_row("SELECT * FROM " . WPPoints_Database::wppoints_get_table( "codes" ) . $codes_str );
		}

		return $result;
	}

	public static function get_code_with_pn ( $code = null ) {
		global $wpdb;

		$result = null;

		if ( isset( $code ) && ( $code !== null ) ) {

			$codes_str = " WHERE code = '" . $code . "' and phone_number is null";
			$result = $wpdb->get_row("SELECT * FROM " . WPPoints_Database::wppoints_get_table( "codes" ) . $codes_str );
		}

		return $result;
	}

	public static function get_user_with_pn ( $phone_number = null ) {
		global $wpdb;

		$result = null;

		if ( isset( $phone_number ) && ( $phone_number !== null ) ) {

			$codes_str = " WHERE phone_number = '" . $phone_number ."'";
			$result = $wpdb->get_row("SELECT * FROM " . WPPoints_Database::wppoints_get_table( "users" ) . $codes_str );
		}

		return $result;
	}

	public static function get_user_point ( $phone_number = null ) {
		global $wpdb;

		$result = null;

		if ( isset( $phone_number ) && ( $phone_number !== null ) ) {

			$codes_str = " WHERE phone_number = '" . $phone_number ."'";
			$result = $wpdb->get_row("SELECT point FROM " . WPPoints_Database::wppoints_get_table( "users" ) . $codes_str );
		}

		return $result;
	}


	public static function insert_data_codes($data) {
		try{
			global $wpdb;

			$sql = "INSERT INTO ". WPPoints_Database::wppoints_get_table( "codes" ) . " (code, point) VALUES ";
			$codes_str = "";
			$result = null;

			foreach($data as $dt) {
				$codes_str = $codes_str . "(". $dt['code'] . "," . $dt['point'] . "),";
			}
			$sql = $sql . $codes_str;
			$sql = trim($sql, ",");
			$sql = $sql . "ON DUPLICATE KEY UPDATE code=code";

			$result = $wpdb->query($sql);
			
			return $result;
		}catch(Exception $e) {
			return [];
		}
	}

	public static function delete_code($code_id) {
		global $wpdb;
		return $wpdb->delete( WPPoints_Database::wppoints_get_table( "codes" ), array( 'code_id' => $code_id ) );
	}

	public static function delete_user($user_id) {
		global $wpdb;
		return $wpdb->delete( WPPoints_Database::wppoints_get_table( "users" ), array( 'user_id' => $user_id ) );
	}
}