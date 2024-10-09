<?php
// include database details from config.php file
require_once("config.php");

// attempt to make database connection
$connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// Check if connection was successful
if ($connection->connect_error) {
    die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
}

// Get the username from the session
$warden_userName = $_SESSION['username'];

// Query to fetch warden details
$warden_res_query = "
    SELECT resName, 
           CONCAT(f_Name, ' ', l_Name) as 'Name', 
           CONCAT(LEFT(house_warden.f_Name, 1), LEFT(house_warden.l_Name, 1)) AS initials
    FROM house_warden 
    WHERE userName = '$warden_userName';";

$warden_res_query_result = $connection->query($warden_res_query);

if ($warden_res_query_result === FALSE) {
    die("<p class=\"error\">Query was Unsuccessful!</p>");
}

$resnamel = $warden_res_query_result->fetch_assoc();
$wardeName = $resnamel['Name']; // Full name
$initials = $resnamel['initials']; // Initials

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" type="image/x-icon" href="pictures/2-removebg-preview.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />    
    <link rel="stylesheet" href="ticketCreationStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
            <aside class="sidebar">
                <!-- Logo section at the top of the sidebar -->
                <div class="logo">ResQue</div>
                <button class="sidebar__collapse-button" id="collapseBtn">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                
                <!-- Navigation menu in the sidebar -->
                <nav>
                    <ul>
                        <!-- Navigation links with icons -->
                        <li id="logFaults" class="sidebar-item">
                            <a class="sidebar-links" href="<?php echo "ticketCreationFinalWarden.php"; ?>">
                                <img src="pictures/addIcon.svg" alt="receipt-add"><span>Log Faults</span>
                            </a>
                        </li>
                        <li id="all-tickets" class="sidebar-item">
                            <a class="sidebar-links" href="<?php echo "../house_warden/house_warden_all_tickets.php?warden_userName={$warden_userName}&res_name={$resnamel['resName']}"; ?>">
                                <img src="pictures/Icon(1).svg" alt="receipt icon"><span>All Tickets</span>
                            </a>
                        </li>
                        <li id="open-tickets" class="sidebar-item">
                            <a class="sidebar-links" href="<?php echo "../house_warden/house_warden_open_tickets.php?warden_userName={$warden_userName}&res_name={$resnamel['resName']}"; ?>">
                                <img src="pictures/layers-05.svg" alt="layer"><span>Opened Tickets</span>
                                <!-- Notification Icon -->
                                <span id="notification-icon" class="notification-icon"></span>
                            </a>
                        </li>
                        <li id="confirmed" class="sidebar-item">
                            <a class="sidebar-links" href="<?php echo "../house_warden/house_warden_confirmed.php?warden_userName={$warden_userName}&res_name={$resnamel['resName']}"; ?>">
                            <img src="pictures/check-broken.svg" alt="layer"><span>Confirmed Tickets</span>
                                <!-- Notification Icon -->
                                <span id="notification-icon" class="notification-icon"></span>
                            </a>
                        </li>
                        <li id="requis" class="sidebar-item">
                            <a class="sidebar-links" href="<?php echo "../house_warden/house_warden_requis_tickets.php?warden_userName={$warden_userName}&res_name={$resnamel['resName']}"; ?>">
                            <img src="pictures/check-contained.svg" alt="layer"><span>Requisitioned Tickets</span>
                                <!-- Notification Icon -->
                                <span id="notification-icon" class="notification-icon"></span>
                            </a>
                        </li>
                        <li id="resolved" class="sidebar-item">
                            <a class="sidebar-links" href="<?php echo "../house_warden/house_warden_resolved_ticket.php?warden_userName={$warden_userName}&res_name={$resnamel['resName']}"; ?>">
                            <img src="pictures/check-square-broken.svg" alt="layer"><span>Resolved Tickets</span>
                                <!-- Notification Icon -->
                                <span id="notification-icon" class="notification-icon"></span>
                            </a>
                        </li>
                        <li id="closed-tickets" class="sidebar-item">
                            <a class="sidebar-links" href="<?php echo "../house_warden/house_warden_closed_tickets.php?warden_userName={$warden_userName}&res_name={$resnamel['resName']}"; ?>">
                                <img src="pictures/check-square-contained.svg" alt="clipboard-tick"><span>Closed Tickets</span>
                            </a>
                        </li>
                        <li id="statistics" class="sidebar-item">
                            <a class="sidebar-links" href="<?php echo "../house_warden/Stats_warden.php?warden_userName={$warden_userName}&res_name={$resnamel['resName']}"; ?>">
                                <img src="pictures/Icon.svg" alt="bar chart icon"><span>Statistics</span>
                            </a>
                        </li>
                    </ul>
                </nav>


                <!-- <hr id="sidebar-hr"> -->

                <!-- Profile section at the bottom of the sidebar -->
                <div class="profile">
                    <div class="profile-pic">
                        <?php echo $initials;?>
                    </div>
                    <!-- Profile information area -->
                    <div class="profile-info">
                        <span id="user-name" class="username"><?php echo $wardeName?></span><br>
                        <span class="role"><?php echo "Warden"?></span>
                    </div>
                    <!-- Logout button with icon -->
                    <div id="sidebar-log-out">
                        <a href="../landing_page/logout.php" onclick = " return confirm('Are you sure you want to log out')">
                            <i class="fa-solid fa-arrow-right-from-bracket fa-xl" style="color: #B45C3D;"></i>
                        </a>
                    </div>
                </div>
            </aside>
<style>
    /* Highlight the active sidebar link */
