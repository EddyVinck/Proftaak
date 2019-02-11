<?php
include '../inc/functions.php';
$con =  ConnectToDatabase();
checkSession();
checkUserVerification();
$rol = $_SESSION['rol'];
$col = $_POST['college'];
$name = $_POST['name'];
if ($rol == "doc" || $rol = "sch" || $rol == "adm") {
    $sql = "INSERT INTO klassen (naam, colleges_id, rol)
        VALUES (?,?,'studenten')";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('si', $name, $col);
        $stmt->execute();
        $stmt->close();
}
else {
    echo "unrestricted";
}
