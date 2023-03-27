<?php
    session_start();

    unset($_SESSION['user']);
    unset($_SESSION['FName']);
    unset($_SESSION['LName']);
    unset($_SESSION['username']);
    unset($_SESSION['profileID']);

    $_SESSION["loggedIn"] = false;

    session_destroy();

    header("Location: ../MoviePage/homePage.php");
?>
