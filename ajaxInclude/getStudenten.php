<?php
include '../inc/functions.php';
$con =  ConnectToDatabase();
checkSession();
checkUserVerification();
$rol = $_SESSION['rol'];
$school = $_SESSION['school_id'];
if (isset($_POST['school']) && $rol == "adm") {
    $school = $_POST['school'];
}

// Get a list of colleges first
$colleges = [];
$sql = "SELECT * FROM colleges WHERE scholen_id =?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $school);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_array(MYSQLI_ASSOC))
{
    $colleges[] = $row;
}
$stmt->close();

// Get a list of klassen dba_first
$klassen = [];
$sql = "SELECT klassen.naam, klassen.id, klassen.rol FROM klassen
        INNER JOIN colleges
        ON colleges.id = klassen.colleges_id
        INNER JOIN scholen
        ON scholen.id = colleges.scholen_id
        WHERE scholen.id = ? AND klassen.rol = 'studenten' ORDER BY klassen.id";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $school);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_array(MYSQLI_ASSOC))
{
    $klassen[] = $row;
}

$users = [];
$sql = "SELECT users.id as user_id, users.naam as name, users.rol as rol, users.klassen_id as users_klasId,
        scholen.id as school_id, colleges.id as college_id FROM users
        INNER JOIN klassen ON klassen.id = users.klassen_id
        INNER JOIN colleges ON colleges.id = klassen.colleges_id
        INNER JOIN scholen ON scholen.id = colleges.scholen_id
        WHERE scholen.id = ? AND users.rol IN('stu', 'ost') ORDER BY users.id";

$stmt = $con->prepare($sql);
$stmt->bind_param('i', $school);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_array(MYSQLI_ASSOC))
{
    $row['verification'] = 'green';
    $row['tooltip'] = 'Klik om de verificatie in te trekken';
    $row['buttonText'] = "Geverifieerd";
    if ($row['rol'] == "ost") {
        $row['verification'] = 'red';
        $row['tooltip'] = 'Klik om te verifieren';
        $row['buttonText'] = "Ongeverifieerd";
    }
    $row['colleges'] = $colleges;
    foreach ($row['colleges'] as $key => $college) {
        $row['colleges'][$key]['selected'] = "";
        if ($row['college_id'] == $college['id']) {
            $row['colleges'][$key]['selected'] = "selected";
        }
    }
    $sql2 = "SELECT klassen.naam, klassen.id, klassen.rol FROM klassen
            INNER JOIN colleges
            ON colleges.id = klassen.colleges_id
            WHERE colleges.id = ? AND klassen.rol = 'studenten' ORDER BY klassen.id";
    $stmt2 = $con->prepare($sql2);
    $stmt2->bind_param('i', $row['college_id']);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    while ($row2 = $result2->fetch_array(MYSQLI_ASSOC))
    {
        $row['klassen'][] = $row2;
    }
    $stmt2->close();
    $users[] = $row;
}
$stmt->close();

if ($rol = 'sch' || $rol == 'adm' || $rol == "doc") {
    // code...
}
echo json_encode($users);
