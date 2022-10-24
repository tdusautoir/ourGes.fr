<?php

require_once './models/Chatroom.php';
require_once './models/User.php';

if (isset($_SESSION['profile']->uid)) {
    $chat = new ChatRoom;
    $chat->setPromotion($_SESSION['class']->promotion);
    $promotionChatData = $chat->get_all_chat_data_from_promotion();
}
