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
    public $users;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->promotions = [];
        $this->users = [];
        echo "Server Started\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection in $this->clients and $this->users
        $this->clients->attach($conn);
        $this->users[$conn->resourceId] = $conn;

        echo "\nNew connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numberReceiver = count($this->clients) - 1;

        $success = 0;

        $data = json_decode($msg, true);


        if (isset($data['command'])) {
            switch ($data['command']) {
                case "register_promotion":

                    //find promotion id from his name
                    $Chatroom = new \ChatRoom;
                    $Chatroom->setPromotion($data['promotion_name']);
                    $promotion_id = $Chatroom->find_promotion_by_name();

                    if (isset($promotion_id)) {
                        $this->promotions[$from->resourceId] = $promotion_id;
                    }

                    break;
                case "send_message":
                    //verifer la promotion de l'utilisateur selon son id 
                    //recuperer l'id de la promotion selon le nom de la promotion
                    //envoyer un message que pour les $this->promotions[$from->resourceId] = $promotion_id;
                    //envoyer le msg

                    //if promotion of the sender is define
                    if (isset($this->promotions[$from->resourceId])) {
                        //the promotion of the sender
                        $promotion_id = $this->promotions[$from->resourceId];


                        //check if the user_id is register to this promotion in db
                        $User = new \User;
                        $User->setUserId($data['user_id']);
                        $User->setPromotionId($promotion_id);

                        if ($User->is_in_promotion()) {


                            //add the msg in db
                            $Chatroom = new \ChatRoom;
                            $Chatroom->setPromotionId($promotion_id);
                            $Chatroom->setUserId($data['user_id']);
                            $Chatroom->setMessage($data['msg']);
                            $Chatroom->add_message();

                            //browse into all the already registered promotions
                            foreach ($this->promotions as $id => $promotion) {
                                if ($promotion == $promotion_id) {

                                    //for all the user in the same promotion as the sender
                                    if ($id == $from->resourceId) {
                                        //data is from me (the sender)
                                        $data['from'] = 'me';

                                        echo "\n";
                                        var_dump($this->users[$id]->resourceId);

                                        $this->users[$id]->send(json_encode($data));
                                        $success = 1;
                                    } else {
                                        //data is from anyone else
                                        $data['from'] = '';

                                        echo "\n";
                                        var_dump($this->users[$id]->resourceId);

                                        $this->users[$id]->send(json_encode($data));
                                        $success = 1;
                                    }
                                }
                            }
                        }
                    }

                    if (!$success) {
                        echo "\nAn error has occured";
                    } else {
                        echo "\nMessage envoyé";
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
