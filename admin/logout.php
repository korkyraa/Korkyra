<?php
session_start();

include '../classes/DB.php';
include '../classes/Admin.php';

if (!isset($_SESSION['admin_id']))
{
    header('location: admin-login.php');
    exit();
}

if (isset($_SESSION['admin_id']))
{
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    header('location: admin-login.php');
    exit();
}



