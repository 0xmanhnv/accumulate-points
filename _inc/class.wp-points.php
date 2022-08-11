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
	public static function get_used_codes ( $limit = null, $order_by = null, $order = null, $output = OBJECT, $search=null ) {
		global $wpdb;
		
		$where_str = " WHERE status = '" . WPPOINTS_STATUS_USED . "'";
		if($search) {
			$str_search = WPPoints::get_search_sql($search, array('phone_number', 'point', 'code'));
			$where_str .= " AND ". $str_search;

		}
		
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
	public static function get_pending_codes ( $limit = null, $order_by = null, $order = null, $output = OBJECT, $search=null ) {
		global $wpdb;
		
		$where_str = " WHERE status = '" . WPPOINTS_STATUS_PENDING . "'";

		if($search) {
			$str_search = WPPoints::get_search_sql($search, array('point', 'code'));
			$where_str .= " AND ". $str_search;

		}
		
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

	public static function get_users ( $limit = null, $order_by = null, $order = null, $output = OBJECT, $search =null ) {
		global $wpdb;
		
		$where_str = " WHERE status = 1";

		if($search) {
			$str_search = WPPoints::get_search_sql($search, array('phone_number', 'point'));
			$where_str .= " AND ". $str_search;

		}
		
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

	protected static function get_search_sql( $search, $columns ) {
		global $wpdb;
	 
		if ( false !== strpos( $search, '*' ) ) {
			$like = '%' . implode( '%', array_map( array( $wpdb, 'esc_like' ), explode( '*', $search ) ) ) . '%';
		} else {
			$like = '%' . $wpdb->esc_like( $search ) . '%';
		}
	 
		$searches = array();
		foreach ( $columns as $column ) {
			$searches[] = $wpdb->prepare( "$column LIKE %s", $like );
		}
	 
		return '(' . implode( ' OR ', $searches ) . ')';
	}

	
	public static function get_reward_exchange ( $status = null, $search = null ) {
		global $wpdb;

		$result = null;
		$str = "";
		if ($status) {
			$str = " WHERE STATUS = ". "'". $status. "'";
		}

		if($search) {
			$str_search = WPPoints::get_search_sql($search, array('phone_number', 'point', 'address', 'name', 'gift'));
			$str .= " AND ". $str_search;

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
			$data = $wpdb->_escape($data);

			$sql = "INSERT INTO ". WPPoints_Database::wppoints_get_table( "codes" ) . " (code, point) VALUES ";
			$codes_str = "";
			$result = null;

			foreach($data as $dt) {
				$codes_str = $codes_str . "('". $dt['code'] . "'," . $dt['point'] . "),";
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
			$data = $wpdb->_escape($data);

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
		$data = $wpdb->_escape($data);
		$point = $wpdb->_escape($point);

		$result1 = null;
		$result2 = null;

		$codes_str = "";
		$codes_str = $codes_str . "(". "'".$data['phone_number']."'" . "," . "'".$data['user']."'" . "," . "'".$data['address']."'" . "," . "'".$data['gift']."'" . "," . "'".$data['point']."'" . "," . "'".$data['gift_id']."'". ")";
		$sql = "INSERT INTO ". WPPoints_Database::wppoints_get_table( "reward_exchanges" ) . " (phone_number, name, address, gift, point, gift_id) VALUES ";
		$sql = $sql . $codes_str;
		$result1 = $wpdb->query($sql);

		if($result1){
			$newPoint = $point - $data['point'];
			$sql = "UPDATE ". WPPoints_Database::wppoints_get_table( "users" ) . " SET POINT = ". "'".$newPoint."'". "WHERE PHONE_NUMBER=". "'".$data['phone_number']."'";
			$result2 = $wpdb->query($sql);
			return $result2;
		}
		return $result1;
	}

	public static function get_gifts () {
		global $wpdb;

		$result = null;

		$sql = "SELECT DISTINCT point  FROM " . WPPoints_Database::wppoints_get_table( "gifts" );
		$result = $wpdb->get_results($sql);
		
		return $result;
	}

	public static function get_gifts_table ($limit = null, $order_by = null, $order = null, $output = OBJECT, $search=null) {
		global $wpdb;
		
		$where_str = " WHERE true ";
		if($search) {
			$str_search = WPPoints::get_search_sql($search, array('gift', 'point'));
			$where_str .= " AND ". $str_search;
		}
		
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
		$id = $wpdb->_escape($id);

		$result = null;

		$sql = "SELECT *  FROM " . WPPoints_Database::wppoints_get_table( "gifts" ) ." WHERE id = " . $id;
		$result = $wpdb->get_results($sql, $output = OBJECT, $y = 0);
		if($result) {
			return $result[0];
		}
		return null;
	}

	public static function get_reward_exchange_from_id ($id) {
		global $wpdb;
		$id = $wpdb->_escape($id);

		$result = null;

		$sql = "SELECT *  FROM " . WPPoints_Database::wppoints_get_table( "reward_exchanges" ) ." WHERE id = " . $id;
		$result = $wpdb->get_row($sql, $output = OBJECT, $y = 0);
		return $result;
	}

	public static function update_gift_from_id($id, $gift, $point) {
		global $wpdb;
		$id = $wpdb->_escape($id);
		$gift = $wpdb->_escape($gift);
		$point = $wpdb->_escape($point);

		$result = null;

		$sql = "UPDATE ". WPPoints_Database::wppoints_get_table( "gifts" ) . " SET gift = '" . $gift."', point=". $point ."  WHERE id = " . $id;
		
		$result = $wpdb->query($sql);
		return $result;
	}

	public static function get_gifts_from_point ($point) {
		global $wpdb;
		$point = $wpdb->_escape($point);

		$result = null;

		$sql = "SELECT gift,id  FROM " . WPPoints_Database::wppoints_get_table( "gifts" ) . " WHERE point = " . $point;
		$result = $wpdb->get_results($sql);

		return $result;
	}

	public static function approve_exchange ($id) {
		global $wpdb;
		$id = $wpdb->_escape($id);

		$result = null;
		$sql = "UPDATE ". WPPoints_Database::wppoints_get_table( "reward_exchanges" ) . " SET STATUS = ". "'".WPPOINTS_REWARD_EXCHANGE_DONE."'". " WHERE ID=". "'".$id."'";
		$result = $wpdb->query($sql);

		return $result;
	}

	public static function reject_exchange ($id) {
		global $wpdb;
		$id = $wpdb->_escape($id);

		$result = null;
		$sql = "UPDATE ". WPPoints_Database::wppoints_get_table( "reward_exchanges" ) . " SET STATUS = ". "'".WPPOINTS_REWARD_EXCHANGE_REJECTED."'". " WHERE ID=". "'".$id."'";
		$result = $wpdb->query($sql);

		return $result;
	}

	public static function update_user_points ($phone_number, $point, $id_reward_exchange, $operation="add") {
		global $wpdb;
		$phone_number = $wpdb->_escape($phone_number);

		$user = WPPoints::get_user_with_pn($phone_number);


		if(!$user) {
			return false;
		}
		// sum point đã tích lũy vào point của user
		$point_da_tich = WPPoints::get_points_user_from_code($user->phone_number);

		if($operation === "add") {
			if($point_da_tich < $point) {
				$point_update = $point_da_tich;
			}else{
				$point_update = $user->point + $point;
			}

			
			if($point_da_tich <= $point_update) {
				
				$point_update = $point_da_tich;
			}

			if($user->point == $point_update) {
				return true;
			}

			// Kiểm tra những điểm đã tích lũy đang chờ hoặc đã duyệt khác
			$point_da_tich_va_chua_duyet = WPPoints::get_points_da_doi_da_duyet_khac($user->phone_number, $id_reward_exchange);
			if($point_da_tich_va_chua_duyet > 0) {
				$point_update = $point_update - $point_da_tich_va_chua_duyet;
			}

			$sql = "UPDATE ". WPPoints_Database::wppoints_get_table( "users" ) . " SET point = '".$point_update."'". " WHERE phone_number=". "'".$phone_number."'";
			$result = $wpdb->query($sql);
			// var_dump($sql);die;
			return $result;
		}
		return false;
		
	}

	public static function get_points_da_doi_da_duyet_khac($phone_number, $id_reward_exchange) {
		global $wpdb;
		$phone_number = $wpdb->_escape($phone_number);
		$id_reward_exchange = $wpdb->_escape($id_reward_exchange);
		$result = null;
		$sql = "SELECT sum(point) as point FROM ". WPPoints_Database::wppoints_get_table( "reward_exchanges" ) . " WHERE phone_number = '" . $phone_number . "' AND (status = '" . WPPOINTS_REWARD_EXCHANGE_PENDING . "' OR status = '" . WPPOINTS_REWARD_EXCHANGE_DONE . "') AND id != " . $id_reward_exchange;
		$result = $wpdb->get_row($sql);
		return $result->point ? $result->point : 0;
	}

	public static function get_points_user_from_code($phone_number) {
		global $wpdb;
		$phone_number = $wpdb->_escape($phone_number);
		$result = null;
		$sql = "SELECT sum(point) as point FROM " . WPPoints_Database::wppoints_get_table( "codes" ) . " WHERE phone_number = " . $phone_number;
		$result = $wpdb->get_row($sql);
		return $result->point ? $result->point : 0;
	}

	public static function points_changed($phone_number) {
		global $wpdb;
		$phone_number = $wpdb->_escape($phone_number);
		$result = null;
		$sql = "SELECT sum(point) as sum_point FROM " . WPPoints_Database::wppoints_get_table( "reward_exchanges" ) . " WHERE PHONE_NUMBER = '" . $phone_number . "' AND (STATUS = '" . WPPOINTS_REWARD_EXCHANGE_DONE."' OR STATUS = '" . WPPOINTS_REWARD_EXCHANGE_PENDING."')";
		
		$result = $wpdb->get_row($sql);
		// var_dump($sql);
		// var_dump($result);
		// die;
		return $result->sum_point ? $result->sum_point : 0;
	}

	public static function get_total_points($phone_number) {
		global $wpdb;
		$phone_number = $wpdb->_escape($phone_number);
		$result = null;
		$sql = "SELECT sum(point) as sum_point FROM " . WPPoints_Database::wppoints_get_table( "codes" ) . " WHERE PHONE_NUMBER = '" . $phone_number . "'";
		$result = $wpdb->get_row($sql);
		return $result->sum_point ? $result->sum_point : 0;
	}
}