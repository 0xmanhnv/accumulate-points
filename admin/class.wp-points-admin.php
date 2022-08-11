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
		add_action( 'admin_init', array( __CLASS__, 'export_reward_exchange' ) );
		// header_remove();
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
			__( 'Gifts', 'Gifts' ),
			__( 'Gifts', 'Gifts' ),
			'manage_options',
			'wp-points-list-gifts',
			array( __CLASS__, 'wppoints_list_gifts_html')
		);

		add_submenu_page(
			'wp-points',
			__( 'Add Code', 'Add Code' ),
			__( 'Add Code', 'Add Code' ),
			'manage_options',
			'wp-points-add-code',
			array( __CLASS__, 'wppoints_add_codes_html')
		);

		add_submenu_page(
			'wp-points',
			__( 'Export', 'Export Reward Exchange' ),
			__( 'Export', 'Export Reward Exchange' ),
			'manage_options',
			'wp-points-export',
			array( __CLASS__, 'export_reward_html')
		);
		
		add_submenu_page(
			'wp-points',
			__( 'Add Gift', 'Add Gift' ),
			__( 'Add Gift', 'Add Gift' ),
			'manage_options',
			'wp-points-add-gift',
			array( __CLASS__, 'add_gift_html')
		);
	}

	public static function wppoints_list_gifts_html() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$action = $_GET['action'];

		if( $action == 'delete_gift' ) {
			$gift_id = $_GET["gift_id"];
			$gift = WPPoints::delete_gift($gift_id);
			if(!$gift) {
				$errors = array(
					"gift_id" => __("Gift not found", "wp-points")
				);
			}
		}

		// include head
		require_once(WPPOINTS_ADMIN_DIR . '/views/gifts/wp-points-gifts-head-view.php');

		require_once ( WPPOINTS_ADMIN_DIR . '/tables/class.wp-points-gifts-table.php' );
		$codesListTable = new WPPoints_Gifts_List_Table();
		$codesListTable->prepare_items();
		// include head
		require_once(WPPOINTS_ADMIN_DIR . '/views/gifts/wp-points-gifts-table-view.php');

		// include head
		require_once(WPPOINTS_ADMIN_DIR . '/views/gifts/wp-points-gifts-foot-view.php');

	}

	public static function add_gift_html () { 
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$plugin_name = "WP-POINTS";
		$errors = array();
		

		if ( isset( $_GET["action"] ) || isset( $_POST["action"] )) {
			if(isset($_GET["action"])){
				$action = $_GET["action"];
			}

			if(isset($_POST["action"])){
				$action = $_POST["action"];
			}

			switch ($action) {
				case 'submit-add-gift':
					$errors = WPPoints_Admin::handle_add_gifts_submit();
					$success = false;
					if($errors === true) {
						$success = $errors;
					}
					require(WPPOINTS_ADMIN_DIR . '/views/wp-points-add-gifts-form-view.php');
					break;
				case 'submit-add-gift-csv':
					$errors_file = WPPoints_Admin::handle_upload_gift_csv_submit();
					$success_file = false;
					if($errors_file === true) {
						$success_file = $errors_file;
					}
					require(WPPOINTS_ADMIN_DIR . '/views/wp-points-add-gifts-form-view.php');
					break;
				
				case 'edit-gift':
					$gift_id = $_GET["gift_id"];
					$gift = WPPoints::get_gift_from_id($gift_id);
					if(!$gift) {
						$errors = array(
							"gift_id" => __("Gift not found", "wp-points")
						);
					}
					require(WPPOINTS_ADMIN_DIR . '/views/wp-points-edit-gifts-form-view.php');
					break;
				case 'edit-gift-post':
					$result = WPPoints::update_gift_from_id(
						$_POST['id'],
						$_POST['gift'],
						$_POST['point']
					);

					if($result) {
						$success = true;
						// /wp-admin/admin.php?page=wp-points-add-gift&action=edit-gift&gift_id=1
						$gift_id = $_POST["id"];
						$gift = WPPoints::get_gift_from_id($gift_id);
						if(!$gift) {
							$errors = array(
								"gift_id" => __("Gift not found", "wp-points")
							);
						}
						require(WPPOINTS_ADMIN_DIR . '/views/wp-points-edit-gifts-form-view.php');
					} else {
						$errors = "Update error";
						$gift_id = $_POST["id"];
						$gift = WPPoints::get_gift_from_id($gift_id);
						if(!$gift) {
							$errors = array(
								"gift_id" => __("Gift not found", "wp-points")
							);
						}
						require(WPPOINTS_ADMIN_DIR . '/views/wp-points-edit-gifts-form-view.php');
					}
					break;
				default:
					require(WPPOINTS_ADMIN_DIR . '/views/wp-points-add-gifts-form-view.php');
					break;
			}
		}else{
			require(WPPOINTS_ADMIN_DIR . '/views/wp-points-add-gifts-form-view.php');
		}
	}

	public static function export_reward_html() { 
		require(WPPOINTS_ADMIN_DIR . '/views/wp-points-export-exchange-view.php');
	}

	public static function export_reward_exchange () {
		if (isset ($_GET['action']) && $_GET['action'] == 'export_reward_exchange') {
			global $wpdb;

			$result = null;
			$sql = "SELECT * FROM ". WPPoints_Database::wppoints_get_table( "reward_exchanges" );
			$result = $wpdb->get_results($sql, ARRAY_A);
			$wp_filename = "reward_exchange_".date("d-m-y").".csv";
		
			// Open file
			$wp_file = fopen($wp_filename,"w");
			$fields = array('Phone number', 'Name', 'Address', 'Gift', 'Status');
			fputcsv($wp_file, $fields);
			// loop for insert data into CSV file
			foreach ($result as $key => $statementFet)
			{
				$wp_array = array(
					"phone_number"=> html_entity_decode($statementFet['phone_number']),
					"name"=> html_entity_decode($statementFet['name']),
					"address"=> html_entity_decode($statementFet['address']),
					"gift"=> $statementFet['gif'],
					"status"=> $statementFet['status'],
				);
				fputcsv($wp_file,$wp_array);
			}
			
			// Close file
			fclose($wp_file);
			
			// download csv file
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=".$wp_filename);
			header('Content-type: text/csv; charset=UTF-8');
			header('Content-Encoding: UTF-8');
			echo "\xEF\xBB\xBF";
			readfile($wp_filename);
			exit;
		}
	}

	public static function convert_to_html ($str) {
		return preg_replace("/&#?[a-z0-9]{2,8};/i","",$str);
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

	public static function handle_reject_exchange($id) {
		$reward_exchange = WPPoints::get_reward_exchange_from_id($id);
		if(!$reward_exchange) {
			return array(
				"reward_exchange_id" => __("Reward exchange not found", "wp-points")
			);
		}

		

		$gift = WPPoints::get_gift_from_id($reward_exchange->gift_id);
		if(!$gift) {
			return array(
				"gift_id" => __("Gift not found", "wp-points")
			);
		}

		// var_dump($gift);
		// var_dump($reward_exchange);
		// die;

		// update point user
		$user_update_result = WPPoints::update_user_points($reward_exchange->phone_number, $gift->point, $reward_exchange->id, "add");
		if(!$user_update_result) {
			return array(
				"user_update" => __("User update failed", "wp-points")
			);
		}
		
		// update reward exchange
		$result = WPPoints::reject_exchange($id);
		if($result) {
			return true;
		}
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
				case 'aprrove_exchange':
					$id = $_GET['id'];
					WPPoints::approve_exchange($id);
					break;
				case 'reject_exchange':
					$id = $_GET['id'];
					WPPoints_Admin::handle_reject_exchange($id);
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
			case 'reward-exchange':
				require_once ( WPPOINTS_ADMIN_DIR . '/tables/class.wp-points-reward-exchange-table.php' );
				$codesListTable = new WPPoints_Reward_Exchange_List_Table();
				$codesListTable->prepare_items();
				require(WPPOINTS_ADMIN_DIR . '/views/wp-points-reward-exchange-table-view.php');
				break;
			case 'reward-exchange-approved':
				require_once ( WPPOINTS_ADMIN_DIR . '/tables/class.wp-points-reward-exchange-approved-table.php' );
				$codesListTable = new WPPoints_Reward_Exchange_List_Table();
				$codesListTable->prepare_items();
				require(WPPOINTS_ADMIN_DIR . '/views/wp-points-reward-exchange-table-view.php');
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
			$values['point'] = $_POST['point'];

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

	public static function handle_upload_gift_csv_submit() {
		if ( !function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}
		$errors = array();

		if ( isset($_POST["submit"]) ) {
			if ( isset($_FILES["file"])) {

				//if there was an error uploading the file
				if ($_FILES["file"]["error"] > 0) {
					echo "Return Gift: " . $_FILES["file"]["error"] . "<br />";
					$errors['file'] = "Return Gift: " . $_FILES["file"]["error"] . "<br />";
				}else {

					$uploadedfile = $_FILES['file'];
					$upload_overrides = array(
						'test_form' => false
					);
					$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
					if ( $movefile && ! isset( $movefile['error'] ) ) {
						// echo __( 'File is valid, and was successfully uploaded.', 'textdomain' ) . "\n";
						// var_dump( $movefile );
						$data = WPPoints_Admin::handle_gift_csv_file( $movefile );
						// handle save csv to database
						if(count($data) > 0) {
							WPPoints::insert_data_gifts($data);
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

	public static function handle_gift_csv_file($movefile) {
		try{
			$handle = fopen($movefile['file'], "r");
			$data = array();

			$line = fgetcsv($handle, null, ";");

			while (($line = fgetcsv($handle, null, ";")) !== FALSE) 
			{
				$data[] = [
					"gift" => $line[0],
					"point" => $line[1]
				];
			}

			fclose($handle);
			return $data;
		}catch(Exception $e) {
			return [];
		}
	}

	public static function handle_add_gifts_submit() {
		global $wpdb;
		$values = array();
		$errors = array();

		if(! isset($_POST['submit']) ) return;


		if(!isset( $_POST['gift'] )){
			$errors['gift'] = "Gift required";
		}

		if(!isset( $_POST['point'] )){
			$errors['point'] = "Point required";
		}

		$gift_point = WPPoints::get_gift_point($_POST['gift'], $_POST['point']);

		if(isset($gift_point)) {
			$errors['gift_point_exists'] = 'Gift already exists';
		}

		if(empty($errors)){

			$values['gift'] = esc_html($_POST['gift']);
			$values['point'] = $_POST['point'];

			$result = $wpdb->insert( WPPoints_Database::wppoints_get_table('gifts'), $values );

			return true;
		}

		return $errors;
    }

}