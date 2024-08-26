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

        //for the res name on top
        $resName = $_REQUEST['residence'];
        $studentID =$_REQUEST['username'];

        //for fault category
        if (isset($_REQUEST['submit'])) {
            $fault = $_REQUEST['fault-category'];
        

        // attempt to make database connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check if connection was successful
        if ($conn -> connect_error) {
            die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
        }

        // query instructions
        // Prepare and execute the query
        //for the res name on top
        $sql1 = "SELECT resName FROM student WHERE studentID = $studentID ";
        $stmt = $conn->prepare($sql1);
        $stmt->bind_param("i", $studentID); // Bind the student ID as an integer
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if query successfull
        if ($result === FALSE) {
            die("<p class=\"error\">Query was Unsuccessful!</p>");
        }

        $sql2 = "INSERT INTO fault_categories (category) VALUES (?)";
        $stmt1 = $conn -> prepare($sql2);
        $stml -> bind_parm("s", $fault);

        //design an error pop up
        if ($stmt->execute()) {
            echo "<p class=\"success\">Fault category successfully inserted into the database!</p>";
        } else {
            echo "<p class=\"error\">Failed to insert fault category!</p>";
        }
        
        
        // close connection
        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>