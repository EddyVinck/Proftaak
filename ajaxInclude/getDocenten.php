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
$docenten = [];
$sql = "SELECT users.id , users.rol , users.naam,
            colleges.id AS college_id,
            colleges.kleur as kleur,
            scholen.id AS school_id
            FROM users
            INNER JOIN klassen
            ON klassen.id = users.klassen_id
            INNER JOIN colleges
            ON klassen.colleges_id = colleges.id
            INNER JOIN scholen
            ON colleges.scholen_id = scholen.id
            WHERE users.rol = 'doc' OR users.rol = 'odo' AND scholen.id = ?  ORDER BY users.id";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $school);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_array(MYSQLI_ASSOC))
{
    $row['verification'] = 'green';
    $row['tooltip'] = 'Klik om de verificatie in te trekken';
    $row['buttonText'] = "Geverifieerd";
    if ($row['rol'] == "odo") {
        $row['verification'] = 'red';
        $row['tooltip'] = 'Klik om te verifieren';
        $row['buttonText'] = "Ongeverifieerd";
    }
    $sql2 = "SELECT naam, id FROM colleges WHERE scholen_id = ?";
    $stmt2 = $db->prepare($sql2);
    $stmt2->bind_param('i', $school);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    while ($row2 = $result2->fetch_array(MYSQLI_ASSOC))
    {
        $row2['selected'] = "";
        if ($row2['id'] == $row['college_id']) {
            $row2['selected'] = "selected";
        }
        $row['selects'][] = $row2;
    }
    $stmt2->close();
    $docenten[] = $row;
}
if ($rol == "adm" || $rol == "sch") {
    echo json_encode($docenten);
}
