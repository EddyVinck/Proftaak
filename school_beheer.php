<?php
include("inc/functions.php");
$db =  ConnectToDatabase();
checkSession();

if($_SESSION['rol']!="sch"){
    header("location: index.php");
}
$id = $_SESSION['id'];
$queryGetShoolID = "SELECT scholen_id FROM users WHERE id = $id";
$result = mysqli_query($db, $queryGetShoolID);
while($row = mysqli_fetch_assoc($result)){
    $data = $row; 	//places everything in the array
}
$school_id = $data['scholen_id'];
$query = "SELECT * FROM colleges WHERE scholen_id = $school_id";
$result = mysqli_query($db,$query);
while($result2 = mysqli_fetch_assoc($result)){
    $colleges[] = $result2; 	//places everything in the array
}
?>
<!DOCTYPE html>
<head>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="css/materializeAddons.css"  media="screen,projection"/>
    
    <link type="text/css" rel="stylesheet" href = "css/school_beheer.css"/>
    <link type="text/css" rel="stylesheet" href = "css/footer.css"/>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
<header>    
    <nav class="top-nav teal">
        <div class="nav-wrapper">
            <div class="container">
        <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
            <div class="col s12" style="padding: 0 .75rem;">                
                <a href="index.php" class="brand-logo">Logo</a>        
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="#" class=" waves-effect"><i class="small material-icons left">home</i>Mijn College</a></li>
                <li><a href="#" class=" waves-effect"><i class="small material-icons left">view_module</i>Colleges</a></li>
                <li><a href="#" class=" waves-effect"><i class="small material-icons left">message</i>Priveberichten</a></li>
                <li><a href="#" class=" waves-effect"><i class="small material-icons left">exit_to_app</i> Log uit </a></li>
            </ul>
            </div>       
            <!--<a href="#" class="brand-logo">Logo</a>-->
            </div>        
        </div>        
    </nav>    
</header>
<sidenav>
    <ul id="slide-out" class="side-nav">
        <li><div class="userView">
            <div class="background">
                <img src="images/office.jpg">
            </div>
                <a href="#!user"><img class="circle" src="images/yuna.jpg"></a>
                <a href="#!name"><span class="white-text name">John Doe</span></a>
                <a href="#!email"><span class="white-text email">jdandturk@gmail.com</span></a>
            </div>
        </li>
        <li><a href="#"><i class="small material-icons left">home</i>Mijn College</a></li>
        <li><a href="#"><i class="small material-icons left">view_module</i>Colleges</a></li>
        <li><a href="#"><i class="small material-icons left">message</i>Priveberichten</a></li>
        <li><a href="#"><i class="small material-icons left">exit_to_app</i> Log uit </a></li>
        <li><a href="#!">Second Link</a></li>
        <li><div class="divider"></div></li>
        <li><a class="subheader">Subheader</a></li>
        <li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
  </ul>
</sidenav>
<main>
    <div class="container">
        <div class="section">
            <div class="card">
                <div class="card-content">
                <h3>placeholder text placeholder text placeholder text</h3>
                </div>
                <div class="card-tabs">
                    <ul class="tabs tabs-fixed-width">
                        <li class="tab"><a class="active" href="#colleges">Colleges</a></li>
                        <li class="tab"><a href="#leraren">leraren</a></li>
                    </ul>
                    </div>
                    <div class="card-content grey lighten-4">
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
                        <!---->
                            <tr>
                                <td>
                                    <!--<div id="fixed-fab" class="fixed-action-btn horizontal click-to-toggle">-->
                                        <a class="btn-floating btn-large red" onclick="addTableRow();">
                                        <i class="material-icons">add</i>
                                        </a>
                                        <!--<ul>
                                        <li><a class="btn-floating red"><i class="material-icons">clear</i></a></li>
                                        </ul>-->
                                    <!--</div>-->
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
                                <a class="<?=$colleges[$tableRow]['kleur']?> waves-effect waves-light btn"></a>
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
                    <div id="leraren">Test 2</div>
                </div>
            </div>
        </div>
    </div>
</main>
<footer class="page-footer teal">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
            <h5 class="white-text">Footer Content</h5>
            <p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer content.</p>
            </div>
            <div class="col l4 offset-l2 s12">
            <h5 class="white-text">Links</h5>
            <ul>
                <li><a class="grey-text text-lighten-3" href="#!">Link 1</a></li>
                <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
                <li><a class="grey-text text-lighten-3" href="#!">Link 3</a></li>
                <li><a class="grey-text text-lighten-3" href="#!">Link 4</a></li>
            </ul>
            </div>
        </div>
        </div>
        <div class="footer-copyright">
        <div class="container">
        &copy 2014 Copyright Text
        <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
        </div>
    </div>
</footer>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<!--https://code.jquery.com/jquery-3.2.1.js ???-->
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/materialize.js"></script>
<script>
    initializeSelectElements();
</script>
</body>
</html>