<?php
include '../inc/functions.php';
$con =  ConnectToDatabase();
checkSession();
checkUserVerification();
$rol = $_SESSION['rol'];
$data = $_POST['data'];
foreach ($data as $key => $docent) {
    $collegeId = $docent['college'];
    $sql = "SELECT * FROM klassen WHERE colleges_id = ? AND rol = 'docenten'";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $collegeId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        $data[$key]['newklasId'] = $row['id'];
    }
    $stmt->close();
}
$sql = "UPDATE users SET klassen_id = ? WHERE id = ?";
$stmt = $con->prepare($sql);
foreach ($data as $key => $docent) {
    $stmt->bind_param('ii', $docent['newklasId'], $docent['id']);
    $stmt->execute();
}
$stmt->close();
// echo json_encode($data);
