<?php
include("inc/functions.php");
$db = ConnectToDatabase();
checkSession();
header('Content-type: application/json');
$names = $_POST["naam"];
$colors = $_POST["colors"];
$school_id = $_POST['schoolId'];
if ($_SESSION['school_id'] != $school_id){
    if ($_SESSION['rol'] != "adm"){
        $school_id = $_SESSION['school_id'];
        
    }
}
echo json_encode($school_id);
$numb = count($colors);
$query = "INSERT INTO  `mydb`.`colleges` (`id` ,
`naam` ,
`kleur` ,
`scholen_id`
)
VALUES ";
for ($x=0; $x < $numb; $x++){
    if ($x == 0){
        $query .= "(NULL , '" . $names[$x] . "','" . $colors[$x] . "','$school_id')";
    }
    else{
        $query .= ",(NULL , '" . $names[$x] . "','" . $colors[$x] . "','$school_id')";
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