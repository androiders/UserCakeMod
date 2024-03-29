<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	//Prevent the user visiting the logged in page if he/she is already logged in
	if(isUserLoggedIn()) { header("Location: account.php"); die(); }
?>

<?php
	/* 
		Below is a very simple example of how to process a new user.
		 Some simple validation (ideally more is needed).
		
		The first goal is to check for empty / null data, to reduce workload here
		we let the user class perform it's own internal checks, just in case they are missed.
	*/

//Forms posted
if(!empty($_POST))
{
	$errors = array();
	$email = trim($_POST["email"]);
	$name = trim($_POST["name"]);
  $surename = trim($_POST["surename"]);
	$password = trim($_POST["password"]);
	$confirm_pass = trim($_POST["passwordc"]);
	$personalnumber = trim($_POST["personalnumber"]);
	
	//Perform some validation
	//Feel free to edit / change as required
		
	if(minMaxRange(2,25,$name))
	{
		$errors[] = lang("ACCOUNT_NAME_CHAR_LIMIT",array(2,25));
	}
	if(minMaxRange(2,25,$surename))
	{
		$errors[] = lang("ACCOUNT_SURENAME_CHAR_LIMIT",array(2,25));
	}		
	if(minMaxRange(3,50,$password) && minMaxRange(3,50,$confirm_pass))
	{
		$errors[] = lang("ACCOUNT_PASS_CHAR_LIMIT",array(3,50));
	}
	else if($password != $confirm_pass)
	{
		$errors[] = lang("ACCOUNT_PASS_MISMATCH");
	}
	if(!isValidEmail($email))
	{
		$errors[] = lang("ACCOUNT_INVALID_EMAIL");
	}
	if(!isCorrectPersonalNumber($personalnumber))
	{
		$errors[] = lang("ACCOUNT_INVALID_PERSONAL_NUMBER");
	}
	
	//End data validation
	if(count($errors) == 0)
	{	
		//Construct a user object
		$user = new User($name,$surename,$password,$email,$personalnumber);
				
		//Checking this flag tells us whether there were any errors such as possible data duplication occured
		if(!$user->status)
		{
			if($user->persnr_taken)
			{ 
				$errors[] = lang("ACCOUNT_PERSNR_IN_USE");
			}
			if($user->email_taken)
			{
				$errors[] = lang("ACCOUNT_EMAIL_IN_USE",array($email));
			}		
		}
		else
		{
			//Attempt to add the user to the database, carry out finishing  tasks like emailing the user (if required)
			if(!$user->userCakeAddUser())
			{
				if($user->mail_failure)
				{
					$errors[] = lang("MAIL_ERROR");
				}
				if($user->sql_failure)
				{
					$errors[] = lang("SQL_ERROR");
				}
			}
			if(!$user->addUserToGroup("Atleter"))
			{
				$errors[] = lang("ADD_USER_TO_GROUP_ERROR");
			}
			 
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registration</title>
<link href="cakestyle.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="wrapper">
	<div id="content">
	
    	 <div id="left-nav">
        <?php include("layout_inc/left-nav.php"); ?>
            <div class="clear"></div>
        </div>
        
        <div id="main">
			
            <h1>Registration</h1>

			<?php
            if(!empty($_POST))
            {
				if(count($errors) > 0)
				{
            ?>
            <div id="errors">
            <?php errorBlock($errors); ?>
            </div>     
            <?php
           		 } else {
          
            	$message = lang("ACCOUNT_REGISTRATION_COMPLETE_TYPE1");
        
            	if($emailActivation)
				{
               		 $message = lang("ACCOUNT_REGISTRATION_COMPLETE_TYPE2");
				}
        ?> 
        <div id="success">
        
           <p><?php echo $message ?></p>
           
        </div>
        <?php } }?>

            <div id="regbox">
                <form name="newUser" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                
                <p>
                    <label>Namn:</label>
                    <input type="text" name="name" />
                </p>

                <p>
                    <label>Efternamn:</label>
                    <input type="text" name="surename" />
                </p>

                <p>
                    <label>Lösenord:</label>
                    <input type="password" name="password" />
                </p>
                
                <p>
                    <label>Bekräfta lösenord:</label>
                    <input type="password" name="passwordc" />
                </p>
                
                <p>
                    <label>Epost:</label>
                    <input type="text" name="email" />
                </p>
                
                <p>
                    <label>Personnummer 10 siffror:</label>
                    <input type="text" name="personalnumber" />
                </p>                
                
                <p>
                    <label>&nbsp;</label>
                    <input type="submit" value="Register"/>
                </p>
                
                </form>
            </div>

			<div class="clear"></div>
	 	</div>
	</div>
</div>
</body>
</html>


