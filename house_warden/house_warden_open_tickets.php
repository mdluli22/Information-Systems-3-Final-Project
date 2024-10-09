<?php
    require_once("secure.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Warden | ResQue</title>
    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="house_warden.css">
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
        // if (isset(($_REQUEST['submit']))) {
        // get house_warden username from login page/pop-up
        $warden_userName = $_SESSION['username'];
        // $resName = "Adamson House";// $_REQUEST['resName'];

        // include database details from config.php file
        require_once("config.php");

        // attempt to make database connection
        $connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check if connection was successful
        if ($connection->connect_error) {
            die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
        }

        // Variable to display approval or rejection of a ticket, stored in session
        $approved_or_rejected = $_SESSION['approved_or_rejected'] ?? null;
        $student_name = $_SESSION['student_name'] ?? null;
        $student_room_num = $_SESSION['student_room_num'] ?? null;

        // Remove APPROVED/CONFIRMED requests from OPENED requests list
        // These requests are now CONFIRMED - they will go to hall secretary's OPENED page
        if (isset($_REQUEST['approve_request'])) {
            $ticketID = intval($_REQUEST['ticketID']);
            $student_name = $_REQUEST['student_name'];
            $student_room_num = $_REQUEST['student_room_num'];
            $approved_or_rejected = 'approved';
        
            // Store in session before redirecting
            $_SESSION['approved_or_rejected'] = $approved_or_rejected;
            $_SESSION['student_name'] = $student_name;
            $_SESSION['student_room_num'] = $student_room_num;
        
            // Update ticket status first before redirecting
            $remove_request = "UPDATE ticket SET ticket_status = 'Confirmed' WHERE ticketID = $ticketID;";
            $remove_request_result = $connection->query($remove_request);
        
            // Check if the update was successful
            if ($remove_request_result === FALSE) {
                die("<p class=\"error\">Request removal was Unsuccessful!</p>");
            }
        
            // Redirect to the same page to prevent form resubmission
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        }

        // Remove REJECTED requests from OPENED requests list
        // These requests are now CLOSED - they will go to house secretary's CLOSED page
        // with ticket_status = "REJECTED" manually
        if (isset($_REQUEST['reject-request'])) {
            $ticketID = intval($_REQUEST['ticket_ID']);
            $student_name = $_REQUEST['student_name'];
            $student_room_num = $_REQUEST['student_room_num'];
    
            $approved_or_rejected = 'rejected';
    
            // Store in session before redirecting
            $_SESSION['approved_or_rejected'] = $approved_or_rejected;
            $_SESSION['student_name'] = $student_name;
            $_SESSION['student_room_num'] = $student_room_num;
    
            // Redirect to the same page to prevent form resubmission
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
    
            // Update ticket status
            // $rejected_request = "UPDATE ticket SET ticket_status = 'Closed' WHERE ticketID = $ticketID;";
            // $rejected_request_result = $connection->query($rejected_request);
            // if ($rejected_request_result === FALSE) {
            //     die("<p class=\"error\">Rejected Request removal was Unsuccessful!</p>");
            // }
        }


        // get hall name from login page/pop-up
        // $warden_userName = $_SESSION['username'];

        $warden_res_query =
        "SELECT *, resName, concat(f_Name, ' ', l_Name) as 'Name', f_Name as 'firstName', CONCAT(LEFT(house_warden.f_Name, 1), LEFT(house_warden.l_Name, 1)) AS initials
        FROM house_warden WHERE userName = '$warden_userName';";
        $warden_res_query_result = $connection->query($warden_res_query);
        
        // Check if the query returned any results before accessing the array
        if ($warden_res_query_result->num_rows > 0) {
            $resnamel = $warden_res_query_result->fetch_assoc();
            $resname = $resnamel['resName'];
            $wardeName = $resnamel['Name'];
            $initials = $resnamel['initials'];
            $firstName = $resnamel['f_Name'];
        } else {
            // Output error message if no data was retrieved
            die("<p class=\"error\">No results found for the current user!</p>");
        }

        
        // query instructions for tickets Opened
        $sql = 
            "SELECT 
                ticket.ticketID, 
                IFNULL(CONCAT(student.f_Name, ' ', student.l_Name), 'Warden') AS full_name, 
                student.f_Name, 
                ticket.resName, 
                student.room_number, 
                ticket.priority 
            FROM 
                ticket 
            LEFT JOIN 
                student ON student.userName = ticket.userName 
            WHERE 
                ticket.ticket_status = 'Opened' 
                AND (ticket.resName = '$resname' OR ticket.userName = '$warden_userName') 
            ORDER BY 
                ticket.ticketID DESC;";


        $opened_tickets_result = $connection->query($sql);

        // Check if query successfull
        if ($opened_tickets_result === FALSE) {
            die("<p class=\"error\">Query was Unsuccessful: " . $connection->error . "</p>");
        }

        if (isset($_REQUEST['mark_seen'])) {
            $ticketID = $_REQUEST['ticket_ID'];
            
            // Update the s_seen value to 1 for this ticket
            $update_seen_query = "UPDATE ticket SET w_seen = 1 WHERE ticketID = $ticketID";
            $connection->query($update_seen_query);
        }


    ?>
    <div class="container">
        <!-- Sidebar section for navigation -->
        <?php require_once("sidebarWarden.php") ;?>

        <!-- Main content area --> 
        <main class="content">
            <header class="page-header">
                <div class="text-container">
                    <!-- Welcome message -->
                    <h1>Welcome, <span class="username"><?php echo $firstName; ?></span></h1>
                    <p>Access & Manage maintenance requisitions efficiently.</p>
                    <?php //unseen tickets Notification

                        $sql = "SELECT resName FROM house_warden where userName = '$warden_userName' ";
                        $thehallname = $connection->query($sql);
                        $thehouse = $thehallname -> fetch_assoc()['resName'];

                        $sql_unseen = "SELECT Count(*) as unseen_count  FROM ticket join residence on ticket.resName = residence.resName where w_seen = 0 and ticket.resName = '$thehouse' and ticket_status = 'Opened';";
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
            <!-- removed the Ticket table section -->

            <?php
                // Check if the request has been rejected
                if ($approved_or_rejected == 'rejected') {
                    // Display the rejected feedback message with a cancel icon
                    echo "<div id='rejected-message' class='rejected-message'>
                            <!-- Removed onclick from the <i> tag-->
                            <h2>Request Rejected!<i class='fas fa-times cancel-icon' onclick='remove_rejected_feedback()'></i></h2>
                            <p>The maintenance request for <strong>$student_name</strong> in <strong>room $student_room_num</strong> has been Rejected!</p>
                        </div>";
                    
                    // Unset session variables to prevent repeating the message on refresh
                    unset($_SESSION['approved_or_rejected']);
                    unset($_SESSION['student_name']);
                    unset($_SESSION['student_room_num']);
                }

                // Check if the request has been approved
                if ($approved_or_rejected == 'approved') {
                    // Display the approved feedback message with a cancel icon
                    echo "<div id='success-message' class='success-message'>
                            <h2>Request Approved!<i class='fas fa-times cancel-icon' onclick='remove_approved_feedback()'></i></h2>
                            <p>The maintenance request for <strong>$student_name</strong> in <strong>room $student_room_num</strong> has been approved successfully.</p>
                        </div>";
                    
                    // Unset session variables to prevent repeating the message on refresh
                    unset($_SESSION['approved_or_rejected']);
                    unset($_SESSION['student_name']);
                    unset($_SESSION['student_room_num']);
                }
                unset($ticketID);
            ?>

            <!-- Maintenance requests section -->
            <section class="maintenance-requests">
                <header id="maintenance-requests-header">
                    <!-- Header with title and view all button -->
                    <h2 id="h2">Tickets Pending Approval</h2>
                    <!-- <button class="view-all">View all</button> -->
                </header>

                <!-- Populate maintenance faults pending approval -->
                    <div class="requests">
                        <?php
                            if ($opened_tickets_result->num_rows > 0) {
                                while ($row = $opened_tickets_result->fetch_assoc()) {
                                    echo "<article class='request'>
                                            <div class='request-top-btns request-btns'>
                                                <!-- Buttons for commenting and deleting a request -->
                                                <a href='house_warden_open_tickets.php?ticket_ID={$row['ticketID']}&mark_seen=1'>
                                                    <button class='comment-btn'>
                                                        <i class='fa-solid fa-pen'></i>&nbsp;&nbsp;&nbsp;Comment
                                                    </button>
                                                </a>
                                                <!-- REJECT A TICKET -->
                                                <a href='#' onclick=\"if (confirm('Are you sure you want to reject {$row['f_Name']}\'s ticket?')) {
                                                    // Redirect to the updateStatus.php page
                                                    window.location.href = 'updateStatus.php?ticket_ID={$row['ticketID']}&student_name={$row['full_name']}&student_room_num={$row['room_number']}';
                                                    // Optionally, remove feedback messages
                                                    remove_rejected_feedback();
                                                }\">
                                                    <button type='button' class='reject-btn'>
                                                        <i class='fa-solid fa-trash' style='color: #e53e3e;'></i>&nbsp;&nbsp;&nbsp;Reject
                                                    </button>
                                                </a>
                                            </div>
                                            
                                            <!-- Request information -->
                                            <div class='request-info'>
                                                <p><strong>{$row['full_name']}</strong></p>
                                                <p>Ticket Number: <strong>{$row['ticketID']}</strong></p>
                                                <p>Residence: <strong>{$row['resName']}</strong></p>
                                                <p>Room Number: <strong>{$row['room_number']}</strong></p>
                                                <form class='request-form' action='house_warden_open_tickets.php' method='get' onsubmit=\"return confirm('Are you sure you want to approve the request for {$row['full_name']} in room {$row['room_number']}?');\">
                                                    <input type='hidden' name='ticketID' value='{$row['ticketID']}'>
                                                    <button type='submit' name='approve_request' class='approve-btn request-btns'>
                                                        <i class='fa-solid fa-plus' style='color: #a020f0;'></i>&nbsp;&nbsp;&nbsp;Approve Request
                                                    </button>
                                                    <input type='hidden' name='student_name' value='{$row['full_name']}'>
                                                    <input type='hidden' name='student_room_num' value='{$row['room_number']}'>
                                                </form>
                                                <p>Priority: <strong>{$row['priority']}</strong></p>
                                            </div>
                                        </article>";

                                    // Check if the current ticket is selected for viewing comments
                                    if (isset($_REQUEST['ticket_ID']) && $_REQUEST['ticket_ID'] == $row['ticketID']) {
                                        $theticketID = $row['ticketID'];

                                        // Query to get the comments related to this ticket
                                        $sql_comments = "SELECT commentID, userName, comment_description, comment_date 
                                                        FROM systemsurgeons.comment 
                                                        WHERE ticketID = '$theticketID' AND soft_delete_comment = false";
                                        $comments_result = $connection->query($sql_comments);

                                        if ($comments_result->num_rows > 0) {
                                            echo "<td><h3>Comments</h3><dl class='comment-list'>";
                                            while ($comment = $comments_result->fetch_assoc()) {
                                                // Calculate time ago for the comment
                                                $comment_time = new DateTime($comment['comment_date']);
                                                $current_time = new DateTime();
                                                $interval = $comment_time->diff($current_time);
                                                
                                                $time_ago = 'Just now';
                                                if ($interval->y > 0) $time_ago = $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
                                                elseif ($interval->m > 0) $time_ago = $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
                                                elseif ($interval->d > 0) $time_ago = $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
                                                elseif ($interval->h > 0) $time_ago = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
                                                elseif ($interval->i > 0) $time_ago = $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
                                                
                                                // Display comment information
                                                echo "<div class='comment-bubble'>
                                                        <dt class='commentor'>" . htmlspecialchars($comment['userName']) . ":</dt>
                                                        <dd class='comment-msg'>" . htmlspecialchars($comment['comment_description']) . "</dd>
                                                        <span class='comment_time'>" . htmlspecialchars($time_ago) . "</span>";
                                                
                                                // Show delete button if the comment belongs to the warden
                                                if ($comment['userName'] == $warden_userName) {
                                                    echo "<form action='soft_delete_comment.php' method='POST' style='display:inline;'>
                                                            <input type='hidden' name='commentID' value='{$comment['commentID']}'>
                                                            <input type='hidden' name='ticketID' value='$theticketID'>
                                                            <input type='hidden' name='userID' value='$warden_userName'>
                                                            <input type='hidden' name='page' value='open'>
                                                            <button type='submit' class='delete-button'>Delete</button>
                                                        </form>";
                                                }

                                                echo "</div><br>";
                                            }
                                            echo "</dl></td>";
                                        } else {
                                            echo "<td><h3>Comments</h3><span class='info-label'>No comments have been made under this ticket yet.</span><br></td>";
                                        }

                                        // Form to submit a new comment
                                        echo "<form action='submit_comment.php' method='POST'>
                                                <input type='hidden' name='ticketID' value='$theticketID'>
                                                <input type='hidden' name='userID' value='$warden_userName'>
                                                <input type='hidden' name='page' value='open'>
                                                <textarea name='comment_description' id='comment' rows='4' cols='50' placeholder='Leave a Comment' required></textarea><br>
                                                <button type='submit' class='comment-button'>Submit Comment</button>
                                            </form>";
                                    }
                                }
                            } else {
                                echo "<tr><td colspan=3><p>No Tickets Available</p></td></tr>";
                            }
                        ?>
                    </div>

                                    
            </section>
        </main>
    </div>
    <!-- Link to external JavaScript file -->
    <script src="house_warden.js"></script>
</body>
</html>