<?php 
	
	//PDHP Version 5.3.29

	#include ('db.php');
	#$dataBase = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);	
	#PREPARE AND TRY	
	#$statement = $dataBase->query('SELECT * FROM users');
	#$rowCount = $statement->rowCount();
	#echo 'There are currently ' . $rowCount . ' rows in the database';	

 session_start();
 //$_SESSION['value'] = 10;
 //print $_SESSION['value'];

/*
if (isset($_SESSION)){
   	print "Session is in progress";
} else {
	print "Session not started";   
}
*/

?>
<HTML>
	<HEAD>
		<meta charset="utf-8">
		<link rel='icon' href='img/favicon.ico?v=2'/ >
		<TITLE>ZZ Test</TITLE>
		<script type="text/javascript" src="jquery/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="jquery/jquery-ui.min.js"></script>
		<link rel="stylesheet" type="text/css" href="styles/main.css" />
		<link rel="stylesheet" href="jquery/jquery-ui.min.css" /> 
		<link rel="stylesheet" href="jquery/jquery-ui-config.css" /> 		
	</HEAD>

	<BODY>
		<div class="globalWrapperTable">
			<div class="globalWrapperCell">
				<div class="globalContent">




				</div>
			</div>
		</div>



		<script type="text/javascript" src="js/main.js"></script>
	</BODY>
</HTML>

