<?php include("inc/functions.php");?>
<?php
$db = ConnectToDatabase();
checkSession();
if($_SESSION['rol'] != "sch" && $_SESSION['rol'] != "doc"){
    header("location: index.php");
}

// dump($_SESSION);


$query = 
"   SELECT users.id, users.naam, users.email, users.klassen_id, users.rol,
    klassen.naam AS klas_naam,
    colleges.naam AS college_naam
    FROM `users`
    INNER JOIN klassen
	ON users.klassen_id = klassen.id
    INNER JOIN colleges
	ON klassen.colleges_id = colleges.id
    WHERE users.rol = 'ost'"; 
    // op de lege plek komt de where college = 1 als je die hebt

$result = mysqli_query($db,$query);
while($row = mysqli_fetch_assoc($result)){
    $unverifiedStudents[] = $row; 	//places everything in the array
}
$schoolId = $_SESSION['school_id'];
$query = "  SELECT colleges.id, colleges.naam 
            FROM colleges
            INNER JOIN scholen
            ON colleges.scholen_id = scholen.id            
            WHERE scholen.id = $schoolId";
$result = mysqli_query($db, $query);
while($row = mysqli_fetch_assoc($result))
{
    $colleges[] = $row;
}

dump($unverifiedStudents);
dump($colleges);
dump($_SESSION);



// dump($_SESSION);
?>
<!DOCTYPE html>
<head>
	<head>
      <!--Import Google Icon Font-->
      <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
      <link type="text/css" rel="stylesheet" href="css/footer.css"  media="screen,projection"/>
      <link rel="stylesheet" href="font-awesome-4.7.0\css\font-awesome.min.css">
      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
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
                <li><a href="projecten_lijst.php?college=<?php echo $_SESSION['college_id'];?>" class=" waves-effect"><i class="small material-icons left">home</i>Mijn College</a></li>
                <li><a href="colleges.php" class=" waves-effect"><i class="small material-icons left">view_module</i>Colleges</a></li>
                <li><a href="#inbox.php" class=" waves-effect"><i class="small material-icons left">message</i>Priveberichten</a></li>
                <li><a href="index.php?logout=true" class=" waves-effect"><i class="small material-icons left">exit_to_app</i> Log uit </a></li>
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
        <li><a href="projecten_lijst.php?college=<?php echo $_SESSION['college_id'];?>"><i class="small material-icons left">home</i>Mijn College</a></li>
        <li><a href="colleges.php"><i class="small material-icons left">view_module</i>Colleges</a></li>
        <li><a href="#inbox.php"><i class="small material-icons left">message</i>Priveberichten</a></li>
        <li><a href="index.php?logout=true"><i class="small material-icons left">exit_to_app</i> Log uit </a></li>
        <li><a href="#!">Second Link</a></li>
        <li><div class="divider"></div></li>
        <li><a class="subheader">Subheader</a></li>
        <li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
  </ul>
</sidenav>
<main>
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col s12">
                    <table>
                        <thead>
                        <tr>
                            <th>Naam</th>
                            <th>College</th>                            
                            <th>Klas</th>
                            <th>rol</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php $idCounter = 0;
                            for ($i=0; $i < count($unverifiedStudents); $i++) { 
                            ?>
                                <tr>
                                    <td><?php echo $unverifiedStudents[$i]['naam'];?></td>
                                    <td>
                                    <!--getSelect_Ajax(this.value,'klassen','colleges_id','klasSelect', 'klas')-->
                                        <select onchange="getSelect_Ajax(this.value,'klassen','colleges_id','klasSelect<?php echo $idCounter;?>', 'klas')">
                                            <option value="" disabled selected>Kies college</option>
                                            <?php 
                                            for($i=0;$i < count($colleges); $i++)
                                            {?>
                                                <option value="<?= $colleges[$i]['id']?>"><?= $colleges[$i]['naam']?></option>
                                            <?php }
                                            ?>
                                        </select>
                                    </td>
                                    <td id="">
                                        <select id="klasSelect<?php echo $idCounter; $idCounter++;?>">
                                            <option value="" disabled selected>Selecteer klas</option>
                                        </select>
                                    </td>                                    
                                    <td><a class="btn waves-effect"><?php echo $unverifiedStudents[$i]['rol'];?></a> </td>                                    
                                </tr>
                            <?php
                        }?>                       
                        </tbody>
                    </table>
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

<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/ajaxfunctions.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>
<script>
    initializeSelectElements();
</script>
</body>