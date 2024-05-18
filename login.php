
<?php
session_start();
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE user_name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

       
        if (password_verify($password, $hashedPassword)) {
            
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $username;

            
            header("Location: index.php");
            exit();
        } else {
           
            echo "<script>alert('Incorrect username or password.'); window.location.replace('login.html');</script>";
            exit();
        }
    } else {
        
        echo "<script>alert('User not found.'); window.location.replace('login.html');</script>";
        exit();
    }

    $stmt->close();
}

$conn->close();
?>