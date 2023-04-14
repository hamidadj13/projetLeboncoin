<?php

require_once("../");

class MessageController
{

    public function sendMessage($senderId, $receiverId, $content, $annonceId)
    {
        $message = new Message();
        $message->setSenderId($senderId);
        $message->setReceiverId($receiverId);
        $message->setContent($content);
        $message->setAnnonceId($annonceId);
        $message->setDeliveredTime(date("Y-m-d H:i:s"));

        // Connexion à la base de données
        try {
            $db = new PDO("mysql:host=localhost;dbname=leboncoin", 'root', '');
            // Configuration des options PDO
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            // Erreur de connexion à la base de données
            echo "Erreur : " . $e->getMessage();
        }
    }
}

?>