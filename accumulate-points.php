<?php

function handle_accumulate_points() {
    if ( isset($_POST['submit'] ) ) {
        $errors = accumulate_points_validation(
            $_POST['phone_number'],
            $_POST['code']
        );

        if(!empty($errors) ){
            status_header(400);
            return json_encode([
                "errors" => $errors,
                "message" => "Invalid data"
            ]);
        }

        

        $result = complete_accumulate_points(
            $_POST['phone_number'],
            $_POST['code']
        );

        if(!$result){
            status_header(400);
            return json_encode([
                "errors" => true,
                "message" => "Invalid data"
            ]);
        }

        status_header(200);
        return json_encode([
            "errors" => false,
            "message" => "Data add success"
        ]);
    }else {
        status_header(400);
        return json_encode([
            "errors" => true,
            "message" => "Invalid data"
        ]);
    }
}

function complete_accumulate_points($phone_number, $code) {
    global $wpdb;
    $code_old = WPPoints::get_code_with_pn($code);
    $old_user = WPPoints::get_user_with_pn($phone_number);

    if(!isset($code_old)) return false;

    $wpdb->update(WPPoints_Database::wppoints_get_table('codes'), array(
        'phone_number' => $phone_number,
        'status' => 'done'
    ), array(
        'code' => $code_old->code
    ));

    if(!isset($old_user)) {

        $data = array(
            'phone_number' => $phone_number,
            'point' => $code_old->point
        );

        $wpdb->insert(WPPoints_Database::wppoints_get_table('users'), $data);
    }else{
        $wpdb->update(WPPoints_Database::wppoints_get_table('users'), array(
            'point' => (int) $old_user->point + $code_old->point
        ), array(
            'phone_number' => $phone_number,
        ));
    }

    return true;
}

function accumulate_points_validation( $phone_number, $code )  {
    global $reg_errors;
    $reg_errors = new WP_Error;

    if ( empty( $phone_number ) || empty( $code ) ) {
        $reg_errors->add('field', 'Required form field is missing');
    }

    if ( 10 != strlen( $phone_number ) ) {
        $reg_errors->add( 'phone_number', 'Phone_number invalid' );
    }

    if ( is_wp_error( $reg_errors ) ) return $reg_errors->get_error_messages();

    return null;
}


function main() {
    $action_acc_point = $_GET['action_point'];

    switch ($action_acc_point) {
        case 'acc_point':
            echo handle_accumulate_points();
            break;
        
        default:
            return json_encode([]);
            break;
    }
}

main();