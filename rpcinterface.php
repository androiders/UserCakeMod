<?php
require_once 'jsonRPCServer.php';
require_once("models/config.php");
require 'remoteinterfaceclass.php';

$myInterface = new RemoteInterfaceClass();
jsonRPCServer::handle($myInterface)
	or print 'no request';
?>