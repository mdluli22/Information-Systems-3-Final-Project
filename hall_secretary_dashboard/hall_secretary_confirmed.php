<?php
    require_once("secure.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Secretary Dashboard</title>
    <link rel="icon" type="image/x-icon" href="pictures/resque-logo.png">
    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="hall_secretary_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
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

        $hall_sec_userName = $_SESSION['username'];
        
        // include database details from config.php file
        require_once("config.php");

        // Attempt to make database connection
        $connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check if connection was successful
        if ($connection->connect_error) {
            die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
        }

        // Variable to display requisition or closure feedback, stored in session
        $requisitioned_or_closed = $_SESSION['requisitioned_or_closed'] ?? null;
        $student_name = $_SESSION['student_name'] ?? null;
        $student_room_num = $_SESSION['student_room_num'] ?? null;


        // Remove REQUISITIONED requests from CONFIRMED requests list
        // These requests are now REQUISITIONED - they will go to maintenance staff's OPENED page
        if (isset($_REQUEST['requisition-request'])) {
            $ticketID = intval($_REQUEST['ticketID']);
            $student_name = $_REQUEST['student_name'];
            $student_room_num = $_REQUEST['student_room_num'];
            $requisitioned_or_closed = 'requisitioned';
        
            // Store in session before redirecting
            $_SESSION['requisitioned_or_closed'] = $requisitioned_or_closed;
            $_SESSION['student_name'] = $student_name;
            $_SESSION['student_room_num'] = $student_room_num;
        
            // Update ticket status to 'Requisitioned'
            $update_request = "UPDATE ticket SET ticket_status = 'Requisitioned' WHERE ticketID = $ticketID";
            $update_request_result = $connection->query($update_request);
        
            // Check if the update was successful
            if ($update_request_result === FALSE) {
                die("<p class=\"error\">Ticket requisition was unsuccessful!</p>");
            }
        
            // Redirect to prevent form resubmission
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        }
        
        // Handle ticket closure
        if (isset($_REQUEST['close-request'])) {
            $ticketID = intval($_REQUEST['ticket_ID']);
            $student_name = $_REQUEST['student_name'];
            $student_room_num = $_REQUEST['student_room_num'];

            $requisitioned_or_closed = 'closed';

            // Store in session before redirecting
            $_SESSION['requisitioned_or_closed'] = $requisitioned_or_closed;
            $_SESSION['student_name'] = $student_name;
            $_SESSION['student_room_num'] = $student_room_num;

            // Update ticket status to 'Closed'
            $close_request = "UPDATE ticket SET ticket_status = 'Closed' WHERE ticketID = $ticketID";
            $close_request_result = $connection->query($close_request);

            // Check if the update was successful
            if ($close_request_result === FALSE) {
                die("<p class=\"error\">Ticket closure was unsuccessful!</p>");
            }

            // Redirect to prevent form resubmission
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        }

        // Retrieve hall secretary information
        $hall_name_sql = 
        "SELECT *, hall_name FROM hall_secretary WHERE HS_userName = '$hall_sec_userName';";
        $hall_name_result = $connection->query($hall_name_sql);
        
        // get ticket information
        $ticket_sql = "SELECT * FROM ticket;";
        $ticket_result = $connection->query($ticket_sql);

        // Get res names of hall overseen by the hall secretary
        $residences =
            "SELECT DISTINCT concat(f_Name, ' ', l_name) AS 'hall_secretary_name',
                resName AS 'residences', f_Name as 'firstName', CONCAT(LEFT(f_Name, 1), LEFT(l_Name, 1)) AS initials
                FROM residence JOIN hall_secretary ON hall_secretary.hall_name = residence.hall_name
                WHERE hall_secretary.HS_userName = '$hall_sec_userName';";
        $residences_result = $connection->query($residences);

        // Check if the query was successful
        if ($ticket_result === FALSE || !$hall_name_result || !$residences_result) {
            die("<p class=\"error\">Query was Unsuccessful!</p>");
        }

        // Get student information and initials
        $residence = $residences_result->fetch_assoc();

        // Get hall name of hall_sec
        $_SESSION['hall_name'] = $hall_name_result->fetch_assoc()['hall_name'];

        // Get hall_sec name/details
        $_SESSION['firstName'] = $residence['firstName'];
        // $_SESSION['firstName'] = ;
    ?>
    <div class="container">
        <!-- Sidebar section for navigation -->
        <?php require_once("sidebarHallSec.php"); ?>

        <!-- Main content area -->
        <main class="content">
        <header class="page-header">
            <div class="text-container">
                <!-- Welcome message -->
                <h1>Welcome, <span class="username"><?php echo $_SESSION['firstName']; ?></span></h1>
                <p>Access & Manage maintenance requisitions efficiently.</p>
            </div>
            <div class="logo-container">
                <img src="../landing_page/pictures/fake logo(1).png" alt="Logo">
            </div>
        </header>

            <!-- House selection links -->
            <nav class="houses">
                <?php
                    $defaulthouse = '';
                    $active = 0;
                    while ($residence = $residences_result->fetch_assoc()) {
                        
                        if ($active == 0) {
                            $active++;
                            $defaulthouse = $residence['residences'];
                        }

                        $activeHouse = isset($_REQUEST['house_name']) ? $_REQUEST['house_name'] : $defaulthouse;
                        $isActive = ($residence['residences'] === $activeHouse) ? 'active' : '';
                        echo "<a href='hall_secretary_closed_tickets.php?house_name={$residence['residences']}' class='house-link {$isActive}'>{$residence['residences']}</a>";
                    }
                ?>
            </nav>

            <!-- Ticket table section -->
            <section class="ticket-table"> <!--scrollbar">-->
                <table>
                    <thead>
                        <!-- Table headers -->
                        <tr>
                            <th>Ticket Number</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Priority</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- populate dashboard board with tickets from database -->
                        <?php
                            if(isset($_REQUEST['house_name'])){
                                $housename = $_REQUEST['house_name'];
                                // get information of Reject and Completed
                                $ticket_sql = "SELECT * FROM ticket WHERE ticket_status = 'Confirmed' AND resName = '$housename' ORDER BY ticketID DESC;";
                                $ticket_result = $connection->query($ticket_sql);
                            }
                            else{
                                $ticket_sql = "SELECT * FROM ticket WHERE ticket_status = 'Confirmed' AND resName = '$defaulthouse' ORDER BY ticketID DESC;";
                                $ticket_result = $connection->query($ticket_sql);
                            }

                            if ($ticket_result === FALSE) {
                                die("<p class=\"error\">Query was Unsuccessful!</p>");
                            }

                            if ($ticket_result->num_rows > 0) {
                                while ($row = $ticket_result->fetch_assoc())
                                {
                                    echo "<tr><td>#{$row['ticketID']}</td>";
                                    echo "<td>{$row['ticket_description']}</td>";
                                    if (strtolower($row['ticket_status']) == "Confirmed") {
                                        echo "<td><span id='completed'><span class='circle'></span>&nbsp;&nbsp;{$row['ticket_status']}</span></td>";
                                    }
                                    else {
                                        echo "<td><span id='rejected'><span class='circle'></span>&nbsp;&nbsp;{$row['ticket_status']}</span></td>";
                                    }
                                    echo "<td>" . date("D h:ia", strtotime($row['ticketDate'])) . "</td>";
                                    echo "<td>{$row['category']}</td>";
                                    switch (strtolower($row['priority'])) {
                                        case "high":
                                            echo "<td><span class='priority high-risk'><span class='circle'></span>&nbsp;&nbsp;High</span></td></tr>";
                                            break;
                                        case "medium":
                                            echo "<td><span class='priority medium-risk'><span class='circle'></span>&nbsp;&nbsp;Medium</span></td></tr>";
                                            break;
                                        default:
                                            echo "<td><span class='priority low-risk'><span class='circle'></span>&nbsp;&nbsp;Low</span></td></tr>";
                                    }
                                }
                            }
                            else {
                                echo "<tr><td colspan=3> <p> No Tickets Available </p></td></tr>";
                            }
                            // close connection
                            $connection->close();
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <!-- Link to external JavaScript file -->
    <script src="hall_secretary_dashboard.js"></script>

</body>
</html>