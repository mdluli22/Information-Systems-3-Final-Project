<?php
require_once("secure.php");

if (isset($_SESSION['username'])) {
    // echo 'Session Username: ' . $_SESSION['username'];
    $studentID = $_SESSION['username'];
    $hall = $_SESSION['hall'];
    //$resName = $_SESSION['resName'];
}else {
    die("User is not logged in.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Requisition Form</title>
    <link rel="icon" type="image/x-icon" href="pictures/resque-logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />    
    <link rel="stylesheet" href="ticketCreationStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="scriptTC.js "></script>
    <!-- Link to the FontAwesome library for icons -->
    <script src="https://kit.fontawesome.com/ddbf4d6190.js" crossorigin="anonymous"></script>
    <style>
        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>
<?php
    //session_start();
    
    // include database details from config.php file
    require_once("config.php");
    
    // database connection
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

    // Check if connection was successful
    if ($conn -> connect_error) {
        die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
    }
        
    //for the res name on top
    $sql1 = "SELECT resName FROM student WHERE userName = '$studentID';";
    $result = $conn->query($sql1);

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $resName = $row['resName'];
        //echo "Residence: " . $resName;
    } else {
        $resName = "Residence not found";
    }
        $fault = $_REQUEST['fault-category'];
        $description = $_REQUEST['description'];
        $priority = $_REQUEST['priority'];
        
        //$picture = time() . $_FILES['picture']['name'];
        
        $ticket_status = "Pending";
        $ticketDate = date("Y-m-d H:i:s");

        //since no rating is provided, we set it to NULL for now
        $rating = NULL;

        // $destination = "pictures/" . $picture;
        // move_uploaded_file($_FILES['picture']['tmp_name'], $destination);


    $sql = "INSERT INTO ticket (userName, resName, ticket_status, ticketDate, ticket_description, category, rating, priority) 
                VALUES ('$studentID', '$resName', '$ticket_status', '$ticketDate', '$description', '$fault', NULL, '$priority');";
    $result = $conn->query($sql);

    //design an error pop up
    if ($result === true) {
        echo "<p class=\"success\">Fault category successfully inserted into the database!</p>";
        //$ticketValue = "SELECT ticketID FROM ticket";
        $ticketValue = $conn->insert_id;

        $countFiles = count($_FILES['picture']['name']);   // Get the number of uploaded files 
        for ($i=0; $i < $countFiles; $i++) { 
            $picture = time() . "_" . basename($_FILES['picture']['name'][$i]);
            $destination = "pictures/" . $picture;

            if (move_uploaded_file($_FILES['picture']['tmp_name'][$i], $destination)) {
                //for uploading the pictures
                $uploadPicture = "INSERT INTO photos (ticketID, photo) VALUES ('$ticketValue', '$picture');";
                //$results = $conn->query($uploadPicture);

                if ($conn->query($uploadPicture) === TRUE) {
                    echo "<p class=\"success\">Picture " . basename($_FILES['picture']['name'][$i]) . " uploaded successfully!</p>";
                } else {
                    echo "<p class=\"error\">Failed to upload picture: " . $conn->error . "</p>";
                }
            } else {
                echo "<p class=\"error\">Error uploading file " . basename($_FILES['picture']['name'][$i]) . ".</p>";
            }
        }
        
    } else {
        echo "<p class=\"error\">Failed to insert the ticket: " . $conn->error . "</p>";
    }
    $conn->close();
// }
?>
</body>
</html>