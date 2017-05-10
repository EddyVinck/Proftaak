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
?>