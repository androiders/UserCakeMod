<?php
	/*
		UserCake
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	//Prevent the user visiting the logged in page if he/she is already logged in
	if(isUserLoggedIn()) { header("Location: account.php"); die(); }
?>
<?php
	/* 
		Below is a very simple example of how to process a login request.
		Some simple validation (ideally more is needed).
	*/

//Forms posted
if(!empty($_POST))
{
		$errors = array();
		$usermail = trim($_POST["email"]);
		$password = trim($_POST["password"]);
	
		//Perform some validation
		//Feel free to edit / change as required
		if($usermail == "")
		{
			$errors[] = lang("ACCOUNT_SPECIFY_EMAIL");
		}
		if($password == "")
		{
			$errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
		}
		
		//End data validation
		if(count($errors) == 0)
		{
			//A security note here, never tell the user which credential was incorrect
			if(!emailExists($usermail))
			{
				$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
			}
			else
			{
				$userdetails = fetchUserDetails($usermail);
			
				//See if the user's account is activation
				if($userdetails["Active"]==0)
				{
					$errors[] = lang("ACCOUNT_INACTIVE");
				}
				else
				{
					//Hash the password and use the salt from the database to compare the password.
					$entered_pass = generateHash($password,$userdetails["Password"]);

					if($entered_pass != $userdetails["Password"])
					{
						//Again, we know the password is at fault here, but lets not give away the combination incase of someone bruteforcing
						$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
					}
					else
					{
						//Passwords match! we're good to go'
						
						//Construct a new logged in user object
						//Transfer some db data to the session object
						$loggedInUser = new loggedInUser();
						$loggedInUser->email = $userdetails["Email"];
						$loggedInUser->user_id = $userdetails["User_ID"];
						$loggedInUser->hash_pw = $userdetails["Password"];
						$loggedInUser->name = $userdetails["Name"];
						$loggedInUser->surename = $userdetails["Surename"];
						
						//Update last sign in
						$loggedInUser->updateLastSignIn();
		
						$_SESSION["userCakeUser"] = $loggedInUser;
						setcookie("sid", session_id());
						file_put_contents("log.txt", "Session id: ", FILE_APPEND | LOCK_EX);
						file_put_contents("log.txt", $_COOKIE['sid'], FILE_APPEND | LOCK_EX);
						
						//Redirect to user account page
						header("Location: account.php");
						die();
					}
				}
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
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
        
        <h1>Login</h1>
        
        <?php
        if(!empty($_POST))
        {
        ?>
        <?php
        if(count($errors) > 0)
        {
        ?>
        <div id="errors">
        <?php errorBlock($errors); ?>
        </div>     
        <?php
        } }
        ?> 
        
            <div id="regbox">
                <form name="newUser" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <p>
                    <label>Epost:</label>
                    <input type="text" name="email" />
                </p>
                
                <p>
                     <label>Password:</label>
                     <input type="password" name="password" />
                </p>
                
                <p>
                    <label>&nbsp;</label>
                    <input type="submit" value="Login" class="submit" />
                </p>

                </form>
                
            </div>
        </div>
        
            <div class="clear"></div>
        </div>
</div>
</body>
</html>


