<?php
session_start();

?>

<HTML>
	<HEAD>
		<meta charset="utf-8">
		<!-- <link rel='icon' href='img/favicon.ico?v=2'/ > -->
		<TITLE>Pantheon Online - Lobby</TITLE>
		<script type="text/javascript" src="../jquery/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="../jquery/jquery-ui.min.js"></script>
		<link rel="stylesheet" href="../jquery/jquery-ui.min.css" /> 
		<link rel="stylesheet" href="../jquery/jquery-ui-config.css" /> 
		<link rel="stylesheet" href="../styles/lobby.css" />
	</HEAD>
	<BODY>
		<?php if(isset($_SESSION['player_username'])){ ?> 
			<p>Welcome, <?php echo $_SESSION['player_username']; ?>. Your rank is <?php echo $_SESSION['player_rank'];?></p>

			<a href="javascript:game.search.request()">Play</a>
		</br>
			<a href="javascript:user.logout.logoutUser()">Logout</a>


		<div id="modal-searchgame" class="modal">
			<div id="modal-searchgame-content">
				<p id="searchgame-title" class="searchgameText">Searching for an opponent</p>
				<div id="searchgame-status"></div>
				<p id="searchgame-comment" class="searchgameText">search will automatically end after 45 seconds</p>
				<a href="javascript:game.search.cancel()" title="search will automatically end after 45 seconds" class="searchgame">Cancel</a>
			</div>
		</div>

		<?php } else { ?>
			<p>No session found. Please <a href="../pantheon/index.php">login</a></p>
		<?php } ?>
	</BODY>
</HTML>


<script type="text/javascript" src="../js/user.js"></script>
<script type="text/javascript" src="../js/game.js"></script>
