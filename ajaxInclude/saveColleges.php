<?php
include '../inc/functions.php';
$db =  ConnectToDatabase();
checkSession();
checkUserVerification();
$rol = $_SESSION['rol'];
$data = $_POST['data'];
$sql = "UPDATE colleges SET naam = ?, kleur = ? WHERE id = ?";
$stmt = $db->prepare($sql);
foreach ($data as $key => $college) {
    $stmt->bind_param('ssi', $college['naam'], $college['kleur'], $college['id']);
    $stmt->execute();
}
$stmt->close();
