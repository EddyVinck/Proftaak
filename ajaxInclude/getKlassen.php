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

$klassen = [];
$sql = "SELECT klassen.colleges_id, colleges.naam AS college_naam, klassen.naam, klassen.id, klassen.rol FROM klassen
        INNER JOIN colleges
        ON colleges.id = klassen.colleges_id
        INNER JOIN scholen
        ON scholen.id = colleges.scholen_id
        WHERE scholen.id = ? AND klassen.rol != 'docenten' ORDER BY klassen.id";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $school);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_array(MYSQLI_ASSOC))
{
    $sql2 = "SELECT naam, id FROM colleges WHERE scholen_id = ?";
    $stmt2 = $db->prepare($sql2);
    $stmt2->bind_param('i', $school);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    while ($row2 = $result2->fetch_array(MYSQLI_ASSOC))
    {
        $row2['selected'] = "";
        if ($row2['id'] == $row['colleges_id']) {
            $row2['selected'] = "selected";
        }
        $row['selects'][] = $row2;
    }
    $stmt2->close();
    $sql3 = "SELECT * FROM users WHERE klassen_id = ?";
    $stmt3 = $db->prepare($sql3);
    $stmt3->bind_param('i', $row['id']);
    $stmt3->execute();
    $stmt3->store_result();
    $row['aantal'] = $stmt3->num_rows;
    $stmt3->close();
    $klassen[] = $row;
}
$stmt->close();
if ($rol == "adm" || $rol == "sch" || $rol == "doc") {
    echo json_encode($klassen);
}
