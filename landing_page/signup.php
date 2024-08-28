<?php
// Database connection
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

//include database credentials 
require_once("config.php");

//check if the query is successful
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// come from a form submission
if (isset($_REQUEST['submit'])){
$fname = $_REQUEST['fname'];
$lname = $_REQUEST['lname'];
$email = $_REQUEST['email'];
$username = $_REQUEST['username'];
$password = $_REQUEST['pass'];
}

//check if the connection is successful 
if ($conn->connect_error) {
    die("<p class=\"error\"> Connection to database failed!!</p>");
}

//issue query instructions
$sql = "INSERT INTO users (userName, user_password, email)
        VALUE('$username', '$password', '$email')";
        "INSERT INTO student(S_username, f_name,l_name)
        VALUE ('$username', '$fname', '$lname')";

//check if the query is successful 
if ($sql === FALSE){
    die("<p class= \"error\" >Unable to add User </p>");
}

// Check if the username already exists
$stmt = $mysqli->prepare("SELECT userName FROM users WHERE userName = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Username already exists
    echo "Username already taken!";
} else {
    // Username is available, proceed to register the user
    $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash the password

    // Insert the new user into the database
    // $stmt = $mysqli->prepare("INSERT INTO users (userName, user_password, email)
    //     VALUE('$username', '$password', '$email')";
    //     "INSERT INTO student(S_username, f_name,l_name)
    //     VALUE ('$username', '$fname', '$lname')";);
    // $stmt->bind_param("ss", $input_username, $hashed_password);

    if ($stmt->execute()) {
        echo "Registration successful! You can now log in.";
    } else {
        echo "Error: Could not register user.";
    }
}

$stmt->close();
$mysqli->close();
//close the connection to the database
$conn ->close();