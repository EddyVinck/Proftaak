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
    $activeTab['colleges'] = "active";
}
if($_SESSION['rol'] != "sch" && $_SESSION['rol'] != "doc" && $_SESSION['rol'] != "adm"){
    header("location: unauthorized.php");
}
//getting vars from the session
$rol = $_SESSION['rol'];
$schoolId = $_SESSION['school_id'];

//getting the schoolnaam from database using the school_id in the session
$schoolNaamQuery = "SELECT naam FROM scholen WHERE id = $schoolId LIMIT 1";
$result = mysqli_query($db, $schoolNaamQuery);
$schoolNaam = "";
if($row = mysqli_fetch_assoc($result)){
    $schoolNaam = $row['naam'];
}

$query = "SELECT * FROM colleges WHERE scholen_id = $schoolId";
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
if(isset($_GET['stu'])){
    $studentenVerificatie = $_GET['stu'];
}
else{
    $studentenVerificatie = "ost";
}
$docentenQuery = "SELECT users.id , users.rol , users.naam,
            colleges.id AS college_id,
            scholen.id AS school_id
            FROM users
            INNER JOIN klassen
            ON klassen.id = users.klassen_id
            INNER JOIN colleges
            ON klassen.colleges_id = colleges.id
            INNER JOIN scholen
            ON colleges.scholen_id = scholen.id
            WHERE users.rol = '$docentenVerificatie' AND scholen.id = $schoolId  ORDER BY users.id";
$sqlResult = mysqli_query($db, $docentenQuery);
$docenten = [];
while($row = mysqli_fetch_assoc($sqlResult)){
    $docenten[] = $row; 	//places everything in the array
}

// query for unverified students
$query = 
"SELECT users.id, users.naam, users.email, users.klassen_id, users.rol,
    klassen.naam AS klas_naam,
    colleges.naam AS college_naam, colleges.id AS college_id,
    scholen.id AS school_id
    FROM `users`
    INNER JOIN klassen
	ON users.klassen_id = klassen.id
    INNER JOIN colleges
	ON klassen.colleges_id = colleges.id
    INNER JOIN scholen
    ON colleges.scholen_id = scholen.id
    WHERE users.rol = '$studentenVerificatie' AND scholen.id = $schoolId"; 

$result = mysqli_query($db,$query);
$unverifiedStudents = [];
while($row = mysqli_fetch_assoc($result)){
    $unverifiedStudents[] = $row; 	//places everything in the array
}

// dump($_SESSION);

