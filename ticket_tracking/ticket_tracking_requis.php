<?php
require_once("secure.php");

if (isset($_SESSION['username'])) {
    $userID = $_SESSION['username']; //get userID for this 
}else {
    die("User is not logged in.");
}

// Include database details from config.php file
require_once("config.php");
                    
// attempt to make database connection
$connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// Check if connection was successful
if ($connection->connect_error) {
    die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
}

// Mark the ticket as seen when "View Details" is clicked
if (isset($_GET['ticketID']) && isset($_GET['mark_seen']) && $_GET['mark_seen'] == 1) {
    $ticketID = $_GET['ticketID'];
    
    // Update the s_seen value to 1 for this ticket
    $update_seen_query = "UPDATE systemsurgeons.ticket SET s_seen = 1 WHERE ticketID = '$ticketID' AND userName = '$userID'";
    $connection->query($update_seen_query);
}

//get the student information to use on the page
$sql = "SELECT * FROM systemsurgeons.student where userName = '$userID'";
$result = $connection -> query($sql); //execute query

if ($result && $result->num_rows > 0) {
    // Fetch the student information from the result set
    $row = $result->fetch_assoc();
    $fname = $row['f_Name'];
    $lname = $row['l_Name'];
    $residence = $row['resName'];
    $room = $row['room_number'];
} else {
    // Handle case where no student data was found
    echo "<p class='error'>No student data found for the user.</p>";
}

// Notifications Code: Use the unseen ticket count from the session (already counted in ticket_tracking_all.php) Unseen tickets count for 'Requisitioned'
$sql_unseen_requis = "SELECT COUNT(*) AS unseen_count FROM systemsurgeons.ticket WHERE userName = '$userID' AND ticket_status = 'Requisitioned' AND s_seen = 0";
$result_unseen_requis = $connection->query($sql_unseen_requis);
$unseen_count_requis = 0; // Default count
if ($result_unseen_requis && $row = $result_unseen_requis->fetch_assoc()) {
    $unseen_count_requis = $row['unseen_count'];
}
// Store the unseen count in the session for use in the sidebar
$_SESSION['unseen_count_requis'] = $unseen_count_requis;
// End of Notifications Code

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Tracking</title>
    <link rel="icon" type="image/x-icon" href="../landing_page/pictures/fake logo(1).png">
    <link rel="stylesheet" href="ticket_tracking.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ddbf4d6190.js" crossorigin="anonymous"></script>
