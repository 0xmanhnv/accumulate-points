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
	public static function get_codes ( $limit = null, $order_by = null, $order = null, $output = OBJECT ) {
		global $wpdb;
		
		$where_str = " WHERE status != '" . WPPOINTS_STATUS_USED . "'";
		
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
}