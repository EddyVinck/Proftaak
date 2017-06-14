<?php
include("inc/functions.php");
$db =  ConnectToDatabase();
$id = $_POST['collegeId'];
$color = $_POST["color"];
$text = $_POST["text"];
$query="
    UPDATE colleges
    SET naam = ?,
    kleur = ?
    WHERE id = ?;
    ";
$prepare_changeName = $db->prepare($query);
$prepare_changeName->bind_param("ssi",$text,$color, $id);
$prepare_changeName->execute();
echo 1;
?>