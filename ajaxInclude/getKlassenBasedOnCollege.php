<?php
include '../inc/functions.php';
$con =  ConnectToDatabase();
checkSession();
checkUserVerification();
$rol = $_SESSION['rol'];
$col = $_POST['col'];

$klassen = [];
$sql = "SELECT klassen.naam, klassen.id, klassen.rol FROM klassen
        INNER JOIN colleges
        ON colleges.id = klassen.colleges_id
        WHERE colleges.id = ? AND klassen.rol = 'studenten' ORDER BY klassen.id";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $col);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_array(MYSQLI_ASSOC))
{
    $klassen[] = $row;
}
$stmt->close();
echo json_encode($klassen);
