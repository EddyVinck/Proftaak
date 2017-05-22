<?php
include("inc/functions.php");
$connection = ConnectToDatabase();
$userId = $_POST["userId"];
$userRole = $_POST["userRole"];

switch ($userRole) {
    case 'ost':
        $newRole = 'stu';
        break;
    case 'stu':
        $newRole = 'ost';
        break;
    case 'odo':
        $newRole = 'doc';
        break;
    case 'doc':
        $newRole = 'odo';
        break;
    default:
        $newRole = 'ost';
        break;
};

$query = 
"   UPDATE users
    SET rol = '$newRole'
    WHERE id = $userId;
";

$result = mysqli_query($connection, $query);
echo mysqli_error($result);

?>