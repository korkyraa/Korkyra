<?php
session_start();

include '../classes/DB.php';
include '../classes/Admin.php';

if (!Admin::isLoggedIn())
{
    header('location: admin-login.php');
    exit();
}

$panelContent = Admin::getPanel();

$tableRows = "";
$i = 1;

foreach ($panelContent as $content)
{
    $checked = "";

    if ($content['status'])
    {
        $checked = "checked";
    }

    if (empty($content['total_votes']))
    {
        $content['total_votes'] = 0;
    }

    $tableRows .= "
                <tr id='".$content['id']."'>
                        <td>" . $i . "</td>
                        <td>" . $content['subject'] . "</td>
                        <td>" . $content['total_votes'] . "</td>
                        <td><label class='switch'><input type='checkbox' onchange='changePollStatus(" . $content['id'] . ")' $checked><div class='slider'></div></label></td>
                        <td class='text-center'><button type='button' onclick='deletePoll(" . $content['id'] . ")' class='btn btn-danger'><i class='icon ion-trash-b'></i></button></td>
                        <td class='text-center'><a href='modify.php?poll=" . $content['id'] . "'><button type='button' class='btn btn-warning'><i class='icon ion-edit'></i></button></a></td>
                        <td class='text-center'><a href='results.php?poll=" . $content['id'] . "'><button type='button' class='btn btn-info'><i class='icon ion-pie-graph'></i></button></a></td>
                </tr>";

    $i++;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
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
    <div class="container" id="adminPanel">
        <h2>Control Panel</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Votes</th>
                    <th>Status</th>
                    <th class='text-center'>Delete</th>
                    <th class='text-center'>Modify</th>
                    <th class='text-center'>Results</th>
                </tr>
                </thead>
                <tbody>
                    <?php echo $tableRows; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>

    function changePollStatus(id)
    {
        $.ajax({
            type: "POST",
            data: {
                changestatus: id
            },
            url: "adminHandler.php",

            success: function(response) {
                console.log(response);
            }
        });
    }

    function deletePoll(id)
    {
        var conf = confirm("Are you sure you want to delete this question?");
        if (conf != true) {
            return false;
        }

        $.ajax({
            type: "POST",
            data: {
                deletepoll: id
            },
            url: "adminHandler.php",

            success: function(response) {
                console.log(response);
                document.getElementById(id).style.display = "none";
            }
        });
    }

</script>
</body>
</html>
