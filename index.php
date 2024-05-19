<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <link rel="stylesheet" href="Style/indexx.css">
</head>
<body>
<div class="logout-container">
        <form method="post">
            <button type="submit" name="logout" class="logout-button">Logout</button>
        </form>
    </div>

    <div class="chat-box">
        <div id="messages-container">
            <ul id="messages"></ul>
        </div>
        <div id="message-input">
            <input type="text" id="message" placeholder="Enter message">
            <button id="send-button">Send</button>
           
            
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <script>
        var username = "<?php echo $_SESSION['username']; ?>";
        var conn = new WebSocket('ws://localhost:8080');
        conn.onopen = function(e) {
            console.log("Connection established!");
        };

        conn.onmessage = function(e) {
            var data = JSON.parse(e.data);
            var key = CryptoJS.enc.Base64.parse(data.key);
            var iv = CryptoJS.enc.Base64.parse(data.iv);
            var encrypted = CryptoJS.enc.Base64.parse(data.message);

            var decrypted = CryptoJS.AES.decrypt({ ciphertext: encrypted }, key, { iv: iv });
            var message = decrypted.toString(CryptoJS.enc.Utf8);

            var messagesContainer = document.getElementById('messages');
            var messageElement = document.createElement('li');
            messageElement.textContent = username + ": " + JSON.parse(message).message; 
            messageElement.classList.add('message');
            messagesContainer.appendChild(messageElement);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        };

        function sendMessage() {
            var message = document.getElementById('message').value;
            var data = { message: message };
            conn.send(JSON.stringify(data));
            document.getElementById('message').value = '';

            var messagesContainer = document.getElementById('messages');
            var messageElement = document.createElement('li');
            messageElement.textContent = "Me: " + message;
            messageElement.classList.add('message', 'my-message');
            messagesContainer.appendChild(messageElement);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        document.getElementById('send-button').addEventListener('click', sendMessage);

        document.getElementById('message').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    </script>

</body>
</html>

