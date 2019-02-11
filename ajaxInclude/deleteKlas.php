<?php
include '../inc/functions.php';
$con =  ConnectToDatabase();
checkSession();
checkUserVerification();
$er = [];
$rol = $_SESSION['rol'];
$school = $_SESSION['school_id'];
$delid = $_POST['id'];
$klas = [];

$sql = "SELECT klassen.id as klas_id, klassen.colleges_id, klassen.naam, colleges.id AS college_id, colleges.scholen_id FROM klassen
        INNER JOIN colleges ON colleges.id = klassen.colleges_id
        WHERE klassen.id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $delid);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_array(MYSQLI_ASSOC))
{
  $klas = $row;
}
$stmt->close();

switch ($rol) {
    case 'adm':
        deleteKlas();
        break;
    case 'sch':
        if ($school == $klas['scholen_id']) {
            deleteKlas();
        }
        else {
            echo "unauthorized";
        }
        break;
    case 'doc':
        if ($school == $klas['scholen_id']) {
            deleteKlas();
        }
        break;
    default:
        echo "unauthorized";
        break;
}
function deleteKlas(){
    global $delid;
    global $con;
    global $er;

    // If there are users in this class, it doesn't delete it, it returns the error
    $sql = "SELECT * FROM users WHERE klassen_id =?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $delid);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        array_push($er, "users");
    }
    $stmt->close();

    if ($er == []) {
        $sql = "DELETE FROM klassen WHERE id= ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('i', $delid);
        $stmt->execute();
        $stmt->close();
    }
}

echo json_encode($er);
