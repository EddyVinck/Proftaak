<?php
include("inc/functions.php");
$connection = ConnectToDatabase();
$userId = $_POST["userId"];

$query2 = " SELECT rol
            FROM users
            WHERE id = $userId";
$result2 = mysqli_query($connection, $query2);
while ($row = mysqli_fetch_assoc($result2))
{
    $userInfo = $row;
}

$userRole = $userInfo['rol'];

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

$query2 = 
"   UPDATE users
    SET rol = '$newRole'
    WHERE id = $userId;
";

$result2 = mysqli_query($connection, $query2);
if(!$result2) {
    echo mysqli_error($result2);    
}

?>