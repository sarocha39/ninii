<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Port Check</title>
<style> 
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f0f0;
    }
    .container {
        max-width: 600px;
        margin: 50px auto;
        background-color: #fff;
        padding: 60px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
        text-align: center;
    }
    form {
        margin-bottom: 20px;
    }
    label {
        display: block;
        margin-bottom: 5px;
    }
    input[type="text"],
    select,
    input[type="submit"] {
        width: calc(100% - 22px); 
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box; 
    }
    input[type="submit"] {
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background-color: #0056b3;
    }
    p {
        padding: 10px;
        border-radius: 5px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
</style>
</head>
<body>
<div class="container">
    <h1>Check port</h1>
    <form action="" method="post">
        <label for="ip_address">IP Address</label>
        <input type="text" id="ip_address" name="ip_address" required><br><br>
        <label for="port_number">Port Number</label>
        <select id="port_number" name="port_number" required>
            <option value="25">25</option>
            <option value="8808">8808</option>
            <option value="1080">1080</option>
            <option value="2222">2222</option>
            <!-- Add more ports as needed -->
        </select><br><br>
        <input type="submit" value="Check">
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ipAddress = $_POST['ip_address'];
        $portNumber = $_POST['port_number'];
        

        $connection = ssh2_connect($ipAddress, $portNumber);
        if (!$connection) {
            echo "<p style='color: red;'>Failed to connect to the remote host</p>";
        } else {
            echo "<p style='color: green;'>Connected to the remote host</p>";

            if (!ssh2_auth_password($connection, 'root', 'root')) {
                die("<p style='color: red;'>Failed to authenticate with the remote host</p>");
            }

            $command = "echo >/dev/tcp/$ipAddress/$portNumber && echo \"Port $portNumber is open\" || echo \"Port $portNumber is closed\"";
            $stream = ssh2_exec($connection, $command);

            stream_set_blocking($stream, true);
            $output = stream_get_contents($stream);

            ssh2_disconnect($connection);
            echo "<p>$output</p>";
        }
    }
    ?>
</div>
</body>
</html>


