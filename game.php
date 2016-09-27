<?php
session_start();

//ajax will make call to retrieve player's information using the ID. If $_SESSION['id'] is not set nothing will be retrieved.
//JS will only start to populate the data (or actualy draw a page, may be a spinner "loading your game" if $_SESSION variable is set on PHP and PHP responds with OK callback to AJAX function which is executed on window.ready

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

		<p> Welcome to game screen, retrieving player data from DB...</p>

	</BODY>
</HTML>

<script>		
	$(document).ready(function() {
    	//retrieve usernamefrom DB for the user
    	var data = {action: 'loadPlayerData'};
			$.ajax({
				url: 'php/load_game.php',
				dataType: 'json',
				type: 'post',
				data: data,
				success: function(response){
					if(response.status === 1){
						$('p').html('You are now logged in as ' + response.username + '. Player id in DB is: ' + response.playerID);
						//$('p').html('Session is SET');

					} else {
						$('p').html('Session not found, please login to load your game');

					}

				}		
			});

	});

</script>


<script type="text/javascript" src="js/signup.js"></script>

