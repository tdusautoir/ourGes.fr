<?php

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
