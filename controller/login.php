<?php

require_once '../functions.php';
require_once '../vendor/autoload.php';

// set_error_handler("errorHandler");

$username = $_POST['username'];
$password = $_POST['password'];

try {
    // client-id = skolae-app
    $client = new MyGes\Client('skolae-app', $username, $password);
} catch (MyGes\Exceptions\BadCredentialsException $e) {
    //wrong credentials
    $err = $e->getMessage();
    create_flash_message(ERROR_LOGIN, 'Invalid login informations.', FLASH_ERROR);
    header("location: ../");
    return;
}

$me = new MyGes\Me($client);

if (isset($me)) {
    try {
        //get profile
        $profile = $me->getProfile();

        //get class
        $class = $me->getClasses(2022);

        //get grades 
        $grades = $me->getGrades(2022);

        //get Agenda
        $Today = date("d/m/Y", strtotime("today"));
        $LastDay = date("d/m/Y", strtotime("+6 days"));

        $d = DateTime::createFromFormat(
            'd/m/Y H:i:s',
            "$Today 00:00:00",
            new DateTimeZone('Europe/Paris')
        );
        $startAt = $d->getTimestamp() * 1000;

        $d = DateTime::createFromFormat(
            'd/m/Y H:i:s',
            "$LastDay 23:59:59",
            new DateTimeZone('Europe/Paris')
        );
        $endedAt = $d->getTimestamp() * 1000;

        $agenda = $me->getAgenda($startAt, $endedAt);

        //get News
        $news = $me->getNewsBanners();
    } catch (Exception $e) {
        $err = $e->getMessage();
        create_flash_message(ERROR_LOGIN, 'An error has occured, please try again.', FLASH_ERROR);
        header("location: ../");
        return;
    }

    init_php_session();
    create_flash_message(SUCCESS_LOGIN, 'Successfully connected.', FLASH_SUCCESS);

    $_SESSION['news'] = $news;
    $_SESSION['class'] = $class[0];
    $_SESSION['profile'] = $profile;
    $_SESSION['grades'] = $grades;
    $_SESSION['agenda'] = $agenda;

    header("location: ../");
    return;
}

//fatal error;
register_shutdown_function("fatalErrorHandler");
