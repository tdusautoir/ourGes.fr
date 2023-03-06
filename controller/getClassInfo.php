<?php
require_once '../functions.php';
require_once '../lang/lang.php';

$lang = $lang[get_user_lang()];

header('Content-type: application/json');

if (isset($_GET['classId'])) {
    $classId = $_GET['classId'];
    $data = array();

    if (!isInteger($classId)) {
        create_flash_message('invalid_class_id', $lang['errors']['class_id'], FLASH_ERROR);
        $data['error'] = $lang['errors']['class_id'];
    }

    if (!isset($_SESSION['agenda'][$classId])) {
        create_flash_message('invalid_class_id', $lang['errors']['class_id'], FLASH_ERROR);
        $data['error'] = $lang['errors']['class_id'];
    } else {
        $startDate = new DateTime(date('Y-m-d H:i:s', $_SESSION['agenda'][$classId]->start_date / 1000));
        $endDate = new DateTime(date('Y-m-d H:i:s', $_SESSION['agenda'][$classId]->end_date / 1000));

        array_push($data, (array) $_SESSION['agenda'][$classId]);
        array_push($data, [$startDate->format('H:i'), $endDate->format('H:i')]);
    }
    
    echo json_encode($data);
}