</head>
<body>

    <div class="container">

        <!--Sidebar section for navigation-->
        <?php require_once("sidebarStudent.php"); ?>

        <!-- Main Ticket Tracking section -->
        <main class="content">
            <header>
                <div>
                    <h1>Welcome, <span class="ticket_type"><?php echo $fname ?></span></h1>
                    <?php //unseen tickets Notification
                        $sql_unseen = "SELECT COUNT(*) AS unseen_count FROM systemsurgeons.ticket WHERE userName = '$userID' AND s_seen = 0 and (ticket_status = 'Requisitioned')";
                        $result_unseen = $connection->query($sql_unseen);
                        $unseen_count = 0; // Default count
                        if ($result_unseen && $row = $result_unseen->fetch_assoc()) {
                            $unseen_count = $row['unseen_count'];
                        }

                        if ( $unseen_count > 0) {
                            echo "<p class='fade-out'>View and make comments on requisistioned tickets.<span style='color: #ef3e3e;'> You have $unseen_count new tickets</span></p>";
                        } else {
                            echo "<p class='fade-out'>View and make comments on requisistioned tickets.</p>";
                        }
                    ?>
                </div>
                <!-- Fix the logo size -->
                <div class="logo-container">
                    <img src="../landing_page/pictures/fake logo(1).png" alt="Logo" >
                </div>
            </header>

            <!-- Flex container for the ticket list and ticket detail -->
            <div class="content-wrapper">
                <!-- Section for the list of tickets -->
                <section class="ticket-list">
                    <!-- <a href="../ticket_creation/ticketCreation.html"><button class="add-ticket">+ Add New Ticket</button></a>
                    <br> -->

                    <h3>Your Tickets</h3>
                    <?php
                        //query instructions for the student's tickets
                        $sql = "SELECT ticketID, resName, ticket_status, s_seen FROM systemsurgeons.ticket where userName = '$userID' and (ticket_status = 'Requisitioned')";
                        $result = $connection -> query($sql); //execute query

                        // Check if query successfull
                        if ($result === FALSE) {
                            die("<p class=\"error\">Your Tickets Query was Unsuccessful!</p>");
                        }
                    
                        //display the student's tickets or display a message if none are found
                        echo "<section class='scrollbar'>";
                        if ($result -> num_rows > 0) {
                            echo "<table class='ticket-table'>";
                            while($row = $result->fetch_assoc()) {
                                echo "<tr class='ticket-card'>";
                                echo "<td class='ticket-number'><img src='pictures/clipboard-tick.png' alt='clipboard-tick' style='margin-right: 10px;'>#{$row['ticketID']}</td>";
                                    
                                // Determine the CSS class based on the ticket_status
                                $statusClass = "status " . strtolower($row['ticket_status']);
                                echo "<td><span class='{$statusClass}'><span class='circle'></span>&nbsp;&nbsp;{$row['ticket_status']}</span></td>";

                                // Add a red circle if the ticket's s_seen value is 0
                                if ($row['s_seen'] == 0) {
                                    echo "<td><a href='ticket_tracking_requis.php?ticketID={$row['ticketID']}&mark_seen=1' class='details-button-unseen'>View Details</a></td>"; // If ticket is unseen
                                } else {
                                    echo "<td><a href='ticket_tracking_requis.php?ticketID={$row['ticketID']}&mark_seen=1' class='details-button'>View Details</a></td>"; // If the ticket has been seen
                                }

                                echo "</tr>";
                            } //end table
                            echo "</table>";
                        }
                        else {
                            echo "<table class='ticket-table'>";
                            echo "<tr class='ticket-card'>";
                            echo "<td class='ticket-number'>";
                            echo "<p class='info-label'>No tickets were found for your residence.</p>";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";
                        }
                        echo "</section>";
                    ?>
                    <br>

                    <?php
                    echo "<h3> $residence Tickets</h3>";

                    //query instructions for all tickets within the same residence
                    $sql = "SELECT ticketID, resName, ticket_status FROM systemsurgeons.ticket where resName = '$residence' and (ticket_status = 'Requisitioned')";
                    $result = $connection -> query($sql); //execute query

                    // Check if query successfull
                    if ($result === FALSE) {
                        die("<p class=\"error\">Residence Tickets Query was Unsuccessful!</p>");
                    }

                    //dynamically display all tickets within that residence
                    echo "<section class='scrollbar'>";
                        if ($result -> num_rows > 0) {
                            echo "<table class='ticket-table'>";
                            while($row = $result->fetch_assoc()) {
                                echo "<tr class='ticket-card'>";
                                echo "<td class='ticket-number'><img src='pictures/clipboard-tick.png' alt='clipboard-tick' style='margin-right: 10px;'>#{$row['ticketID']}</td>";
                                    
                                // Determine the CSS class based on the ticket_status so the correct color is produced
                                // Determine the CSS class based on the ticket_status
                                $statusClass = "status " . strtolower($row['ticket_status']);
                                echo "<td><span class='{$statusClass}'><span class='circle'></span>&nbsp;&nbsp;{$row['ticket_status']}</span></td>";
                                echo "<td><a href='ticket_tracking_requis.php?ticketID={$row['ticketID']}&mark_seen=1' class='details-button'>View Details</a></td>";
                                echo "</tr>";
                            } //end table
                            echo "</table>";
                        }
                        else {
                            echo "<table class='ticket-table'>";
                            echo "<tr class='ticket-card'>";
                            echo "<td class='ticket-number'>";
                            echo "<p class='info-label'>No tickets were found for your residence.</p>";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";
                        }
                    echo "</section>";
                    ?>
                </section>
                
                

                <!-- Section for the detailed view of a single ticket -->
                <section class="ticket-detail">
                    <article class="ticket-info">
                        <?php

                            // Check if a ticketID is provided via GET request
                            if (isset($_GET['ticketID'])) {
                                $ticketID = $_GET['ticketID'];

                                //query instructions for the student's tickets
                                $sql = "SELECT * FROM systemsurgeons.ticket where ticketID = '$ticketID'";
                                $result = $connection -> query($sql); //execute query

                                // Check if query successfull
                                if ($result === FALSE) {
                                    die("<p class=\"error\">Could not connect to database to get ticket details!</p>");
                                }

                                // Fetch ticket details and set $ticketowner
                                $ticketowner = ''; //will be used to authorise user to make comments on their ticket, and to allow them to delete comments & images under their ticket
                                if ($result->num_rows > 0) {
                                    $ticket = $result->fetch_assoc(); // Get related ticket details
                                    $ticketowner = $ticket['userName']; // Set the ticket owner
                                } else {
                                    echo "<p>No details found for this ticket.</p>";
                                }

                                // Fetch and display photos from the 'photos' table for the ticketID
                                $sql_photos = "SELECT photoID, photo FROM systemsurgeons.photos WHERE ticketID = '$ticketID'";
                                $photos_result = $connection->query($sql_photos);

                                if ($photos_result->num_rows > 0) {
                                    echo "<div class='carousel'>";
                                    echo "<div class='carousel-images'>";
                                    while ($photo = $photos_result->fetch_assoc()) {
                                        $photo_src = "../pictures/" . $photo['photo'];
                                        $photoID = $photo['photoID'];
                                        echo "<div class='carousel-slide'>";
                                        echo "<img src='$photo_src' alt='Ticket Image' class='carousel-image'>";
                                        
                                        // Add delete button for the image positioned on top but ONLY if the user is the ticket owner
                                        if ($ticketowner == $userID) {
                                            echo "<form action='delete_image.php' method='POST' class='carousel-delete'>";
                                            echo "<input type='hidden' name='photoID' value='$photoID'>";
                                            echo "<input type='hidden' name='ticketID' value='$ticketID'>"; // Pass ticketID to reload details of the ticket
                                            echo "<input type='hidden' name='page' value='requisitioned'>";
                                            echo "<button type='submit' class='delete-button'><i class='fa fa-trash' aria-hidden='true'></i></button>";
                                            echo "</form>";
                                        }
                                        echo "</div>";
                                    }
                                    echo "</div>";
                                    echo "<button class='carousel-prev'>Prev</button>";
                                    echo "<button class='carousel-next'>Next</button>";
                                    echo "</div>";
                                } else {
                                    echo "<div class='carousel'><div class='carousel-images'><div class='carousel-slide'>";
                                    echo "<img src='pictures/tools2.jpg' alt='Ticket Image'>";
                                    echo "</div></div></div>";
                                }
                                //image carousel ends here
                                
                                if (!empty($ticketowner)) { //display ticket details if the ticket details exists
                                    //display the ticket details for the specific ticket
                                    echo "<table class='info-table'>";
                                    echo "<tr><td><span class='info-data'><h3>Details for Ticket #$ticketID</h3></span></td></tr>";
                                    echo "</table>";
                                    echo "<table class='info-table'>";
                                        echo "<tr>";
                                            echo "<td class='info-cell'>";
                                                echo "<span class='info-label'>Date Logged:</span>";
                                                // Convert the date from the database to the desired format
                                                $date = date_create($ticket['ticketDate']); // Create a DateTime object
                                                echo "<span class='info-data'>" . date_format($date, 'j F Y') . "</span>"; // Format the date
                                            echo "</td>";
                                            echo "<td class='info-cell'>";
                                                echo "<span class='info-label'>Priority:</span>";
                                                echo "<span class='info-data'>{$ticket['priority']}</span>";
                                            echo "</td>";
                                            echo "<td class='info-cell'>";
                                                echo "<span class='info-label'>Category:</span>";
                                                echo "<span class='info-data'>{$ticket['category']}</span>";
                                        echo "</td>";
                                        echo "<tr>";
                                            echo "<td class='info-cell' colspan='3'>";
                                                echo "<span class='info-label'>Description:</span>";
                                                echo "<span class='info-data'>{$ticket['ticket_description']}</span>";
                                            echo "</td>";
                                        echo "</tr>";
                                    echo "</table>";
                                }
                                else {
                                    echo "<p>No details found for this ticket.</p>";
                                }

                                //COMMENTS SECTION
                                // Query to get the comments related to this ticket
                                $sql_comments = "SELECT commentID, userName, comment_description, comment_date FROM systemsurgeons.comment WHERE ticketID = '$ticketID' and soft_delete_comment = false";
                                $comments_result = $connection->query($sql_comments); // Execute query for comments

                                if ($comments_result->num_rows > 0) {
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

                                        //display the comment info

                                        // Determine the display name for the comment owner
                                        if ($comment['userName'] == $userID) {
                                            $displayName = 'You'; // Use the current user's first name
                                        } elseif (strpos($comment['userName'], 'w') === 0) {
                                            $displayName = "House Warden";
                                        } elseif (strpos($comment['userName'], 'm') === 0) {
                                            $displayName = "Maintenance";
                                        } elseif (strpos($comment['userName'], 'h') === 0) {
                                            $displayName = "Hall Secretary";
                                        } elseif (strpos($comment['userName'], 'g') === 0) {

                                            // Query to get the name of the commentor
                                            $sql_commentor = "SELECT f_Name, l_Name FROM systemsurgeons.student WHERE userName = '{$comment['userName']}'";
                                            $commentor_result = $connection->query($sql_commentor);
                                
                                            if ($commentor_result && $commentor_result->num_rows > 0) {
                                                $commentor = $commentor_result->fetch_assoc();
                                                $displayName = $commentor['f_Name'] . " " . $commentor['l_Name']; // Display the first name and last name of the user
                                            } else {
                                                $displayName = $comment['userName']; // Fallback to username if not found
                                            }
                                
                                        } else {
                                            $displayName = $comment['userName']; // Default to the original username
                                        }

                                        // Display the comment info
                                        echo "<div class='comment-bubble'>";
                                        echo "<dt class='commentor'>" . htmlspecialchars($displayName) . ":</dt>";
                                        echo "<dd class='comment-msg'> " . htmlspecialchars($comment['comment_description']) . "</dd>";
                                        echo "<span class='comment_time'>" . htmlspecialchars($time_ago) . "</span>"; // Display time ago
                                        // For each comment, show delete button BUT ONLY for the comment owner
                                        if ($comment['userName'] == $userID) {
                                            echo "<form action='soft_delete_comment.php' method='POST' style='display:inline;'>
                                                    <input type='hidden' name='commentID' value='{$comment['commentID']}'>
                                                    <input type='hidden' name='userID' value='{$comment['userName']}'>
                                                    <input type='hidden' name='page' value='requisitioned'>"; // tells the form handler which page to return to
                                            echo   "&nbsp;&nbsp;&nbsp;&nbsp;<button type='submit' class='delete-button'><i class='fa fa-trash' aria-hidden='true'></i></button>
                                                </form>";
                                        }
                                        echo "</div>";
                                        echo "<br>";
                                    }
                                    echo "</dl>";
                                } else {
                                    echo "<h3>Comments</h3>";
                                    echo "<span class='info-label'>No comments have been made under this ticket yet.</span><br>";
                                }

                                // Form to submit a new comment - only if they are the ticket creator
                                if ($userID == $ticketowner){
                                    echo "<form action='submit_comment.php' method='POST'>
                                        <input type='hidden' name='ticketID' value='$ticketID'>
                                        <input type='hidden' name='userID' value='$userID'>
                                        <input type='hidden' name='page' value='requisitioned'>
                                        <textarea name='comment_description' id='comment' rows='2' cols='50' placeholder='Leave a Comment' required></textarea><br>
                                        <button type='submit' class='comment-button'>Submit Comment</button>
                                    </form>";
                                }
                            }
                            else { //deafult image to be displayed by ticket details
                                echo "<div class='carousel'><div class='carousel-images'><div class='carousel-slide'>";
                                echo "<img src='pictures/tools2.jpg' alt='Ticket Image'>";
                                echo "</div></div></div>";
                                echo "<p>Please select a ticket to view details.</p>";
                            }
                        ?>
                    </article>
                </section>
            </div>
        </main>

        <script> 
            document.addEventListener('DOMContentLoaded', function() {
                const unseenCount = <?php echo $unseen_count_requis; ?>;
                const notificationIcon = document.querySelector('.notification-icon');

                if (notificationIcon) {
                    if (unseenCount > 0) {
                        notificationIcon.textContent = unseenCount;
                        notificationIcon.style.display = 'inline-block';
                    } else {
                        notificationIcon.style.display = 'none';
                    }
                }
            });
        </script> <!-- End of Notification code -->

    </div>
    <!-- Link to external JavaScript file -->
    <script src="ticket_tracking.js"></script>
</body>
</html>

<?php $connection->close(); ?>
