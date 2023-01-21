<?php

require_once '../functions.php';
require_once '../vendor/autoload.php';
require_once '../models/Chatroom.php';
require_once '../models/User.php';

// set_error_handler("errorHandler");

$username = $_POST['username'];
$password = $_POST['password'];

try {
    // client-id = skolae-app
    $client = new MyGes\Client('skolae-app', $username, $password);
} catch (MyGes\Exceptions\BadCredentialsException | Exception $e) {
    $err = $e->getMessage();

    if (get_class($e) == "MyGes\Exceptions\BadCredentialsException") {
        //wrong credentials
        create_flash_message(ERROR_LOGIN, 'Invalid login informations.', FLASH_ERROR);
    } else {
        create_flash_message(ERROR_HANDLER, 'An error has occured, please try again later.', FLASH_ERROR);
    }
}

if (isset($client)) {
    $me = new MyGes\Me($client);

    if (isset($me)) {

        //get actual year
        $currentYear = intval(date("Y", strtotime("-1 year")));

        try {
            //get class
            $class = $me->getClasses($currentYear);

            //create promotion if the promo doesn't already exist
            $Chatroom = new ChatRoom;
            if (isset($class[0]->promotion)) {
                $Chatroom->setPromotion($class[0]->promotion);
                $promotionId = $Chatroom->find_promotion_by_name($class[0]->promotion);
                if (!$promotionId) {
                    $promotionId = $Chatroom->create_promotion();
                }
            }

            //get profile
            $profile = (object) $me->getProfile();

            //create user if the user doesn't already exist
            $User = new User;
            $User->setUserId($profile->uid);
            $User->setUserName($profile->firstname);
            $User->setPromotionId($promotionId);
            $User->create_user();

            //insert the profile image
            if (isset($profile->_links->photo->href)) {
                $User->setUserImg($profile->_links->photo->href);
                $User->add_user_img();
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

            //get Absences
            $absences = $me->getAbsences($currentYear);
        } catch (Exception $e) {
            $err = $e->getMessage();
            create_flash_message(ERROR_LOGIN, 'An error has occured, please try again.', FLASH_ERROR);
        }

        init_php_session();
        create_flash_message(SUCCESS_LOGIN, 'Successfully connected.', FLASH_SUCCESS);

        $_SESSION['news'] = $news;
        $_SESSION['class'] = $class[0];
        $_SESSION['profile'] = $profile;
        $_SESSION['grades'] = $grades;
        $_SESSION['agenda'] = $agenda;
        $_SESSION['absences'] = $absences;
    }
}

if (isset($_GET['surveyToken']) && !empty($_GET['surveyToken'])) {
    header('location: ../survey/index.php?token=' . $_GET['surveyToken']);
} else {
    header('location: ../');
    return;
}

register_shutdown_function("fatalErrorHandler");
return;
