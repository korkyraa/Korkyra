<?php
session_start();

include '../classes/DB.php';
include '../classes/Admin.php';

if (!Admin::isLoggedIn())
{
    header('location: admin-login.php');
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - CNQ</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/main.css">
    <link rel="stylesheet" href="../style/admin.css">
    <link rel="stylesheet" href="../assets/fonts/ionicons.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<header>

    <?php include_once '../includes/adminHeader.php'; ?>

</header>

<main>
    <div class="container">
        <div class="cnq">
            <form action="adminHandler.php" method="post">
                <div class="answer-wrapper">
                    <div class="form-group">
                        <label for="question">Question:</label>
                        <input type="text" name="question" class="form-control" id="question" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="an1">Answer:</label>
                        <input type="text" name="answers[]" class="form-control" id="an1" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="an2">Answer:</label>
                        <input type="text" name="answers[]" class="form-control" id="an2" autocomplete="off">
                    </div>
                </div>
                <button id="addMore" class="btn btn-info">Add Field</button>
                <button type="submit" name="createquestion" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
</main>

<script>

    $(document).ready(function() {

        var maxFields = 10; //maximum input boxes allowed
        var wrapper = $(".answer-wrapper"); //Fields wrapper
        var addButton = $("#addMore"); //Add button ID
        var x = 2; //initlal text box count

        $(addButton).click(function(e) { //on add input button click
            e.preventDefault();

            if (x < maxFields) { //max input box allowed
                x++; //text box increment
                console.log(wrapper);

                $(wrapper).append('<div class="form-group">' +
                                    '<label for="an' + x + '">Answer:</label>' +
                                        '<input type="text" name="answers[]" class="form-control" id="an' + x + '" autocomplete="off">' +
                                        '<a href="#" id="removeField">Remove</a>' +
                                    '</div>'); //add input box
            }
        });

        $(wrapper).on("click", "#removeField", function(e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        })
    });

</script>
</body>
</html>