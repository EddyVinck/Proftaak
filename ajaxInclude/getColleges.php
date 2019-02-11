<?php
include '../inc/functions.php';
$db =  ConnectToDatabase();
checkSession();
checkUserVerification();
$rol = $_SESSION['rol'];
$school = $_SESSION['school_id'];
if (isset($_POST['school']) && $rol == "adm") {
    $school = $_POST['school'];
}
$colleges = [];
$sql = "SELECT * FROM colleges WHERE scholen_id =?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $school);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_array(MYSQLI_ASSOC))
{
    $colleges[] = $row;
}
$stmt->close();
if ($rol == "adm" || $rol == "sch") {
    echo json_encode($colleges);
}
else {
    echo $rol;
}
