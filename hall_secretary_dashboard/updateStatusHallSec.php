<?php 
// require_once("../config.php");

// // Attempt to make database connection
// $connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// $ticketID = intval($_REQUEST['ticket_ID']);
// $studentName = $connection->real_escape_string($_REQUEST['student_name']);
// $roomNum = $connection->real_escape_string($_REQUEST['student_room_num']);

// // Check if connection was successful
// if ($connection->connect_error) {
//     die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
// }

// // Prepare and bind the SQL statement
// $sql_update = $connection->prepare("UPDATE ticket SET ticket_status = ? WHERE ticketID = ?");
// $status = 'Rejected';
// $sql_update->bind_param("si", $status, $ticketID);

// if ($sql_update->execute()) {
//     header("Location: hall_secretary_open_tickets.php?ticket_ID=$ticketID&reject-request&student_name=$studentName&student_room_num=$roomNum");
//     exit();
// } else {
//     echo "<p class=\"error\">Update failed: " . $sql_update->error . "</p>";
// }

// $sql_update->close();
// $connection->close();
?>
<?php 
    require_once("../config.php");

    // Attempt to make database connection
    $connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

    $ticketID = intval($_REQUEST['ticket_ID']);
    $studentName = $connection->real_escape_string($_REQUEST['student_name']);
    $roomNum = $connection->real_escape_string($_REQUEST['student_room_num']);
    $status = '';

    if (isset($_REQUEST['requisition-request'])) {
        $status = 'Requisitioned';
        $_SESSION['requisitioned_or_closed'] = 'requisitioned';
    } elseif (isset($_REQUEST['close-request'])) {
        $status = 'Closed';
        $_SESSION['requisitioned_or_closed'] = 'closed';
    }

    // Store student details in the session
    $_SESSION['student_name'] = $studentName;
    $_SESSION['student_room_num'] = $roomNum;

    // Check if connection was successful
    if ($connection->connect_error) {
        die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
    }

    // Prepare and bind the SQL statement
    $sql_update = $connection->prepare("UPDATE ticket SET ticket_status = ? WHERE ticketID = ?");
    $sql_update->bind_param("si", $status, $ticketID);

    // IMPORTANT TO SET close-request - close-request. NBNBNBNBNBNB********* - LIKEWISE SIMILARLY IN UpdateStatus FOR HOUSE_WARDEN

    if ($sql_update->execute()) {
        header("Location: hall_secretary_open_tickets.php?close-request&house_name");
        exit();
    } else {
        echo "<p class=\"error\">Update failed: " . $sql_update->error . "</p>";
    }

    $sql_update->close();
    $connection->close();
?>
