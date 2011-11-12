<?php
/*
 UserCake Version: 1.4
http://usercake.com

Developed by: Adam Davis
*/


class User
{
	private $clean_email;
	public $status = false;
	public $sql_failure = false;
	public $mail_failure = false;
	public $email_taken = false;
	public $persnr_taken = false;
	public $activation_token = 0;

	private $clean_password;
	//private $name_clean;
	private $name;
	private $surename;
	private $personnalnumber;
	private $user_active = 0;

	function __construct($name, $surename, $pass, $email, $persnr)
	{
		//Sanitize
		$this->clean_email = sanitize($email);
		$this->clean_password = trim($pass);
		$this->name = $name;
		$this->surename = $surename;
		$this->personalnumber = $persnr;

		if(persNrExists($this->personalnumber))
		{
			$this->persnr_taken = true;
		}
		else if(emailExists($this->clean_email))
		{
			$this->email_taken = true;
		}
		else
		{
			//No problems have been found.
			$this->status = true;
		}
	}

	public function userCakeAddUser()
	{
		global $db,$emailActivation,$websiteUrl,$db_table_prefix;

		//Prevent this function being called if there were construction errors
		if($this->status)
		{
			//Construct a secure hash for the plain text password
			$secure_pass = generateHash($this->clean_password);

			//Construct a unique activation token
			$this->activation_token = generateActivationToken();

			//Do we need to send out an activation email?
			if($emailActivation)
			{
				//User must activate their account first
				$this->user_active = 0;
					
				$mail = new userCakeMail();
					
				//Build the activation message
				$activation_message = lang("ACTIVATION_MESSAGE",array($websiteUrl,$this->activation_token));

				//Define more if you want to build larger structures
				$hooks = array(
					"searchStrs" => array("#ACTIVATION-MESSAGE","#ACTIVATION-KEY","#USERNAME#"),
					"subjectStrs" => array($activation_message,$this->activation_token,$this->unclean_username)
				);

				/* Build the template - Optional, you can just use the sendMail function
				 Instead to pass a message. */
				if(!$mail->newTemplateMsg("new-registration.txt",$hooks))
				{
					$this->mail_failure = true;
				}
				else
				{
					//Send the mail. Specify users email here and subject.
					//SendMail can have a third parementer for message if you do not wish to build a template.
					if(!$mail->sendMail($this->clean_email,"New User"))
					{
						$this->mail_failure = true;
					}
				}
			}
			else
			{
				//Instant account activation
				$this->user_active = 1;
			}


			if(!$this->mail_failure)
			{
				//Insert the user into the database providing no errors have been found.
				$sql = "INSERT INTO `".$db_table_prefix."Users` (
							`Name`,
							`Surename`,
							`Password`,
							`Email`,
							`Personalnr`,
							`ActivationToken`,
							`LastActivationRequest`,
							`LostPasswordRequest`, 
							`Active`,
							`SignUpDate`,
							`LastSignIn`
							)
					 		VALUES (
							'".$db->sql_escape($this->name)."',
							'".$db->sql_escape($this->surename)."',
							'".$secure_pass."',
							'".$db->sql_escape($this->clean_email)."',
							'".$db->sql_escape($this->personalnumber)."',
							'".$this->activation_token."',
							'".time()."',
							'0',
							'".$this->user_active."',	
							'".time()."',
							'0'
							)";

				$result = $db->sql_query($sql);

				return (bool)$result;
			}
		}
	}
	
	public function addUserToGroup($groupName = NULL)
	{
		global $db,$db_table_prefix;
		
		if($groupName == NULL)
		{
			return false;
		}
		$groupId = getGroupIdFromGroupName($groupName);
		$userData = fetchUserDetails($this->clean_email);
		$userId = $userData['User_ID'];

 		$sql = "INSERT INTO `".$db_table_prefix."UserGroupMap` (`UserId`,`GroupId`)
 								VALUES ('".$userId."','".$groupId."')";

		$result = $db->sql_query($sql);
		return (bool)$result;
	}
}

?>