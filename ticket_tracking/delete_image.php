<?php
// Include database details from config.php file
require_once("config.php");

// Check if the request is POST and photoID is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['photoID'])) {
    $photoID = $_POST['photoID'];
    $ticketID = $_POST['ticketID']; // To redirect back to the ticket details
    $page = $_POST['page']; //the page the processor must go back to to return the data

    // Attempt to make a database connection
    $connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

    // Check if the connection was successful
    if ($connection->connect_error) {
        die("<p class='error'>Connection failed: " . $connection->connect_error . "</p>");
    }

    // Fetch the photo's filename to delete it from the server
    $sql_get_photo = "SELECT photo FROM systemsurgeons.photos WHERE photoID = '$photoID'";
    $result = $connection->query($sql_get_photo);

    if ($result->num_rows > 0) {
        $photo = $result->fetch_assoc();
        $photo_path = "../landing_page/pictures/" . $photo['photo'];

        // Delete the image file from the server
        if (file_exists($photo_path)) {
            unlink($photo_path);
        }

        // Delete the photo record from the database
        $sql_delete = "DELETE FROM systemsurgeons.photos WHERE photoID = '$photoID'";
        if ($connection->query($sql_delete) === TRUE) {
            if ($page == 'all') {
                header("Location: ticket_tracking_all.php?ticketID=$ticketID");
                exit();}
            else if ($page == 'open') {
                header("Location: ticket_tracking_open.php?ticketID=$ticketID");
                exit();}
            else if ($page == 'closed') {
                header("Location: ticket_tracking_closed.php?ticketID=$ticketID");
                exit();}
            else {
                echo "<p class='error'>Failed to find page to return to: " . $connection->error . "</p>";
            }
        } else {
            echo "<p class='error'>Failed to delete photo: " . $connection->error . "</p>";
        }
    } else {
        echo "<p class='error'>Photo not found in the database.</p>";
    }

    // Close the connection
    $connection->close();
} else {
    echo "<p class='error'>Invalid request.</p>";
}
?>
