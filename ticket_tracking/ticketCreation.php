<?php
require_once("secure.php");

if (isset($_SESSION['username'])) {
    $userID = $_SESSION['username'];
    // $resName = $_SESSION['resName'];
} else {
    die("User is not logged in.");
}

if (isset($_SESSION['studentHall'])) {
    $hall = $_SESSION['studentHall'];
} else {
    $hall = "Default Hall";  // Provide a default value or handle the missing data case
}

require_once("config.php");

// database connection
$connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// Check if connection was successful
if ($connection -> connect_error) {
    die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
}

// Retrieve the student's resName from the database
$sql1 = "SELECT resName FROM student WHERE userName = '$userID'
        UNION
        SELECT resName FROM house_warden WHERE userName = '$userID';";

$result1 = $connection->query($sql1);

if($result1->num_rows > 0) {
    $row = $result1->fetch_assoc();
    $resName = $row['resName'];  // Assign the resName
} else {
    $resName = "Unknown Residence";  // Handle the case if no resName is found
}

$fault = $_REQUEST['fault-category'];
$description = $_REQUEST['description'];
$priority = $_REQUEST['priority'];

$ticket_status = "Opened";
$ticketDate = date("Y-m-d H:i:s");
// Default values for the new columns
$s_seen = 0;
$w_seen = 0;
$h_seen = 0;
$m_seen = 0;
$endDate = NULL;  // Set endDate to NULL for now

// Insert the ticket into the database
$sql = "INSERT INTO ticket (userName, resName, ticket_status, ticketDate, ticket_description, category, priority, s_seen, w_seen, h_seen, m_seen, endDate) 
        VALUES ('$userID', '$resName', '$ticket_status', '$ticketDate', '$description', '$fault', '$priority', '$s_seen', '$w_seen', '$h_seen', '$m_seen', NULL);";
$result = $connection->query($sql);

if ($result === TRUE) {
    // Ticket creation successful, get the inserted ticket ID
    $ticketValue = $connection->insert_id;
    
    // Process file uploads
    $countFiles = count($_FILES['picture']['name']); // Get the number of uploaded files
    for ($i=0; $i < $countFiles; $i++) {
        $picture = time() . "_" . basename($_FILES['picture']['name'][$i]);
        $destination = "../pictures/" . $picture;

        // Move uploaded file to the destination directory
        if (move_uploaded_file($_FILES['picture']['tmp_name'][$i], $destination)) {
            // Insert the picture into the photos table
            $uploadPicture = "INSERT INTO photos (ticketID, photo) VALUES ('$ticketValue', '$picture');";
            if ($connection->query($uploadPicture) !== TRUE) {
                echo "<p class=\"error\">Failed to upload picture: " . $connection->error . "</p>";
            }
        } else {
            echo "<p class=\"error\">Error uploading file " . basename($_FILES['picture']['name'][$i]) . ".</p>";
        }
    }

    // After processing all the files, redirect to the final page
    $_SESSION['ticketID'] = $ticketValue; // Store ticket ID in session
    header("Location: ticketCreationFinal.php?success=1&ticketID=$ticketValue");
    exit();  // Make sure to exit after the redirect

} else {
    echo "<p class=\"error\">Failed to insert the ticket: " . $connection->error . "</p>";
}

$connection->close();
?>