<?php 
    require_once("../config.php");

        $ticketID = $_REQUEST['ticket_ID'];
        $houseName = $_REQUEST['house_name'];
        // attempt to make database connection
        $connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check if connection was successful
        if ($connection->connect_error) {
            die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
        }

        

        $sql_update = "update ticket set ticket_status = 'Resolved', m_seen = 0, s_seen = 0, h_seen = 0 where ticketID = $ticketID; ";

        if ($connection->query($sql_update) === TRUE) {
            header("Location: maintenance_opened_tickets.php?ticket_ID=$ticketID&house_name=$houseName");
            exit();
        }
        else{
            echo "update went wrong";
        }
?>
