<?php
include("inc/functions.php");
$db =  ConnectToDatabase();
$text = $_GET['text'];
$color = $_GET['color'];

$query="
INSERT INTO  `mydb`.`colleges` (
`id` ,
`naam` ,
`kleur` ,
`scholen_id`
)
VALUES (
NULL ,  '$text',  '$color',  '1'
);
";
$result = mysqli_query($db,$query);
?>