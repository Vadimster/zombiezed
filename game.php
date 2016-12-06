<?php
session_start();

?>

<HTML>
	<HEAD>
		<meta charset="utf-8">
		<!-- <link rel='icon' href='img/favicon.ico?v=2'/ > -->
		<TITLE>Pantheon Online</TITLE>
		<script type="text/javascript" src="jquery/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="jquery/jquery-ui.min.js"></script>
		<link rel="stylesheet" href="jquery/jquery-ui.min.css" /> 
		<link rel="stylesheet" href="jquery/jquery-ui-config.css" /> 
		<!-- <link rel="stylesheet" href="styles/game.css" /> -->
	</HEAD>

	<BODY>

		<?php if(isset($_SESSION['player_username'])){ ?> 
			<p>Welcome, <?php echo $_SESSION['player_username']; ?>. Your rank is <?php echo $_SESSION['player_rank'];?></p>

			<a href="javascript:game.start()">Play</a>
		</br>
			<a href="javascript:user.logout.logoutUser()">Logout</a>


		<?php } else { ?>
			<p>No session found. Please <a href="../pantheon/index.php">login</a></p>
		<?php } ?>
	</BODY>
</HTML>


<script type="text/javascript" src="js/user.js"></script>
<script type="text/javascript" src="js/game.js"></script>
