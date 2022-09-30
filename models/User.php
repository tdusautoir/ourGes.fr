<?php

//ChatUser.php

class User
{
    private $user_id;
    private $user_name;
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
            $add_account = $this->connect->query("INSERT INTO chat_user (user_id, user_name, user_login_status) VALUES ($this->user_id, '$this->user_name', 'Login')");
        }
    }
}
