<?php

class User
{
    private $user_id;
    private $user_name;
    private $user_img;
    private $promotion_id;
    private $user_login_status;
    public $connect;

    public function __construct()
    {
        require_once('Db.php');

        $database_object = new Db;

        $this->connect = $database_object->connect();
    }

    function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    function getUserId()
    {
        return $this->user_id;
    }

    function setUserName($user_name)
    {
        $this->user_name = $user_name;
    }

    function getUserName()
    {
        return $this->user_name;
    }

    function setUserImg($user_img)
    {
        $this->user_img = $user_img;
    }

    function getUserImg()
    {
        return $this->user_img;
    }


    function setPromotionId($promotion_id)
    {
        $this->promotion_id = $promotion_id;
    }

    function getPromotionId()
    {
        return $this->promotion_id;
    }

    function setUserLoginStatus($user_login_status)
    {
        $this->user_login_status = $user_login_status;
    }

    function getUserLoginStatus()
    {
        return $this->user_login_status;
    }

    function create_user()
    {
        //check if the profile already exist in the db
        $req = $this->connect->prepare("SELECT * FROM chat_user WHERE chat_user.user_id = :user_id");
        $req->bindValue(":user_id", $this->user_id);
        $req->execute();

        //insert the profile in db if not
        if ($req->rowCount() < 1) {
            $this->connect->query("INSERT INTO chat_user (user_id, user_name, promotion_id, user_login_status) VALUES ($this->user_id, '$this->user_name', $this->promotion_id, 'Login')");
            return;
        }

        //if the user have change of promotion, change his promotion id
        $user = $req->fetch(PDO::FETCH_ASSOC);

        if ($this->promotion_id != $user['promotion_id']) {
            $this->connect->query("UPDATE chat_user SET promotion_id = $this->promotion_id WHERE user_id = $this->user_id");
        }
    }

    function is_in_promotion()
    {
        //check if the user is in the promotion
        $req = $this->connect->prepare("SELECT * FROM chat_user WHERE chat_user.user_id = :user_id AND chat_user.promotion_id = :promotion_id");
        $req->bindValue(":user_id", $this->user_id);
        $req->bindValue(":promotion_id", $this->promotion_id);
        $req->execute();

        if ($req->rowCount() > 0) {
            return 1;
        }
        return 0;
    }

    function get_user_chat_data()
    {
        $get_chat_data = $this->connect->prepare('SELECT * FROM chatrooms INNER JOIN chat_user ON chat_user.user_id = chatrooms.user_id WHERE chatrooms.user_id = :user_id ORDER BY chatrooms.id ASC');
        $get_chat_data->bindValue(":user_id", $this->user_id);
        $get_chat_data->execute();

        return $get_chat_data->fetchAll(PDO::FETCH_ASSOC);
    }

    function add_user_img()
    {
        //check if an image already exist in the db
        $req = $this->connect->prepare("SELECT user_img FROM chat_user WHERE chat_user.user_id = :user_id");
        $req->bindValue(":user_id", $this->user_id);
        $req->execute();

        $data = $req->fetch(PDO::FETCH_ASSOC);

        //update the image in db if image don't exist
        if (!isset($data['user_img'])) {
            $this->connect->query("UPDATE chat_user SET user_img = '$this->user_img' WHERE user_id = $this->user_id");
            return;
        }

        //update image in db if he change his img
        if (isset($data['user_img']) && $data['user_img'] == $this->user_img) {
            $this->connect->query("UPDATE chat_user SET user_img = '$this->user_img' WHERE user_id = $this->user_id");
            return;
        }
    }
}
