<?php

//ChatUser.php

class ChatUser
{
    private $user_id;
    private $user_name;
    private $user_login_status;
    public $connect;

    public function __construct()
    {
        require_once('Database_connection.php');

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

    function update_user_login_data()
    {
        $query = "
		UPDATE chat_user
		SET user_login_status = :user_login_status 
		WHERE user_id = :user_id
		";

        $statement = $this->connect->prepare($query);

        $statement->bindParam(':user_login_status', $this->user_login_status);

        $statement->bindParam(':user_id', $this->user_id);

        if ($statement->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function get_user_data_by_id()
    {
        $query = "
		SELECT * FROM chat_user_table 
		WHERE user_id = :user_id";

        $statement = $this->connect->prepare($query);

        $statement->bindParam(':user_id', $this->user_id);

        try {
            if ($statement->execute()) {
                $user_data = $statement->fetch(PDO::FETCH_ASSOC);
            } else {
                $user_data = array();
            }
        } catch (Exception $error) {
            echo $error->getMessage();
        }
        return $user_data;
    }

    function get_user_all_data()
    {
        $query = "
		SELECT * FROM chat_user_table 
		";

        $statement = $this->connect->prepare($query);

        $statement->execute();

        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
}
