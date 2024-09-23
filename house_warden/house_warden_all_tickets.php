<?php
    require_once("secure.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Warden</title>
    <link rel="icon" type="image/x-icon" href="pictures/resque-logo.png">
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

    $warden_res_query = "SELECT resName, concat(f_Name, ' ', l_Name) as 'Name' FROM house_warden WHERE userName = '$warden_userName';";
    $warden_res_query_result = $connection->query($warden_res_query);
    
    if ($warden_res_query_result === FALSE) {
        die("<p class=\"error\">Query was Unsuccessful!</p>");
    }

    $resnamel = $warden_res_query_result->fetch_assoc();
    $resname = $resnamel['resName'];
    $wardeName = $resnamel['Name'];

    // query instructions for tickets pending and processing
    $sql = "SELECT * FROM ticket WHERE resName = '$resname' ;";
    $result = $connection->query($sql);

    // Check if query successfull
    if ($result === FALSE) {
        die("<p class=\"error\">Query was Unsuccessful!</p>");
    }

    // close connection
    $connection->close();
    ?>
    <div class="container">
        <!-- Sidebar section for navigation -->
        <aside class="sidebar">
            <!-- Logo section at the top of the sidebar -->
            <div class="logo">
                <h2>ResQue</h2>
            </div>

            <!-- Search bar in the sidebar -->
            <form action="hall_secretary_dashboard.php" method="post" class="search">
                <span id="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input class="search-input" type="search" name="search-field" id="search-field" placeholder="Search">
            </form>

            <!-- Navigation menu in the sidebar -->
            <nav>
                <ul id="sidebar-nav">
                    <!-- Navigation links with icons -->
                    <li id="all-tickets"><a class="sidebar-links active" href="<?php echo "house_warden_all_tickets.php?warden_userName=$warden_userName&hall_name=$resname" ?>"><img src="pictures/receipt-icon.png" alt="receipt icon">All Tickets</a></li>
                    <li id="open-tickets"><a class="sidebar-links" href="<?php echo "house_warden_open_tickets.php?warden_userName=$warden_userName&hall_name=$resname"; ?>"><img src="pictures/layer.png" alt="layer">Opened Tickets</a></li>
                    <li id="closed-tickets"><a class="sidebar-links" href="<?php echo "house_warden_closed_tickets.php?warden_userName=$warden_userName&hall_name=$resname"; ?>"><img src="pictures/clipboard-tick.png" alt="clipboard-tick">Closed Tickets</a></li>
                    <li id="statistics"><a class="sidebar-links" href="<?php echo "Stats_warden.php?warden_userName=$warden_userName&hall_name=$resname" ?>"><img src="pictures/bar-chart-icon.png" alt="bar chart icon">Statistics</a></li>
                </ul>
            </nav>

            <!-- <hr id="sidebar-hr"> -->

            <!-- Profile section at the bottom of the sidebar -->
            <div class="profile">
                <!-- Profile picture area -->
                <div class="profile-pic">
                    <?php echo "TT"; ?>
                </div>
                <!-- Profile information area -->
                <div class="profile-info">
                    <span id="user-name" class="username"><?php echo $wardeName ?></span><br>
                    <span class="role"><?php echo "Warden" ?></span>
                </div>
                <!-- Logout button with icon -->
                <div id="sidebar-log-out">
                    <a href="../landing_page/logout.php" onclick = " return confirm('Are you sure you want to log out')"><i class="fa-solid fa-arrow-right-from-bracket fa-xl" style="color: #B197FC;"></i></a>
                </div>
            </div>
        </aside>

        <!-- Main content area -->
        <main class="content">
            <header class="page-header">
                <!-- Welcome message -->
                <h1>Welcome, <span class="username"><?php echo $wardeName  ?></span></h1>
                <p>Access & Manage maintenance requisitions efficiently.</p>
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
                        </tr>
                    </thead>
                    <tbody>
                        <!-- populate dashboard board with tickets from database -->
                        <?php
                            while ($row = $result->fetch_assoc())
                            {
                                if ($row['ticket_status'] != "Pending") {
                                    echo "<tr><td>#{$row['ticketID']}</td>";
                                    echo "<td>{$row['ticket_description']}</td>";
                                    echo "<td><span class='status processing'><span class='circle'></span>&nbsp;&nbsp;{$row['ticket_status']}</span></td>";
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
                        ?>
                    </tbody>
                </table>
            </section>

            <!-- Maintenance requests section -->


        </main>

        <!-- Link to external JavaScript file -->
        <script src="house_warden.js"></script>
</body>

</html>