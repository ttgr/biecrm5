<?php

require_once('../base.php');




$input['token']     = getParam($_POST,'token','');
$input['device']    = getParam($_POST,'device','');
$input['username']  = getParam($_POST,'username','');
$input['password']  = getParam($_POST,'password','');

$error_msg = "";
$json_data = array();

if (empty($input['token'])) {
    sendData([
        'success' => false,
        'data' => "The initial Handshake Data are not correct"
    ]);
}

if (empty($input['device'])) {
    sendData([
        'success' => false,
        'data' => "The initial Handshake Data are not correct"
    ]);
}

if (empty($input['username'])) {
    sendData([
        'success' => false,
        'data' => "The initial Handshake Data are not correct"
    ]);
}


if (empty($input['password'])) {
    sendData([
        'success' => false,
        'data' => "The initial Handshake Data are not correct"
    ]);
}


if (!isValidInitialToken($db, $input['token'], $input['device'])) {
    sendData([
        'success' => false,
        'data' => "The initial Handshake Data are not correct: Incorrect Token"
    ]);
}


$t = validateLogin($db, $input['username'], $input['password'],$config->get('secret'));
if (is_array($t) && count($t) > 0 && $t['id'] >0 && !empty($t['token'])) {
    registerGetApiToken($db,$t['id'], $t['username'], $t['mail'],$t['token']);
    sendData([
        'success' => true,
        'data' => $t,
    ]);
} else {
    sendData([
        'success' => false,
        'data' => "Unable to login: Incorrect Username or Password"
    ]);
}



if ($error_msg == "") {
    //
} else {
    $json_data['error'] = array($error_msg);
}



function sendData($data){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data,JSON_UNESCAPED_UNICODE);
    exit();
}
