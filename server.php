<?php
require __DIR__ . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        foreach ($this->clients as $client) {
            if ($client !== $from) {
                $encryptedMsg = $this->encryptMessage($msg);
                $encryptedData = json_decode($encryptedMsg, true);
                echo "Encrypted message: " . $encryptedData['message'] . "\n";
                $client->send($encryptedMsg);
            }
        }
    
        
        $conn = new mysqli("localhost", "root", "", "clientserverchat");
    
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        $this->saveMessage($conn, $from, $encryptedMsg);
        $conn->close();
    }
    
    private function saveMessage($conn, ConnectionInterface $from, $encryptedMsg) {
        $sender = $from->resourceId; 
    
        
        $encryptedData = json_decode($encryptedMsg, true);
        $message = $encryptedData['message'];
    
       
        $stmt = $conn->prepare("INSERT INTO messages (sender, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $sender, $message);
    
       
        if (!$stmt->execute()) {
            echo "Error saving message: " . $conn->error;
        }
    
        $stmt->close();
    }
    
    
    

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    private function encryptMessage($msg) {
        $key = random_bytes(32);
        $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($msg, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        return json_encode(['key' => base64_encode($key), 'iv' => base64_encode($iv), 'message' => base64_encode($encrypted)]);
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);

echo "Server running at 0.0.0.0:8080\n";

$server->run();
