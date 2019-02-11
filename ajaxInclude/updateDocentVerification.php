<?php
include '../inc/functions.php';
$con =  ConnectToDatabase();
checkSession();
checkUserVerification();
$rol = $_SESSION['rol'];
$newRol = $_POST['rol'];
$id = $_POST['id'];
$er = 0;
if ($rol == "adm" || $rol = "sch") {
    $sql = "UPDATE users SET rol = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('si', $newRol, $id);
    $stmt->execute();
    $stmt->close();
}
else {
    $er = 1;
}
echo $er;
