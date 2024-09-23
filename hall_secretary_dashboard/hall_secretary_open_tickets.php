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
    // if (isset(($_REQUEST['submit']))) {
    // get hall_sec username from login page/pop-up
    $hall_sec_userName = $_SESSION['username'];
    
    // include database details from config.php file
    require_once("config.php");

    // attempt to make database connection
    $connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

    // Check if connection was successful
    if ($connection->connect_error) {
        die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
    }

    // Remove REQUISITIONED requests from CONFIRMED requests list
    // These requests are now REQUISITIONED - they will go to maintenance staff's OPENED page
    if (isset(($_REQUEST['approve_request']))) {
        $ticketID = $_REQUEST['ticketID'];
        $student_name = $_REQUEST['student_name'];
        $student_room_num = $_REQUEST['student_room_num'];
        $remove_request = "UPDATE ticket SET ticket_status = 'Requisitioned' WHERE ticketID = $ticketID";
        $remove_request_result = $connection->query($remove_request);

        // Check if request removal was successful
        if ($remove_request_result === FALSE) {
            die("<p class=\"error\">Request removal was Unsuccessful!</p>");
        }
    }

    //  use hall_sec userName to hall name
    $hall_name_sql = 
      "SELECT hall_name FROM hall_secretary WHERE HS_userName = '$hall_sec_userName';";
    $hall_name_result = $connection->query($hall_name_sql);
    
    // get ticket information
    $ticket_sql = "SELECT * FROM ticket;";
    $ticket_result = $connection->query($ticket_sql);

    // Get res names of hall overseen by the hall secretary
    $residences =
        "SELECT DISTINCT f_Name, CONCAT(hall_secretary.f_Name, ' ', hall_secretary.l_name) AS 'hall_secretary_name', CONCAT(LEFT(hall_secretary.f_Name, 1), LEFT(hall_secretary.l_Name, 1)) AS initials, resName AS 'residences'
                FROM residence JOIN hall_secretary ON hall_secretary.hall_name = residence.hall_name
                WHERE hall_secretary.HS_userName = '$hall_sec_userName';";
    $residences_result = $connection->query($residences);

    // Check if query successful
    if ($ticket_result === FALSE || !$hall_name_result || !$residences_result) {
        die("<p class=\"error\">Query was Unsuccessful!</p>");
    }

    // get hall secretary name + initials
    $residence = $residences_result->fetch_assoc();
    
    // get hall name of hall_sec
    // REPLACED $hall_name with $_SESSION['hall_name]
    $_SESSION['hall_name'] = $hall_name_result->fetch_assoc()['hall_name'];


    // }
    ?>
    <div class="container">
        <!-- Sidebar section for navigation -->
        <aside class="sidebar">
            <!-- Logo section at the top of the sidebar -->
            <div class="logo">
                <h2>ResQue</h2>
            </div>

            <!-- Search bar in the sidebar -->
            <form action="hall_secretary_open_dashboard.php" method="post" class="search">
                <span id="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input class="search-input" type="search" name="search-field" id="search-field" placeholder="Search">
            </form>

            <!-- Navigation menu in the sidebar -->
            <nav>
                <ul id="sidebar-nav">
                    <!-- Navigation links with icons -->
                    <li id="all-tickets"><a class="sidebar-links" href="<?php echo "hall_secretary_all_tickets.php?hall_sec_userName=$hall_sec_userName&hall_name={$_SESSION['hall_name']}" ?>"><img src="pictures/receipt-icon.png" alt="receipt icon">All Tickets</a></li>
                    <li id="open-tickets"><a class="sidebar-links active" href="<?php echo "hall_secretary_open_tickets.php?hall_sec_userName=$hall_sec_userName&hall_name={$_SESSION['hall_name']}"; ?>"><img src="pictures/layer.png" alt="layer">Opened Tickets</a></li>
                    <li id="closed-tickets"><a class="sidebar-links" href="<?php echo "hall_secretary_closed_tickets.php?hall_sec_userName=$hall_sec_userName&hall_name={$_SESSION['hall_name']}"; ?>"><img src="pictures/clipboard-tick.png" alt="clipboard-tick">Closed Tickets</a></li>
                    <li id="statistics"><a class="sidebar-links" href="<?php echo "../Statistics/Stats_hallsec.php?hall_sec_userName=$hall_sec_userName&hall_name={$_SESSION['hall_name']}" ?>"><img src="pictures/bar-chart-icon.png" alt="bar chart icon">Statistics</a></li>
                </ul>
            </nav>

            <!-- <hr id="sidebar-hr"> -->

            <!-- Profile section at the bottom of the sidebar -->
            <div class="profile">
                <!-- Profile picture area -->
                <div class="profile-pic">
                    <?php 
                        // Get initials and full name
                        $_SESSION['initials'] = $residence['initials'];
                        $_SESSION['full_name'] = $residence['hall_secretary_name'];
                        
                        // get hall sec first name
                        $_SESSION['first_name'] = $residence['f_Name'];
                        
                        echo $_SESSION['initials'];
                    ?>
                </div>
                <!-- Profile information area -->
                <div class="profile-info">
                    <span id="user-name" class="username"><?php echo $_SESSION['full_name']; ?></span><br>
                    <span class="role"><?php echo "Hall Secretary" ?></span>
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
                <h1>Welcome, 
                    <span class="username"><?php echo $_SESSION['first_name']; ?></span>
                </h1>
                <p>Access & Manage maintenance requisitions efficiently.</p>
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
                if (isset($ticketID) && !empty($ticketID)) {
                    echo "<div id='success-message' class='success-message'>
                            <h2>Ticket Requestioned!<i class='fas fa-times cancel-icon' onclick='remove_feedback()'></i></h2>
                            <p>The maintenance request for <strong>$student_name</strong> in <strong>room $student_room_num</strong> has been requisitioned successfully. The maintenance team will be notified shortly.</p>
                        </div>";
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
                    $count = 0;

                    if(isset($_REQUEST['house_name'])){
                        // get approved/confirmed tickets from house warden
                        $housename = $_REQUEST['house_name'];
                        $requisitioned_tickets_query =
                            "SELECT ticketID, concat(f_Name, ' ', l_Name) AS 'full_name', t.resName, room_number, priority
                                    FROM student s JOIN ticket t ON s.userName = t.userName
                                    WHERE ticket_status = 'Confirmed' and t.resName = '$housename';";
                        $requisitioned_tickets_result = $connection->query($requisitioned_tickets_query);
                    }
                
                    else{
                
                        $requisitioned_tickets_query =
                        "SELECT ticketID, concat(f_Name, ' ', l_Name) AS 'full_name', t.resName, room_number, priority
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
                                            <button class='comment-btn' onclick><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;&nbsp;Comment</button>
                                            <button class='delete-btn'><i class='fa-solid fa-trash' style='color: #e53e3e;'></i>&nbsp;&nbsp;&nbsp;Delete</button>
                                        </div>
                                        <!-- Request information -->
                                        <div class='request-info'>
                                            <p><strong>{$row['full_name']}</strong></p>
                                            <p>Residence: <strong>{$row['resName']}</strong></p>
                                            <p>Room Number: <strong>{$row['room_number']}</strong></p>
                                            <form class='request-form' action='hall_secretary_open_tickets.php' method='get'>
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
                                    $count++;
                        }
                    }
                    else {
                        echo "<p><strong>There are currently no Opened tickets!</strong></p>";
                    }
                    echo "COUNT = $count";
                    echo "";

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