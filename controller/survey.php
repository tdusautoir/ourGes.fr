<?php

require_once '../functions.php';
require_once '../models/Survey.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $survey = new Survey;

    if(isset($_GET['method'])){
        if ($_GET['method'] == 'response') {
            if(isset($_POST['choice']) && isInteger($_POST['choice'])){
                $survey->setResponses($_POST['choice']);
                $survey->setToken($_POST['token']);
                $survey->setUser($_SESSION['profile']->uid);

                if(count($survey->get_response_from_user()) > 0) {
                    echo json_encode(['error' => true]);
                    return;
                }

                if($survey->send_response()) {
                    echo json_encode(['success' => true]);
                    return;
                }
            }

            echo json_encode(['error' => true]);
            return;
        }
    } else {
        if (!isset($_POST['name']) || empty($_POST['name'])) {
            //erreur
            die('erreur 2');
            return;
        }
        
        if (!isInteger($_POST['nb-choice'])) {
            die('erreur 3');
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
                $survey->setChoices("Choix $i");
            }
        }
        $token = guidv4();
        $survey->setToken($token);
    
        if ($survey->create_survey()) {
            header("location: ../survey/?token=$token");
        } else {
            echo "Erreur lors de la création";
            return;
        }
    }
}
