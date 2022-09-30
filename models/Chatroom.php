<?php

class ChatRoom
{
    private $promotion;
    private $chat_id;
    private $promotion_id;
    private $user_id;
    private $message;
    protected $connect;


    public function __construct()
    {
        require_once("Db.php");

        $database_object = new Db;

        $this->connect = $database_object->connect();
    }

    function setChatId($chat_id)
    {
        $this->chat_id = $chat_id;
    }

    function getChatId()
    {
        return $this->chat_id;
    }

    function setPromotionId($promotion_id)
    {
        $this->promotion_id = $promotion_id;
    }

    function getPromotionId()
    {
        return $this->promotion_id;
    }

    function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    function getUserId()
    {
        return $this->user_id;
    }

    function setMessage($message)
    {
        $this->message = $message;
    }

    function getMessage()
    {
        return $this->message;
    }

    function setPromotion($promotion)
    {
        $this->promotion = $promotion;
    }

    function create_promotion()
    {
        //check if the promotions is already in db
        $req = $this->connect->prepare("SELECT * FROM promotions WHERE promotions.name = :promotion_name");
        $req->bindValue(":promotion_name", $this->promotion);
        $req->execute();

        //insert the promotions in db if not
        if ($req->rowCount() < 1) {
            $add_promotion = $this->connect->prepare("INSERT INTO promotions (name) VALUES (:promotion_name)");
            $add_promotion->bindValue(":promotion_name", $this->promotion);
            $add_promotion->execute();
        }
    }

    function find_promotion_by_name()
    {
        //get promo id from his name
        $find_id_promo = $this->connect->prepare("SELECT id FROM promotions WHERE name = :promotion_name");
        $find_id_promo->bindValue(':promotion_name', $this->promotion);
        $find_id_promo->execute();
        $id_promo = $find_id_promo->fetch(PDO::FETCH_ASSOC);

        return $id_promo['id'];
    }

    function add_message()
    {
        //add the msg in the chatroom
        $add_msg = $this->connect->prepare('INSERT INTO chatrooms (id_promotion, user_id, msg) VALUES (:id_promotion, :user_id, :msg)');
        $add_msg->bindValue(':id_promotion', $this->promotion_id);
        $add_msg->bindValue(':user_id', $this->user_id);
        $add_msg->bindValue(':msg', $this->message);
        $add_msg->execute();
    }
}
