<?php

if (!is_logged() && (!isset($_GET['action']) || empty($_GET['action']))) {
    header("location: ../?callbackUrl=".(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
}