<?php
include("inc/functions.php");
$connection = ConnectToDatabase();
$klasId = $_POST['klasId'];
$userId = $_POST['userId'];

$query = 
"   UPDATE users
    SET klassen_id = '$klasId'
    WHERE id = '$userId';";
mysqli_query($connection, $query);
echo 1;