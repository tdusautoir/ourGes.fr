<?php

require_once '../functions.php';
require_once '../vendor/autoload.php';

session_start();
set_error_handler("errorHandler");

$username = $_POST['username'];
$password = $_POST['password'];

try {
    // client-id = skolae-app
    $client = new MyGes\Client('skolae-app', $username, $password);
} catch (MyGes\Exceptions\BadCredentialsException $e) {
    //wrong credentials
    create_flash_message(ERROR_LOGIN, $e->getMessage(), FLASH_ERROR);
    header('location: ../index.php');
    return;
}

$me = new MyGes\Me($client);

$profile = $me->getProfile();
$class = $me->getClasses(2022);

// $news = $me->getNews(0);
// $_SESSION['news'] = $news;

init_php_session();

$_SESSION['class'] = $class[0];
$_SESSION['profile'] = $profile;

header('location: ../index.php');
