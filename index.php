<?php 
session_start();
?>
<HTML>
	<HEAD>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>
		<link rel='icon' href='img/favicon.ico?v=2'/ > 
		<TITLE>ZombieZed</TITLE>
		<script type="text/javascript" src="js/jquery/jquery-3.1.1.min.js"></script>
<!-- COMBINE CSS INTO ONE FILE. MINMIZE -->
		<link rel="stylesheet" type="text/css" href="css/signup.css" />

	</HEAD>

	<BODY>
	<div id="signup-area">
		<form id="form-signup" class="signup">
			<label for="signup-username" class="signup">Username:</label>
			<input id="signup-username" type="text" maxlength="15" placeholder="max 15 characters"></input>
			<div  id="signup-username-status" class="form-field-check"></div> 	
			<span class="form-field-note">letters and numbers only</span>
			<span id="signup-error-username" class="form-field-error"></span>
			<br />

			<label for="signup-email" class="signup">Email address:</label>
			<input  id="signup-email" type="text" maxlength="50" placeholder="max 50 characters"></input>
			<div id="signup-email-status" class="form-field-check" ></div> 	
			<span class="form-field-note">activation email will be sent</span>
			<span id="signup-error-email" class="form-field-error"></span>
			<br />

			<label for="signup-password" class="signup">Password:</label>
			<input id="signup-password" type="password" minlength="50" placeholder="min 5 characters"></input>
			<br />

			<label for="signup-confirmpassword" class="signup">Confirm password:</label>
			<input id="signup-confirmpassword" type="password" minlength="50" placeholder="min 5 characters"></input>
			<div id="signup-confirmpassword-status" class="form-field-check" ></div>
			<span id="signup-error-confirmpassword" class="form-field-error"></span>

			<div id="signup-progress"></div>
		</form>

		<div id="signup-button">REGISTER</div>
		
		<div id="login-box">
			<div id="login-icon"></div>
			<div id="login-text">Existing players login <a href="#">here</a></div>
		</div>
	</div> <!-- signup-area closed -->

		<div id="modal-login" class="modal">
			<div id="modal-login-content">
				<a href="javascript:signupModule.login.closeModalDialogue()" title="Close" class="modal-close-button">X</a>
				<form>				
					<label for="login-username" class="login">Username:</label>
					<input  id="login-username" type="text"></input>
					<br />

					<label for="login-password" class="login">Password:</label>
					<input  id="login-password" type="password"></input>
					<div id="login-error"></div>
					<a href="javascript:signupModule.login.loginUser()" title="Login" class="login">Login</a>
				</form>
			</div>		
		</div>


		<script type="text/javascript" src="js/signup.js"></script>

	</BODY>
</HTML>

