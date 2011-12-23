<?php

require_once("models/config.php");
require 'remoteinterfaceclass.php';


//if(!empty($_GET))
{
	//make singleton or something (static?)
	$myInterface = new RemoteInterfaceClass();
	
	$method = trim($_GET["method"]);
	$arg = trim($_GET["arg"]);
//	var_dump($_GET);
	$user = null;
	if($method == "getLoggedInUser")
	{
		$user = $myInterface->getLoggedInUser();
	}
	
	$answer = array('Answer' => "Ok");
	$logData = print_r($_SESSION, true);
	file_put_contents("log.txt", $logData, FILE_APPEND | LOCK_EX);
	$logData2 = print_r($_GET, true);
	file_put_contents("log.txt", $logData2, FILE_APPEND | LOCK_EX);
	echo json_encode($user);
}

?>