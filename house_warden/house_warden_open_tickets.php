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

        // Remove APPROVED/CONFIRMED requests from OPENED requests list
        // These requests are now CONFIRMED - they will go to hall secretary's OPENED page
        if (isset(($_REQUEST['approve_request']))) {
            $ticketID = $_REQUEST['ticketID'];
            $student_name = $_REQUEST['student_name'];
            $student_room_num = $_REQUEST['student_room_num'];
            $remove_request = "UPDATE ticket SET ticket_status = 'Confirmed' WHERE ticketID = $ticketID";
            $remove_request_result = $connection->query($remove_request);

            // Check if request removal was successful
            if ($remove_request_result === FALSE) {
                die("<p class=\"error\">Request removal was Unsuccessful!</p>");
            }
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
        $sql = "SELECT ticketID, concat(f_Name, ' ', l_Name) AS 'full_name', t.resName, room_number, priority FROM student s JOIN ticket t ON s.userName = t.userName WHERE ticket_status = 'Opened' and t.resName = '$resname';";;
        $opened_tickets_result = $connection->query($sql);



        // Check if query successfull
        if ($opened_tickets_result === FALSE) {
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
                    <li id="all-tickets"><a class="sidebar-links" href="<?php echo "house_warden_all_tickets.php?warden_userName=$warden_userName&res_name={$resname}"?>"><img src="pictures/receipt-icon.png" alt="receipt icon">All Tickets</a></li>
                    <li id="open-tickets"><a class="sidebar-links active" href="<?php echo "house_warden_open_tickets.php?warden_userName=$warden_userName&res_name={$resname}"; ?>"><img src="pictures/layer.png" alt="layer">Opened Tickets</a></li>
                    <li id="closed-tickets"><a class="sidebar-links" href="<?php echo "house_warden_closed_tickets.php?warden_userName=$warden_userName&res_name={$resname}"; ?>"><img src="pictures/clipboard-tick.png" alt="clipboard-tick">Closed Tickets</a></li>
                    <li id="statistics"><a class="sidebar-links" href="<?php echo "Stats_warden.php?warden_userName=$warden_userName&res_name={$resname}"?>"><img src="pictures/bar-chart-icon.png" alt="bar chart icon">Statistics</a></li>
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
                    <span id="user-name" class="username"><?php echo $wardeName?></span><br>
                    <span class="role"><?php echo "Warden"?></span>
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
                <h1>Welcome, <span class="username"><?php echo $wardeName?></span></h1>
                <p>Access & Manage maintenance requisitions efficiently.</p>
            </header>
            <!-- removed the Ticket table section -->

            <?php
                if (isset($ticketID) && !empty($ticketID)) {
                    echo "<div id='success-message' class='success-message'>
                            <h2>Request Approved!<i class='fas fa-times cancel-icon' onclick='remove_feedback()'></i></h2>
                            <p>The maintenance request for <strong>$student_name</strong> in <strong>room $student_room_num</strong> has been approved successfully. The hall secretary will be notified shortly.</p>
                        </div>";
                }
            ?>

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
                        $count = 0;
                        if ($opened_tickets_result->num_rows > 0) {
                            while ($row = $opened_tickets_result->fetch_assoc())
                            {
                                echo "<article class='request'>
                                        <div class='request-top-btns request-btns'>
                                            <!-- Buttons for commenting and deleting a request -->
                                            <button class='comment-btn' onclick><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;&nbsp;Comment</button>
                                            <button class='delete-btn'><i class='fa-solid fa-trash' style='color: #e53e3e;'></i>&nbsp;&nbsp;&nbsp;Delete</button>
                                        </div>
                                        <!-- Request information -->
                                        <div class='request-info'>
                                            <p><strong>{$row['full_name']}</strong></p>
                                            <p>Residence: <strong>{$row['resName']}</strong></p>
                                            <p>Room Number: <strong>{$row['room_number']}</strong></p>
                                            <form class='request-form' action='house_warden_open_tickets.php' method='get'>
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
                                    
                                // echo "<article class='request'>
                                //         <div class='request-top-btns request-btns'>
                                //             <!-- Buttons for commenting and deleting a request -->
                                //             <button class='comment-btn'><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;&nbsp;Comment</button>
                                //             <button class='delete-btn'><i class='fa-solid fa-trash' style='color: #e53e3e;'></i>&nbsp;&nbsp;&nbsp;Delete</button>
                                //         </div>
                                //         <!-- Request information -->
                                //         <div class='request-info'>
                                //             <p><strong>{$row['full_name']}<strong></p>
                                //             <p>Residence: <strong>{$row['resName']}<strong></p>
                                //             <p>Room Number: <strong>{$row['room_number']}<strong></p>
                                //             <p>Priority: <strong>{$row['priority']}<strong>
                                //             <button class='approve-btn request-btns'><i class='fa-solid fa-plus' style='color: #a020f0;'></i>&nbsp;&nbsp;&nbsp;Approve Request</button></p>
                                        
                                //           </div>
                                //     </article>";
                                $count++;
                            }
                        }
                        else {
                            echo "<p><strong>There are currently no Opened tickets!</strong></p>";
                        }
                        echo "COUNT = $count";
                        echo "";
                    ?>
                </div>
                
            </section>
        </main>
    </div>
    <!-- Link to external JavaScript file -->
    <script src="house_warden.js"></script>
</body>
</html>