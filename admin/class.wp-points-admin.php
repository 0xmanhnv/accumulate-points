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
				__( 'Codes', 'Accumulate points' ),
				'manage_options',
				'wp-points',
				array( __CLASS__, 'wppoints_codes_html'),
				'dashicons-chart-pie'
		);

		add_submenu_page(
			'wp-points',
			__( 'Add Code', 'Add Code' ),
			__( 'Add Code', 'Add Code' ),
			'manage_options',
			'wp-points-add-code',
			array( __CLASS__, 'wppoints_add_codes_html')
		);
	}

	public static function wppoints_add_codes_html() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$plugin_name = "WP-POINTS";
		$errors = array();

		if ( isset( $_GET["action"] ) || isset( $_POST["action"] )) {
			$action = $_GET["action"];

			if(isset($_POST["action"])){
				$action = $_POST["action"];
			}

			switch ($action) {
				case 'submit-add-code':
					$errors = WPPoints_Admin::handle_add_code_submit();
					$success = false;
					if($errors === true) {
						$success = $errors;
					}
					require(WPPOINTS_ADMIN_DIR . '/views/wp-points-add-code-form-view.php');
					break;
				case 'submit-add-code-csv':
					$errors_file = WPPoints_Admin::handle_upload_csv_submit();
					$success_file = false;
					if($errors_file === true) {
						$success_file = $errors_file;
					}
					require(WPPOINTS_ADMIN_DIR . '/views/wp-points-add-code-form-view.php');
					break;
				
				default:
					require(WPPOINTS_ADMIN_DIR . '/views/wp-points-add-code-form-view.php');
					break;
			}
		}else{
			require(WPPOINTS_ADMIN_DIR . '/views/wp-points-add-code-form-view.php');
		}
	}

	public static function delete_code($code_id) {

	}

    public static function wppoints_codes_html() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		//Get the active tab from the $_GET param
		$default_tab = null;
		$tab = isset($_GET['point_type']) ? $_GET['point_type'] : $default_tab;

		if ( isset( $_GET["action"] ) ) {
			$action = $_GET["action"];

			switch ($action) {
				case 'delete_code':
					$code_id = $_GET['code_id'];
					WPPoints::delete_code($code_id);
					break;
				case 'delete_user':
					$user_id = $_GET['user_id'];
					WPPoints::delete_user($user_id);
					$_GET['point_type'] = "users";
					break;
				
				default:
					# code...
					break;
			}
		}

		// include head
		require_once(WPPOINTS_ADMIN_DIR . '/views/codes/wp-points-codes-head-view.php');

		switch($tab) :
			case 'users':
				require_once ( WPPOINTS_ADMIN_DIR . '/tables/class.wp-points-users-table.php' );
				$codesListTable = new WPPoints_Users_List_Table();
				$codesListTable->prepare_items();
				require(WPPOINTS_ADMIN_DIR . '/views/wp-points-users-table-view.php');
				break;
			case 'used-codes':
				require_once ( WPPOINTS_ADMIN_DIR . '/tables/class.wp-points-used-codes-table.php' );
				$codesListTable = new WPPoints_Used_Codes_List_Table();
				$codesListTable->prepare_items();
				require(WPPOINTS_ADMIN_DIR . '/views/wp-points-used-codes-table-view.php');
				break;
			default:
				require_once ( WPPOINTS_ADMIN_DIR . '/tables/class.wp-points-codes-table.php' );
				$codesListTable = new WPPoints_Codes_List_Table();
				$codesListTable->prepare_items();
				require(WPPOINTS_ADMIN_DIR . '/views/wp-points-codes-table-view.php');
				break;
		endswitch;

		//include foot
		require_once(WPPOINTS_ADMIN_DIR . '/views/codes/wp-points-codes-foot-view.php');

    }


	// return errors if error
    public static function handle_add_code_submit() {
		global $wpdb;
		$values = array();
		$errors = array();

		if(! isset($_POST['submit']) ) return;


		if(!isset( $_POST['code'] )){
			$errors['code'] = "Code required";
		}

		if(!isset( $_POST['point'] )){
			$errors['point'] = "Point required";
		}

		$code = WPPoints::get_code($_POST['code']);

		if(isset($code)) {
			$errors['code_exists'] = 'Code already exists';
		}

		if(empty($errors)){

			$values['code'] = esc_html($_POST['code']);
			$values['point'] = (int) $_POST['point'];

			$result = $wpdb->insert( WPPoints_Database::wppoints_get_table('codes'), $values );

			return true;
		}

		return $errors;
    }
	
	public static function handle_upload_csv_submit() {
		if ( !function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}
		$errors = array();

		if ( isset($_POST["submit"]) ) {
			if ( isset($_FILES["file"])) {

				//if there was an error uploading the file
				if ($_FILES["file"]["error"] > 0) {
					echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
					$errors['file'] = "Return Code: " . $_FILES["file"]["error"] . "<br />";
				}else {

					$uploadedfile = $_FILES['file'];
					$upload_overrides = array(
						'test_form' => false
					);
					$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
					if ( $movefile && ! isset( $movefile['error'] ) ) {
						// echo __( 'File is valid, and was successfully uploaded.', 'textdomain' ) . "\n";
						// var_dump( $movefile );
						$data = WPPoints_Admin::handle_csv_file( $movefile );
						// handle save csv to database
						if(count($data) > 0) {
							WPPoints::insert_data_codes($data);
							unlink($movefile['file']);
							return true;
						}

						return false;
					} else {
						/*
						 * Error generated by _wp_handle_upload()
						 * @see _wp_handle_upload() in wp-admin/includes/file.php
						 */
						echo $movefile['error'];
						$errors['file'] = $movefile['error'];
					}
				}
			}else {
				echo "No file selected <br />";

				$errors['file'] = "No file selected <br />";
			}
			
			if(!empty($errors)){
				return $errors;
			}
		}
	}

	public static function handle_csv_file($movefile) {
		try{
			$handle = fopen($movefile['file'], "r");
			$data = array();

			$line = fgetcsv($handle, null, ";");

			while (($line = fgetcsv($handle, null, ";")) !== FALSE) 
			{
				$data[] = [
					"code" => $line[0],
					"point" => $line[1]
				];
			}

			fclose($handle);
			return $data;
		}catch(Exception $e) {
			return [];
		}
	}
}