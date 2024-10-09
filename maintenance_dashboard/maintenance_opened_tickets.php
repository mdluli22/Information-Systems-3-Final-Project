<?php

    require_once("secure.php");

    if (isset($_SESSION['username'])) {
        // echo 'Session Username: ' . $_SESSION['username'];
        $MaintenanceID = $_SESSION['username'];
    }else {
        die("User is not logged in.");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Staff</title>
    <link rel="shortcut icon" href="pictures/fake logo(1).png" type="image/x-icon">
    <!-- Link to the external CSS files -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />    
    <link rel="stylesheet" href="maintenance.css">
    <link rel="stylesheet" href="comment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="maintenance.js"></script>
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
        // include database details from config.php file
        require_once("config.php");

        // attempt to make database connection
        $connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check if connection was successful
        if ($connection->connect_error) {
            die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
        }
        
        $sql = "SELECT concat(f_Name,  ' ', l_Name) as 'name' FROM maintenance_staff  WHERE userName = '$MaintenanceID'";
        $thename = $connection -> query($sql);


        if ($thename === FALSE) {
            die("<p class=\"error\">Query was Unsuccessful!</p>");
        }

        $name = $thename->fetch_assoc();

        
        $halls = "SELECT DISTINCT hall_name FROM hall_secretary";

        $residences_result = $connection->query($halls);

        if ($residences_result === FALSE) {
            die("<p class=\"error\">Query was Unsuccessful!</p>");
        }

        if (isset($_REQUEST['mark_seen'])) {
            $ticketID = $_REQUEST['ticket_ID'];
            
            // Update the s_seen value to 1 for this ticket
            $update_seen_query = "UPDATE ticket SET m_seen = 1 WHERE ticketID = $ticketID";
            $connection->query($update_seen_query);
        }
 
    ?>
<!-- <div class="container"> -->
    <!-- Sidebar section for navigation -->
    <?php
    require_once("sidebarMaintenance.php");
    ?>
    <!-- Main content area -->
    <main class="content">
        <header class="page-header">
            <div class="text-container">
                <!-- Welcome message -->
                <h1>Welcome, <span class="username" ><?php echo $name['name'] ?></span></h1>
                <p>Access & Manage maintenance requisitions efficiently.</p>
                <?php //unseen tickets Notification


                        $sql_unseen = "SELECT Count(*) as unseen_count  FROM ticket where m_seen = 0 and ticket_status = 'Requisitioned';";
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
        </header>

        <!-- House selection links -->
        <nav class="houses">

                <?php
                    $activeHouse = "nohuese";
                    $active = 0;
                    while ($residence = $residences_result->fetch_assoc()) {
                        
                        if ($active == 0) {
                            $active++;
                            $defaulthouse = $residence['hall_name'];
                        }

                        $activeHouse = isset($_REQUEST['house_name']) ? $_REQUEST['house_name'] : $defaulthouse;
                        $isActive = ($residence['hall_name'] === $activeHouse) ? 'active' : '';
                        echo "<a href='maintenance_opened_tickets.php?house_name={$residence['hall_name']}' class='house-link {$isActive}'>{$residence['hall_name']}</a>";
                    }

                ?>
        </nav>

        <!-- Ticket table section -->
        <section class="ticket-table scrollbar">
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
                        <th>Comments/Details</th>
                        <th>Update status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- populate dashboard board with tickets from database -->
                    <?php
                        if(isset($_REQUEST['house_name'])){
                            $resname = $_REQUEST['house_name'];
                            $sql = "SELECT * FROM ticket join residence on ticket.resName = residence.resName where hall_name = '$resname' and ticket_status = 'Requisitioned' ";
                        }
                        else{
                            $sql = "SELECT * FROM ticket join residence on ticket.resName = residence.resName where hall_name = '$defaulthouse' and ticket_status = 'Requisitioned'  ";
                        }

                        $result = $connection->query($sql);

                        // Check if query successfull
                        if ($result === FALSE) {
                            die("<p class=\"error\">Query was Unsuccessful!</p>");
                        }

                        
                        if($result -> num_rows == 0){
                            echo "<tr><td colspan=3> <p> No Tickets Available </p></td></tr>";
                        }

                        while ($row = $result->fetch_assoc())
                        {
                            echo "<tr id = '{$row['ticketID']}'><td>#{$row['ticketID']}</td>";
                            echo "<td>{$row['ticket_description']}</td>";
                            // if ($row['ticket_status'] == "Processing") {
                            echo "<td><span class='status processing'><span class='circle'></span>&nbsp;&nbsp;{$row['ticket_status']}</span></td>";
                            // }
                            echo "<td>" . date("D h:ia", strtotime($row['ticketDate'])) . "</td>";
                            echo "<td>{$row['category']}</td>";
                            switch (strtolower($row['priority'])) {
                                case "high":
                                    echo "<td><span class='priority high-risk'><span class='circle'></span>&nbsp;&nbsp;High</span></td>";
                                    break;
                                case "medium":
                                    echo "<td><span class='priority medium-risk'><span class='circle'></span>&nbsp;&nbsp;Medium</span></td>";
                                    break;
                                default:
                                    echo "<td><span class='priority low-risk'><span class='circle'></span>&nbsp;&nbsp;Low</span></td>";
                            }
                            echo "<a id={$row['ticketID']}></a>";
                            // echo "<td><a href='maintenance_opened_tickets.php?ticket_ID={$row['ticketID']}&house_name={$activeHouse}#{$row['ticketID']}' >Comment</a>";
                            echo "<td><p><a href='maintenance_opened_tickets.php?ticket_ID={$row['ticketID']}&house_name={$activeHouse}&mark_seen=1' class='custom-button'' id='viewButton'>View Details</a></p></td>";
                            echo "<td><a href='updateStatus.php?ticket_ID={$row['ticketID']}&house_name={$activeHouse}' class='custom-button' onclick= \"return confirm('Are you sure you want to update ticket status')\">Resolve Ticket</a></td></tr>";
                    
                            echo "<tr>";
                            //code for rendering the comments
                            if(isset($_REQUEST['ticket_ID'])){
                                //when we reach the specific ticket we want comments for.
                               if($_REQUEST['ticket_ID'] == $row['ticketID']){
                                    $theticketID = $row['ticketID'];
                                   // Query to get the comments related to this ticket
                                    $sql_comments = "SELECT commentID, userName, comment_description, comment_date FROM systemsurgeons.comment WHERE ticketID = '$theticketID' and soft_delete_comment = false";
                                    $comments_result = $connection->query($sql_comments); // Execute query for comments

                                    echo "<td></td>";

                                    if ($comments_result->num_rows > 0) {
                                        echo "<td colspan = 3>";
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
                                            if ($comment['userName'] == $MaintenanceID) {
                                                echo "<form action='soft_delete_comment.php' method='POST' style='display:inline;'>
                                                        <input type='hidden' name='commentID' value='{$comment['commentID']}'>
                                                        <input type='hidden' name='house_name' value='$activeHouse'>
                                                        <input type='hidden' name='ticketID' value=$theticketID>
                                                        <input type='hidden' name='userID' value= $MaintenanceID>
                                                        <input type='hidden' name='page' value='open'>"; //tells the form handler which page to return to
                                                echo   "<button type='submit' class='delete-button'>Delete</button>
                                                    </form>";
                                            }

                                            echo "</div>";
                                            echo "<br>";
                                        }
                                        echo "</dl>";
                                    } else {
                                        echo "<td colspan = 3 >";
                                        echo "<h3>Comments</h3>";
                                        echo "<span class='info-label'>No comments have been made under this ticket yet.</span><br>";
                                    }

                                    echo "<form action='submit_comment.php' method='POST'>
                                    <input type='hidden' name='ticketID' value=$theticketID>
                                    <input type='hidden' name='house_name' value='$activeHouse'>
                                    <input type='hidden' name='userID' value=$MaintenanceID>
                                    <input type='hidden' name='page' value='open'>
                                    <textarea name='comment_description' id='comment' rows='4' cols='50' placeholder='Leave a Comment' required></textarea><br>
                                    <button type='submit' class='comment-button'>Submit Comment</button>
                                    </form>";

                                    echo "</td>";
        
                                    echo "<td colspan = 2 >";
                                    
                                    // Fetch and display photos from the 'photos' table for the ticketID
                                    $sql_photos = "SELECT photo FROM systemsurgeons.photos WHERE ticketID = '$theticketID'";
                                    $photos_result = $connection->query($sql_photos);


                                    if ($photos_result->num_rows > 0) {
                                        echo "<div class='carousel'>";
                                        echo "<div class='carousel-images'>";
                                        while ($photo = $photos_result->fetch_assoc()) {
                                            $photo_src = "../pictures/" . $photo['photo'];
                                            echo "<div class='carousel-slide'>";
                                            echo "<img src='$photo_src' alt='Ticket Image' class='carousel-image'>";
                                            echo "</div>";
                                        }
                                        echo "</div>";
                                        echo "<button class='carousel-prev'>Prev</button>";
                                        echo "<button class='carousel-next'>Next</button>";
                                        echo "</div>";
                                    } else {
                                        // echo "<p>No photos have been uploaded for this ticket.</p>";
                                        echo "<div class='carousel'>";
                                        echo "<img src='../ticket_tracking/pictures/tools2.jpg' alt='Ticket Image' class='carousel-image' >";
                                        echo "</div>";
                                    }
                                    //image carousel ends here
        
                                    echo "</td>";
        
                                    echo "</tr>";
                                        
                                }        
                            }
                        }
                        // close connection
                        $connection->close();
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>