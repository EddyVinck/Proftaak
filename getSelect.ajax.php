<?php
include("inc/functions.php");
$q = intval($_GET['q']);
$tableName = $_GET['tableName'];
$idName = $_GET['idName'];
$nextSelect = $_GET['nextSelect'];
$connection = ConnectToDatabase();
if (!$connection) {
    die('Could not connect: ' . mysqli_error($connection));
}

// the standard option that should always be inserted
echo "<option value='' disabled selected>Kies je {$nextSelect}</option>";

$query = "SELECT * FROM $tableName WHERE $idName = $q";
$result = mysqli_query($connection, $query);

// add an option element for every row found in $result
// if the row's name is something other than docenten
while($row = mysqli_fetch_array($result)) {
    if($row['rol'] != 'docenten'){?>
        <option value="<?=$row['id']?>"><?=$row['naam']?></option>
    <?php }
}
mysqli_close($connection);
?>