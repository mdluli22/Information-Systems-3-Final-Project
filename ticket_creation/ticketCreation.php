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

        //for fault category
        if (isset($_REQUEST['submit'])) {
             //for the res name on top
            $resName = $_REQUEST['residence'];
            $studentID = $_REQUEST['username'];
            $fault = $_REQUEST['fault-category'];
            $description = $_REQUEST['description']; 

        // attempt to make database connection
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check if connection was successful
        if ($conn -> connect_error) {
            die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
        }

        $sql = "SELECT resName FROM student WHERE userName = $studentID ";
        $result = $conn->query($sql);

        // $sql1 = "SELECT resName FROM student WHERE studentID = $studentID ";
        // $stmt1 = $conn->prepare($sql2);
        // $stmt1-> bind_param("i", $studentID); // Bind the student ID as an integer
        // $stmt1-> execute();
        // $result = $stmt->get_result();

        // Check if query successfull
        if ($result === FALSE) {
            die("<p class=\"error\">Query was Unsuccessful!</p>");
        }

        $sql3 = "INSERT INTO ticket (category) VALUES (?)";
        $stmt2 = $conn -> prepare($sql3);
        $stmt2 ->bind_param("s", $fault);

        //echo $category;


        //design an error pop up
        if ($stmt->execute()) {
            echo "<p class=\"success\">Fault category successfully inserted into the database!</p>";
        } else {
            echo "<p class=\"error\">Failed to insert fault category!</p>";
        }
        
        $sql3 = "INSERT INTO ticket (description) VALUES (?)";
        $stmt3 = $connection->prepare($sql);
        $stmt3 -> bind_param("s", $description);
        
        // Execute and check success
        if ($stmt3->execute()) {
            echo "<p class=\"success\">Description successfully inserted into the database!</p>";
        } else {
            echo "<p class=\"error\">Failed to insert description!</p>";
        }

        // Close statements and connection
        $stmt1->close();
        $stmt2->close();
        $stmt3->close();
        $conn->close();
    }
    ?>
</body>
</html>