<?php
// Start the session
session_start();

// Include database details from config.php file
require_once("../config.php");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Store the userID in the session
    $_SESSION['userID'] = $_POST['userID'];
}

// Get userID from session or set default value
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : '';
                    
// attempt to make database connection
$connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// Check if connection was successful
if ($connection->connect_error) {
    die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Tracking</title>
    <link rel="icon" type="image/x-icon" href="pictures/resque-logo.png">
    <link rel="stylesheet" href="ticket_tracking.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ddbf4d6190.js" crossorigin="anonymous"></script>
</head>
<body>

    <div class="container">

        <!--Sidebar section for navigation-->
        <aside class="sidebar">
            <div class="logo">ResQue</div>

            <!--Search bar in the sidebar-->
            <form action="" class="search">
                <span class="search-icon material-symbols-outlined">search</span>
                <input class="search-input" type="search" name="search-field" id="search-field" placeholder="Search">
            </form>

            <!-- Navigation menu in the sidebar -->
            <nav>
                <ul>
                    <!-- Navigation links with icons -->
                    <li id="log-faults"><a class="sidebar-links" href="../ticket_creation/ticketCreation.html"><img src="pictures/receipt-add.png" alt="Log faults">Log Faults</a></li>
                    <li id="all-tickets"><a class="sidebar-links" href="#"><img src="pictures/receipt-icon.png" alt="All tickets">All Tickets</a></li>
                    <li id="open-tickets"><a class="sidebar-links" href="#"><img src="pictures/layer.png" alt="layer">Open Tickets</a></li>
                    <li id="closed-tickets"><a class="sidebar-links" href="#"><img src="pictures/clipboard-tick.png" alt="clipboard-tick">Closed Tickets</a></li>
                </ul>
            </nav>
    
            <!-- <hr id="sidebar-hr"> -->
    
            <!-- Profile section at the bottom of the sidebar -->
            <div class="profile">
                <!-- Profile picture area -->
                <div class="profile-pic">YR</div>
                <!-- Profile information area -->
                <div class="profile-info">
                    <span id="user-name" class="username">Yeukai Runyowa</span><br>
                    <span class="role">Student</span>
                </div>
                <!-- Logout button with icon -->
                <div id="sidebar-log-out">
                    <a href="#"><i class="fa-solid fa-arrow-right-from-bracket fa-xl" style="color: #B197FC;"></i></a>
                </div>
            </div>
        </aside>

        <!-- Main Ticket Tracking section -->
        <main class="content">
            <header>
                <div>
                    <h1>Ticket Tracking<br></h1>
                    <p class="fade-out">View logged tickets and make comments.</p>
                </div>
                <!-- Fix the logo size -->
                 <div class="logo-container">
                    <img src="pictures/resque-logo.png" alt="Logo" width="150" height="110">
                </div>
            </header>

            <!--  TEMPORARY Form for userID input  -->
            <section class="user-id-input">
                <h3>Enter User ID to View Tickets</h3>
                <form action="ticket_tracking.php" method="POST">
                    <label for="userID">User ID:</label>
                    <input type="text" id="userID" name="userID" required>
                    <button type="submit">Submit</button>
                </form>
            </section>
            <br><br>

            <!-- Flex container for the ticket list and ticket detail -->
            <div class="content-wrapper">
                <!-- Section for the list of tickets -->
                <section class="ticket-list">
                    <!-- <a href="../ticket_creation/ticketCreation.html"><button class="add-ticket">+ Add New Ticket</button></a>
                    <br> -->
                    <h3>Your Tickets</h3>
                    <?php

                        //query instructions for the student's tickets
                        $sql = "SELECT ticketID, resName, ticket_status FROM systemsurgeons.ticket where userName = '$userID'";
                        $result = $connection -> query($sql); //execute query

                        // Check if query successfull
                        if ($result === FALSE) {
                            die("<p class=\"error\">Your Tickets Query was Unsuccessful!</p>");
                        }
                    
                        //display the student's tickets
                        echo "<table class='ticket-table'>";
                        while($row = $result->fetch_assoc()) {
                            echo "<tr class='ticket-card'>";
                            echo "<td class='ticket-number'><img src='pictures/clipboard-tick.png' alt='clipboard-tick' style='margin-right: 10px;'>#{$row['ticketID']}</td>";
                                
                                // Determine the CSS class based on the ticket_status so the correct color is produced
                                if ($row['ticket_status'] == "Pending") {
                                    $statusClass = "status pending";
                                } elseif ($row['ticket_status'] == "Processing") {
                                    $statusClass = "status processing";
                                } elseif ($row['ticket_status'] == "Completed") {
                                    $statusClass = "status completed";
                                } elseif ($row['ticket_status'] == "Rejected") {
                                    $statusClass = "status rejected";
                                } else {
                                    $statusClass = "status"; // Default class if needed
                                }
                                
                            //store residence name to use in Residence Tickets section
                            $residence = $row['resName'];

                            echo "<td><span class='{$statusClass}'><span class='circle'></span>&nbsp;&nbsp;{$row['ticket_status']}</span></td>";
                            echo "<td><button class='details-button' onclick='showDetails('734959')'>View Details</button></td>";
                            echo "</tr>";
                        } //end table
                        echo "</table>";
                    ?>

                    <?php
                    echo "<h3>$residence Tickets</h3>";

                        //query instructions for all tickets within the same residence
                        $sql = "SELECT ticketID, resName, ticket_status FROM systemsurgeons.ticket where resName = '$residence'";
                        $result = $connection -> query($sql); //execute query

                        // Check if query successfull
                        if ($result === FALSE) {
                            die("<p class=\"error\">Residence Tickets Query was Unsuccessful!</p>");
                        }

                        //dynamically display all tickets within that residence
                        echo "<table class='ticket-table'>";
                        while($row = $result->fetch_assoc()) {
                            echo "<tr class='ticket-card'>";
                            echo "<td class='ticket-number'><img src='pictures/clipboard-tick.png' alt='clipboard-tick' style='margin-right: 10px;'>#{$row['ticketID']}</td>";
                                
                                // Determine the CSS class based on the ticket_status so the correct color is produced
                                if ($row['ticket_status'] == "Pending") {
                                    $statusClass = "status pending";
                                } elseif ($row['ticket_status'] == "Processing") {
                                    $statusClass = "status processing";
                                } elseif ($row['ticket_status'] == "Completed") {
                                    $statusClass = "status completed";
                                } elseif ($row['ticket_status'] == "Rejected") {
                                    $statusClass = "status rejected";
                                } else {
                                    $statusClass = "status"; // Default class if needed
                                }

                            echo "<td><span class='{$statusClass}'><span class='circle'></span>&nbsp;&nbsp;{$row['ticket_status']}</span></td>";
                            echo "<td><button class='details-button' onclick='showDetails('734959')'>View Details</button></td>";
                            echo "</tr>";
                        } //end table
                        echo "</table>";
                    ?>

                </section>
                

                <!-- Section for the detailed view of a single ticket -->
                <section class="ticket-detail">
                    <article class="ticket-info">
                        <img src="pictures/leak.jpg" alt="Ticket Image">
                        <h3>Ticket Details</h3>
                        <table class="info-table">
                            <tr>
                                <td class="info-cell">
                                    <span class="info-label">Ticket Number:</span>
                                    <span class="info-data">#123454</span>
                                </td>
                                <td class="info-cell">
                                    <span class="info-label">Residence:</span>
                                    <span class="info-data">Cory House</span>
                                </td>
                                <td class="info-cell">
                                    <span class="info-label">Category:</span>
                                    <span class="info-data">Plumbing</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="info-cell">
                                    <span class="info-label">Room Number:</span>
                                    <span class="info-data">21</span>
                                </td>
                                <td class="info-cell">
                                    <span class="info-label">Comments:</span>
                                    <span class="info-data">2</span>
                                </td>
                                <td class="info-cell">
                                    <span class="info-label">Status:</span>
                                    <span class="info-data">Processing</span>
                                </td>
                            </tr>
                        </table>
                    </article>
                </section>
            </div>
        </main>


    </div>
    <!-- Link to external JavaScript file -->
    <script src="ticket_tracking.js"></script>
</body>
</html>

<?php $connection->close(); ?>
