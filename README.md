# Secure Client-Server Chat

SecureClientServerChat is a PHP-based chat application designed to ensure secure and authenticated communication between clients and the server. This project implements a secure communication protocol using WebSocket for real-time messaging, along with encryption and authentication mechanisms to protect data integrity and confidentiality. 

## Technical Details

The application is built with the following key components:
-	Backend: PHP with Ratchet library for WebSocket communication.
-	Frontend: HTML, CSS, and JavaScript for the user interface.
-	Database: MySQL for storing user credentials and encrypted messages.
-	Encryption: AES-256-CBC encryption for messages.
-	Authentication: Session-based authentication with user registration and login.

## Server-Side Code (server.php)

-	Establishes a WebSocket server.
-	Handles connections, messages, and disconnections.
-	Encrypts messages before sending them to clients.
-	Saves encrypted messages to the MySQL database.

## Client-Side Code (index.php)

-	Establishes a WebSocket connection to the server.
-	Sends and receives encrypted messages.
-	Decrypts received messages for display.

## User Authentication (login.php, register.php)

-	Handles user registration and login.
-	Stores user credentials securely using password hashing.
-	Manages user sessions.

## Requirements

-	PHP 7.4 or higher
-	Composer for dependency management
-	MySQL database server
-	Web server (e.g., Apache)
-	Web browser for accessing the application

## How to Run

-	Clone the repository
-	Install dependencies 
-	Set up the MySQL database
-	Start the WebSocket server: php server.php

## Encryption

-	Algorithm: AES-256-CBC
-	Process:  
       -	When a message is sent, it is encrypted using a randomly generated key and initialization vector (IV).
       -	The encrypted message, key, and IV are then base64 encoded and sent to the server.
       -	On the server side, the encrypted message is stored in the database.
       -	When a message is received, the client decrypts it using the provided key and IV.





## Authentication

-	Registration: Users register with a unique username and password. The password is hashed using PHP's password_hash() function before being stored in the database.
-	Login: During login, the user's password is verified using PHP's password_verify() function. Upon successful login, a session is created for the user.
-	Session Management: Sessions are used to maintain user authentication across different pages of the application.

## Conclusion

SecureClientServerChat provides a solution for secure and authenticated real-time communication. By leveraging encryption and authentication mechanisms, it ensures that messages remain confidential and users are properly authenticated. 