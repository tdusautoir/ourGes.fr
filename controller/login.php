<?php

require_once '../functions.php';
require_once '../lang/lang.php';
require_once '../vendor/autoload.php';
require_once '../models/Chatroom.php';
require_once '../models/User.php';

$lang = $lang[get_user_lang()];

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
        create_flash_message(ERROR_LOGIN, $lang['errors']['login'], FLASH_ERROR);
    } else {
        create_flash_message(ERROR_HANDLER,  $lang['errors']['error_occured_later'], FLASH_ERROR);
    }
}

if (isset($client)) {
    $me = new MyGes\Me($client);

    if (isset($me)) {

        //get actual year
        $currentYear = intval(date("Y", strtotime("-1 year")));

        try {
            //get class
            try {
                $class = $me->getClasses($currentYear);
            } catch (Exception $e) {
                if(get_class($e) == "GuzzleHttp\Exception\ClientException" && $e->getCode() == 423) {
                    create_flash_message(ERROR_LOGIN, $lang['errors']['satisfaction_request'], FLASH_ERROR);
                }
            }

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
            var_dump(get_class($e));
            create_flash_message(ERROR_LOGIN, $lang['errors']['error_occured'], FLASH_ERROR);
        }

        init_php_session();

        if(isset($class) && isset($news) && isset($profile) && isset($grades) && isset($agenda)) {
            $_SESSION['news'] = $news;
            $_SESSION['profile'] = $profile;
            $_SESSION['grades'] = $grades;
            $_SESSION['agenda'] = $agenda;
            $_SESSION['class'] = $class[0];
        }

        if(isset($absences)) {
            $_SESSION['absences'] = $absences;
        } else {
            $_SESSION['absences'] = [];
        }

        if(is_logged()) {
            create_flash_message(SUCCESS_LOGIN, $lang['success']['login'], FLASH_SUCCESS);
        }
    }
}

if(isset($_GET['callbackUrl']) && !empty($_GET['callbackUrl'])) {
    header('location:'.$_GET['callbackUrl']);
    return;
} else {
    header('location: ../');
    return;
}

register_shutdown_function("fatalErrorHandler");
return;
