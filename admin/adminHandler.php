<?php

require_once '../classes/DB.php';
require_once '../classes/Admin.php';

if (isset($_POST['changestatus']))
{
    $changeStatus = Admin::changePollStatus($_POST['changestatus']);

    if ($changeStatus) {
        echo "success";
    } else {
        echo "fail";
    }
}

if (isset($_POST['deletepoll']))
{
    $delete = Admin::deletePoll($_POST['deletepoll']);

    if ($delete) {
        echo "success";
    } else {
        echo "fail";
    }
}

if (isset($_POST['createquestion']))
{
    $question = $_POST['question'];
    $answers = $_POST['answers'];

    Admin::createQuestion($question, $answers);

    header('location: admin-panel.php');
    exit();
}

