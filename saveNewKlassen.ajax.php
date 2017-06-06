<?php
include("inc/functions.php");
$db = ConnectToDatabase();
header('Content-type: application/json');
$names = $_POST["naam"];
$college = $_POST["college"];
$numb = count($names);
$query = "INSERT INTO  `mydb`.`klassen` (`id` ,
`naam` ,
`colleges_id`,
`rol`
)
VALUES ";
for ($x=0; $x < $numb; $x++){
    if ($x == 0){
        $query .= "(NULL , '" . $names[$x] . "'," . $college[$x] . ",'studenten')";
    }
    else{
        $query .= ",(NULL , '" . $names[$x] . "'," . $college[$x] . ",'studenten')";
    }
}
$query .= ";";
if (mysqli_query($db,$query)){
    echo 1;
}
else{
    echo 0;
}
?>