if(isset($_SESSION['college_id']))
{
    $collegeId = $_SESSION['college_id'];
    $pageColor = changePageColors($db, $collegeId);
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
<?php createHeader($pageColor);?>
<main>
    <div class="container">
        <div class="section">
            <div class="card">
                <div class="card-content">
                <h3><?=$schoolNaam?></h3>
                </div>
                <div class="card-tabs">
                    <ul class="tabs tabs-fixed-width">
                        <?php if($rol == "sch" || $rol == 'adm'){?>
                        <li class="tab"><a class="<?=$activeTab['colleges']?>" href="#colleges">Colleges</a></li>
                        <li class="tab"><a class="<?=$activeTab['leraren']?>" href="#leraren">leraren</a></li>
                        <?php }
                        if ($rol == "doc" || $rol == 'adm'){
                        ?>
                        <li class="tab"><a class="<?=$activeTab['studenten']?>" href="#studenten">studenten</a></li>
                        <?php } ?>
                    </ul>
                    </div>
                    <div class="card-content grey lighten-4">
                    <!--begin Tabje colleges-->
                    <?php if($rol == "sch" || $rol == 'adm'){?>
                    <div id="colleges"> 
                        <table id="collegeTable" class="responsive-table">
                        <thead>
                        <tr>
                            <th class="center" style="width: 15%">Bewerk</th>
                            <th style="width: 45%">Naam</th>
                            <th style="width: 25%">Kleur</th>
                            <th class="center" style="width: 15%">Selecteer</th>
                        </tr>
                        </thead>
                        <tfoot> 
                            <tr>
                                <td class="center">
                                    <a class="btn-floating btn-large red" onclick="addTableRow();">
                                    <i class="material-icons">add</i>
                                    </a>
                                </td>
                                <td>
                                    <a id="saveAllRows" class="btn-floating btn-large red tooltipped" 
                                    data-position="bottom"
                                    data-delay="10"
                                    data-tooltip="Klik om alle nieuwe rijen op te slaan"
                                    onclick="">
                                        <i class="material-icons">save</i>
                                    </a>
                                <td></td>
                                <td class="center">
                                    <a id="deleteSelectedRows" class="btn-floating btn-large red tooltipped" 
                                    data-position="bottom"
                                    data-delay="10"
                                    data-tooltip="Klik om alle nieuwe rijen op te slaan"
                                    onclick="">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </td>
                            </tr>
                        </tfoot>    
                        <tbody id="collegeTbody">
                        <?php
                        for($tableRow=0;$tableRow<count($colleges);$tableRow++){
                        ?>
                            <tr id="<?=$tableRow?>">
                                <td class="center">
                                <a onclick="editCollegeAjax(<?=$colleges[$tableRow]['id']?>);" 
                                        class="btn-floating btn-medium waves-effect waves-light red tooltipped"
                                        data-position="bottom"
                                    data-delay="10"
                                    data-tooltip="Klik om deze rij te bewerken">
                                        <i class="material-icons">edit</i></a>
                                </td>
                                <td class="center">
                                <div class="row center ">
                                    <form method="POST">
                                    <div  class="input-field beheer-inputs col s2 center">
                                        <input value="<?=$colleges[$tableRow]['naam']?>" 
                                        id="input<?=$colleges[$tableRow]['id']?>" 
                                        type="text" class="validate">
                                        <label id="lbl<?=$colleges[$tableRow]['id']?>" class="active" 
                                        data-error="Het is hetzelfde" 
                                        data-success=""
                                        for="input<?=$colleges[$tableRow]['id']?>"> </label>
                                    </div>
                                    
                                    </form>
                                </div>
                                </td>
                                <td class="center">
                                    <input id="col<?=$colleges[$tableRow]['id']?>" class='colorpicker' value='<?=$colleges[$tableRow]['kleur']?>'/>
                                </td>
                                <td class="center">
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
                        <table  class="centered responsive-table" id="lerarenTabel">
                        <thead>
                        <tr>
                            <th>Naam</th>
                            <th>College</th>
                            <th>Geverifieerd</th>
                        </tr>
                        </thead>
                        <tbody id="lerarenTbody">
                            <?php 
                            for($x=0;$x<count($docenten);$x++){
                            ?>
                            <tr>
                                <td>
                                    <?=$docenten[$x]['naam']?>
                                </td>
                                <td>
                                    <select name="colleges" class="collegeSelect" onchange="changeLeraarCollege(this.value,<?=$docenten[$x]['id']?>);">
                                        <?php
                                        for($y=0;$y<count($colleges);$y++){
                                            if ($docenten[$x]['college_id'] == $colleges[$y]['id']){?>
                                                <option selected value="<?=$colleges[$y]['id']?>"><?=$colleges[$y]['naam']?></option>
                                        <?php } else{?>
                                                <option  value="<?=$colleges[$y]['id']?>"><?=$colleges[$y]['naam']?></option>
                                        <?php }}?>
                                    </select>
                                </td>
                               <td class="valign-wrapper">
                                    <div class="row">
                                        <div class="col s12">
                                            <a id="verifyLeraren<?=$x; ?>" class="btn waves-effect <?= properButtonColorForRole($docenten[$x]['rol']); ?>"
                                            onclick="updateVerifiedStatusAjax(
                                                <?= $docenten[$x]['id'];?>,
                                                '<?=$x; ?>','verifyLeraren')">
                                                <?= properRole($docenten[$x]['rol']);?>
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
                    <?php } if ($rol == "doc" || $rol == 'adm'){
                    ?>
                    <!--begin tabje studenten-->
                    <div id="studenten">
                        <div class="row">
                            <?php if ($studentenVerificatie == "stu"){?>
                                <div class="col s12 m3 l3">
                                    <a href="?stu=ost&active=studenten" class="waves-effect waves-light btn">ongeverifieerd</a>
                                </div>
                            <?php }else if ($studentenVerificatie == "ost"){ ?>
                                <div class="col s12 m3 l3">
                                    <a href="?stu=stu&active=studenten" class="waves-effect waves-light btn">geverifieerd</a>
                                </div>
                            <?php } ?>
                        </div>
                        <table  class="responsive-table">
                            <thead>
                            <tr>
                                <th>Naam</th>
                                <th>College</th>                            
                                <th>Klas</th>
                                <th>rol</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            // dump($unverifiedStudents);
                                for ($i=0; $i < count($unverifiedStudents); $i++) { 
                                ?>
                                    <tr class="">
                                        <td class="valign-wrapper"><?php echo $unverifiedStudents[$i]['naam'];?></td>
                                        <td>
                                        <!--getSelect_Ajax(this.value,'klassen','colleges_id','klasSelect', 'klas')-->
                                            <select id="collegeSelect<?=$i?>" onchange="getSelect_Ajax(this.value,'klassen','colleges_id','klasSelect<?php echo $i;?>', 'klas')">
                                                <option value="" selected><?= $unverifiedStudents[$i]['college_naam']?></option>
                                                <?php 
                                                for($j=0;$j < count($colleges); $j++)
                                                {?>
                                                    <option value="<?= $colleges[$j]['id']?>"><?= $colleges[$j]['naam']?></option>
                                                <?php }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select id="klasSelect<?php echo $i;?>" 
                                            onchange="changeStudentKlas(this.value,<?= $unverifiedStudents[$i]['id']?>);">
                                                <option value="" disabled selected><?= $unverifiedStudents[$i]['klas_naam']?></option>
                                                <?php
                                                $collegeVanKlas = $unverifiedStudents[$i]['college_id'];
                                                $query = "SELECT naam FROM klassen WHERE colleges_id = $collegeVanKlas AND rol = 'studenten'";
                                                $result = mysqli_query($db, $query);
                                                $options = [];
                                                while ($row = mysqli_fetch_assoc($result)){
                                                    $options[] = $row;
                                                }                                                                                                                                                                                                                             
                                                for($k = 0; $k < count($options); $k++){?>
                                                    <option value=""><?= $options[$k]['naam']?></option><?php
                                                }
                                                dump($options);
                                            ?>                                    
                                            </select>                                            
                                        </td>                                    
                                        <td class="valign-wrapper">
                                            <a style="width: 200px;" id="verifiedButton<?php echo $i; ?>" class="btn waves-effect <?php echo properButtonColorForRole($unverifiedStudents[$i]['rol']); ?>"
                                            onclick="updateVerifiedStatusAjax(
                                                <?php echo $unverifiedStudents[$i]['id'];?>, 
                                                '<?php echo $i;?>','verifiedButton'                                            
                                            )">
                                                <?php echo properRole($unverifiedStudents[$i]['rol']);?>
                                            </a>                                        
                                        </td>                                    
                                    </tr>
                                <?php
                            }?>                       
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
</main>
<?php createFooter($pageColor);?>
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