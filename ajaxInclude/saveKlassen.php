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
$data = $_POST['data'];
$sql = "UPDATE klassen SET naam = ?, colleges_id = ? WHERE id = ?";
$stmt = $db->prepare($sql);
foreach ($data as $key => $klas) {
    $stmt->bind_param('sii', $klas['naam'], $klas['college'], $klas['id']);
    $stmt->execute();
}
$stmt->close();
