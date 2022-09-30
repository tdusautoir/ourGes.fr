<?php

namespace MyApp;

use PDO;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        echo "Server Started\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection in $this->clients
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        global $db;

        $numberReceiver = count($this->clients) - 1;

        $data = json_decode($msg, true);

        //get promo id from his name
        $find_id_promo = $db->prepare("SELECT id FROM promotions WHERE name = :promotion_name");
        $find_id_promo->bindValue(':promotion_name', $data['promotion_name']);
        $find_id_promo->execute();
        $id_promo = $find_id_promo->fetch(PDO::FETCH_ASSOC);

        //add the msg in the chatroom
        $add_msg = $db->prepare('INSERT INTO chatrooms (id_promotion, user_id, msg) VALUES (:id_promotion, :user_id, :msg)');
        $add_msg->bindValue(':id_promotion', $id_promo['id']);
        $add_msg->bindValue(':user_id', $data['user_id']);
        $add_msg->bindValue(':msg', $data['msg']);
        $add_msg->execute();

        foreach ($this->clients as $client) {

            if ($from == $client) {
                $data['from'] = 'Me';
            } else {
                $data['from'] = 'test';
            }

            $client->send(json_encode($data));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
