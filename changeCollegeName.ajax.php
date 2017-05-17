<?php
include("inc/functions.php");
$db =  ConnectToDatabase();
$id = $_POST['collegeId'];
$color = $_POST["color"];
$text = $_POST["text"];
$query="
    UPDATE colleges
    SET naam = '$text',
    kleur = '$color'
    WHERE id = $id;
    ";
$result = mysqli_query($db,$query);
?>