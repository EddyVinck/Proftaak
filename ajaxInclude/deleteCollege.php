<?php
include '../inc/functions.php';
$con =  ConnectToDatabase();
checkSession();
checkUserVerification();
$er = [];
$rol = $_SESSION['rol'];
$school = $_SESSION['school_id'];
$delid = $_POST['id'];
$college = [];

// Get the college details to see if the school is the same as the $rol
$sql = "SELECT * FROM colleges WHERE id =?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $delid);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_array(MYSQLI_ASSOC))
{
  $college = $row;
}
$stmt->close();

switch ($rol) {
    case 'adm':
        deleteCollege();
        break;
    case 'sch':
        if ($school == $college['scholen_id']) {
            deleteCollege();
        }
        else {
            echo "unauthorized";
        }
        break;
    default:
        echo "unauthorized";
        break;
}

function deleteCollege(){
    global $delid;
    global $con;
    global $er;

    // If there are klassen in this college, it doesn't delete it, it returns the error
    $sql = "SELECT * FROM klassen WHERE colleges_id =? AND rol != 'docenten'";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $delid);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        array_push($er, "studentenklassen");
    }
    $stmt->close();

    // If there are docenten in this college, it doesn't delete it and it returns an error
    $sql = "SELECT * FROM users
    INNER JOIN klassen ON klassen.id = users.klassen_id
    INNER JOIN colleges ON colleges.id = klassen.colleges_id
     WHERE klassen.rol = 'docenten' AND colleges.id=? AND users.rol != 'sch'";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $delid);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        array_push($er, "docenten");
    }
    $stmt->close();

    // If there is a schooladmin registered in this college, it won't delete aand return an error
    $sql = "SELECT * FROM users
    INNER JOIN klassen ON klassen.id = users.klassen_id
    INNER JOIN colleges ON colleges.id = klassen.colleges_id
     WHERE klassen.rol = 'docenten' AND colleges.id=? AND users.rol = 'sch'";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $delid);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        array_push($er, "schooladmin");
    }
    $stmt->close();

    // If the $er array is empty it will delete both the empty docentenklas and the college
    if ($er == []) {
        $sql = "DELETE FROM klassen WHERE colleges_id = ? AND rol = 'docenten'";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('i', $delid);
        $stmt->execute();
        $stmt->close();

        $sql = "DELETE FROM colleges WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('i', $delid);
        $stmt->execute();
        $stmt->close();
    }
}
echo json_encode($er);
