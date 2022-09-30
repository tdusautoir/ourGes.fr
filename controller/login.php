<?php

require_once '../functions.php';
require_once '../vendor/autoload.php';
require_once '../db/config.php';

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

    //get actual year
    $currentYear = date('Y');

    try {
        //get profile
        $profile = $me->getProfile();

        dump($profile);

        //check if the profile already exist in the db
        $req = $db->prepare("SELECT * FROM chat_user WHERE chat_user.user_id = :user_id");
        $req->bindValue(":user_id", $profile->uid);
        $req->execute();

        //insert the profile in db if not
        if ($req->rowCount() < 1) {
            $add_account = $db->query("INSERT INTO chat_user (user_id, user_name, user_login_status) VALUES ($profile->uid, '$profile->firstname', 'Login')");
        }

        //get class
        $class = $me->getClasses($currentYear);

        //check if the promotions is already in db
        $req = $db->prepare("SELECT * FROM promotions WHERE promotions.name = :promotion_name");
        $req->bindValue(":promotion_name", $class[0]->promotion);
        $req->execute();

        //insert the promotions in db if not
        if ($req->rowCount() < 1) {
            $add_promotion = $db->prepare("INSERT INTO promotions (name) VALUES (:promotion_name)");
            $add_promotion->bindValue(":promotion_name", $class[0]->promotion);
            $add_promotion->execute();
        }

        //get grades 
        $grades = $me->getGrades($currentYear);

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
