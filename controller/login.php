<?php

require_once '../functions.php';
require_once '../vendor/autoload.php';

set_error_handler("errorHandler");
// register_shutdown_function("fatalErrorHandler");

$username = $_POST['username'];
$password = $_POST['password'];

try {
    // client-id = skolae-app
    $client = new MyGes\Client('skolae-app', $username, $password);
} catch (MyGes\Exceptions\BadCredentialsException $e) {
    //wrong credentials
    $err = $e->getMessage();
    create_flash_message(ERROR_LOGIN, 'Invalid login informations.', FLASH_ERROR);
    header('location: ../index.php');
    return;
}

$me = new MyGes\Me($client);

//get profile
$profile = $me->getProfile();

//get class
$class = $me->getClasses(2022);

//get grades 
$grades = $me->getGrades(2022);

//get Agenda
$startAt = floor(microtime(true) * 1000);
$endedAt = floor(microtime(true) * 1000) - 604800000;
$agenda = $me->getAgenda($startAt, $endedAt);

$news = $me->getNewsBanners();
$_SESSION['news'] = $news;

init_php_session();
create_flash_message(SUCCESS_LOGIN, 'Successfully connected.', FLASH_SUCCESS);

$_SESSION['class'] = $class[0];
$_SESSION['profile'] = $profile;
$_SESSION['grades'] = $grades;
$_SESSION['agenda'] = $agenda;

header('location: ../index.php');
