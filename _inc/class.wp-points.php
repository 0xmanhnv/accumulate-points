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

	public static function get_gift_point ( $gift = null, $point = null ) {
		global $wpdb;

		$result = null;

		if ( isset( $gift ) && ( $gift !== null ) && ( $point ) && ( $point !== null ) ) {

			$codes_str = " WHERE gift = '" . $gift. "'". " AND point = '" . $point. "'";
			$result = $wpdb->get_row("SELECT * FROM " . WPPoints_Database::wppoints_get_table( "gifts" ) . $codes_str );
		}

		return $result;
	}

	
	public static function get_reward_exchange ( $status = null ) {
		global $wpdb;

		$result = null;
		$str = "";
		if ($status) {
			$str = " WHERE STATUS = ". "'". $status. "'";
		}

		$result = $wpdb->get_results("SELECT * FROM " . WPPoints_Database::wppoints_get_table( "reward_exchanges" ). $str, ARRAY_A );

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

	public static function insert_data_gifts($data) {
		try{
			global $wpdb;

			$sql = "INSERT INTO ". WPPoints_Database::wppoints_get_table( "gifts" ) . " (gift, point) VALUES ";
			$codes_str = "";
			$result = null;
			foreach($data as $dt) {
				$codes_str = $codes_str . "(". $dt['gift'] . "," . $dt['point'] . "),";
			}
			$sql = $sql . $codes_str;
			$sql = trim($sql, ",");
			$sql = $sql . " ON DUPLICATE KEY UPDATE gift=gift, point=point";

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

	public static function delete_gift($gift_id) {
		global $wpdb;
		return $wpdb->delete( WPPoints_Database::wppoints_get_table( "gifts" ), array( 'id' => $gift_id ) );
	}

	public static function delete_user($user_id) {
		global $wpdb;
		return $wpdb->delete( WPPoints_Database::wppoints_get_table( "users" ), array( 'user_id' => $user_id ) );
	}

	public static function insert_reward_exchange ($data, $point) {
		global $wpdb;

		$result = null;

			$codes_str = "";
			$codes_str = $codes_str . "(". "'".$data['phone_number']."'" . "," . "'".$data['user']."'" . "," . "'".$data['address']."'" . "," . "'".$data['gift']."'" . ")";
			$sql = "INSERT INTO ". WPPoints_Database::wppoints_get_table( "reward_exchanges" ) . " (phone_number, name, address, gift) VALUES ";
			$sql = $sql . $codes_str;
			$result = $wpdb->query($sql);
			$newPoint = $point - $data['point'];
			$sql = "UPDATE ". WPPoints_Database::wppoints_get_table( "users" ) . " SET POINT = ". "'".$newPoint."'". "WHERE PHONE_NUMBER=". "'".$data['phone_number']."'";
			$result = $wpdb->query($sql);
			return $result;


		return $result;
	}

	public static function get_gifts () {
		global $wpdb;

		$result = null;

		$sql = "SELECT DISTINCT point  FROM " . WPPoints_Database::wppoints_get_table( "gifts" );
		$result = $wpdb->get_results($sql);
		
		return $result;
	}

	public static function get_gifts_table ($limit = null, $order_by = null, $order = null, $output = OBJECT) {
		global $wpdb;
		
		$where_str = "";
		
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

		$result = $wpdb->get_results("SELECT * FROM " . WPPoints_Database::wppoints_get_table( "gifts" ) . $where_str . $order_by_str . $order_str . $limit_str, $output );

		return $result;
	}

	public static function get_gift_from_id ($id) {
		global $wpdb;

		$result = null;

		$sql = "SELECT *  FROM " . WPPoints_Database::wppoints_get_table( "gifts" ) ." WHERE id = " . $id;
		$result = $wpdb->get_results($sql, $output = OBJECT, $y = 0);
		if($result) {
			return $result[0];
		}
		return null;
	}

	public static function update_gift_from_id($id, $gift, $point) {
		global $wpdb;

		$result = null;

		$sql = "UPDATE ". WPPoints_Database::wppoints_get_table( "gifts" ) . " SET gift = '" . $gift."', point=". $point ."  WHERE id = " . $id;
		
		$result = $wpdb->query($sql);
		return $result;
	}

	public static function get_gifts_from_point ($point) {
		global $wpdb;

		$result = null;

		$sql = "SELECT gift  FROM " . WPPoints_Database::wppoints_get_table( "gifts" ) . " WHERE point = " . $point;
		$result = $wpdb->get_results($sql);
		// var_dump($sql);
		// var_dump($result);
		// die;

		return $result;
	}

	public static function approve_exchange ($id) {
		global $wpdb;

		$result = null;
		$sql = "UPDATE ". WPPoints_Database::wppoints_get_table( "reward_exchanges" ) . " SET STATUS = ". "'".WPPOINTS_REWARD_EXCHANGE_DONE."'". " WHERE ID=". "'".$id."'";
		$result = $wpdb->query($sql);

		return $result;
	}
}