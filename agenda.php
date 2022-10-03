<?php

//to sort the agenda
if (isset($_SESSION['agenda'])) {
    usort($_SESSION['agenda'], "cmp");
}

$DAYS = [
    strtotime("today"),
    strtotime("+1 day"),
    strtotime("+2 days"),
    strtotime("+3 days"),
    strtotime("+4 days"),
    strtotime("+5 days"),
    strtotime("+6 days"),
];
