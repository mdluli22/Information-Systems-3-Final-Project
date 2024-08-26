<?php
/// start session
session_start();

// get values from form
$username = $_REQUEST['name'];
$password = $_REQUEST['pass'];

// include database credentials
require_once("config.php");

// make connection to database
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// check connection successful
if ($conn->connect_error) {
    die("<p class='error'>Connection to database failed!</p>");
}

// issue query instructions
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);
// check query successful
if($result == FALSE) {
    die("<p class='error'>Unable to retrieve data!</p>");
}

if ($result->num_rows > 1) {
    // The username exists
    $row = $result->fetch_assoc();
    $storedPassword = $row['pass']; // Assuming the password is stored in the 'pass' column

    // Verify password using password_verify()
    if (password_verify($input_password, $input_username)) {
        // Password is correct
        echo "Username and password are correct.";
        // Start session and set session variable to allow access to web pages
        session_start();
        $_SESSION['access'] = "yes";
        // Redirect user to appropriate web page
        header("Location: login.php");
        exit(); // Ensure no further code is executed
    } else {
        // Password is incorrect
        echo "Username is correct, but password is incorrect.";
    }
} else {
    // Username is incorrect or does not exist
    echo "Username is incorrect.";
    // Redirect back to the home page
    header("Location: landing.html");
    exit(); // Ensure no further code is executed
}

// Close the connection
$mysqli->close();

