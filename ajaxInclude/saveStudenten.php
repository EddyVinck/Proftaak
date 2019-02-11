<?php
include '../inc/functions.php';
$con =  ConnectToDatabase();
checkSession();
checkUserVerification();
$rol = $_SESSION['rol'];
$data = $_POST['data'];
if ($rol == "sch" || $rol == "doc" || $rol == "adm") {
    $sql = "UPDATE users SET klassen_id = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    foreach ($data as $key => $user) {
        $stmt->bind_param('ii', $user['klas'], $user['id']);
        $stmt->execute();
    }
    $stmt->close();
}
