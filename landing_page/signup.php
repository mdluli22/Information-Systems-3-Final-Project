<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    
    // Get data from the form submission
    $fname = $_REQUEST['fname'];
    $lname = $_REQUEST['lname'];
    $email = $_REQUEST['email'];
    $resname = $_REQUEST['resName'];
    $roomNumber = $_REQUEST['roomNumber'];
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $hall = $_REQUEST['hall'];	
    
    // Include database credentials
    require_once("config.php");

    // Database connection
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("<p class=\"error\">Connection to the database failed!</p>" . $conn->connect_error);
    }

    // Query to check if the user exists in the database
    $sql = "SELECT * FROM user WHERE userName = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;  // Set the username in the session
        $_SESSION['hall'] = $hall;
        $_SESSION['resName'] = $resname;
        $_SESSION['access'] = "yes";
        header("Location: ../landing_page/landing_Page.html");
        exit();
    } else {
        // User doesn't already exist
        $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash the password
        $sql2 = "INSERT INTO user(userName, user_password, user_role, email)
             VALUES ('$username', '$hashed_password', 'student', '$email')";

        if ($conn->query($sql2) === TRUE) {
            // Inserting into the res table first
            $checkRes = "SELECT * FROM residence WHERE resName = '$resname' AND hall_name = '$hall'";
            $resResult = $conn->query($checkRes);

            if ($resResult->num_rows == 0) {
                $sql4 = "INSERT INTO residence (resName, hall_name) VALUES ('$resname', '$hall')";
                if ($conn->query($sql4) === TRUE) {
                    // Insert into the student table using the username
                    $sql3 = "INSERT INTO student(f_Name, l_Name, resName, userName, room_number)
                    VALUES ('$fname', '$lname', '$resname', '$username', '$roomNumber')";
                    if ($conn->query($sql3) === TRUE) {
                        echo "<p class=\"success\">User and Student added successfully!</p>";
                        header("Location: ticketCreationFinal.php");
                        exit();
                    } else {
                        // Display the SQL error for debugging
                        die("<p class=\"error\">Error adding student: " . $conn->error . " SQL: " . $sql3 . "</p>");
                    }
                } else {
                    die("<p class=\"error\">Error adding residence: " . $conn->error . " SQL: " . $sql4 . "</p>");
                }
            } else {
                echo "<p class=\"success\">Residence already exists, proceeding with student insert.</p>";
            }
        } else {
            die("<p class=\"error\">Error adding user: " . $conn->error . " SQL: " . $sql2 . "</p>");
        }
    }
    $conn->close();
    ?>
</body>
</html>
