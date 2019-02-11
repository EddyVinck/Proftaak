<?php
include '../inc/functions.php';
$db =  ConnectToDatabase();
checkSession();
checkUserVerification();
$rol = $_SESSION['rol'];
$query = "SELECT * FROM scholen";
$result = mysqli_query($db,$query);
while($row = mysqli_fetch_assoc($result)){
    $scholen[] = $row; 	//places everything in the array
}
$db = ConnectToDatabase();
if ($rol == "adm") {
    echo json_encode($scholen);
}
?>
