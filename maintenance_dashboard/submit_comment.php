<?php
// Include database details from config.php file
require_once("../config.php");
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // attempt to make database connection
    $connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

    // Get the submitted data
    $ticketID = mysqli_real_escape_string($connection, $_POST['ticketID']);
    $houseName = mysqli_real_escape_string($connection, $_POST['house_name']);
    $comment_description = mysqli_real_escape_string($connection, $_POST['comment_description']);
    $userName = mysqli_real_escape_string($connection, $_POST['userID']); // Replace this with the actual user session value
    $page = mysqli_real_escape_string($connection, $_POST['page']); //the page the processor must go back to to return the data
    $currentDate = (new DateTime())->format('Y-m-d H:i:s'); // Automatically capture the current date

    // Check if connection was successful
    if ($connection->connect_error) {
        die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
    }

    // Insert the comment into the comment table
    $sql_insert = "INSERT INTO systemsurgeons.comment (ticketID, userName, comment_description, comment_date, soft_delete_comment) VALUES ('$ticketID', '$userName', '$comment_description', '$currentDate', false)";

    if ($connection->query($sql_insert) === TRUE) {
        // Redirect back to the ticket page

        if ($page == 'open') {
            header("Location: maintenance_opened_tickets.php?ticketID=$ticketID&house_name=$houseName");
            exit();}
        else if ($page == 'closed'){
            header("Location: maintenance_closed_tickets.php?ticketID=$ticketID&house_name=$houseName");
            exit();}
        else {
            echo "<p class='error'>Failed to find page to return to: " . $connection->error . "</p>";
        }
         
    } else {
        echo "<p class='error'>Failed to submit comment: " . $connection->error . "</p>";
    }

    // Close the database connection
    $connection->close();
}
?>
