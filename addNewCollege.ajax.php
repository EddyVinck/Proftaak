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

$lerarenKlassenQuery = "INSERT INTO  `mydb`.`klassen` (
`id` ,
`naam` ,
`colleges_id` ,
`rol`
)
VALUES ";

for ($y=0; $y < $numb; $y++){
    if ($y == 0){
        $lerarenKlassenQuery .= "(NULL ,  'docenten', '". $ids[$y]['id'] ."','docenten')";
    }
    else{
       $lerarenKlassenQuery .= ",(NULL ,  'docenten', '". $ids[$y]['id'] ."','docenten')";
    }
}

$lerarenKlassenQuery .= ";";
mysqli_query($db,$lerarenKlassenQuery);
echo json_encode($ids);