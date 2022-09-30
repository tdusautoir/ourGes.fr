<?php

//Constants
const FLASH = 'FLASH_MESSAGES';
const FORM = 'FORM_INFO';

//flash type
const FLASH_ERROR = 'error';
const FLASH_WARNING = 'warning';
const FLASH_INFO = 'info';
const FLASH_SUCCESS = 'success';

//flash name
const ERROR_LOGIN = 'error_login';
const ERROR_HANDLER = 'error_handler';
const SUCCESS_LOGIN = 'success_login';

function dump($var) //function for debug
{
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

function init_php_session(): bool //init php session
{
    if (!session_id()) {
        session_start();
        session_regenerate_id();
        return true;
    }
    return false;
}

function clean_php_session(): void //clean the php session
{
    session_unset();
    session_destroy();
}

function is_logged(): bool
{
    if (isset($_SESSION["profile"])) {
        return true;
    }
    return false;
}

function errorHandler($errno, $errstr, $errfile, $errline) //error
{
    /* $error = [$errno, $errstr, $errfile, $errline]; //information about the error (for debug) */
    create_flash_message(ERROR_HANDLER, 'An error has occured, please try again.', FLASH_ERROR);
    header("location: ../");

    /* Don't execute PHP internal error handler */
    return true;
}


function fatalErrorHandler() //fatal error
{
    create_flash_message(ERROR_HANDLER, 'An error has occured, please try again later.', FLASH_ERROR);
    header("location: ../");

    /* Don't execute PHP internal error handler */
    return true;
}


function create_flash_message(string $name, string $message, string $type): void //creer un flash message
{
    // supprimer le flash message s'il est défini suivant le nom
    if (isset($_SESSION[FLASH][$name])) {
        unset($_SESSION[FLASH][$name]);
    }
    // Ajouter le flash message dans la session
    $_SESSION[FLASH][$name] = ['message' => $message, 'type' => $type];
}

function isset_flash_message_by_name(string $name): bool //Verifier si le flash message est défini via son nom
{
    if (isset($_SESSION[FLASH][$name])) {
        return true;
    } else {
        return false;
    }
}

function isset_flash_message_by_type(string $type): bool //Verifier si le flash message est défini via son type
{
    if (isset($_SESSION[FLASH])) {
        foreach ($_SESSION[FLASH] as $key => $value) { //parcourir les flashs messages et verifier si le type est défini
            if ($value['type'] == $type) { //si oui, retourner vrai
                return true;
            } else {
                return false;
            }
        }
    }
    return false;
}

function delete_flash_message_by_name(string $name): bool //Supprimer un flash message via son nom
{
    if (isset($_SESSION[FLASH][$name])) {
        unset($_SESSION[FLASH][$name]);
    } else {
        return false;
    }
}


function delete_flash_message_by_type(string $type): bool //Supprimer un flash message via son type
{
    if (isset($_SESSION[FLASH])) {
        foreach ($_SESSION[FLASH] as $key => $value) { //parcourir les flashs messages et verifier si le type existe
            if ($value['type'] == $type) { //si oui, le supprimer
                unset($_SESSION[FLASH][$key]);
            } else {
                return false;
            }
        }
    }
    return false;
}

function display_flash_message_by_name(string $name): void //Afficher le flash message via son nom
{
    //s'il n'existe pas ne rien renvoyer
    if (!isset($_SESSION[FLASH][$name])) {
        return;
    }

    // recuperer la valeur du message dans une variable
    $flash_message[$name] = $_SESSION[FLASH][$name];

    // supprimer le flash message de la session
    unset($_SESSION[FLASH][$name]);


    echo $flash_message[$name]['message'];
}

function display_flash_message_by_type(string $type): void //Afficher le flash message via son type
{
    if (isset($_SESSION[FLASH])) {
        foreach ($_SESSION[FLASH] as $key => $value) {  //parcourir les flashs messages et verifier si le type existe
            if ($value['type'] == $type) { //si oui, récupérer le message dans une variable
                $flash_message = $value['message'];

                // supprimer le flash message de la session
                unset($_SESSION[FLASH][$key]);

                // Afficher le flash message
                echo $flash_message;
            }
        }
    }
}
