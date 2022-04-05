<?php
/**
 * class.wp-points-admin.php
 *
 * Copyright (c) manhnv https://manhnv.com
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
 * @package wppoints
 * @since wppoints 1.0.0
 */

/**
 * WPPoints Admin class
 */
class WPPoints_Admin {

	public static function init () {
		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ), 40 );
	}

	public static function admin_notices() {
		if ( !empty( self::$notices ) ) {
			foreach ( self::$notices as $notice ) {
				echo $notice;
			}
		}
	}

    /**
	 * Adds the admin section.
	 */
	public static function admin_menu() {
		add_menu_page(
				__( 'Accumulate Points', 'Accumulate points' ),
				__( 'Points', 'Accumulate points' ),
				'manage_options',
				'wp-points',
				array( __CLASS__, 'wppoints_menu'),
				'dashicons-chart-pie'
		);

		add_submenu_page(
				'wp-points',
				__( 'Codes', 'codes' ),
				__( 'Codes', 'Code of Accumulate points' ),
				'manage_options',
				'codes-wp-points',
				array( __CLASS__, 'codes_wppoints_menu')
		);
	}

    public static function wppoints_menu() {
        $codesListTable = new WPPoints_Codes_List_Table();
        $codesListTable->prepare_items();
        require(WPPOINTS_ADMIN_DIR . '/views/wp-points-codes-table-view.php');
    }

    public static function codes_wppoints_menu() {
        //TODO: handle menu
    }
}