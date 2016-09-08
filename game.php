<?php
session_start();

//ajax will make call to retrieve player's information using the ID. If $_SESSION['id'] is not set nothing will be retrieved.
//JS will only start to populate the data (or actualy draw a page, may be a spinner "loading your game" if $_SESSION variable is set on PHP and PHP responds with OK callback to AJAX function which is executed on window.ready
print $_SESSION['player_id'];

?>

<HTML>
	<HEAD>
		<meta charset="utf-8">
		<link rel='icon' href='img/favicon.ico?v=2'/ >
		<TITLE>ZZ Test</TITLE>
		<script type="text/javascript" src="jquery/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="jquery/jquery-ui.min.js"></script>
		<link rel="stylesheet" href="jquery/jquery-ui.min.css" /> 
		<link rel="stylesheet" href="jquery/jquery-ui-config.css" /> 		
	</HEAD>

	<BODY>

		<p> Welcome to game screen </p>

	</BODY>
</HTML>