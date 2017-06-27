<?php
include("inc/functions.php");
$connection = ConnectToDatabase();
$klasId = $_POST['klasId'];
$userId = $_POST['userId'];

$query = 
"   UPDATE users
    SET klassen_id = ?
    WHERE id = ?;";

$prepareQuery = $connection->prepare($query);
$prepareQuery->bind_param("ii", $klasId,$userId);
$prepareQuery->execute();
echo 1;