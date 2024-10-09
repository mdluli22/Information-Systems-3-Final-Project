<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto; /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
    }
    .modal-content {
        background-color: #c3e6cb;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        text-align: center;
    }
    .close-btn {
        background-color: #155724;
        color: #d4edda;
        padding: 10px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }
    .error {
        color: red;
    }
    .success {
        color: green;
    }
</style>
</head>
<body>

<!-- Modal for success message -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <p id="successMessage"></p>
        <button class="close-btn" onclick="closeModal()">Close</button>
    </div>
</div>

<?php
//Start the session
session_start();

// come from a form submission
$fname = $_REQUEST['fname'];
$lname = $_REQUEST['lname'];
$email = $_REQUEST['email'];
// $resname = $_REQUEST['resName'];
$resnameStudent = $_REQUEST['resNameStudent'];
$roomNumber = $_REQUEST['roomNumber'];
$username = $_REQUEST['username'];
$password = $_REQUEST['password'];
$hall = $_REQUEST['studentHall'];
$hall2 = $_REQUEST['hw-hall'];
$hall3 = $_REQUEST['hallSecretaryHall'];
$role = $_REQUEST['role']; // Retrieve role from the form

//echo "Selected role: " . $role . "<br>"; // Debugging statement

//include database credentials 
require_once("config.php");

// Database connection
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

//check if the query is successful
if ($conn->connect_error) {
    die("<p class=\"error\">Connection to the database failed!</p>" . $conn->connect_error);
}


//query to check if the user exists in the database
$sql = "SELECT * FROM user WHERE userName = '$username'";
$result = $conn->query($sql);

if (!$result) {
    die("Error querying user table: " . $conn->error);
}

if ($result->num_rows > 0) {
    //user already exists, log them in
    $_SESSION['username'] = $username;  // Set the username in the session
    $_SESSION['hall'] = $hall;
    $_SESSION['resName'] = $resname;
    $_SESSION['resNameStudent'] = $resnameStudent;
    $_SESSION['error'] = "User already exists! Please try logging in.";
    header("Location: home.php");
    exit();
    // $_SESSION['access'] = "yes";
    // header("Location: ../ticket_tracking/ticket_tracking_all.php");
    // exit();
} else {
    //user doesn't already exist
    $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash the password
    $userTable = "INSERT INTO user(userName, user_password, user_role, email)
                  VALUES ('$username', '$hashed_password', '$role', '$email')";
    $fromUser = $conn->query($userTable);

    //check if the user has been added successfully into user table
    if ($fromUser === TRUE) {
        echo "User added successfully.";
        //check the role of the user
        switch ($role) {
            //if the user is a student
            case 'student':
                //check if the residence exists
                $checkRes = "SELECT * FROM residence WHERE resName = '$resnameStudent'";
                $resResult = $conn->query($checkRes);

                //if the residence doesn't exist, add the residence
                if ($resResult->num_rows == 0) {
                    $residenceTable = "INSERT INTO residence (resName, hall_name) VALUES ('$resnameStudent', '$hall')";
                    $residence = $conn->query($residenceTable);
                    
                    if (!$residence) {
                        die("<p class=\"error\">Error adding into residence table: " . $conn->error . "</p>");
                    }
                }

                //insert into the student table
                $studentTable = "INSERT INTO student(f_Name, l_Name, resName, userName, room_number)
                                 VALUES ('$fname', '$lname', '$resnameStudent', '$username', '$roomNumber')";
                $student = $conn->query($studentTable);

                //check if the student has been added successfully and go to the dashboard
                if ($student === TRUE) {
                    $_SESSION['success'] = "Student added successfully!";
                    header("Location: home.php");
                    exit();
                } else {
                    die("<p class=\"error\">Error adding into student table: " . $conn->error . "</p>");
                }
                break;

            //if the user is a house warden
            case 'house warden':
                // Get the hall name based on the selected residence
                $resName = $_REQUEST['hw_resName'];
                $checkRes = "SELECT hall_name FROM residence WHERE resName = '$resName'";
                $resResult = $conn->query($checkRes);

                if ($resResult->num_rows == 0) {
                    die("<p class='error'>Residence not found: $resName</p>");
                } else {
                    // If the residence exists, get the hall name
                    $row = $resResult->fetch_assoc();
                    $hall_name = $row['hall_name'];
                }
            
                // Find the hall secretary for the hall
                $checkHSUser = "SELECT HS_userName FROM hall_secretary WHERE hall_name = '$hall_name'";
                $hsResult = $conn->query($checkHSUser);

                if ($hsResult->num_rows == 0) {
                    die("<p class='error'>No hall secretary found for the hall: $hall_name</p>");
                } else {
                    // Get the hall secretary's username
                    $row = $hsResult->fetch_assoc();
                    $HS_userName = $row['HS_userName'];
                }

                // Insert into the house warden table
                $wardenTable = "INSERT INTO house_warden (f_Name, l_Name, resName, userName, HS_userName) VALUES ('$fname', '$lname', '$resName', '$username', '$HS_userName')";
                $warden = $conn->query($wardenTable);

                if ($warden === TRUE) {
                    $_SESSION['success'] = "House Warden added successfully!";
                    header("Location: home.php");
                    exit();
                } else {
                    die("<p class=\"error\">Error adding into house warden table: " . $conn->error . "</p>");
                }
                break;
            
            case 'hall secretary':
                // Logic for hall secretary
                $secretaryTable = "INSERT INTO hall_secretary (HS_userName, f_Name, l_Name, userName, hall_name)
                                   VALUES ('$username', '$fname', '$lname', '$username', '$hall3')";
                $secretary = $conn->query($secretaryTable);

                if ($secretary === TRUE) {
                    $_SESSION['success'] = "Hall Secretary added successfully!";
                    header("Location: home.php");
                    exit();
                } else {
                    die("<p class='error'>Error adding to hall secretary table: " . $conn->error . "</p>");
                }
                break;

            // Add the following case in the switch statement that checks the user's role
            case 'maintenance_staff':
                // Insert into the maintenance_staff table
                $maintenanceTable = "INSERT INTO maintenance_staff (M_userName, f_Name, l_Name, userName)
                                    VALUES ('$username', '$fname', '$lname', '$username')";
                $maintenance = $conn->query($maintenanceTable);

                if ($maintenance === TRUE) {
                    $_SESSION['success'] = "Maintenance staff added successfully!";
                    header("Location: home.php");
                    exit();
                } else {
                    die("<p class='error'>Error adding to maintenance staff table: " . $conn->error . "</p>");
                }
                break;
            default:
                // Handle other roles
                die("<p class=\"error\">Unknown user role: $role</p>");
                break;
            }
        } else {
            $_SESSION['error'] = "Error adding user to the database.";
            header("Location: home.php");
            exit();
    }
}
// Close connection to the database
$conn->close();
?>
<script>
    // Function to close the modal
    function closeModal() {
        document.getElementById('messageModal').style.display = 'none';
    }

    // Show modal if there's a message in the session
    <?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            var messageContent = document.getElementById('messageContent');
            var message = "<?php echo isset($_SESSION['success']) ? $_SESSION['success'] : $_SESSION['error']; ?>";
            messageContent.textContent = message;
            document.getElementById('messageModal').style.display = 'block';

            // Clear session messages
            <?php unset($_SESSION['success'], $_SESSION['error']); ?>
        });
    <?php endif; ?>
</script>

</body>
</html>
