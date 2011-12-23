<?php
require_once 'jsonRPCClient.php';
require_once("models/config.php");

$myInterface = new jsonRPCClient('http://localhost/~androiders/UserCakeMod/rpcinterface.php');

echo "hello world<br>";

$x = 2;
$y = 5;
$user = null;
try{
	$user = $myInterface->getLoggedInUser();
// 	$add = $myCalculator->add($x,$y);
//  	$sub = $myCalculator->subtract($x,$y);
// 	$mul = $myCalculator->multiply($x,$y);
// 	$div = $myCalculator->divide($x,$y);
}
catch(Exception $e)
{
	echo nl2br($e->getMessage()).'<br />'."\n";
}

echo $user;
var_dump($_SESSION);
echo session_id();
// echo "add $add<br>";
// echo "sub $sub<br>";
// echo "mul $mul<br>";
// echo "div $div<br>";
?>