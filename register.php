<?php
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    
    $userId = uniqid();

    
    $stmt = $conn->prepare("INSERT INTO users (user_id, full_name, user_name, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $userId, $fullname, $username, $hashedPassword);

    try {
        
        $stmt->execute();
        header("Location: login.html");
        exit();
    } catch (mysqli_sql_exception $e) {
        
        if ($e->getCode() == 1062) {
            
            echo "<script>alert('Username already exists. Please choose a different username.'); window.location.replace('login.html');</script>";
        } else {
           
            echo "Error: " . $e->getMessage();
        }
    }

    $stmt->close();
}

$conn->close();
?>
