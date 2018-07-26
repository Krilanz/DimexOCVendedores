<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
} else if (isset($_SESSION['user']) != "") {
    header("Location: novedades.php");
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['userId']);
    header("Location: login.php");
    exit;
}
