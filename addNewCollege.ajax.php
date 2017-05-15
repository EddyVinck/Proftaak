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

$getIDQuery ="SELECT id FROM colleges ORDER BY id DESC LIMIT 1";
$result2 = mysqli_query($db,$getIDQuery);

while($row = mysqli_fetch_assoc($result2)){
    $id = $row; 	//places everything in the array
}
echo $id['id'];
?>