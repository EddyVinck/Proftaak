<?php
include("inc/functions.php");
$db =  ConnectToDatabase();
checkSession();
$activeTab = [
    'colleges' => "",
    'leraren' =>  "",
    'studenten' => ""

];
if (isset($_GET['active'])){
    $activeTab[$_GET['active']] = "active";
}
else{
    $activeTab['leraren'] = "active";
}
if($_SESSION['rol']!="sch" && $_SESSION['rol']!="doc" && $_SESSION['rol']!="adm"){
    header("location: index.php");
}
$id = $_SESSION['id'];

$school_id = $_SESSION['school_id'];
$query = "SELECT * FROM colleges WHERE scholen_id = $school_id";
$result = mysqli_query($db,$query);
while($result2 = mysqli_fetch_assoc($result)){
    $colleges[] = $result2; 	//places everything in the array
}
if(isset($_GET['doc'])){
    $docentenVerificatie = $_GET['doc'];
}
else{
    $docentenVerificatie = "odo";
}
$usersQuery = "SELECT users.id , users.rol , users.naam,
            colleges.id AS college_id,
            scholen.id AS school_id                  
            FROM users
            INNER JOIN klassen
            ON klassen.id = users.klassen_id
            INNER JOIN colleges
            ON klassen.colleges_id = colleges.id
            INNER JOIN scholen
            ON colleges.scholen_id = scholen.id
            WHERE users.rol = '$docentenVerificatie'  ORDER BY users.id";
$sqlResult = mysqli_query($db, $usersQuery);
$users = [];
while($row = mysqli_fetch_assoc($sqlResult)){
    $users[] = $row; 	//places everything in the array
}
?>
<!DOCTYPE html>
<head>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="css/materializeAddons.css"  media="screen,projection"/>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.simple-color.js"></script>
    <link type="text/css" rel="stylesheet" href = "css/school_beheer.css"/>
    <link type="text/css" rel="stylesheet" href = "css/footer.css"/>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
<?php createHeader();?>
<main>
    <div class="container">
        <div class="section">
            <div class="card">
                <div class="card-content">
                <h3>placeholder text placeholder text placeholder text</h3>
                </div>
                <div class="card-tabs">
                    <ul class="tabs tabs-fixed-width">
                        <li class="tab"><a class="<?=$activeTab['colleges']?>" href="#colleges">Colleges</a></li>
                        <li class="tab"><a class="<?=$activeTab['leraren']?>" href="#leraren">leraren</a></li>
                    </ul>
                    </div>
                    <div class="card-content grey lighten-4">
                    <!--begin Tabje colleges-->
                    <div id="colleges"> 
                        <table id="collegeTable">
                        <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Kleur</th>
                            <th>Selecteer</th>
                        </tr>
                        </thead>
                        <tfoot> 
                            <tr>
                                <td>
                                    <a class="btn-floating btn-large red" onclick="addTableRow();">
                                    <i class="material-icons">add</i>
                                    </a>
                                    <a id="saveAllRows" class="btn-floating btn-large red tooltipped" 
                                    data-position="bottom"
                                    data-delay="10"
                                    data-tooltip="Klik om alle nieuwe rijen op te slaan"
                                    onclick="">
                                        <i class="material-icons">save</i>
                                    </a>
                                    <td></td><td>
                                    <a id="deleteSelectedRows" class="btn-floating btn-large red tooltipped" 
                                    data-position="bottom"
                                    data-delay="10"
                                    data-tooltip="Klik om alle nieuwe rijen op te slaan"
                                    onclick="">
                                        <i class="material-icons">delete</i>
                                    </a>
                                    </td>
                                </td>
                                <td>
                                    
                                </td>
                            </tr>
                        </tfoot>    
                        <tbody id="collegeTbody">
                        <?php
                        for($tableRow=0;$tableRow<count($colleges);$tableRow++){
                        ?>
                            <tr id="<?=$tableRow?>">
                                <td>
                                <div class="row">
                                    <form method="POST">
                                    <div  class="input-field beheer-inputs col s2">
                                        <input value="<?=$colleges[$tableRow]['naam']?>" 
                                        id="input<?=$colleges[$tableRow]['id']?>" 
                                        type="text" class="validate">
                                        <label id="lbl<?=$colleges[$tableRow]['id']?>" class="active" 
                                        data-error="Het is hetzelfde" 
                                        data-success=""
                                        for="input<?=$colleges[$tableRow]['id']?>"> </label>
                                    </div>
                                    <a onclick="editCollegeAjax(<?=$colleges[$tableRow]['id']?>);" 
                                        class="btn-floating btn-medium waves-effect waves-light red">
                                        <i class="material-icons">edit</i></a>
                                    </form>
                                </div>
                                </td>
                                <td>
                                    <input id="col<?=$colleges[$tableRow]['id']?>" class='colorpicker' value='<?=$colleges[$tableRow]['kleur']?>'/>
                                </td>
                                <td>
                                    <input class="filled-in" type="checkbox" id="select<?=$colleges[$tableRow]['id']?>"/>
                                    <label for="select<?=$colleges[$tableRow]['id']?>"></label>
                                </td>

                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                    </div>
                    <!--begin tabje leraren-->
                    <div id="leraren">
                        <div class="row">
                        <?php if ($docentenVerificatie == "doc"){?>
                            <div class="col s12 m3 l3">
                                <a href="?doc=odo&active=leraren" class="waves-effect waves-light btn">ongeverifieerd</a>
                            </div>
                        <?php }else if ($docentenVerificatie == "odo"){ ?>
                            <div class="col s12 m3 l3">
                                <a href="?doc=doc&active=leraren" class="waves-effect waves-light btn">geverifieerd</a>
                            </div>
                        <?php } ?>
                        </div>
                        <table class="centered" id="lerarenTabel">
                        <thead>
                        <tr>
                            <th>Naam</th>
                            <th>College</th>
                            <th>Geverifieerd</th>
                        </tr>
                        </thead>
                        <tbody id="lerarenTbody">
                            <?php 
                            for($x=0;$x<count($users);$x++){
                            ?>
                            <tr>
                                <td>
                                    <?=$users[$x]['naam']?>
                                </td>
                                <td>
                                    <select name="colleges" class="collegeSelect" onchange="changeLeraarCollege(this.value,<?=$users[$x]['id']?>);">
                                        <?php
                                        for($y=0;$y<count($colleges);$y++){
                                            if ($users[$x]['college_id'] == $colleges[$y]['id']){?>
                                                <option selected value="<?=$colleges[$y]['id']?>"><?=$colleges[$y]['naam']?></option>
                                        <?php } else{?>
                                                <option  value="<?=$colleges[$y]['id']?>"><?=$colleges[$y]['naam']?></option>
                                        <?php }}?>
                                    </select>
                                </td>
                               <td class="valign-wrapper">
                                    <div class="row">
                                        <div class="col s12">
                                            <a id="verifiedButton<?=$x; ?>" class="btn waves-effect <?= properButtonColorForRole($users[$x]['rol']); ?>"
                                            onclick="updateVerifiedStatusAjax(
                                                <?= $users[$x]['id'];?>,
                                                '<?=$x; ?>')">
                                                <?= properRole($users[$x]['rol']);?>
                                            </a> 
                                        </div>
                                    </div>
                                </td>       
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</main>
<?php createFooter();?>

<!--https://code.jquery.com/jquery-3.2.1.js ???-->
<script type="text/javascript" src="js/ajaxfunctions.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/materialize.js"></script>


<script>
    initializeSelectElements();
    initSideNav();
</script>
</body>
</html>