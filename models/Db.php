<?php

class Db
{
    function connect()
    {
        $connect = new PDO("mysql:host=localhost; dbname=chat", "root", "root");

        return $connect;
    }
}
