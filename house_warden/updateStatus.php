<?php 
require_once("../config.php");

// Attempt to make database connection
$connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

$ticketID = intval($_REQUEST['ticket_ID']);
$studentName = $connection->real_escape_string($_REQUEST['student_name']);
$roomNum = $connection->real_escape_string($_REQUEST['student_room_num']);

// Check if connection was successful
if ($connection->connect_error) {
    die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
}

// Prepare and bind the SQL statement
$sql_update = $connection->prepare("UPDATE ticket SET ticket_status = ? WHERE ticketID = ?");
$status = 'Rejected';
$sql_update->bind_param("si", $status, $ticketID);

// IMPORTANT TO SET reject-request - &reject-request NBNBNBNBNBNB*********
if ($sql_update->execute()) {
    header("Location: house_warden_open_tickets.php?ticket_ID=$ticketID&reject-request&student_name=$studentName&student_room_num=$roomNum");
    exit();
} else {
    echo "<p class=\"error\">Update failed: " . $sql_update->error . "</p>";
}

$sql_update->close();
$connection->close();
?>
