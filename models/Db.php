<?php

class Db
{
    function connect()
    {
        $connect = new PDO("mysql:host=127.0.0.1; dbname=chat", "root", "root");

        return $connect;
    }
}
