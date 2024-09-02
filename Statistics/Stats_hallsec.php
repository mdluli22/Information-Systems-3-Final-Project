<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <link rel="icon" type="image/x-icon" href="pictures/resque-logo.png">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Link to the FontAwesome library for icons -->
    <script src="https://kit.fontawesome.com/ddbf4d6190.js" crossorigin="anonymous"></script>
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
 

    ?>
    <div class="container">
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
                <li id="all-tickets"><a class="sidebar-links" href="<?php echo "../hall_secretary_dashboard/hall_secretary_all_tickets.php?hall_sec_userName=$hall_sec_userName&hall_name=$hall_name"?>"><img src="pictures/receipt-icon.png" alt="receipt icon">All Tickets</a></li>
                <li id="open-tickets"><a class="sidebar-links" href="<?php echo "../hall_secretary_dashboard/hall_secretary_open_tickets.php?hall_sec_userName=$hall_sec_userName&hall_name=$hall_name"; ?>"><img src="pictures/layer.png" alt="layer">Open Tickets</a></li>
                <li id="closed-tickets"><a class="sidebar-links" href="<?php echo "../hall_secretary_dashboard/hall_secretary_closed_tickets.php?hall_sec_userName=$hall_sec_userName&hall_name=$hall_name"; ?>"><img src="pictures/clipboard-tick.png" alt="clipboard-tick">Closed Tickets</a></li>
                <li id="statistics"><a class="sidebar-links" href="<?php echo "index.php?hall_sec_userName=$hall_sec_userName&hall_name=$hall_name"?>"><img src="pictures/bar-chart-icon.png" alt="bar chart icon">Statistics</a></li>
            </ul>
        </nav>

        <!-- <hr id="sidebar-hr"> -->

        <!-- Profile section at the bottom of the sidebar -->
        <div class="profile">
            <!-- Profile picture area -->
            <div class="profile-pic">AM</div>
            <!-- Profile information area -->
            <div class="profile-info">
                <span id="user-name" class="username">Amogelang Mphela</span><br>
                <span class="role">Hall Secretary</span>
            </div>
            <!-- Logout button with icon -->
            <div id="sidebar-log-out">
                <a href="#"><i class="fa-solid fa-arrow-right-from-bracket fa-xl" style="color: #B197FC;"></i></a>
            </div>
        </div>
    </aside>
    <main class="content">
        <header class="header">
            <h2>Statistics</h2>
            <div class="filters">
                <span>From</span>
                <input type="date" value="2021-06-10">
                <span>To</span>
                <input type="date" value="2021-06-10">
            </div>
        </header>
        <nav class="houses">
                <a href="#" class="house-link active">Cory House</a>
                <a href="#" class="house-link">Botha House</a>
                <a href="#" class="house-link">Matthews House</a>
                <a href="#" class="house-link">College House</a>
        </nav>
        
        <div class="stats-overview">
            <?php 
             
                $ticket_status = array("Pending", "Processing", "Completed");
                $icons = array("pictures/layer.svg", "pictures/clipboard-tick.svg", "pictures/task.svg");
                $class_names = array("card-icon", "card-icon1", "card-icon2");
                $names = array("Pending Tickets", "Processing Tickets", "Completed Tickets");
                $ticketTotals = array(0,0,0);
                $index = 0;
                $total = 0;
                
               //for each loop for rendering the Pending, Processing, Closed and Total tickets
                foreach($ticket_status as $status){
                    $sql = "SELECT * FROM ticket WHERE ticket_status = '$status'";
                    $result = $connection->query($sql);

                    // Check if query successfull
                    if ($result === FALSE) {
                        die("<p class=\"error\">Query was Unsuccessful!</p>");
                    }
                    
                    echo "<div class=\"card\" >";

                    echo      "<div class= {$class_names[$index]}>";
                    echo           "<img src={$icons[$index]} alt = 'Icon' >";
                    echo      "</div>";

                    echo      "<div class= \"card-info\" >";

                    echo          "<div class= \"card-number\">";
                    echo             $result -> num_rows;
                    echo           "</div>";

                    echo           "<div class=\"card-text\">";
                    echo             $names[$index];
                    echo           "</div>";
                    
                    echo       "</div>";

                    echo "</div>";

                    $ticketTotals[$index] = $result -> num_rows;
                    $index++;
                    $total += $result -> num_rows;
                }

             
            ?>

            <div class="card">
                <div class="card-icon3">
                    <img src="pictures/clipboard-text.svg" alt="Icon">
                </div>
                <div class="card-info">
                    <div class="card-number"><?php echo $total; ?></div>
                    <div class="card-text">Total Tickets</div>
                </div>
            </div>

            </div>

        <div class="chartlayout">
            <div class="charts">
                <canvas id="ticketsChart"></canvas>
            </div>
            <div class="charts">
                <canvas id="ticketStatusChart"></canvas>
            </div>

        </div>

        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>

            //code for the line chart
            const ctx = document.getElementById('ticketsChart').getContext('2d');

            const myLineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                    datasets: [{
                        label: 'Tickets',
                        data: [
                            <?php
                                $num = 1;
                                while($num <= 9){
                                    $sql = "SELECT * FROM ticket WHERE MONTH(ticketDate) = '$num'";
                                    $result = $connection -> query($sql);

                                    // Check if query successfull
                                    if ($result === FALSE) {
                                        die("<p class=\"error\">Query was Unsuccessful!</p>");
                                    }

                                    echo ($result -> num_rows).",";

                                    $num++;

                                }
                            ?>
                            ],
                        fill: true,
                        borderColor: '#7e5bef',
                        backgroundColor: 'rgba(126, 91, 239, 0.1)',
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#7e5bef',
                        pointHoverBackgroundColor: '#7e5bef',
                        pointHoverBorderColor: '#fff',
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        lineTension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 50,

                        },

                        x: {
                            grid:{
                                display: false
                            }
                        }
                        
                    },
                    plugins: {

                        legend: {
                            display: false  
                        },

                        title: {
                            display: true,  
                            text: 'Total Number of Tickets per Month',  
                            align: 'start',  
                            font: {
                            size: 18,  
                            weight: 'bold'  
                            },
                            padding: {
                                top: 10,
                                bottom: 30
                            }
                        },

                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Tickets: ${context.raw}`;
                                }
                            }
                        }
                    }
                }
            });


            //code for the piechart
            const thechart = document.getElementById('ticketStatusChart').getContext('2d');
            const ticketStatusChart = new Chart(thechart, {
                type: 'doughnut', // Use 'doughnut' for the circular chart
                data: {
                    labels: ['Pending', 'Processing', 'Completed'],
                    datasets: [{
                        data: [
                            <?php 
                                foreach($ticketTotals as $total){
                                    echo $total.",";
                                }
                                // close connection
                                $connection->close();
                            ?>
                        ], // Data points
                        backgroundColor: ['#444444', '#A2D9CE', '#85C1E9'], // Colors for each section
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right', // Position legend to the right
                            labels: {
                                usePointStyle: true, // Use dots as legend markers
                                boxWidth: 10,
                                padding: 20,
                                color: '#000'
                            }
                        },
                        title: {
                            display: true,  
                            text: 'Distribution of Current Tickets',  
                            align: 'start',  
                            font: {
                            size: 18,  
                            weight: 'bold'  
                            },
                            padding: {
                                top: 10,
                                bottom: 30
                            }
                        }
                    }
                }
            });
            
            //javascript for the bargraph
            const thebarchart = document.getElementById('Barchart').getContext('2d');
            const Barchart = new Chart(thebarchart, {
            type: 'bar',
            data: {
                labels: ['Linux', 'Mac', 'iOS', 'Windows', 'Android', 'Other'],
                datasets: [{
                    label: 'Requisitions per Weekday',
                    data: [15, 25, 20, 30, 10, 20],
                    backgroundColor: [
                        'rgba(93, 102, 255, 0.6)', // Linux
                        'rgba(168, 235, 179, 0.6)', // Mac
                        'rgba(38, 38, 38, 0.9)',    // iOS
                        'rgba(144, 202, 249, 0.6)', // Windows
                        'rgba(155, 177, 196, 0.6)', // Android
                        'rgba(121, 217, 182, 0.6)'  // Other
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 50,
                        title: {
                            display: true,
                            text: 'Requisitions',
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Platforms',
                        }
                    }
                }
            }
        });

          </script>
          
        </main>
    </div>
</body>
</html>