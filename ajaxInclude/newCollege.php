<?php
include '../inc/functions.php';
$con =  ConnectToDatabase();
checkSession();
checkUserVerification();
$rol = $_SESSION['rol'];
$school = $_SESSION['school_id'];
$col = $_POST['col'];
$name = $_POST['name'];
if (isset($_POST['school']) && $rol == "adm") {
    $school = $_POST['school'];
}

$sql = "INSERT INTO colleges (naam, kleur, scholen_id) VALUES (?,?,?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("ssi", $name, $col, $school);
$stmt->execute();
$insert_id = $stmt->insert_id;
$stmt->close();
$insRol = "docenten";
$sql = "INSERT INTO `klassen`(`naam`, `colleges_id`, `rol`) VALUES (?,?,?)";
$stmt = $con->prepare($sql);
$stmt->bind_param('sis', $insRol, $insert_id, $insRol);
$stmt->execute();
$stmt->close();
