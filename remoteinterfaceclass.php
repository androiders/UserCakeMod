<?php
require_once("models/config.php");

class RemoteInterfaceClass
{
	/**
	 * Returns the logged in user if any or null
	 */
	public function getLoggedInUser()
	{
 		global $loggedInUser;
// 		if( isset($_SESSION["userCakeUser"]))
// 		{
 			$user = $loggedInUser;//$_SESSION["userCakeUser"];
// 		}
// 		$user = $_SESSION["userCakeUser"];
// 		if( is_null($user))
// 			return "user null";
// 		else 
 			$logData = print_r($_SESSION, true);
 			file_put_contents("log.txt", $logData, FILE_APPEND | LOCK_EX);
 			file_put_contents("log.txt", session_id(), FILE_APPEND | LOCK_EX);
 			return print_r($_SESSION,true);
//			return var_dump($_SESSION);
	}
	
	public function getAppointments($dateFrom, $dateTo)
	{
		
	}
	
	public function addUserToApointment($appointment, $user)
	{
		
	}
	
	public function isUserLoggedIn($userId)
	{
		
	}
	
	public function removeUserFromAppointment($theAppointment, $user)
	{
		
	}
}

?>