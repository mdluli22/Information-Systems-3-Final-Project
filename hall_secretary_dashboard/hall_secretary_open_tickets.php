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

        
        if (isset($_REQUEST['mark_seen'])) {
            $ticketID = $_REQUEST['ticket_ID'];
            
            // Update the s_seen value to 1 for this ticket
            $update_seen_query = "UPDATE ticket SET h_seen = 1 WHERE ticketID = $ticketID";
            $connection->query($update_seen_query);
        }

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
                    
                    <?php //unseen tickets Notification
                    $sql = "SELECT hall_name FROM hall_secretary where HS_userName = '$hall_sec_userName' ";
                    $thehallname = $connection->query($sql);
                    $hallname = $thehallname -> fetch_assoc()['hall_name'];

                    $sql_unseen = "SELECT Count(*) as unseen_count  FROM ticket join residence on ticket.resName = residence.resName where ticket_status = 'Confirmed' and h_seen = 0 and hall_name = '$hallname';";
                    $result_unseen = $connection->query($sql_unseen);


                    $unseen_count = 0; // Default count
                    if ($result_unseen && $row = $result_unseen->fetch_assoc()) {
                        $unseen_count = $row['unseen_count'];
                    }

                    if ( $unseen_count > 0) {
                        echo "<p class='fade-out'>View and make comments on all logged tickets. View all your residence's tickets.<span style='color: #ef3e3e;'> You have $unseen_count new tickets</span></p>";
                    } else {
                        echo "<p class='fade-out'>View and make comments on all logged tickets. View all your residence's tickets.</p>";
                    }
                    ?>
                </div>
                <div class="logo-container">
                    <img src="../landing_page/pictures/fake logo(1).png" alt="Logo">
                </div>
            </header>

            <!-- House selection links -->
            <nav class="houses">
                <?php
                    // $residence = array();
                    $defaulthouse = '';
                    $active = 0;
                    do {
                            
                        if ($active == 0) {
                            $active++;
                            $defaulthouse = $residence['residences'];
                        }

                        $activeHouse = isset($_REQUEST['house_name']) ? $_REQUEST['house_name'] : $defaulthouse;
                        $isActive = ($residence['residences'] === $activeHouse) ? 'active' : '';
                        echo "<a href='hall_secretary_open_tickets.php?house_name={$residence['residences']}' class='house-link {$isActive}'>{$residence['residences']}</a>";
                    } while ($residence = $residences_result->fetch_assoc());
                ?>
            </nav>
            
            <?php
                if ($requisitioned_or_closed == 'requisitioned') {
                    echo "<div id='requisition-message' class='requisition-message'>
                            <h2>Ticket Requisitioned!<i class='fas fa-times cancel-icon' onclick='remove_requisitioned_feedback()'></i></h2>
                            <p>The maintenance request for <strong>$student_name</strong> in <strong>room $student_room_num</strong> has been requisitioned successfully. The maintenance team will be notified shortly.</p>
                        </div>";

                    // Unset session variables to prevent repeating the message on refresh
                    unset($_SESSION['requisitioned_or_closed']);
                    unset($_SESSION['student_name']);
                    unset($_SESSION['student_room_num']);
                }

                if ($requisitioned_or_closed == 'closed') {
                    echo "<div id='close-message' class='close-message'>
                            <h2>Ticket Closed!<i class='fas fa-times cancel-icon' onclick='remove_close_feedback()'></i></h2>
                            <p>The maintenance request for <strong>$student_name</strong> in <strong>room $student_room_num</strong> has been closed successfully.</p>
                        </div>";

                    // Unset session variables to prevent repeating the message on refresh
                    unset($_SESSION['requisitioned_or_closed']);
                    unset($_SESSION['student_name']);
                    unset($_SESSION['student_room_num']);
                }
            ?>
            
            <!-- <input type="hidden" name=""> -->
            <!-- Maintenance requests section -->
            <section class="maintenance-requests"> <!--maintenance-scrollbar">-->
                <header id="maintenance-requests-header">
                    <!-- Header with title and view all button -->
                    <h2 id="h2">Tickets Pending Approval</h2>
                    <!-- <button class="view-all">View all</button> -->
                </header>

                <!-- populate maintenance faults pending approval -->
                <div class="requests">

                    <!--*** N.B. FLIP form and Priority -->

                    <?php

                    if(isset($_REQUEST['house_name'])){
                        // get approved/confirmed tickets from house warden
                        $housename = $_REQUEST['house_name'];
                        $requisitioned_tickets_query =
                            "SELECT ticketID, s.userName, concat(f_Name, ' ', l_Name) AS 'full_name', f_Name, t.resName, room_number, priority
                                    FROM student s JOIN ticket t ON s.userName = t.userName
                                    WHERE ticket_status = 'Confirmed' and t.resName = '$housename';";
                        $requisitioned_tickets_result = $connection->query($requisitioned_tickets_query);
                    }
                
                    else{
                
                        $requisitioned_tickets_query =
                        "SELECT ticketID, s.userName, concat(f_Name, ' ', l_Name) AS 'full_name', f_Name, t.resName, room_number, priority
                                FROM student s JOIN ticket t ON s.userName = t.userName
                                WHERE ticket_status = 'Confirmed' and t.resName = '$defaulthouse';";
                        $requisitioned_tickets_result = $connection->query($requisitioned_tickets_query);
                
                    }

                    // Check if query successful
                    if ($requisitioned_tickets_query === FALSE) {
                        die("<p class=\"error\">Query was Unsuccessful!</p>");
                    }

                    if ($requisitioned_tickets_result->num_rows > 0) {

                        while ($row = $requisitioned_tickets_result->fetch_assoc()) {
                            echo "<article class='request'>
                                        <div class='request-top-btns request-btns'>
                                            <!-- Buttons for commenting and deleting a request -->
                                            <a href = 'hall_secretary_open_tickets.php?ticket_ID={$row['ticketID']}&house_name={$activeHouse}&mark_seen=1' ><button class='comment-btn' onclick><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;&nbsp;Comment</button></a>
                                            <a href='#' onclick=\"if (confirm('Are you sure you want to close the ticket for {$row['f_Name']} in room {$row['room_number']}?')) {
                                                window.location.href = 'updateStatusHallSec.php?ticket_ID={$row['ticketID']}&close-request&student_name={$row['full_name']}&student_room_num={$row['room_number']}';
                                            }\">
                                                <button type='button' class='close-ticket-btn'>
                                                    <strong><i class='fa-solid fa-check' style='color: green;'></i>&nbsp;&nbsp;&nbsp;Close</strong>
                                                </button>
                                            </a>

                                        </div>
                                        <!-- Request information -->
                                        <div class='request-info'>
                                            <p><strong>{$row['full_name']}</strong></p>
                                            <p>Ticket Number: <strong>{$row['ticketID']}</strong></p>
                                            <p>Residence: <strong>{$row['resName']}</strong></p>
                                            <p>Room Number: <strong>{$row['room_number']}</strong></p>
                                            <form class='request-form' action='hall_secretary_open_tickets.php' method='get' onsubmit=\"return confirm('Are you sure you want to requisition the ticket for {$row['full_name']} in room {$row['room_number']}?');\">
                                                <input type='hidden' name='ticketID' value='{$row['ticketID']}'>
                                                <button type='submit' name='requisition-request' class='requisition-btn request-btns'>
                                                    <i class='fa-solid fa-plus' style='color: #a020f0;'></i>&nbsp;&nbsp;&nbsp;Requisition
                                                </button>
                                                <input type='hidden' name='student_name' value='{$row['full_name']}'>
                                                <input type='hidden' name='student_room_num' value='{$row['room_number']}'>
                                                <input type='hidden' name='house_name' value='{$activeHouse}'>
                                            </form>

                                            <p>Priority: <strong>{$row['priority']}</strong></p>
                                            
                                        </div>
                                    </article>";

                                    if(isset($_REQUEST['ticket_ID'])){
                                        //when we reach the specific ticket we want comments for.
                                       if($_REQUEST['ticket_ID'] == $row['ticketID']){
                                            $theticketID = $row['ticketID'];
                                           // Query to get the comments related to this ticket
                                            $sql_comments = "SELECT commentID, userName, comment_description, comment_date FROM systemsurgeons.comment WHERE ticketID = '$theticketID' and soft_delete_comment = false";
                                            $comments_result = $connection->query($sql_comments); // Execute query for comments
        
                                            if ($comments_result->num_rows > 0) {
                                                echo "<td>";
                                                echo "<h3>Comments</h3>";
                                                echo "<dl class='comment-list'>";
                                                while ($comment = $comments_result->fetch_assoc()) {
                                                    
                                                    //calculate how long ago a comment was made
                                                    $comment_time = new DateTime($comment['comment_date']); //date comment was made
                                                    $current_time = new DateTime(); //today's date
                                                    $interval = $comment_time->diff($current_time); //difference in the time
        
                                                    // Create a readable time difference (e.g., '2 hours ago')
                                                    if ($interval->y > 0) {
                                                        $time_ago = $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
                                                    } elseif ($interval->m > 0) {
                                                        $time_ago = $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
                                                    } elseif ($interval->d > 0) {
                                                        $time_ago = $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
                                                    } elseif ($interval->h > 0) {
                                                        $time_ago = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
                                                    } elseif ($interval->i > 0) {
                                                        $time_ago = $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
                                                    } else {
                                                        $time_ago = 'Just now';
                                                    }
                                                    
                                                    // Determine the display name for the comment owner
                                                    if (strpos($comment['userName'], 'h') === 0) {
                                                        $displayName = "Hall Secretary";
                                                    } elseif (strpos($comment['userName'], 'g') === 0) {
                                                        $displayName = "Student";
                                                    } elseif (strpos($comment['userName'], 'w') === 0) {
                                                        $displayName = "House Warden";
                                                    } elseif (strpos($comment['userName'], 'm') === 0) {
                                                        $displayName = "Maintenance";
                                                    } else {
                                                        $displayName = $comment['userName']; // Default to the original username
                                                    }
                                                    
                                                    //display the comment info
                                                    echo "<div class='comment-bubble'>";
                                                    echo "<dt class='commentor'><strong>" . htmlspecialchars($displayName) . ":</strong></dt>";
                                                    echo "<dd class='comment-msg'> " . htmlspecialchars($comment['comment_description']) . "</dd>";
                                                    echo "<span class='comment_time'>" . htmlspecialchars($time_ago) . "</span>"; // Display time ago
                                                    
                                                    // For each comment, show delete button BUT ONLY for the comment owner
                                                    if ($comment['userName'] == $hall_sec_userName) {
                                                        echo "<form action='soft_delete_comment.php' method='POST' style='display:inline;'>
                                                                <input type='hidden' name='commentID' value='{$comment['commentID']}'>
                                                                <input type='hidden' name='house_name' value='$activeHouse'>
                                                                <input type='hidden' name='ticketID' value=$theticketID>
                                                                <input type='hidden' name='userID' value= $hall_sec_userName>
                                                                <input type='hidden' name='page' value='open'>"; //tells the form handler which page to return to
                                                        echo   "<button type='submit' class='delete-button'>Delete</button>
                                                            </form>";
                                                    }
        
                                                    echo "</div>";
                                                    echo "<br>";
                                                }
                                                echo "</dl>";
                                            } else {
                                                echo "<td>";
                                                echo "<h3>Comments</h3>";
                                                echo "<span class='info-label'>No comments have been made under this ticket yet.</span><br>";
                                            }
        
                                            echo "<form action='submit_comment.php' method='POST'>
                                            <input type='hidden' name='ticketID' value=$theticketID>
                                            <input type='hidden' name='house_name' value='$activeHouse'>
                                            <input type='hidden' name='userID' value='$hall_sec_userName'>
                                            <input type='hidden' name='page' value='open'>
                                            <textarea name='comment_description' id='comment' rows='4' cols='50' placeholder='Leave a Comment' required></textarea><br>
                                            <button type='submit' class='comment-button'>Submit Comment</button>
                                            </form>";
        
                                            echo "</td>";
        
        
                                            echo "<td>";
                                            echo "<p></p>";
                                            echo "</td>";
                
                                            echo "<td>";
                                            echo "<p></p>";
                                            echo "</td>";
        
                                            echo "<td>";
                                            echo "<p> </p>";
                                            echo "</td>";
                
                                            echo "</tr>";
                                                
                                        }        
                                    }
                        }
                    }
                    else {
                        echo "<tr><td colspan=3> <p> No Tickets Available </p></td></tr>";
                    }

                    // close connection
                    $connection->close();
                    ?>
                </div>
            </section>
        </main>
    </div>
    <!-- Link to external JavaScript file -->
    <script src="hall_secretary_dashboard.js"></script>
</body>
</html>