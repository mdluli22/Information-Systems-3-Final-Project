<?php
//session_start();

require_once("secure.php");

if (isset($_SESSION['username'])) {
    // echo 'Session Username: ' . $_SESSION['username'];
    $userID = $_SESSION['username'];
}else {
    die("User is not logged in.");
}

// Check if a success parameter is present in the URL
if (isset($_GET['success']) && $_GET['success'] == 1 && isset($_GET['message'])) {
    $successMessage = htmlspecialchars($_GET['message']); // Sanitize the message for output
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Form | ResQue</title>
    <link rel="icon" type="image/x-icon" href="pictures/2-removebg-preview.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />    
    <link rel="stylesheet" href="ticketCreationStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="scriptTC.js "></script>
</head>
<body>
<?php
    // include database details from config.php file
    require_once("config.php");

    // attempt to make database connection
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

    // Check if connection was successful
    if ($conn -> connect_error) {
        die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
    }

    //for the res name on top
    $sql1 = "SELECT resName FROM student WHERE userName = '$userID'
            UNION
            SELECT resName FROM house_warden WHERE userName = '$userID';";
    $result = $conn->query($sql1);

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $resName = $row['resName'];
    } else {
        $resName = "Residence not found";
    }

    // Get user role from the 'user' table
    $sqlRole = "SELECT user_role FROM user WHERE userName = '$userID'";
    $resultRole = $conn->query($sqlRole);

    if ($resultRole->num_rows > 0) {
        $userRoleRow = $resultRole->fetch_assoc();
        $userRole = $userRoleRow['user_role'];  // Retrieve the user role
    } else {
        die("<p class=\"error\">User role not found!</p>");
    }

    // For student and house warden user role
    if ($userRole == 'student') {
        $sql = "SELECT s.f_Name AS first_name, s.l_Name AS last_name, CONCAT(LEFT(s.f_Name, 1), LEFT(s.l_Name, 1)) AS initials, '$userRole' AS role
                FROM student AS s 
                WHERE s.userName = '$userID'";
    } elseif ($userRole == 'house warden') {
        $sql = "SELECT w.f_Name AS first_name, w.l_Name AS last_name, CONCAT(LEFT(w.f_Name, 1), LEFT(w.l_Name, 1)) AS initials, '$userRole' AS role, w.resName
                FROM house_warden AS w 
                WHERE w.userName = '$userID'";
    } else {
        die("<p class=\"error\">User role not recognized!</p>");
    }

    $result = $conn->query($sql);  

    // Check if the query was successful and fetch the result
    if ($result && $result->num_rows > 0) {
    $studentInfo = $result->fetch_assoc();
    $_SESSION['initials'] = $studentInfo['initials'];
    $_SESSION['full_name'] = $studentInfo['first_name'] . ' ' . $studentInfo['last_name'];
    $_SESSION['role'] = $studentInfo['role'];
    } else {
        die("<p class=\"error\">Student not found or query unsuccessful!</p>");
    }

    $conn->close();
