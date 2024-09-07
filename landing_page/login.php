<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
</head>

<body>
    <style>
        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
    <?php
    // Start the session
    session_start();

    // Get the values from the form
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];

    // Include database credentials
    require_once("config.php");

    // Make a connection to the database
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

    // Check if the connection is successful
    if ($conn->connect_error) {
        die("<p class=\"error\">Connection to the database failed!</p>");
    }

    // Query to select the user based on the username
    $sql = "SELECT user_password FROM user WHERE userName = '$username'";
    $result = $conn->query($sql);

    if ($result === FALSE) {
        die("<p class=\"error\">Unable to retrieve data!</p>");
    }

    // Check if the user exists in the database
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['user_password'];

        // Verify the entered password against the hashed password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;  // Set the username in the session
            $_SESSION['hall'] = $hall;
            $_SESSION['resName'] = $resname;
            $_SESSION['access'] = "yes";
            header("Location: ticketCreationFinal.php");
            exit();
        } else {
            echo "<p class=\"error\">Password is incorrect!</p>";
        }
    } else {
        // Username doesn't exist
        echo "<p class=\"error\">Username does not exist!</p>";
    }

    // Close connection to the database
    $conn->close();
    ?>
</body>

</html>