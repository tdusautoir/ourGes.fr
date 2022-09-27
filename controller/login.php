<?php

require_once '../functions.php';
require_once '../vendor/autoload.php';

set_error_handler("errorHandler");

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
    //get profile
    $profile = $me->getProfile();

    //get class
    $class = $me->getClasses(2022);

    //get grades 
    $grades = $me->getGrades(2022);

    //get Agenda
    $firstDayOfThisWeek = strftime("%d/%m/%Y", strtotime("this week"));
    $lastDayOfThisWeek = strftime("%d/%m/%Y", strtotime("this week +4 days"));

    $firstDayOfNextWeek = strftime("%d/%m/%Y", strtotime("next week"));
    $lastDayOfNextWeek = strftime("%d/%m/%Y", strtotime("next week +4 days"));

    $d = DateTime::createFromFormat(
        'd/m/Y H:i:s',
        "$firstDayOfThisWeek 00:00:00",
        new DateTimeZone('Europe/Paris')
    );
    $startAt_firstWeek = $d->getTimestamp() * 1000;

    $d = DateTime::createFromFormat(
        'd/m/Y H:i:s',
        "$lastDayOfThisWeek 23:59:59",
        new DateTimeZone('Europe/Paris')
    );
    $endedAt_firstWeek = $d->getTimestamp() * 1000;

    $d = DateTime::createFromFormat(
        'd/m/Y H:i:s',
        "$firstDayOfNextWeek 00:00:00",
        new DateTimeZone('Europe/Paris')
    );
    $startAt_nextWeek = $d->getTimestamp() * 1000;

    $d = DateTime::createFromFormat(
        'd/m/Y H:i:s',
        "$lastDayOfNextWeek 23:59:59",
        new DateTimeZone('Europe/Paris')
    );
    $endedAt_nextWeek = $d->getTimestamp() * 1000;

    $agenda_this_week = $me->getAgenda($startAt_firstWeek, $endedAt_firstWeek);
    $agenda_next_week = $me->getAgenda($startAt_nextWeek, $endedAt_nextWeek);

    //get News
    $news = $me->getNewsBanners();

    init_php_session();
    create_flash_message(SUCCESS_LOGIN, 'Successfully connected.', FLASH_SUCCESS);

    $_SESSION['news'] = $news;
    $_SESSION['class'] = $class[0];
    $_SESSION['profile'] = $profile;
    $_SESSION['grades'] = $grades;
    $_SESSION['agenda']['this_week'] = $agenda_this_week;
    $_SESSION['agenda']['next_week'] = $agenda_next_week;

    header("location: ../");
    return;
}

//fatal error;
register_shutdown_function("fatalErrorHandler");