?>
    <div class="container">
        <!-- the white left side of the page -->
            <aside class="sidebar">
                <div class="logo">ResQue</div>
                <button class="sidebar__collapse-button" id="collapseBtn">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <nav>
                    <ul>
                        <li id="logFaults" class="sidebar-item">
                            <a href="../landing_Page/ticketCreationFinal.php">
                                <img src="pictures/receipt-add.png" alt="receipt-add"><span>Log faults</span></a>
                        </li>
                        <li id="allTickets" class="sidebar-item">
                        <a class="sidebar-links" href="<?php echo "house_warden_all_tickets.php?warden_userName=$warden_userName&res_name={$resname}"; ?>">
                                <img src="pictures/receipt-icon.png" alt="receipt-icon"><span>All Tickets</span></a>
                        </li>
                        <li id="openTickets" class="sidebar-item">
                            <a href="../ticket_tracking/ticket_tracking_open.php"><img src="pictures/layer.png" alt="layer"><span>Open Tickets</span></a>
                        </li>
                        <li id="closedTickets" class="sidebar-item">
                            <a href="../ticket_tracking/ticket_tracking_closed.php"><img src="pictures/clipboard-tick.png" alt="clipboard-tick"><span>Closed Tickets</span></a>
                        </li>
                    </ul>
                </nav>

                <div class="profile">
                    <div class="profile-pic">
                        <?php echo $_SESSION['initials']; ?>
                    </div>
                    <div class="profile-info">
                        <span id="user-name" class="username"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span><br>
                        <span class="role"><?php echo htmlspecialchars($_SESSION['role']); ?></span>
                    </div>
                    <div id="sidebar-log-out">
                        <a href="../landing_page/logout.php" onclick="return confirm('Are you sure you want to log out')">
                            <i class="fa-solid fa-arrow-right-from-bracket fa-xl" style="color: #B197FC;"></i>
                        </a>
                    </div>
                </div>
            </aside>
        <script>
            document.getElementById("collapseBtn").addEventListener("click", function() {
            const sidebar = document.querySelector(".sidebar");
            sidebar.classList.toggle("collapsed");

            // Toggle the chevron icon direction
            const icon = this.querySelector(".material-symbols-outlined");
            if (sidebar.classList.contains("collapsed")) {
                icon.textContent = "chevron_right"; // Change icon to right chevron
            } else {
                icon.textContent = "chevron_left"; // Change icon to left chevron
            }
            });
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('collapsed');
            }
        </script>
        
        <main class="content">
            <header class="page-header">
                <div>
                    <h1>Maintenance requisition form</h1>   
                    <p class="fade-out" id="residence" name="residence">
                        <?php echo htmlspecialchars($resName); ?>
                    </p>
                </div>
                 
                <img src="pictures/fake logo(1).png" alt="Logo" width="150" height="110">
            </header>
            <?php

                // Assuming that the ticket ID, student name, and room number are stored in session or passed through URL.
                if (isset($_SESSION['ticketID']) && !empty($_SESSION['ticketID'])) {
                    $ticketID = $_SESSION['ticketID'];

                    // Fetch user information (assuming it's stored in the session or retrieved earlier)
                    $user_name = $_SESSION['full_name']; 

                    echo "<div id='success-message' class='success-message'>
                            <h2>Ticket Requisitioned!<i class='fas fa-times cancel-icon' onclick='remove_feedback()'></i></h2>
                            <p>The maintenance request for <strong>". ($_SESSION['full_name']) ."</strong> has been requisitioned successfully. The maintenance team will be notified shortly.</p>
                        </div>";
                } 
                // else {
                //     echo "<p class='error'>No ticket information found.</p>";
                // }
            ?>

            <script>
                function remove_feedback() {
                    const successMessage = document.getElementById('success-message');
                    if (successMessage) {
                        successMessage.style.display = 'none';
                    }
                }
            </script>
            <section>
                <div>
                    <!-- the actual form for fault -->
                    <form action="ticketCreation.php" method="post" enctype="multipart/form-data" class="requisition-form">
                        <div class="form-header">
                            <h3>Requisition Details</h3>
                            <p class="fade-out">Please fill in the form below</p>
                        </div>

                        <!-- dropdown for fault category -->
                        <div class="form-group">
                            <label for="fault-category">Fault Category *</label>
                            <div class="form-input">
                                <select id="fault-category" name="fault-category" required>
                                    <option value="" >Please enter fault category</option>
                                    <option value="Electrical">Electrical</option>
                                    <option value="Plumbing">Plumbing</option>
                                    <option value="Furniture">Furniture</option>
                                    <option value="Heater">Heater</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <div class="form-extra-info"><small>Please provide any additional information or instructions related to the requisition</small></div>
                            <div class="form-input">
                                <textarea id="description" name="description" required></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="priority">Priority *</label>
                            <div class="form-input">
                                <select id="priority" name="priority" required>
                                    <option value="">Please indicate severity of fault</option>
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="picture">Upload an Image</label>
                            <div class="form-input">
                                <input type="file" name="picture[]" id="picture" placeholder="Choose file" multiple>
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type="hidden" id="resName" name="residence" value="<?php $resName; ?>">
                            <button type="reset" class="cancel-btn">Cancel</button>
                            <input type="submit" value="Submit" >   
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>
    </div>
</body>
</html>