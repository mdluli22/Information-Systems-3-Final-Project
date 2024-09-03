 `<!DOCTYPE html>
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
        // get hall name from login page/pop-up
        $warden_userName = "w07k1234";
        $resName = "Adamson House";// $_REQUEST['resName'];

        // include database details from config.php file
        require_once("config.php");

        // attempt to make database connection
        $connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check if connection was successful
        if ($connection->connect_error) {
            die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
        }

        // query instructions
        $sql = "SELECT * FROM ticket;-- WHERE ticket_status = 'Processing'";
        $result = $connection->query($sql);

        $pending_query = 
            "SELECT concat(f_Name, ' ', l_Name) AS 'full_name', t.resName, room_number, priority
            FROM student s JOIN ticket t ON s.userName = t.userName
            WHERE lower(ticket_status) = 'pending'";
        $pending_result = $connection->query($pending_query);


        // Check if query successfull
        if ($result === FALSE || $pending_result === FALSE) {
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
                    <li id="all-tickets"><a class="sidebar-links active" href="<?php echo "house_warden_all_tickets.php?warden_userName=$warden_userName&hall_name=$resName"?>"><img src="pictures/receipt-icon.png" alt="receipt icon">All Tickets</a></li>
                    <li id="open-tickets"><a class="sidebar-links" href="<?php echo "house_warden_open_tickets.php?warden_userName=$warden_userName&hall_name=$resName"; ?>"><img src="pictures/layer.png" alt="layer">Opened Tickets</a></li>
                    <li id="closed-tickets"><a class="sidebar-links" href="<?php echo "house_warden_closed_tickets.php?warden_userName=$warden_userName&hall_name=$resName"; ?>"><img src="pictures/clipboard-tick.png" alt="clipboard-tick">Closed Tickets</a></li>
                    <li id="statistics"><a class="sidebar-links" href="<?php echo "../Statistics/Stats_warden.php?warden_userName=$warden_userName&hall_name=$resName"?>"><img src="pictures/bar-chart-icon.png" alt="bar chart icon">Statistics</a></li>
                </ul>
            </nav>
    
            <!-- <hr id="sidebar-hr"> -->
    
            <!-- Profile section at the bottom of the sidebar -->
            <div class="profile">
                <!-- Profile picture area -->
                <div class="profile-pic">
                    <?php echo "TT";?>
                </div>
                <!-- Profile information area -->
                <div class="profile-info">
                    <span id="user-name" class="username"><?php echo "Thokozile Tshabalala"?></span><br>
                    <span class="role"><?php echo "Warden"?></span>
                </div>
                <!-- Logout button with icon -->
                <div id="sidebar-log-out">
                    <a href="#"><i class="fa-solid fa-arrow-right-from-bracket fa-xl" style="color: #B197FC;"></i></a>
                </div>
            </div>
        </aside>

        <!-- Main content area -->
        <main class="content">
            <header class="page-header">
                <!-- Welcome message -->
                <h1>Welcome, <span class="username"><?php echo "Thokozile"?></span></h1>
                <p>Access & Manage maintenance requisitions efficiently.</p>
            </header>
            <!-- removed the Ticket table section -->

            <!-- Maintenance requests section -->
            <section class="maintenance-requests">
                <header id="maintenance-requests-header">
                    <!-- Header with title and view all button -->
                    <h2 id="h2">Tickets Pending Approval</h2>
                    <!-- <button class="view-all">View all</button> -->
                </header>

                <!-- populate maintenance faults pending approval -->
                <div class="requests">
                    <?php 
                        while ($row = $pending_result->fetch_assoc())
                        {
                            echo "<article class='request'>
                                    <div class='request-top-btns request-btns'>
                                        <!-- Buttons for commenting and deleting a request -->
                                        <button class='comment-btn'><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;&nbsp;Comment</button>
                                        <button class='delete-btn'><i class='fa-solid fa-trash' style='color: #e53e3e;'></i>&nbsp;&nbsp;&nbsp;Delete</button>
                                    </div>
                                    <!-- Request information -->
                                    <div class='request-info'>";
                            echo    "<p><strong>{$row['full_name']}<strong></p>";
                            echo       "<p>Residence: <strong>{$row['resName']}<strong></p>";
                            echo       "<p>Room Number: <strong>{$row['room_number']}<strong></p>";
                            echo       "<p>Priority: <strong>{$row['priority']}<strong>";
                            echo       "<button class='approve-btn request-btns'><i class='fa-solid fa-plus' style='color: #a020f0;'></i>&nbsp;&nbsp;&nbsp;Approve Request</button></p>";
                            echo    "</div></article>";
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