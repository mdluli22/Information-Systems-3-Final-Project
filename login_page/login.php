<?php
// start the session
session_start();

// get the values from the form 
$username= $_REQUEST['name'];
$password= $_REQUEST['pass'];

// include database credentials
require_once("config.php");

// make connection to database
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// check connection successful
if ($conn->connect_error) {
    die("<p class='error'>Connection to database failed!</p>");
}

// create prepare statement
$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

// bind parameters
$stmt->bind_param("ss", $username, $password);

// set parameters and execute
$username = $conn->real_escape_string($_REQUEST['name']);
$password = $conn->real_escape_string($_REQUEST['pass']);
$stmt->execute();

//if the username is incorrect, and if the password is incorrect 

 //check if the user exists in the database
 if ($result->num_rows == 1) {
     // user exists in the database, so set session variable to allow access to web pages
     $_SESSION['access'] = "yes";

     // direct user to appropriate web page
     header("Location:login.php");
 } 
 
 else {
     // if user does not exist in database then redirect back to home page
     header("Location:landing.html");
 }

 
 // close prepared statement
$stmt->close();

// close connection to database
$conn->close();
