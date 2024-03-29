<html>
<head>
<title>UserCake - Database Setup</title>
<style type="text/css">
<!--
html, body {
   margin-top:15px;
   background: #fff;
   font-family: Verdana, Arial, Helvetica, sans-serif;
   font-size:0.85em;
   color:#4d4948;
   text-align:center;
}

a {
 color:#4d4948;
}
-->
</style>
</head>
<body>
<p><img src="usercake-badge.jpg"></p>
<?php
   /*
      UserCake Version: 1.4
      http://usercake.com
      
      Developed by: Adam Davis
   */

   //  Primitive installer
   
   
   require_once("../models/settings.php");
   
   //Dbal Support - Thanks phpBB ; )
   require_once("../models/db/".$dbtype.".php");
   require_once("../models/funcs.user.php");
   
   //Construct a db instance
   $db = new $sql_db();
   if(is_array($db->sql_connect(
                     $db_host, 
                     $db_user,
                     $db_pass,
                     $db_name, 
                     $db_port,
                     false, 
                     false
   ))) {
      echo "<strong>Unable to connect to the database, check your settings.</strong>";	
      
      echo "<p><a href=\"?install=true\">Try again</a></p>";
   }
   else
   {
   
   if(returns_result("SELECT * FROM ".$db_table_prefix."Groups LIMIT 1") > 0)
   {
      echo "<strong>UserCake has already been installed.<br /> Please remove / rename the install directory.</strong>";	
   }
   else
   {
      if(isset($_GET["install"]))
      {
   
            $db_issue = false;
         
            $groups_sql = "
               CREATE TABLE IF NOT EXISTS `".$db_table_prefix."Groups` (
               `Group_ID` int(11) NOT NULL auto_increment,
               `Group_Name` varchar(225) NOT NULL,
                PRIMARY KEY  (`Group_ID`)
               ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            ";
            
            $group_entry = "
               INSERT INTO `".$db_table_prefix."Groups` (`Group_ID`, `Group_Name`) VALUES
               (1, 'Admin'),(2, 'Atleter'),(3, 'Instruktörer'),(4, 'Styrelse');
            ";
            
            $users_sql = "
               CREATE TABLE IF NOT EXISTS `".$db_table_prefix."Users` (
                 `User_ID` int(11) NOT NULL auto_increment,
                 `Name` varchar(150) NOT NULL,
                 `Surename` varchar(150) NOT NULL,
                 `Password` varchar(225) NOT NULL,
                 `Email` varchar(150) NOT NULL,
                 `Personalnr` varchar(10) NOT NULL,
                 `ActivationToken` varchar(225) NOT NULL,
                 `LastActivationRequest` int(11) NOT NULL,
                 `LostPasswordRequest` int(1) NOT NULL default '0',
                 `Active` int(1) NOT NULL,
                 `SignUpDate` int(11) NOT NULL,
                 `LastSignIn` int(11) NOT NULL,
                  PRIMARY KEY  (`User_ID`)
                  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

            ";
            
            $userGroupMap = "
            	CREATE TABLE `".$db_table_prefix."UserGroupMap` (
                	`UserId` int(11) NOT NULL,
                  `GroupId` int(11) NOT NULL,
                   PRIMARY KEY  (`UserId`,`GroupId`)
				       ) ENGINE=MyISAM ;
				       ";
            
         $events_sql = "
               CREATE TABLE IF NOT EXISTS `".$db_table_prefix."Events` (
                 `Event_ID` int(11) NOT NULL auto_increment,
                 `Active` tinyint(1) NOT NULL,
                 `Name` varchar(20) NOT NULL,
                 `Descr` varchar(150) NOT NULL,
                 `Type` varchar(20) NOT NULL,
                 `StartTime` DATETIME NOT NULL,
                 `EndTime` DATETIME NOT NULL,
                 `Bookable` BOOLEAN NOT NULL,
                 `Vacancies` int(11) NOT NULL,
                  PRIMARY KEY  (`Event_ID`)
                  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            ";
            
				$UserEventMap = "
            	CREATE TABLE `".$db_table_prefix."UserEventMap` (
                	`UserId` int(11) NOT NULL,
                  `EventId` int(11) NOT NULL,
                   PRIMARY KEY  (`UserId`,`EventId`)
				       ) ENGINE=MyISAM ;
				       ";            
            
            if($db->sql_query($groups_sql))
            {
               echo "<p>".$db_table_prefix."Groups table created.....</p>";
            }
            else
            {
               echo "<p>Error constructing ".$db_table_prefix."Groups table.</p><br /><br /> DBMS said: ";
               
               echo print_r($db->_sql_error());
               $db_issue = true;
            }
            
            if($db->sql_query($group_entry))
            {
               echo "<p>Inserted Standard User groups into ".$db_table_prefix."Groups table.....</p>";
            }
            else
            {
               echo "<p>Error constructing Groups table.</p><br /><br /> DBMS said: ";
               
               echo print_r($db->_sql_error());
               $db_issue = true;
            }
            
            if($db->sql_query($users_sql))
            {
               echo "<p>".$db_table_prefix."Users table created.....</p>";
            }
            else
            {
               echo "<p>Error constructing user table.</p><br /><br /> DBMS said: ";
               
               echo print_r($db->_sql_error());
               $db_issue = true;
            }
            if($db->sql_query($userGroupMap))
            {
               echo "<p>".$db_table_prefix."userGroupMap created.....</p>";
            }
            else
            {
               echo "<p>Error constructing userGroupMap table.</p><br /><br /> DBMS said: ";
               
               echo print_r($db->_sql_error());
               $db_issue = true;
            }
            if($db->sql_query($events_sql))
            {
               echo "<p>".$db_table_prefix."event table created.....</p>";
            }
            else
            {
               echo "<p>Error constructing Event table.</p><br /><br /> DBMS said: ";
               
               echo print_r($db->_sql_error());
               $db_issue = true;
            }            
            if($db->sql_query($UserEventMap))
            {
               echo "<p>".$db_table_prefix."UserEventMap table created.....</p>";
            }
            else
            {
               echo "<p>Error constructing UserEventMap table.</p><br /><br /> DBMS said: ";
               
               echo print_r($db->_sql_error());
               $db_issue = true;
            }
            
            if(!$db_issue)
            echo "<p><strong>Database setup complete, please delete the install folder.</strong></p>";
            else
            echo "<p><a href=\"?install=true\">Try again</a></p>";
            
         
            
      }
      else
      {
   ?>
         <a href="?install=true">Install UserCake</a>
   
   
   <?php } } }
   ?>
</body>
</html>