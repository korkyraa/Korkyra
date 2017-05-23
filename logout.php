<?php
session_start();

include 'classes/DB.php';
include 'classes/User.php';

if (!isset($_SESSION['user_id']))
{
    header('location: login.php');
    exit();
}

if (isset($_SESSION['user_id']))
{
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    header('location: login.php');
    exit();
}
