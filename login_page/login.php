<?php
// start the session
session_start();

// get the values from the form 
$username= $_REQUEST['name'];
$password= $_REQUEST['pass'];

// include database credentials 
require_once("config.php");

// make connection to the database
$conn=new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// check if the connection is successful 
if ($conn->connect_error) {
    die("<p class=\"error\">Connection to database failed!</p>");
}
 // issue query instructions
 $sql = "SELECT * FROM users WHERE username = '$name' AND password = '$pass'";

//  if statement if password is found what, if email is found but password is not what now?

 //check query successful
 $result = $conn->query($sql);

 if ($result === FALSE) {
     die("<p class=\"error\">Unable to retrieve data!</p>");
 }

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

 //close connection to database
 $conn->close();

