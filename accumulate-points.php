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
            'point' => $old_user->point + $code_old->point
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

function reward_exchange_validation( $phone_number, $name, $address, $point, $gift)  {
    global $reg_errors;
    $reg_errors = new WP_Error;

    if ( empty( $phone_number ) || empty( $name ) || empty( $address ) || empty( $point ) || $point == 'null' || empty( $gift ) || $gift == 'null' ) {
        $reg_errors->add('field', 'Required form field is missing');
    }

    if ( is_wp_error( $reg_errors ) ) return $reg_errors->get_error_messages();

    return null;
}

function handle_look_points() {
    if(!isset($_GET['phone_number'])) {
        status_header(400);
        return json_encode([
            "errors" => true,
            "message" => "Invalid data"
        ]);
    }
    $phone_number = $_GET['phone_number'];

    $point = WPPoints::get_user_point($phone_number);

    if(isset($point)) {
        status_header(200);
        return json_encode([
            "errors" => false,
            "message" => "Success",
            "data" => [
                "point" => $point->point
            ]
        ]);
    }
    return json_encode([
        "errors" => true,
        "message" => "Invalid data"
    ]);
}

function handle_reward_exchange() {
    if ( isset($_POST['submit'] ) ) {
        // var_dump($_POST);
        $errors = reward_exchange_validation(
            $_POST['phone_number'],
            $_POST['user'],
            $_POST['address'],
            $_POST['point'],
            $_POST['gift']
        );

        if(!empty($errors) ){
            status_header(400);
            return json_encode([
                "errors" => $errors,
                "message" => "Invalid data"
            ]);
        }
    }
    
    $phone_number = $_POST['phone_number'];

    $point = WPPoints::get_user_point($phone_number);
    if(isset($point)) {
        if ($point->point < $_POST['point']) {
            status_header(400);
            return json_encode([
                "errors" => true,
                "message" => "not enough points",
                "code" => NOT_ENOUGH_POINT_CODE
            ]);
        }
        $data = array(
            "phone_number" => $_POST['phone_number'],
            "user" => $_POST['user'],
            "address" => $_POST['address'],
            "point" => $_POST['point'],
            "gift" => $_POST['gift']
        );
        
        WPPoints::insert_reward_exchange($data, $point->point);
        status_header(200);
        return json_encode([
            "errors" => false,
            "message" => "Success",
            "reward_exchange" => true
        ]);
    } else {
        status_header(400);
            return json_encode([
                "errors" => true,
                "message" => "not enough points",
                "code" => NOT_ENOUGH_POINT_CODE
            ]);
    }
    status_header(400);
    return json_encode([
        "errors" => true,
        "message" => "Invalid data"
    ]);
}

function handle_get_gifs() {
    $points = WPPoints::get_gifts();
    $arrayGifs = [];
    if(isset($points)) {
        foreach($points as $point) {
            $gifts = WPPoints::get_gifts_from_point($point->point);
            $arrGifts = [];
            foreach($gifts as $gift) {
                array_push($arrGifts, $gift->gift);
            }
            
            array_push($arrayGifs, [
                "point" => $point->point,
                "gifts" =>  $arrGifts
            ]);
        }

        // var_dump($arrayGifs);
        status_header(200);
        return json_encode([
            "errors" => false,
            "message" => "Success",
            "data" => [
                "gifts" => $arrayGifs
            ]
        ]);
    }
    status_header(400);
    return json_encode([
        "errors" => true,
        "message" => "Invalid data"
    ]);
}


function main() {
    $action_acc_point = $_GET['action_point'];

    switch ($action_acc_point) {
        case 'acc_point':
            echo handle_accumulate_points();
            break;
        case 'point_look':
            echo handle_look_points();
            break;
        case 'reward_exchange':
            echo handle_reward_exchange();
            break;
        case 'get_gifs':
            echo handle_get_gifs();
            break;
        default:
            return json_encode([]);
            break;
    }
}

main();