<?php
include("inc/functions.php");
$db =  ConnectToDatabase();
$collegeID = $_POST['collegeID'];
$userID = $_POST['userID'];
$getNewKlasIDQuery = "SELECT id FROM klassen WHERE colleges_id = $collegeID";
$result = mysqli_query($db,$getNewKlasIDQuery);
while($row = mysqli_fetch_assoc($result)){
    $newKlasID = $row; 	//places everything in the array
}
$newKlas = $newKlasID['id'];
$query="
    UPDATE users
    SET klassen_id = ?
    WHERE id = ?; ";
$prepare_changeNameKlas = $db->prepare($query);
$prepare_changeNameKlas->bind_param("ii",$newKlas, $userID);
$prepare_changeNameKlas->execute();
echo $newKlas;
?>