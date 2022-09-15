<?php

require_once '../functions.php';
require_once '../vendor/autoload.php';

init_php_session();

dump($_POST);

$username = $_POST['username'];
$password = $_POST['password'];

try {
    // client-id = skolae-app
    $client = new MyGes\Client('skolae-app', $username, $password);
} catch (MyGes\Exceptions\BadCredentialsException $e) {
    die($e->getMessage()); // bad credentials
}

$me = new MyGes\Me($client);

$profile = $me->getProfile();

$_SESSION['profile'] = $profile;

header('location: ../index.php');
