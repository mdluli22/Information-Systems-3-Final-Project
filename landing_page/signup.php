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
    
    // come from a form submission
    if (isset($_REQUEST['submit'])){
        $fname = $_REQUEST['fname'];
        $lname = $_REQUEST['lname'];
        $email = $_REQUEST['email'];
        $roomNumber = $_REQUEST['roomNumber'];
        $username = $_REQUEST['username'];
        $password = $_REQUEST['pass'];
        

    //include database credentials 
    require_once("config.php");

    // Database connection
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

    //check if the query is successful
    if ($conn->connect_error) {
        die("<p class=\"error\">Connection to the database failed!</p>" . $conn->connect_error);
    }

    //query to check if the user exists in the database
    $sql = "SELECT * FROM user WHERE userName = '$username' AND user_password = '$password'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $_SESSION['access'] = "yes";
        header("Location: ../landing_page/landing_Page.html");
        exit();
    } else {
        // Password is incorrect
        $sql2 = "INSERT INTO user(userName, user_password, user_role, email)
             VALUES ('$username', '$password', 'student', '$email')";
        $result = $conn->query($sql2);

        if ($result === FALSE) {
            die("<p class=\"error\">Unable to add User! Error: " . $conn->error . "</p>");
        } else {
            echo "<p class=\"success\">User added successfully!</p>";
            header("Location: ../ticket_creation/ticketCreation.html"); // Redirect to the next page
            exit(); // Ensure the script stops executing after redirection
    }
    }
    
    //issue query instructions
    $sql2 = "INSERT INTO user(userName, user_password, user_role, email)
            VALUE('$username', '$password', 'student', '$email')";
            // "INSERT INTO student(S_username, f_Name,l_Name)
            // VALUE ('$username', '$fname', '$lname')";
    $result = $conn->query($sql2);

    //check if the query is successful 
    if ($result === FALSE){
        die("<p class= \"error\" >Unable to add User!</p>");
    } else {
        echo "<p class=\"success\">User added successfully!</p>";
    }
}

    // Check if the username already exists
    // $stmt = $mysqli->prepare("SELECT userName FROM users WHERE userName = ?");
    // $stmt->bind_param("s", $username);
    // $stmt->execute();
    // $stmt->store_result();

    // if ($stmt->num_rows > 0) {
    //     // Username already exists
    //     echo "Username already taken!";
    // } else {
    //     // Username is available, proceed to register the user
    //     $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash the password

        // Insert the new user into the database
        // $stmt = $mysqli->prepare("INSERT INTO users (userName, user_password, email)
        //     VALUE('$username', '$password', '$email')";
        //     "INSERT INTO student(S_username, f_name,l_name)
        //     VALUE ('$username', '$fname', '$lname')";);
        // $stmt->bind_param("ss", $input_username, $hashed_password);

    //     if ($stmt->execute()) {
    //         echo "Registration successful! You can now log in.";
    //     } else {
    //         echo "Error: Could not register user.";
    //     }
    // }

    // $stmt->close();
    // $mysqli->close();
    //close the connection to the database
    $conn ->close();
    ?>
</body>

</html>