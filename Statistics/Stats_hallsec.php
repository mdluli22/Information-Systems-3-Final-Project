<?php
    require_once("secure.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <link rel="icon" type="image/x-icon" href="../landing_page/pictures/fake logo(1).png">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Link to the FontAwesome library for icons -->
    <script src="https://kit.fontawesome.com/ddbf4d6190.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        // get hall_sec username from login page/pop-up
        $hall_sec_userName = $_SESSION['username'];
        // $hall_name = $_SESSION['hall_name'];

        // include database details from config.php file
        require_once("config.php");

        // attempt to make database connection
        $connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

        // Check if connection was successful
        if ($connection->connect_error) {
            die("<p class=\"error\">Connection failed: Incorrect credentials or Database not available!</p>");
        }
        
        $residences = 
            "SELECT DISTINCT CONCAT(hall_secretary.f_Name, ' ', hall_secretary.l_name) AS 'hall_secretary_name', resName AS 'residences'
            FROM residence JOIN hall_secretary ON hall_secretary.hall_name = residence.hall_name
            WHERE hall_secretary.HS_userName = '$hall_sec_userName';";

        $residences_result = $connection->query($residences);

        if ($residences_result === FALSE) {
            die("<p class=\"error\">Query was Unsuccessful!</p>");
        }

    ?>
    <div class="container">
    <?php require_once("../hall_secretary_dashboard/sidebarHallSec.php"); ?>
    <main class="content">
        <header class="header">
            <h2>Statistics</h2>
            <div class="logo-container">
                <img src="../landing_page/pictures/fake logo(1).png" alt="Logo" >
            </div>
        </header>

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
                    echo "<a href='Stats_hallsec.php?house_name={$residence['residences']}' class='house-link {$isActive}'>{$residence['residences']}</a>";
                }
            ?>
        </nav>
                
        <div class="stats-overview active">
            <?php 

                if(isset($_REQUEST['house_name'])){
                    $housename = $_REQUEST['house_name'];  
                }
                else{
                    $housename = "Cory House";
                }

                
                $ticket_status = array("Opened", "Confirmed", "Closed");
                $icons = array("pictures/layer.svg", "pictures/clipboard-tick.svg", "pictures/task.svg");
                $class_names = array("card-icon", "card-icon1", "card-icon2");
                $names = array("Opened Tickets", "Confirmed Tickets", "Closed Tickets");
                $ticketTotals = array(0,0,0);
                $index = 0;
                $total = 0;
                
               //for each loop for rendering the Pending, Processing, Closed and Total tickets
                foreach($ticket_status as $status){
                    if(isset($_REQUEST['house_name'])){
                        $resname = $_REQUEST['house_name'];
                        $sql = "SELECT * FROM ticket WHERE ticket_status = '$status' AND resName = '$resname' ";
                    }
                    else{
                        $sql = "SELECT * FROM ticket WHERE ticket_status = '$status' AND resName = '$defaulthouse'";
                    }

                    $result = $connection->query($sql);

                    // Check if query successfull
                    if ($result === FALSE) {
                        die("<p class=\"error\">Query was Unsuccessful this one!</p>");
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

            <?php
                $num = 1;
                $categories = ['Electrical', 'Plumbing', 'Furniture', 'Heater', 'Other'];
                $data = []; // Array to store monthly ticket count for each category

                while ($num <= 12) {
                    foreach ($categories as $category) {

                        if(isset($_REQUEST['house_name'])){
                            $house_name = $_REQUEST['house_name'];
                            $sql = "SELECT * FROM ticket 
                                JOIN residence ON ticket.resName = residence.resName 
                                WHERE ticket.resName = '$house_name' 
                                AND MONTH(ticketDate) = $num 
                                AND ticket.category = '$category'
                                AND ticket_status IN ('Requisitioned', 'Closed', 'Resolved') ;";
                        }
                        else{
                            $sql = "SELECT * FROM ticket 
                            JOIN residence ON ticket.resName = residence.resName 
                            WHERE ticket.resName = '$defaulthouse' 
                            AND MONTH(ticketDate) = $num 
                            AND ticket.category = '$category'
                            AND ticket_status IN ('Requisitioned', 'Closed', 'Resolved') ;";
                        }

                        $result = $connection->query($sql);

                        if ($result === FALSE) {
                            die("<p class=\"error\">Query was Unsuccessful!</p>");
                        }

                        $data[$category][] = $result->num_rows;
                    }
                    $num++;
                }
            ?>

            //code for the line chart
            const ctx = document.getElementById('ticketsChart').getContext('2d');

            const myLineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        {
                            label: 'Electrical',
                            data: [<?php echo implode(",", $data['Electrical']); ?>],
                            fill: true,
                            borderColor: '#ff6384',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#ff6384',
                            lineTension: 0.4
                        },
                        {
                            label: 'Plumbing',
                            data: [<?php echo implode(",", $data['Plumbing']); ?>],
                            fill: true,
                            borderColor: '#36a2eb',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#36a2eb',
                            lineTension: 0.4
                        },
                        {
                            label: 'Furniture',
                            data: [<?php echo implode(",", $data['Furniture']); ?>],
                            fill: true,
                            borderColor: '#cc65fe',
                            backgroundColor: 'rgba(204, 101, 254, 0.2)',
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#cc65fe',
                            lineTension: 0.4
                        },
                        {
                            label: 'Heater',
                            data: [<?php echo implode(",", $data['Heater']); ?>],
                            fill: true,
                            borderColor: '#ffce56',
                            backgroundColor: 'rgba(255, 206, 86, 0.2)',
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#ffce56',
                            lineTension: 0.4
                        },
                        {
                            label: 'Other',
                            data: [<?php echo implode(",", $data['Other']); ?>],
                            fill: true,
                            borderColor: '#4bc0c0',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#4bc0c0',
                            lineTension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 50,
                            title: {
                                display: true,
                                text: 'Number of Tickets',  // Y-axis label
                                font: {
                                    size: 14
                                }
                            },
                            ticks: {
                                stepSize: 10
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Months',  // X-axis label
                                font: {
                                    size: 14
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Total Number of Tickets per Category per Month',
                            align: 'center',
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
                                    const category = context.dataset.label;
                                    const month = context.label;
                                    const value = context.raw;
                                    return `${category} - ${month}: ${value} tickets`;
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
                    labels: ['Electrical', 'Plumbing', 'Furniture','Heater', 'Other' ],
                    datasets: [{
                        data: [
                            <?php 
                                foreach($ticketTotals as $category){
                                    if(isset($_REQUEST['house_name'])) {
                                        $sql = "SELECT * FROM ticket JOIN residence on ticket.resName = residence.resName where category = '$category' AND hall_name = '$house_name' and ticket_status in ('Requisitioned', 'Closed', 'Resolved')";
                                    }
                                    else{
                                        $sql = "SELECT * FROM ticket join residence on ticket.resName = residence.resName where category = '$category' AND hall_name = '$defaulthouse' and ticket_status in ('Requisitioned', 'Closed', 'Resolved') ";
                                    }
                                    $result = $connection -> query($sql);

                                    if ($result === FALSE) {
                                        die("<p class=\"error\">Query was Unsuccessful!</p>");
                                    }
                                    echo ($result -> num_rows).",";
                                }
                            ?>
                        ], // Data points
                        backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56', '#4bc0c0'], // Colors for each section
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
        </script>
        <!-- Semester report -->
            <div class="report">
                <div class = "cards">
                    <div class="report-card" >
                        <?php
                            if(isset($_REQUEST['house_name'])) {
                                $semester_one_sql = 
                                    "SELECT * FROM ticket t JOIN residence r ON t.resName = r.resName WHERE r.resname = '$house_name' AND (MONTH(ticketDate) > 6);";
                        
                                // get average response time
                                $sem_one_resp_time_sql =
                                    "SELECT ticketID, endDate, ticketDate, ROUND(AVG(DATEDIFF(endDate, ticketDate)))
                                    AS avg_response_time_semester_1 FROM ticket 
                                    WHERE ticket_status = 'Closed' AND resName = '$house_name' AND MONTH(ticketDate) > 6;";
                            }
                            else {
                                $semester_one_sql = 
                                    "SELECT * FROM ticket t JOIN residence r ON t.resName = r.resName WHERE r.resname = '$defaulthouse' AND (MONTH(ticketDate) > 6);";
                        
                                // get average response time
                                $sem_one_resp_time_sql =
                                    "SELECT ticketID, endDate, ticketDate, ROUND(AVG(DATEDIFF(endDate, ticketDate)))
                                    AS avg_response_time_semester_1 FROM ticket 
                                    WHERE ticket_status = 'Closed' AND resName = '$defaulthouse' AND MONTH(ticketDate) > 6;";
                            }
                            
                            $semester_one_results = $connection->query($semester_one_sql);
                            $sem_one_resp_time_results = $connection->query($sem_one_resp_time_sql);
                        
                            // check if semester queries was successful
                            if ($semester_one_results === FALSE || $sem_one_resp_time_results === FALSE) {
                                die("<p class=\"error\">Semester 2 Query was Unsuccessful!</p>");
                            }
                            echo "<h4>Semester 1 Number of Tickets: &nbsp;&nbsp;&nbsp;{$semester_one_results->num_rows}</h4>";
                        ?>
                    </div>
                    
                    <div class="report-card" >
                        <?php
                            echo "<h4>Semester 1 Average Response Time: &nbsp;&nbsp;&nbsp;" . 
                                ($semester_one_results->num_rows > 0 ? 
                                $sem_one_resp_time_results->fetch_assoc()['avg_response_time_semester_1'] : 0) . 
                                " day(s)</h4>";
                        ?>
                    </div>
                    
                    <div class="report-card" >
                        <?php
                            if(isset($_REQUEST['house_name'])) {
                                $semester_two_sql = 
                                    "SELECT * FROM ticket t JOIN residence r ON t.resName = r.resName WHERE r.resname = '$house_name' AND (MONTH(ticketDate) > 6);";
                        
                                // get average response time
                                $sem_two_resp_time_sql =
                                    "SELECT ticketID, endDate, ticketDate, ROUND(AVG(DATEDIFF(endDate, ticketDate)))
                                    AS avg_response_time_semester_2 FROM ticket 
                                    WHERE ticket_status = 'Closed' AND resName = '$house_name' AND MONTH(ticketDate) > 6;";
                            }
                            else {
                                $semester_two_sql = 
                                    "SELECT * FROM ticket t JOIN residence r ON t.resName = r.resName WHERE r.resname = '$defaulthouse' AND (MONTH(ticketDate) > 6);";
                        
                                // get average response time
                                $sem_two_resp_time_sql =
                                    "SELECT ticketID, endDate, ticketDate, ROUND(AVG(DATEDIFF(endDate, ticketDate)))
                                    AS avg_response_time_semester_2 FROM ticket 
                                    WHERE ticket_status = 'Closed' AND resName = '$defaulthouse' AND MONTH(ticketDate) > 6;";
                            }
                            
                            $semester_two_results = $connection->query($semester_two_sql);
                            $sem_two_resp_time_results = $connection->query($sem_two_resp_time_sql);
                        
                            // check if semester queries was successful
                            if ($sem_two_resp_time_results === FALSE || $semester_two_results === FALSE) {
                                die("<p class=\"error\">Semester 2 Query was Unsuccessful!</p>");
                            }
                            echo "<h4>Semester 2 Number of Tickets: &nbsp;&nbsp;&nbsp;{$semester_two_results->num_rows}</h4>";
                        ?>
                    </div>
                        
                    <div class="report-card" >
                        <?php
                            echo "<h4>Semester 2 Average Response Time: &nbsp;&nbsp;&nbsp;" . 
                                ($semester_two_results->num_rows > 0 ? 
                                $sem_two_resp_time_results->fetch_assoc()['avg_response_time_semester_2'] : 0) . 
                                " day(s)</h4>";
                        ?>
                    </div>
                </div>
            </div>
            
            <?php
                // close connection
                $connection->close();
            ?>
          <script src="script.js"></script>
        </main>
    </div>
</body>
</html>
