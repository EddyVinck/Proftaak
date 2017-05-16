<?php
include("inc/functions.php");
$db = ConnectToDatabase();
header('Content-type: application/json');
$names = $_POST["naam"];
$colors = $_POST["colors"];
$numb = count($colors);
$query = "INSERT INTO  `mydb`.`colleges` (`id` ,
`naam` ,
`kleur` ,
`scholen_id`
)
VALUES ";
for ($x=0; $x < $numb; $x++){
    if ($x == 0){
        $query .= "(NULL , '" . $names[$x] . "','" . $colors[$x] . "','1')";
    }
    else{
        $query .= ",(NULL , '" . $names[$x] . "','" . $colors[$x] . "','1')";
    }
}
$query .= ";";
mysqli_query($db,$query);
$query = "SELECT `id` FROM `colleges` ORDER BY `id` DESC LIMIT $numb";
$result = mysqli_query($db,$query);
while($row = mysqli_fetch_assoc($result)){
    $ids[] = $row; 	//places everything in the array
}
echo json_encode($ids);