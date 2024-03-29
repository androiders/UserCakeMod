<?php
/*
 UserCake Version: 1.4
http://usercake.com

Developed by: Adam Davis
*/

function sanitize($str)
{
	return strtolower(strip_tags(trim(($str))));
}

function isValidEmail($email)
{
	return preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",trim($email));
}

function minMaxRange($min, $max, $what)
{
	if(strlen(trim($what)) < $min)
	return true;
	else if(strlen(trim($what)) > $max)
	return true;
	else
	return false;
}

//@ Thanks to - http://phpsec.org
function generateHash($plainText, $salt = null)
{
	if ($salt === null)
	{
		$salt = substr(md5(uniqid(rand(), true)), 0, 25);
	}
	else
	{
		$salt = substr($salt, 0, 25);
	}

	return $salt . sha1($salt . $plainText);
}

function replaceDefaultHook($str)
{
	global $default_hooks,$default_replace;

	return (str_replace($default_hooks,$default_replace,$str));
}

function getUniqueCode($length = "")
{
	$code = md5(uniqid(rand(), true));
	if ($length != "") return substr($code, 0, $length);
	else return $code;
}

function errorBlock($errors)
{
	if(!count($errors) > 0)
	{
		return false;
	}
	else
	{
		echo "<ul>";
		foreach($errors as $error)
		{
			echo "<li>".$error."</li>";
		}
		echo "</ul>";
	}
}

function lang($key,$markers = NULL)
{
	global $lang;

	if($markers == NULL)
	{
		$str = $lang[$key];
	}
	else
	{
		//Replace any dyamic markers
		$str = $lang[$key];

		$iteration = 1;
			
		foreach($markers as $marker)
		{
			$str = str_replace("%m".$iteration."%",$marker,$str);

			$iteration++;
		}
	}

	//Ensure we have something to return
	if($str == "")
	{
		return ("No language key found");
	}
	else
	{
		return $str;
	}
}

function destorySession($name)
{
	if(isset($_SESSION[$name]))
	{
		//unset($_SESSION[$name]);
		session_unset();
		session_destroy();
//		$_SESSION[$name] = NULL;
	}
}

function isCorrectPersonalNumber($personnr)
{
	if(!strlen($personnr) == 10 )
		return false;

	if(!is_numeric($personnr))
		return false;

	return luhn($personnr);
}

//verify personal number with luhn algorithm
function luhn($ssn)
{
	$sum = 0;

	for ($i = 0; $i < strlen($ssn)-1; $i++)
	{
		$tmp = substr($ssn, $i, 1) * (2 - ($i & 1)); //växla mellan 212121212
		if ($tmp > 9) $tmp -= 9;
		$sum += $tmp;
	}

	//extrahera en-talet
	$sum = (10 - ($sum % 10)) % 10;

	return substr($ssn, -1, 1) == $sum;
}


?>