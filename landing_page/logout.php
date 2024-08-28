<?php

session_start();
if (isset($_SESSION['login'])) {
    // remove all session variables
    session_unset();
    // destroy the session
    session_destroy();
}

header("Location:landing_Pagibf.html");