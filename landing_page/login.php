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

    $sql = "SELECT * FROM user WHERE userName = '$username' AND user_password = '$password'";
    $result = $conn->query($sql);

    if ($result === FALSE) {
        die("<p class=\"error\">Unable to retrieve data!</p>");
    }

    // Check if the user exists in the database
    if ($result->num_rows == 1) {
        $_SESSION['access'] = "yes";
        header("Location: ../ticket_creation/ticketCreation.html");
    } else {
        // Password is incorrect
        header("Location: landing.html");
    }

    // Close connection to the database
    $conn->close();
    ?>
</body>

</html>