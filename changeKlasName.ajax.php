<?php
include("inc/functions.php");
$db =  ConnectToDatabase();
$id = $_POST['klasId'];
$text = $_POST["text"];
$query="
    UPDATE klassen
    SET naam = ?
    WHERE id = ?;
    ";
$prepare_changeNameKlas = $db->prepare($query);
$prepare_changeNameKlas->bind_param("si",$text, $id);
$prepare_changeNameKlas->execute();
echo 1;
?>