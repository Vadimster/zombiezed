var user = {	

	login: {
		
		messages: {

			loginOK: function(){
				console.log('Login OK!');
				//window.location.href = '../pantheon/lobby.php';
				window.location.href = '../pantheon/php/lobby.php';

			},

			loginNotOK: function(){
				console.log('Login NOT OK!');
				$('#login-error').css('background-color','red').html('Invalid credentials. <a href="#">Forgot password?</a>');
			}

		},

		startModalDialogue: function(){ //opens a modal login dialog from the signup page
			$('#login-error').css('background-color', '').empty();
			$('#form-signup, #register-button, #login-box').addClass('blur');
			$('#modal-login').css('display', 'flex');
		},

		closeModalDialogue: function(){
			$('#form-signup, #register-button, #login-box').removeClass('blur');
			$('#modal-login').css('display', 'none');	
		},

		loginUser: function(){
			console.log('-> login() launched');
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
					user.login.messages[response.msg](); //running relevant method of signup object
				}
			});	
		}
	},	

	logout: {

		logoutUser: function(){
			console.log(' -> user.logout.logoutUser() lunched');
			var data = {action:'logout'};			
			$.ajax({
				url: '../php/ajaxhandler.php',
				dataType: 'json',
				type: 'post',
				data: data,
				success: function(response){
					if(response.msg){
						window.location.href = '../index.php';
						console.log('Logget out');
					} 
				}
			});	
		}
	},	

	register: {

		messages: {
			usernameMissing: function(){
				$('#signup-error-username').html("Username missing");
				$('#register-progress').css("background-image", "none").empty();
			},
			emailMissing: function(){
				$('#signup-error-email').html("Email missing");
				$('#register-progress').css("background-image", "none").empty();	
			},
			passwordMissing: function(){
				$('#signup-error-confirmpassword').html("Password missing");
				$('#register-progress').css("background-image", "none").empty();
			},
			confirmPasswordMissing: function(){
				$('#signup-error-confirmpassword').html("Password confirmation missing");
				$('#register-progress').css("background-image", "none").empty();
			},
			passwordMismatch: function(){
				$('#signup-error-confirmpassword').html("Passwords do not match");
				$('#register-progress').css("background-image", "none").empty();
			},
			usernameInvalid: function(){
				$('#signup-error-username').html("Username has invalid characters");
				$('#register-progress').css("background-image", "none").empty();
			},
			emailInvalid: function(){
				$('#signup-error-email').html("Email is invalid");
				$('#register-progress').css("background-image", "none").empty();
			},
			passwordSameUsername: function(){
				$('#signup-error-confirmpassword').html("Password same as username");
				$('#register-progress').css("background-image", "none").empty();
			},
			passwordShort: function(){
				$('#signup-error-confirmpassword').html("Password too short");
				$('#register-progress').css("background-image", "none").empty();
			},
			usernameExists: function(){
				$('#signup-error-username').html("Username taken");
				$('#register-progress').css("background-image", "none").empty();
			},
			emailExists: function(){
				$('#signup-error-email').html("Email taken");
				$('#register-progress').css("background-image", "none").empty();
			},
			signupOK: function(){
				$('#register-progress').css('background-color', 'green').html('Please check your email to confirm registration.');
				$('#register-progress').css("background-image", "none");
			}
		},	

		registerUser: function(){ //launches validation registration process on BE.
			$('.form-field-error').empty();
			$('#register-progress').empty();
			$('#register-progress').css("background-image", "url(img/signup/spinner.gif)");
			$('#register-progress').css("background-color", 'white');

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
					user.register.messages[response.msg]();
				}
			});	
		}
	}
};
