<?php
require '../functions.php';

header('Content-type: application/json');

if (isset($_GET['classId'])) {
    $classId = $_GET['classId'];
    $data = array();

    if (!isInteger($classId)) {
        create_flash_message('invalid_class_id', 'Invalid class id', FLASH_ERROR);
        $data['error'] = "Invalid class id.";
    }

    if (!isset($_SESSION['agenda'][$classId])) {
        create_flash_message('invalid_class_id', 'Invalid class id', FLASH_ERROR);
        $data['error'] = "Invalid class id.";
    } else {
        $startDate = new DateTime(date('Y-m-d H:i:s', $_SESSION['agenda'][$classId]->start_date / 1000));
        $endDate = new DateTime(date('Y-m-d H:i:s', $_SESSION['agenda'][$classId]->end_date / 1000));

        array_push($data, (array) $_SESSION['agenda'][$classId]);
        array_push($data, [$startDate->format('H:i'), $endDate->format('H:i')]);
    }
    
    echo json_encode($data);
}
