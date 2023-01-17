<?php

date_default_timezone_set('Europe/Paris');
session_start();

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

function get_user_lang() {
    $lang = explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
    $lang = strtolower(substr(chop($lang[0]), 0, 2));

    return $lang;
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
    create_flash_message(ERROR_HANDLER, 'An error has occured, please try again later.', FLASH_ERROR);

    /* Don't execute PHP internal error handler */
    return true;
}


function fatalErrorHandler() //fatal error
{
    if(!is_logged()) {
        create_flash_message(ERROR_HANDLER, 'An error has occured, please try again later.', FLASH_ERROR);
    }

    /* Don't execute PHP internal error handler */
    return true;
}

//create a flash message
function create_flash_message(string $name, string $message, string $type): void
{
    if (isset($_SESSION[FLASH][$name])) {
        unset($_SESSION[FLASH][$name]);
    }
    $_SESSION[FLASH][$name] = ['message' => $message, 'type' => $type];
}

//check if flash message is define by name
function isset_flash_message_by_name(string $name): bool
{
    if (isset($_SESSION[FLASH][$name])) {
        return true;
    } else {
        return false;
    }
}

//check if flash message is define by type
function isset_flash_message_by_type(string $type): bool
{
    if (isset($_SESSION[FLASH])) {
        foreach ($_SESSION[FLASH] as $key => $value) {
            if ($value['type'] == $type) {
                return true;
            } else {
                return false;
            }
        }
    }
    return false;
}

//Delete flash message by name
function delete_flash_message_by_name(string $name): bool
{
    if (isset($_SESSION[FLASH][$name])) {
        unset($_SESSION[FLASH][$name]);
    } else {
        return false;
    }
}


//delete flash message by type
function delete_flash_message_by_type(string $type): bool
{
    if (isset($_SESSION[FLASH])) {
        foreach ($_SESSION[FLASH] as $key => $value) {
            if ($value['type'] == $type) {
                unset($_SESSION[FLASH][$key]);
            } else {
                return false;
            }
        }
    }
    return false;
}

//Display flash message by type
function display_flash_message_by_name(string $name): void
{
    if (!isset($_SESSION[FLASH][$name])) {
        return;
    }
    $flash_message[$name] = $_SESSION[FLASH][$name];
    unset($_SESSION[FLASH][$name]);


    echo $flash_message[$name]['message'];
}


//Display flash message by type
function display_flash_message_by_type(string $type): void
{
    if (isset($_SESSION[FLASH])) {
        foreach ($_SESSION[FLASH] as $key => $value) {
            if ($value['type'] == $type) {

                $flash_message = $value['message'];
                unset($_SESSION[FLASH][$key]);
                echo $flash_message;
            }
        }
    }
}

// Check if a value is a integer (
// "23" return true, 23 return true, 23.4 return false
function isInteger($input)
{
    return (ctype_digit(strval($input)));
}


// Sort the agenda multiple times
function sortAgenda(){
    //to sort the agenda
    if(isset($_SESSION['agenda'])) {
        usort($_SESSION['agenda'], "cmp");
    }
}

//to sort the agenda array by starting date
function cmp($a, $b)
{
    return $a->start_date - $b->start_date;
}

//generate an unique token
function guidv4($data = null)
{
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}