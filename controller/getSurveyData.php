<?php
require_once '../functions.php';
require_once '../models/Survey.php';
require_once '../lang/lang.php';

$lang = $lang[get_user_lang()];

header('Content-type: application/json');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $survey = new Survey;
    $survey->setToken($token);
    $responses = $survey->get_responses();
    $users_info = $survey->get_users_info();

    $data = ['responses' => $responses, 'users_info' => $users_info];
    
    echo json_encode($data);
}
