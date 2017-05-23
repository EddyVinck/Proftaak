<?php
function ConnectToDatabase(){
	$db = mysqli_connect("localhost","root","usbw",'mydb');	//connects to the database from MyPHPAdmin
	mysqli_query($db, "SET NAMES 'utf8'");			// to make sure all quotation marks are not weird symbols	
	return $db;
}
function dump($var, $varname = false, $file = false, $line = false)
{
	echo "variable: " . $varname ." dumped in file: " . $file . " on line: " . $line;
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}
function checkSession(){
	session_start();
	if (!isset($_SESSION['loggedIn'])){
		$_SESSION['loggedIn'] = false;
		$_SESSION['rol'] = '';
		$_SESSION['id'] = '';
		$_SESSION['naam'] = '';
	}
}
function checkSchool($debug = false) {
	$db = ConnectToDatabase();    
    $sessionSchool = $_SESSION['school_id'];
    $sessionCollege = $_SESSION['college_id'];
    $getCollege = $_GET['college'];   
	   
    $query = 
    "   SELECT scholen_id FROM `colleges`
        WHERE id = $getCollege;";
    $result = mysqli_query($db,$query);
    while ($row = mysqli_fetch_assoc($result))
    {
        $scholenId[] = $row;
		// if the school_id in the session is the same id as in the adress bar
        if($sessionSchool == $scholenId[0]['scholen_id'])
        {
            // everything is good :)
        }
		else {
			// user entered a college id in the adress bar that does not belong to his or her school
			header("Location: unauthorized.php");			
		}
    }
	if($debug == true){
		echo "<pre>";
		echo "SESSION school: ". $sessionSchool ." & SESSION college: ". $sessionCollege;
		echo "\nGET college: ".$getCollege;
		echo "</pre>";
		// dump($scholenId, __FILE__, __LINE__);
	}    
}
function truncate($text, $maxLength) 
{
	if(strlen($text) > $maxLength)
	{
		$truncatedString = substr($text, 0, $maxLength);
		$truncatedString .= "...";
		return $truncatedString;
	}
	else {
		return $text;
	}
}

// return a proper string based on user role
function properRole($rol){
    switch ($rol) {
		// unverified						
        case 'ost':
            return "Ongeverifi&euml;erd";
            break;
		// verified						
        case 'stu':
            return "Geverifi&euml;erd";
            break;
		// unverified						
        case 'odo':
            return "Ongeverifi&euml;erd";
            break;
		// verified						
        case 'doc':
            return "Geverifi&euml;erd";
            break;
        default:
            return "butt";
            break;
    }
}
function createHeader($color = 'teal') { ?>
    <header>    
        <nav class="top-nav <?php echo $color; ?>">
            <div class="nav-wrapper">
                <div class="container">
            <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
                <div class="col s12" style="padding: 0 .75rem;">                
                    <a href="index.php" class="brand-logo">Logo</a>        
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="projecten_lijst.php?college=<?php echo $_SESSION['college_id'];?>" class=" waves-effect"><i class="small material-icons left">home</i>Mijn College</a></li>
                    <li><a href="colleges.php" class=" waves-effect"><i class="small material-icons left">view_module</i>Colleges</a></li>
                    <li><a href="#inbox.php" class=" waves-effect"><i class="small material-icons left">message</i>Priveberichten</a></li>
                    <li><a href="school_beheer.php"><i class="small material-icons left">settings</i> Beheer </a></li>
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
                    <img src="">
                </div>
                    <a href="#!user"><img class="circle" src=""></a>
                    <a href="#!name"><span class="white-text name">John Doe</span></a>
                    <a href="#!email"><span class="white-text email">jdandturk@gmail.com</span></a>
                </div>
            </li>
            <li><a href="projecten_lijst.php?college=<?php echo $_SESSION['college_id'];?>" class=" waves-effect"><i class="small material-icons left">home</i>Mijn College</a></li>
            <li><a href="colleges.php" class=" waves-effect"><i class="small material-icons left">view_module</i>Colleges</a></li>
            <li><a href="#inbox.php" class=" waves-effect"><i class="small material-icons left">message</i>Priveberichten</a></li>
            <li><a href="school_beheer.php"><i class="small material-icons left">settings</i> Beheer </a></li>
            <li><a href="index.php?logout=true" class=" waves-effect"><i class="small material-icons left">exit_to_app</i> Log uit </a></li>
            <li><a href="#!">Second Link</a></li>
            <li><div class="divider"></div></li>
            <li><a class="subheader">Subheader</a></li>
            <li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
    </ul>
    </sidenav>
<?php }
function createFooter($color = 'teal'){?>
    <footer class="page-footer <?php echo $color; ?>">
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
<?php }
// return a color based on user role
function properButtonColorForRole($rol){
    switch ($rol) {
		// unverified
        case 'ost':
            return "red";
            break;
		// verified			
        case 'stu':
            return "green";
            break;
		// unverified
        case 'odo':
            return "red";
            break;
		// verified			
        case 'doc':
            return "green";
            break;
        default:
            return "butt";
            break;
    }
}
/*
geef de database connectie mee
geef daarna het correcte collegeId mee waarop de kleuren moeten
gebaseerd mits dit van toepassing is
*/
function changePageColors($connection, $collegeId = false)
{
    if($collegeId != false){
        if(is_numeric($collegeId)) 
        {
            $query = 
            "   SELECT kleur
                FROM colleges
                WHERE id = $collegeId";
            $result = mysqli_query($connection, $query);
            while($row = mysqli_fetch_assoc($result))
            {
                $myColor = $row;
            }
        }
    } else {
        return $myColor = 'teal';
    }
    return $myColor['kleur'];
}
function changeFontColorBasedOn($pageColor) {
    switch ($pageColor) {
        case 'red':
            return 'black';
            break;
        case 'red':
            return 'black';
            break;
        case 'red':
            return 'black';
            break;
        case 'red':
            return 'black';
            break;
        case 'red':
            return 'black';
            break;
        case 'red':
            return 'black';
            break;
        case 'red':
            return 'black';
            break;
        case 'red':
            return 'black';
            break;
        case 'red':
            return 'black';
            break;
        default:
            return 'black';            
            break;
    }
}

