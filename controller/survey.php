<?php


require_once '../functions.php';
require_once '../models/Survey.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $survey = new Survey;

    if (!isset($_POST['name']) || empty($_POST['name'])) {
        //erreur
        die('erreur 1');
        return;
    }

    if (!isset($_POST['description']) || empty($_POST['description'])) {
        die('erreur 2');
        return;
    }

    if (!isset($_POST['type']) || empty($_POST['type'])) {
        die('erreur 3');
        return;
    }

    if (!isInteger($_POST['nb-choice'])) {
        die('erreur 4');
        return;
    }

    $survey->setName($_POST['name']);
    $survey->setDescription($_POST['description']);
    $survey->setType($_POST['type']);
    for ($i = 1; $i <= $_POST['nb-choice']; $i++) {
        if (!isset($_POST["choice-$i"]) || empty($_POST["choice-$i"])) {
            return;
        }
        $survey->setChoices($_POST["choice-$i"]);
    }
    $token = guidv4();
    $survey->setToken($token);

    if ($survey->create_survey()) {
        header("location: ../survey/?token=$token");
    } else {
        //erreur
        return;
    }
}
