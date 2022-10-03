<?php

namespace MyApp;

use PDO;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require dirname(__DIR__) . "/models/User.php";
require dirname(__DIR__) . "/models/ChatRoom.php";

class Chat implements MessageComponentInterface
{
    protected $clients;
    public $promotions;

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
        $numberReceiver = count($this->clients) - 1;

        $data = json_decode($msg, true);

        if ($data['command']) {
            switch ($data['command']) {
                case "register_promotion":
                    $this->promotions[$from->resourceId] = $data['promotion'];
                    break;
                case "send_message":
                    //envoyer un message que pour les $this->promotions[$from->resourceId] = $data['promotion'];
                    //ensuite verifier si l'id de la personne est dans cette promotion (bdd)
                    //envoyer le msg

                    //find promotion id from his name
                    $Chatroom = new \ChatRoom;
                    $Chatroom->setPromotion($data['promotion_name']);
                    $promotion_id = $Chatroom->find_promotion_by_name();


                    $Chatroom->setPromotionId($promotion_id);
                    $Chatroom->setUserId($data['user_id']);
                    $Chatroom->setMessage($data['msg']);
                    $Chatroom->add_message();


                    foreach ($this->clients as $client) {

                        if ($from == $client) {
                            $data['from'] = 'Me';
                        } else {
                            $data['from'] = 'test';
                        }

                        $client->send(json_encode($data));
                    }
                    break;
            }
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
