<?php
include("inc/functions.php");
$db =  ConnectToDatabase();
$id = $_POST['klasId'];
$text = $_POST["text"];
$query="
    UPDATE klassen
    SET naam = '$text'
    WHERE id = $id;
    ";
$result = mysqli_query($db,$query);
echo 1;
?>