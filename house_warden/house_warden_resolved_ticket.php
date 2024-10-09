<?php
    require_once("secure.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Warden | ResQue</title>
    <link rel="icon" type="image/x-icon" href="../landing_page/pictures/fake logo(1).png">
    <link rel="stylesheet" href="house_warden.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
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

    // get hall name from login page/pop-up
    $warden_userName = $_SESSION['username'];

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

    // query instructions for tickets pending and processing
    $sql = "SELECT * FROM ticket WHERE resName = '$resname' ORDER BY ticketID DESC;";
    $result = $connection->query($sql);

    // Check if query successfull
    if ($result === FALSE) {
        die("<p class=\"error\">Query was Unsuccessful!</p>");
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
        <?php require_once("sidebarWarden.php");?>
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

                        $sql_unseen = "SELECT Count(*) as unseen_count  FROM ticket join residence on ticket.resName = residence.resName where w_seen = 0 and ticket.resName = '$thehouse' and ticket_status = 'Resolved';";
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
            <!-- Ticket table section -->
            <section class="ticket-table">
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
                        </tr>
                    </thead>
                    <tbody>
                        <!-- populate dashboard board with tickets from database -->
                        <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc())
                                {
                                    if ($row['ticket_status'] == "Resolved") {
                                        echo "<tr><td>#{$row['ticketID']}</td>";
                                        echo "<td>{$row['ticket_description']}</td>";
                                        echo "<td><span class='status processing'><span class='circle'></span>&nbsp;&nbsp;{$row['ticket_status']}</span></td>";
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

                                        // echo "<td><a href='house_warden_resolved_ticket.php?ticket_ID={$row['ticketID']}'> Comment </a>";
                                        echo "<td><p><a href='house_warden_resolved_ticket.php?ticket_ID={$row['ticketID']}&mark_seen=1' class='custom-button' id='viewButton'> View Details</a></p></td></tr>";
                                        echo "<tr>";

                                        if(isset($_REQUEST['ticket_ID'])){
                                            //when we reach the specific ticket we want comments for.
                                           if($_REQUEST['ticket_ID'] == $row['ticketID']){
                                                $theticketID = $row['ticketID'];
                                               // Query to get the comments related to this ticket
                                                $sql_comments = "SELECT commentID, userName, comment_description, comment_date FROM systemsurgeons.comment WHERE ticketID = '$theticketID' and soft_delete_comment = false";
                                                $comments_result = $connection->query($sql_comments); // Execute query for comments

                                                echo "<td> </td>";
            
                                                if ($comments_result->num_rows > 0) {
                                                    echo "<td colspan=3>";
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
                                                        if ($comment['userName'] == $warden_userName) {
                                                            echo "<form action='soft_delete_comment.php' method='POST' style='display:inline;'>
                                                                    <input type='hidden' name='commentID' value='{$comment['commentID']}'>
                                                                    <input type='hidden' name='ticketID' value=$theticketID>
                                                                    <input type='hidden' name='userID' value= $warden_userName>
                                                                    <input type='hidden' name='page' value='resolved'>"; //tells the form handler which page to return to
                                                            echo   "<button type='submit' class='delete-button'>Delete</button>
                                                                </form>";
                                                        }
            
                                                        echo "</div>";
                                                        echo "<br>";
                                                    }
                                                    echo "</dl>";
                                                } else {
                                                    echo "<td colspan=3>";
                                                    echo "<h3>Comments</h3>";
                                                    echo "<span class='info-label'>No comments have been made under this ticket yet.</span><br>";
                                                }
            
                                                echo "<form action='submit_comment.php' method='POST'>
                                                <input type='hidden' name='ticketID' value=$theticketID>
                                                <input type='hidden' name='userID' value='$warden_userName'>
                                                <input type='hidden' name='page' value='resolved'>
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
                    
                                                echo "<td>";
                                                echo "<p></p>";
                                                echo "</td>";
                    
                                                echo "</tr>";
                                                    
                                            }        
                                        }
                                    }
                                }
                            }
                            else {
                                echo "<tr><td colspan=3> <p> No Tickets Available </p></td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <!-- Link to external JavaScript file -->
    <script src="house_warden.js"></script>  
</body>
</html>