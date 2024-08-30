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
        $resname = $_REQUEST['resName'];
        $roomNumber = $_REQUEST['roomNumber'];
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        

    //include database credentials 
    require_once("config.php");

    // Database connection
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

    //check if the query is successful
    if ($conn->connect_error) {
        die("<p class=\"error\">Connection to the database failed!</p>" . $conn->connect_error);
    }

    //query to check if the user exists in the database
    $sql = "SELECT * FROM user WHERE userName = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['access'] = "yes";
        header("Location: ../landing_page/landing_Page.html");
        exit();
    } else {

        //user already exists
        $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash the password
        $sql2 = "INSERT INTO user(userName, user_password, user_role, email)
             VALUES ('$username', '$hashed_password', 'student', '$email')";

        if ($conn->query($sql2) === TRUE) {
            
            //insert into the student table using the username
            $sql3 = "INSERT INTO student(f_Name, l_Name, resName, userName, room_number)
                     VALUES ('$fname', '$lname', '$resname', '$username', '$roomNumber')";
        
            if ($conn->query($sql3) === TRUE) {
                echo "<p class=\"success\">User and Student added successfully!</p>";
                header("Location: ../ticket_creation/ticketCreation.html");
                exit();
            } else {
                die("<p class=\"error\">Error adding student: " . $conn->error . "</p>");
            }
        } else {
            die("<p class=\"error\">Unable to add User! Error: " . $conn->error . "</p>");
        }
    }
    $conn ->close();
    }
    ?>
</body>

</html>