<?php

require_once '../functions.php';
require_once '../models/Survey.php';
require_once '../lang/lang.php';

$lang = $lang[get_user_lang()];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $survey = new Survey;

    if (isset($_GET['method'])) {
        if ($_GET['method'] == 'response') {
            if (isset($_POST['choice']) && isInteger($_POST['choice'])) {
                $survey->setResponses($_POST['choice']);
                $survey->setToken($_POST['token']);
                $survey->setUser($_SESSION['profile']->uid);

                if (count($survey->get_response_from_user()) > 0) {
                    create_flash_message(ERROR_LOGIN, $lang['errors']['already_answered'], FLASH_ERROR);
                    echo json_encode(['error' => true]);
                    return;
                }

                if ($survey->send_response()) {
                    echo json_encode(['success' => true]);
                    return;
                }
            }

            create_flash_message('response_error', $lang['errors']['form']['something_wrong'], FLASH_ERROR);
            echo json_encode(['error' => true]);
            return;
        }
    } else {
        if (!isset($_POST['name']) || empty($_POST['name'])) {
            create_flash_message('title_error', $lang['errors']['form']['title_required'], FLASH_ERROR);
            redirectToReferer('../survey');
            return;
        }

        if (!isInteger($_POST['nb-choice'])) {
            create_flash_message('choice_error', $lang['errors']['form']['something_wrong'], FLASH_ERROR);
            redirectToReferer('../survey');
            return;
        }

        $survey->setName($_POST['name']);
        $survey->setDescription($_POST['description']);
        $survey->setType(1);
        $survey->setNbChoice($_POST['nb-choice']);
        for ($i = 1; $i <= $_POST['nb-choice']; $i++) {
            if (isset($_POST["choice-$i"]) && !empty($_POST["choice-$i"])) {
                $survey->setChoices($_POST["choice-$i"]);
            } else {
                $survey->setChoices($lang['survey']['choice']." $i");
            }
        }

        if(isset($_POST['publicity'])) {
            $survey->setPublic(1);
        } else {
            $survey->setPublic(0);
        }

        if(isset($_POST['anonymous'])) {
            $survey->setAnonymous(1);
        } else {
            $survey->setAnonymous(0);
        }

        $token = guidv4();
        $survey->setToken($token);

        if ($survey->create_survey()) {
            header("location: ../survey/?token=$token");
            create_flash_message('create_success', $lang['success']['created'], FLASH_SUCCESS);
            return;
        }

        create_flash_message('create_error', $lang['errors']['form']['something_wrong'], FLASH_ERROR);
        redirectToReferer('../survey');
        return;
    }
}

register_shutdown_function("fatalErrorHandler");