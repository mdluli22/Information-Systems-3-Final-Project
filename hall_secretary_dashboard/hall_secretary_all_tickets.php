<?php
    require_once("secure.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Secretary Dashboard | ResQue</title>
    <link rel="icon" type="image/x-icon" href="ResQue/hall_secretary_dashboard/pictures/finalLogo.png">
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

        // attempt to make database connection
        $connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check if connection was successful
        if ($connection->connect_error) {
            die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
        }

        // Get res names of hall overseen by the hall secretary           
        $residences = 
        "SELECT DISTINCT concat(hall_secretary.f_Name, ' ', hall_secretary.l_name) AS 'hall_secretary_name', resName AS 'residences'
        FROM residence JOIN hall_secretary ON hall_secretary.hall_name = residence.hall_name
        WHERE hall_secretary.HS_userName = '$hall_sec_userName';";

        $residences_result = $connection->query($residences);

        // Fetch residence details if any
        $residence = $residences_result->fetch_assoc();
        
        //get all tickets
        $all_tickets_query = 
            "SELECT concat(f_Name, ' ', l_Name) AS 'full_name', t.resName, room_number, priority
            FROM student s JOIN ticket t ON s.userName = t.userName;";
        $all_tickets_query_results = $connection->query($all_tickets_query);

        // Check if query successful
        if ($residences_result === FALSE || !$all_tickets_query_results) {
            die("<p class=\"error\">Query was Unsuccessful!</p>");
        }

        //  use hall_sec userName to hall name
        $hall_name_sql = 
        "SELECT hall_name FROM hall_secretary WHERE HS_userName = '$hall_sec_userName';";
        $hall_name_result = $connection->query($hall_name_sql);

        // Check if hall name query was successful
        if ($hall_name_result === FALSE) {
            die("<p class=\"error\">Query for hall name was Unsuccessful!</p>");
        }

        // Get hall name of hall_sec
        $hall_name_row = $hall_name_result->fetch_assoc();
        $_SESSION['hall_name'] = $hall_name_row['hall_name'];

        if ($residence) {
            // Split the full name into parts using space as the delimiter
            $name_parts = explode(' ', $residence['hall_secretary_name']);
            // Store the first part (first name) in the session variable
            $_SESSION['first_name'] = $name_parts[0];
        } else {
            die("<p class=\"error\">Unable to retrieve hall secretary details!</p>");
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
                    <h1>Welcome, <span class="username"><?php echo $_SESSION['first_name']; ?></span></h1>
                    <p>Access & Manage maintenance requisitions efficiently.</p>
                </div>
                <div class="logo-container">
                    <img src="../landing_page/pictures/fake logo(1).png" alt="Logo">
                </div>
            </header>

            <!-- House selection links -->
            <nav class="houses">
            <?php
                    
                    $active = 0;
                    while ($residence = $residences_result->fetch_assoc()) {
                        
                        if ($active == 0) {
                            $active++;
                            $defaulthouse = $residence['residences'];
                        }

                        $activeHouse = isset($_REQUEST['house_name']) ? $_REQUEST['house_name'] : $defaulthouse;
                        $isActive = ($residence['residences'] === $activeHouse) ? 'active' : '';
                        echo "<a href='hall_secretary_all_tickets.php?house_name={$residence['residences']}' class='house-link {$isActive}'>{$residence['residences']}</a>";
                    }
                ?>
            </nav>

            <!-- Ticket table section -->
            <section class="ticket-table"> <!--scrollbar"> -->
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
                            if(isset($_REQUEST['house_name'])){
                                $housename = $_REQUEST['house_name'];
                                // get ticket information
                                $ticket_sql = "SELECT * FROM ticket  WHERE resName = '$housename' ORDER BY ticketID DESC;";
                                $ticket_result = $connection->query($ticket_sql);
                            }
                            else{
                                // get ticket information
                                $ticket_sql = "SELECT * FROM ticket  WHERE resName = '$defaulthouse' ORDER BY ticketID DESC;";
                                $ticket_result = $connection->query($ticket_sql);
                            }

                            if ($ticket_result === FALSE) {
                                die("<p class=\"error\">Query was Unsuccessful!</p>");
                            }

                            if ($ticket_result->num_rows > 0) {
                                while ($row = $ticket_result->fetch_assoc())
                                {
                                    echo "<tr><td>#{$row['ticketID']}</td>";
                                    echo "<td>{$row['ticket_description']}</td>";
                                    // display ticket status
                                    switch (strtolower($row['ticket_status'])) {
                                        case "completed":
                                            echo "<td><span id='completed'><span class='circle'></span>&nbsp;&nbsp;{$row['ticket_status']}</span></td>";
                                            break;
                                        case "rejected":
                                            echo "<td><span id='rejected'><span class='circle'></span>&nbsp;&nbsp;{$row['ticket_status']}</span></td>";
                                            break;
                                        default:
                                            echo "<td><span class='status processing'><span class='circle'></span>&nbsp;&nbsp;{$row['ticket_status']}</span></td>";
                                    }
                                    echo "<td>" . date("D h:ia", strtotime($row['ticketDate'])) . "</td>";
                                    echo "<td>{$row['category']}</td>";
                                    // display ticket priority
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
                            else {
                                echo "<tr><td colspan=3> <p> No Tickets Available </p></td></tr>";
                            }
                            // close connection
                            $connection->close();
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <!-- Link to external JavaScript file -->
    <script src="hall_secretary_dashboard.js"></script>

</body>
</html>