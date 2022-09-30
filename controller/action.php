<?php

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'logout') {
        clean_php_session();
        header("location: ./");
    }
}
