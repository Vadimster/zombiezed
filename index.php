<?php 
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
		<link rel="stylesheet" type="text/css" href="styles/signup.css" />
		<link rel="stylesheet" href="jquery/jquery-ui.min.css" /> 
		<link rel="stylesheet" href="jquery/jquery-ui-config.css" /> 		
	</HEAD>

	<BODY>
		<div class="globalWrapperTable">
			<div class="globalWrapperCell">
				<div class="globalContent"> <!-- cgeck if this div is global? May be this can be pure signup form div? --> 
					<form id="form-signup" onsubmit="return false;">
						<!-- onkeyup: function to restrict input on front end through regex -->
						<!-- onblur: once field loses focus this is when field value check to be performed against the DB-->
						<!-- onfocus: when cursor set in the field idea is to clear any error messages the form might have. Such error is placed in a specific div. Function could be emptyElement('elementID') and can be used for other purposes too.-->
							<label for="signupUsername">Username:</label>
							<input id="signupUsername" type="text" maxlength="10" placeholder="max 10 characters" onkeyup="restrictInput('signupUsername')" onblur="signupValidate('signupUsername')" onfocus="signupEmptyElement('errorUsername')"> <!--  -->
							<div  id="signupUsername-status"class="form-check-status"></div> <!-- will show .gif spinner and check/cross-->	
							<span class="form-field-note">letters and numbers only</span>
							<span id="errorUsername" class="form-field-error"></span>
							<br />														
							
							<label for="signupEmail">Email address:</label>
							<input id="signupEmail" type="text" maxlength="50" placeholder="max 50 characters" onkeyup="restrictInput('signupEmail')" onblur="signupValidate('signupEmail')" onfocus="signupEmptyElement('errorEmail')"> <!--  -->
							<div id="signupEmail-status" class="form-check-status" ></div> <!-- will show .gif spinner and check/cross-->	
							<span id="errorEmail" class="form-field-error"></span>
							<br />
							
							<label for="signupPassword">Password:</label>
							<input id="signupPassword" type="password"> 
							<br />

							<label for="signupConfirmPassword">Confirm password:</label>
							<input id="signupConfirmPassword" type="password" onblur="signupValidate('signupConfirmPassword')" onfocus="signupEmptyElement('errorPassword')"> <!--  -->
							<div id="signupConfirmPassword-status" class="form-check-status" ></div>
							<span id="errorPassword" class="form-field-error"></span>
							<br />							
					</form>
					<div id="register-button" onclick="signupRegister()">REGISTER</div>




				</div>
			</div>
		</div>



		<script type="text/javascript" src="js/signup.js"></script>
	</BODY>
</HTML>

