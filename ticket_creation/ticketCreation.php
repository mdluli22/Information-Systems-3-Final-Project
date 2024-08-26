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
        // include database details from config.php file
        require_once("config.php");

        $resName = $_REQUEST['residence'];
        $studentID =$_REQUEST['username'];
        // attempt to make database connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check if connection was successful
        if ($conn -> connect_error) {
            die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
        }

        // query instructions
        // Prepare and execute the query
        $sql1 = "SELECT resName FROM student WHERE studentID = ?";
        $stmt = $conn->prepare($sql1);
        $stmt->bind_param("i", $studentID); // Bind the student ID as an integer
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if query successfull
        if ($result === FALSE) {
            die("<p class=\"error\">Query was Unsuccessful!</p>");
        }
        
        // close connection
        $conn->close();
    ?>
</body>
</html>