<?php
include("inc/functions.php");
$q = intval($_GET['q']);
$tableName = $_GET['tableName'];
$idName = $_GET['idName'];
$con = ConnectToDatabase();
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con,"ajax_demo");
echo "<option value='' disabled selected>Kies je college</option>";
$sql="SELECT id, naam FROM $tableName WHERE $idName = $q";
$result2 = mysqli_query($con,$sql);

while($row = mysqli_fetch_array($result2)) {?>
    <option value="<?=$row['id']?>"><?=$row['naam']?></option>
<?php }
mysqli_close($con);
?>