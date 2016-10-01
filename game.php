<?php
session_start();

//ajax will make call to retrieve player's information using the ID. If $_SESSION['id'] is not set nothing will be retrieved.
//JS will only start to populate the data (or actualy draw a page, may be a spinner "loading your game" if $_SESSION variable is set on PHP and PHP responds with OK callback to AJAX function which is executed on document.ready

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
		<link rel="stylesheet" href="styles/game.css" /> 		
	</HEAD>

	<BODY>

		<div class="spinner"></div>
		<div class="test"></div>

		<div class="flex" id="player-stats-container">
		  <div class="player-stats-item">
		    <div class="centered" id="player-stats-item-username">Placeholder</div>
		    <div class="stats-value" id="player-stats-item-logout"><a href="php/logout.php">Logout</a></div><!-- a href="javascript:alert('You clicked!')" -->
		  </div>
		  <div class="player-stats-item">
		    <div id="player-stats-item-population-img" title="Population"></div>
		    <div class="stats-value" id="player-stats-item-population-val">0</div>
		  </div>
		  <div class="player-stats-item">
		    <div id="player-stats-item-food-img" title="Food"></div>
		    <div class="stats-value" id="player-stats-item-food-val">0</div>

		  </div>
		  <div class="player-stats-item">
		    <div id="player-stats-item-water-img" title="Water"></div>
		    <div class="stats-value" id="player-stats-item-water-val">0</div>
		  </div>
		</div>

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
						//build the page accordingly.
						$('.spinner').hide();
						//$('.spinner').remove();
						//$('.test').show();
						$('#player-stats-container').css('display','flex');

						//$('.test').html('You are now logged in as ' + response.username + '. Player id in DB is: ' + response.playerID);
						//$('p').html('Session is SET');

					} else {
						$('.spinner').hide();
						$('.test').show();
						$('.test').html('ERROR. Session not found, please login to load your game');

					}

				}		
			});

	});

</script>


<script type="text/javascript" src="js/signup.js"></script>

