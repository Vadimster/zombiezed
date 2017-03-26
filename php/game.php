<?php
session_start();

?>

<HTML>
	<HEAD>
		<meta charset="utf-8">
		<!-- <link rel='icon' href='img/favicon.ico?v=2'/ > -->
		<TITLE>ZombieZed Camp</TITLE>
		<script type="text/javascript" src="../js/jquery/jquery-3.1.1.min.js"></script>
		<link rel="stylesheet" href="../css/game.css" />
	</HEAD>
	<BODY>
		<?php if(isset($_SESSION['player_username'])){ ?> 
		<div id="game-area">
			<div class="flex" id="player-stats-container">
			  <div class="player-rank-detail" id="player-rank-detail" title="Ranks">
			    <div class="centered" id="player-stats-rank-img"></div>
			    <div class="stats-value" id="player-stats-rank-name"><?php echo $_SESSION['player_username'] ?></div>
			  </div>
			  <div class="player-stats-item">
			    <div class="stats-button" id="player-stats-item-leadership-img" title="Leadership"></div>
			    <div class="stats-value" id="player-stats-item-leadership-val">0</div>
			  </div>

			  <div class="player-stats-item">
			    <div class="stats-button" id="player-stats-item-population-img" title="Population"></div>
			    <div class="stats-value" id="player-stats-item-population-val">0</div>
			  </div>
			  
			  <div class="player-stats-item">
			    <div class="stats-button" id="player-stats-item-food-img" title="Food"></div>
			    <div class="stats-value" id="player-stats-item-food-val">0</div>
			  </div>

			  <div class="player-stats-item">
			    <div class="stats-button" id="player-stats-item-schedule-img" title="Schedule"></div>
			    <div class="stats-value" id="player-stats-item-schedule-val"></div>
			  </div>

			 <div class="player-stats-item">
 			    <div class="stats-button" id="player-stats-item-supplies-img" title="Supplies"></div>
			    <div class="stats-value" id="player-stats-item-supplies-val"></div>
	 	    </div>

			<div class="player-stats-item">
			    <div class="stats-button" id="player-stats-item-politics-img" title="Politics"></div>
			    <div class="stats-value" id="player-stats-item-politics-val"></div>
			</div>

			<div class="player-stats-item">
			    <div class="stats-button" id="player-stats-item-world-img" title="World"></div>
			    <div class="stats-value"></div>
			</div>

			<div class="player-stats-item">
			    <div class="stats-button" id="player-stats-item-market-img" title="Market"></div>
			    <div class="stats-value"></div>
			</div>

			<div class="player-stats-item">
			    <div class="stats-button" id="player-stats-item-calendar-img" title="Calendar"></div>
			    <div class="stats-value" id="player-stats-item-calendar-val"></div>
			</div>


			<div class="player-stats-item">
			    <div class="stats-button" id="player-stats-item-messages-img" title="Messages"></div>
			    <div class="stats-value" id="player-stats-item-messages-val">0</div>
			</div>

			<div class="player-stats-item">
			    <div class="stats-button" id="player-stats-item-settings-img" title="Settings"></div>
			    <div class="stats-value" id="player-stats-item-settings-val"></div>
			  </div>
			</div>

			<div class="centered" id="map-container"></div>
		</div>
		
		<?php } else { ?>
			<p>No session found. Please <a href="../index.php">login</a></p>
		<?php } ?>
	</BODY>
</HTML>


<script type="text/javascript" src="../js/game.js"></script>
