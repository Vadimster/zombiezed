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


		<?php if(isset($_SESSION['player_id'])){  ?> 
			<div class="flex" id="player-stats-container">
			  <div class="player-stats-item">
			    <div class="centered spinner" id="player-stats-item-username"></div>
			    <div class="stats-value" id="player-stats-item-logout"><a href="php/logout.php">Logout</a></div><!-- a href="javascript:alert('You clicked!')" -->
			  </div>
			  <div class="player-stats-item">
			    <div id="player-stats-item-population-img" title="Population"></div>
			    <div class="stats-value spinner" id="player-stats-item-population-val">0</div>
			  </div>
			  <div class="player-stats-item">
			    <div id="player-stats-item-food-img" title="Food"></div>
			    <div class="stats-value spinner" id="player-stats-item-food-val">0</div>

			  </div>
			  <div class="player-stats-item">
			    <div id="player-stats-item-water-img" title="Water"></div>
			    <div class="stats-value spinner" id="player-stats-item-water-val">0</div>
			  </div>
			</div>

			<div id="map-container"></div>
			
			<script>
				$(document).ready(function() {
			    	//console.log('Document ready!');				
			    	var data = {action: 'loadPlayerData'};
						$.ajax({
							url: 'php/load_game.php',
							dataType: 'json',
							type: 'post',
							data: data,
							success: function(response){
								if(response.status === 1){
									//UPDATE PLAYER STATS
									$('#player-stats-item-username, #player-stats-item-population-val, #player-stats-item-food-val, #player-stats-item-water-val').removeClass('spinner');
									$('#player-stats-item-username').html(response.username);
									playerStatsCounterMassUpdate(response.population, response.food, response.water);
									
									//HANDLE THE MAP ARRAY
									response.map.sort(function(obj1, obj2){  //sort array ASC based on tile_id value in every object
										return obj1.tile_id - obj2.tile_id;
									});
					//TO DO ---- possibly convert string values in the array to numeric values? Number(string) for integers and parseFloat(string)?

									map.prepare(response.map);					
									
								} else {
									alert('Game data cannot be loaded, pelase refresh the page or try later');
								}

							}		
						});
				});
			</script>

		<?php } else { ?>
			<p>No session found. Please <a href="http://www.arsenij.eu/zombiezed/vadim-test/index.php">login</a></p>
		<?php } ?>
	</BODY>
</HTML>


<!-- <script type="text/javascript" src="js/signup.js"></script> -->
<script type="text/javascript" src="js/stats.js"></script>
<script type="text/javascript" src="js/buildings.js"></script>
<script type="text/javascript" src="js/map.js"></script>

