<?php
include("inc/functions.php");
$db =  ConnectToDatabase();
$text = $_GET['text'];
$id = $_GET['id'];

$query="
UPDATE colleges
SET naam = '$text'
WHERE id = $id;
";
$result = mysqli_query($db,$query);
echo "1";
?>