.sidebar-links.active {
    background-color: #B45C3D; /* Highlighting color */
    color: white; /* Text color */
}

body {
    margin: 0;
    padding: 0;
    font-family: 'Inter', sans-serif;
    height: 100vh;
}

* {
    box-sizing: border-box;
}

.sidebar {
    position: fixed;
    width: 17rem; /* 280px */
    background-color: #12222E;
    padding: 1.5rem; /* 24px */
    padding-top: 2.5rem; /* Add extra padding to accommodate the logo height */
    display: flex;
    flex-direction: column;
    gap: 0.5rem; /* 8px */
    height: 100vh; /* Ensure sidebar takes up full height */
    transition: width 0.4s ease;
}

.sidebar.collapsed {
    width: 8rem; /* 80px */
}

.sidebar__collapse-button {
    cursor: pointer;
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 2rem; /* 24px */
    height: 2rem; /* 24px */
    border-radius: 1rem; /* 16px */
    background-color: #f5f5f5;
    border: 0.0625rem solid #a9aeb4; /* 1px */
    right: -1rem; /* -10px */
    top: 4rem; /* Adjusted to be below the logo */
    z-index: 10; /* Ensure it’s above other elements */
}

.sidebar-item {
    display: flex;
    align-items: center;
    gap: 0.5rem; /* 8px */
    padding: 0.625rem; /* 10px */
    cursor: pointer;
    transition: all 0.4s ease;
}

.sidebar-item img {
    width: 1.5rem; /* 24px */
    height: 1.5rem; /* 24px */
}

/* Hide text labels in collapsed state */
.sidebar.collapsed .sidebar-item span,
.sidebar.collapsed .search-input,
.sidebar.collapsed .profile-info {
    display: none; /* Hide text */
}

/* Notification icon styles */
.notification-icon {
    background-color: #ef3e3e; /* Red color for the notification */
    color: white;
    border-radius: 50%;
    padding: 0.2rem 0.5rem;
    font-size: 0.75rem;
    margin-left: 0.5rem;
    display: none; /* Hide by default */
}


.profile {
    display: flex;
    align-items: center;
    gap: 0.5rem; /* 8px */
    margin-top: auto;
}

.profile-pic {
    width: 2.5rem; /* 40px */
    height: 2.5rem; /* 40px */
    background-color: #B45C3D;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    color: white;
}

nav ul {
    list-style: none;
    padding: 0;
}


nav ul li a {
    align-items: center;
    text-decoration: none;
    color: white;
    font-size: 1.125rem; /* 18px */
    display: flex; /* Change to flex for icon and text alignment */
    padding: 0.5rem;
    border-radius: 0.5rem; /* 8px */
    transition: background-color 0.25s;
    width: 100%; /* Full width for items */
}

nav ul li a img {
    margin-right: 0.5rem; /* 8px */
    width: 1.5rem; /* 24px */
    height: 1.5rem; /* 24px */
    object-fit: contain;
}

nav ul li a:hover {
    background-color: #B45C3D;
}

.profile {
    text-align: left;
    margin-top: auto;
    padding: 0.5625rem; /* 9px */
    border-top: 0.0625rem solid #ddd; /* 1px */
    position: relative;
    color: white;
}

.role {
    color: white;
}

.logo {
    font-size: 1.5rem; /* 24px */
    font-weight: 700;
    margin-bottom: 1.875rem; /* 30px */
    color: white;
}

.content {
    flex-grow: 1;
    padding: 2.5rem;
    padding-right: 5rem;
    position: relative;
    height: 100vh;
    margin-left: 17.5rem; /* Matches the width of the sidebar */
    /* Allows scrolling if content overflows */
    /* overflow-y: auto;  */
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem; /* 20px */
}

/* Media Queries for Responsive Design */
@media (max-width: 768px) {
    .sidebar {
        width: 15rem; /* Adjusted width for smaller screens */
    }
    
    .sidebar.collapsed {
        width: 5rem; /* Maintain collapsed width */
    }

    .content {
        margin-left: 15rem; /* Adjust for smaller screens */
    }

    .search, nav ul li a {
        width: 100%; /* Full width on smaller screens */
    }

    .search-input {
        font-size: 0.875rem; /* 14px */
    }
}

@media (max-width: 480px) {
    .sidebar {
        width: 12rem; /* Further adjust for mobile devices */
    }
    
    .sidebar.collapsed {
        width: 4rem; /* Maintain collapsed width */
    }

    .content {
        margin-left: 12rem; /* Adjust for smaller screens */
    }
}
</style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Highlight the active sidebar link based on the current page
            const sidebarLinks = document.querySelectorAll('.sidebar-links');
            const currentPage = window.location.pathname.split('/').pop().split('?')[0]; // Get the current page name without query parameters

            // Iterate over each sidebar link
            sidebarLinks.forEach(link => {
                // Extract the page name from the link's href attribute without query parameters
                const pageName = link.getAttribute('href').split('/').pop().split('?')[0];

                // Check if the current page matches the link's page name
                if (currentPage === pageName) {
                    link.classList.add('active'); // Add 'active' class to the matching link
                } else {
                    link.classList.remove('active'); // Remove 'active' class from other links
                }
            });
        });

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

        document.addEventListener("DOMContentLoaded", function() {
            // Update the notification icon for the "Opened Tickets" link
            const notificationIcon = document.getElementById('notification-icon');

            if (openedTicketsCount > 0) {
                notificationIcon.textContent = openedTicketsCount; // Set the count
                notificationIcon.style.display = 'inline-block'; // Show the icon
            }
        });
</script>
</body>
</html>