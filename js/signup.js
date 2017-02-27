
$(document).ready(function(){ //adding JS events listeners unobtrusively 
	
	$('#signup-username').keyup(function(){
		$(this).val($(this).val().replace(/[^a-z0-9]/gi, ''));
	});

	$('#signup-username').focusout(function(){ 
		if(!$.trim($(this).val()).length){
			signupModule.register.messages.usernameMissing();
		} else { //ask server
			$('#signup-username-status').css("background-image", "url(img/signup/spinner.gif)");
			signupModule.register.checkExistence('username', $(this).val());
		}
	});

	$('#signup-username').focus(function(){
		$('#signup-error-username').empty();
		$('#signup-username-status').css('background-image', '');
	});

	$('#signup-email').keyup(function(){
		$(this).val($(this).val().replace(/[' "]/g, ''));
	});

	$('#signup-email').focusout(function(){ 
		//add more front end checks before calling the method.
		if(!$.trim($(this).val()).length){
			signupModule.register.messages.emailMissing();
		} else { //ask server
			$('#signup-email-status').css("background-image", "url(img/signup/spinner.gif)");
			signupModule.register.checkExistence('email', $(this).val());
		}
	});

	$('#signup-email').focus(function(){
		$('#signup-error-email').empty();
		$('#signup-email-status').css('background-image', '');
	});

	$('#signup-confirmpassword').focus(function(){
		$(this).empty();
		$('#signup-confirmpassword-status').css('background-image', '');
	});

	$('#signup-button').click(function(){
		signupModule.register.registerUser();
	});

	$('#login-text > a').click(function(){
		signupModule.login.startModalDialogue();
	});

	$(document).keyup(function(e){ //binding event listener to submit log in when Enter is pressed.
		if (e.which == 13){
			if(signupModule.login.modalDialogOpen){
				signupModule.login.loginUser();
			}
		} else if (e.which == 27){
			if(signupModule.login.modalDialogOpen){
				signupModule.login.closeModalDialogue();
			}
		}
	});
});


var signupModule = {	

	login: {
		
		modalDialogOpen: false,

		messages: {
			loginOK: function(){
				//console.log('Login OK!');
				this.modalDialogOpen = false;
				window.location.href = '../zombiezed/php/game.php';
			},
			loginNotOK: function(){
				//console.log('Login NOT OK!');
				$('#login-error').css('background-color','red').html('Invalid credentials. <a href="#">Forgot password?</a>');
			}
		},

		startModalDialogue: function(){ //opens a modal login dialog from the signup page
			this.modalDialogOpen = true;
			$('#login-error').css('background-color', '').empty();
			$('#signup-area').addClass('blur');
			$('#modal-login').css('display', 'flex');
		},

		closeModalDialogue: function(){
			this.modalDialogOpen = false;
			$('#signup-area').removeClass('blur');
			$('#modal-login').css('display', 'none');	
		},

		loginUser: function(){
			//console.log('-> login() launched');
			$('#login-error').empty();
			var username  = $('#login-username').val();
			var password  = $('#login-password').val();
			var data = {action:'login', username:username, password:password};			
			$.ajax({
				url: 'php/ajaxhandler.php',
				dataType: 'json',
				type: 'post',
				data: data,
				success: function(response){
					signupModule.login.messages[response.msg](); 
				}
			});	
		}
	},	

	register: {

		messages: {
			usernameMissing: function(){
				$('#signup-error-username').html("Username missing");
				$('#signup-username-status').css("background-image", "url(img/signup/no.png)");
				$('#signup-progress').css("background-image", "none").empty();
			},

			usernameLong: function(){
				$('#signup-error-username').html("Username too long");
				$('#signup-username-status').css("background-image", "url(img/signup/no.png)");
				$('#signup-progress').css("background-image", "none").empty();
			},

			emailMissing: function(){
				$('#signup-error-email').html("Email missing");
				$('#signup-email-status').css("background-image", "url(img/signup/no.png)");
				$('#signup-progress').css("background-image", "none").empty();	
			},
			passwordMissing: function(){
				$('#signup-error-confirmpassword').html("Password missing");
				$('#signup-confirmpassword-status').css("background-image", "url(img/signup/no.png)");
				$('#signup-progress').css("background-image", "none").empty();
			},
			confirmPasswordMissing: function(){
				$('#signup-error-confirmpassword').html("Password confirmation missing");
				$('#signup-confirmpassword-status').css("background-image", "url(img/signup/no.png)");
				$('#signup-progress').css("background-image", "none").empty();
			},
			passwordMismatch: function(){
				$('#signup-error-confirmpassword').html("Passwords do not match");
				$('#signup-confirmpassword-status').css("background-image", "url(img/signup/no.png)");
				$('#signup-progress').css("background-image", "none").empty();
			},
			usernameInvalid: function(){
				$('#signup-error-username').html("Username has invalid characters");
				$('#signup-username-status').css("background-image", "url(img/signup/no.png)");
				$('#signup-progress').css("background-image", "none").empty();
			},
			emailInvalid: function(){
				$('#signup-error-email').html("Email is invalid");
				$('#signup-email-status').css("background-image", "url(img/signup/no.png)");
				$('#signup-progress').css("background-image", "none").empty();
			},
			passwordSameUsername: function(){
				$('#signup-error-confirmpassword').html("Password same as username");				
				$('#signup-confirmpassword-status').css("background-image", "url(img/signup/no.png)");
				$('#signup-progress').css("background-image", "none").empty();
			},
			passwordShort: function(){
				$('#signup-error-confirmpassword').html("Password too short");
				$('#signup-confirmpassword-status').css("background-image", "url(img/signup/no.png)");
				$('#signup-progress').css("background-image", "none").empty();
			},
			usernameExists: function(){
				$('#signup-error-username').html("Username taken");
				$('#signup-username-status').css("background-image", "url(img/signup/no.png)");
				$('#signup-progress').css("background-image", "none").empty();
			},
			usernameOK: function(){
				$('#signup-username-status').css("background-image", "url(img/signup/yes.png)");
			},
			emailExists: function(){
				$('#signup-error-email').html("Email taken");
				$('#signup-email-status').css("background-image", "url(img/signup/no.png)");
				$('#signup-progress').css("background-image", "none").empty();
			},
			emailOK: function(){
				$('#signup-email-status').css("background-image", "url(img/signup/yes.png)");
			},
			signupOK: function(){
				$('#signup-progress').css('background-color', 'green').html('Please check your email to confirm registration.');
				$('#signup-progress').css("background-image", "none");
			}
		},	

		checkExistence: function(element, value){ //will check if username of email address already exist in DB
			var data = {action:'checkExistence', element:element, value:value};
			$.ajax({
				url: '../zombiezed/php/ajaxhandler.php?', 
				dataType: 'json', 
				type: 'get', 
				cache: false, 
				data: data,
				success: function(response){
					if(response.msg){
						signupModule.register.messages[response.msg](); 
					} 
				}
			});
		},

		registerUser: function(){ //launches validation registration process on BE.
			$('.form-field-error').empty();
			$('#signup-progress').empty();
			$('#signup-progress').css("background-color", 'white');
			$('.form-field-check').css("background-image", "none");

			//basic front end checks on data validity prior to calling the server.			
			if(!$.trim($('#signup-username').val()).length){
				signupModule.register.messages.usernameMissing();
			} else if (!$.trim($('#signup-email').val()).length){
				signupModule.register.messages.emailMissing();
			} else if(!$.trim($('#signup-password').val()).length){
				signupModule.register.messages.passwordMissing();
			} else if (!$.trim($('#signup-confirmpassword').val()).length){
				signupModule.register.messages.confirmPasswordMissing();
			} else if($('#signup-password').val().length < 5) {
				signupModule.register.messages.passwordShort();
			} else if ($('#signup-password').val() === $('#signup-username').val()){
				signupModule.register.messages.passwordSameUsername();
			} else if ($('#signup-password').val() !== $('#signup-confirmpassword').val()){
				signupModule.register.messages.passwordMismatch();
			} else {
				$('#signup-progress').css("background-image", "url(img/signup/spinner.gif)");

				var username  = $('#signup-username').val();
				var email  = $('#signup-email').val();
				var password  = $('#signup-password').val();
				var confirmPassword  = $('#signup-confirmpassword').val();
				var data = {action:'signup', username:username, email:email, password:password, confirmPassword:confirmPassword};

				$.ajax({
					url: 'php/ajaxhandler.php',
					dataType: 'json',
					type: 'post',
					data: data,
					success: function(response){
						signupModule.register.messages[response.msg]();
					}
				});	
			}
		}
	}
};
