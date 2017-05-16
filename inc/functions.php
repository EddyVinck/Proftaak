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
		dump($scholenId, __FILE__, __LINE__);
	}    
}
?>