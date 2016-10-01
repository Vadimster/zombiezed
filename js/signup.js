var test = function(){
	console.log('Test function works!');
}

var restrictInput = function(elementID){	
	if(elementID === 'signupUsername'){
		var allowedChars = $('#'+ elementID).val().replace(/[^a-z0-9]/gi, ''); 
		$('#' + elementID).val(allowedChars);
	}else if(elementID === 'signupEmail'){
		var restrictedChars = $('#'+ elementID).val().replace(/[' "]/g, '');
		$('#' + elementID).val(restrictedChars);
	}
};

var signupEmptyElement = function(elementID){
	$('#'+elementID).empty();
	if(elementID === 'errorUsername'){
		$('#signupUsername-status').css("background-image", "none");

	}else if(elementID === 'errorEmail' ){
		$('#signupEmail-status').css("background-image", "none");

	} else if(elementID === 'errorPassword'){
		$('#signupConfirmPassword-status').css("background-image", "none");
	}	
};

var signupValidate = function(elementID){ //checks a) if passwords match, b) if username/email address already exists in DB before form is submitted for processing
	var value = $('#' + elementID).val();
	if (value !== ''){
		if(elementID === 'signupConfirmPassword'){
			var pass1 = $('#signupPassword').val();
			var pass2 = $('#signupConfirmPassword').val();
			if(pass1 === pass2){
				$('#signupConfirmPassword-status').css("background-image", "url(img/signup/yes.png)");	
			}else{
				$('#signupConfirmPassword-status').css("background-image", "url(img/signup/no.png)");	
				$('#errorPassword').html('Passwords do not match');
			}
		} else {
			$('#'+elementID+'-status').css("background-image", "url(img/signup/spinner.gif)");
			var data = {id: elementID, val: value};
			$.ajax({
				url: 'php/validator_signup.php',
				dataType: 'json',
				type: 'post',
				data: data,
				success: function(response){
					if(response.status === 1){
						$('#'+elementID+'-status').css("background-image", "url(img/signup/no.png)");
						if(elementID === 'signupUsername'){
							$('#errorUsername').html('Username is taken');
						}else if(elementID === 'signupEmail'){
							$('#errorEmail').html('Email already registered. <a href="">Forgot password?</a>');
						}
					}else if(response.status === 0){
						$('#'+elementID+'-status').css("background-image", "url(img/signup/yes.png)");	
					}else if(response.status === 'error'){
						console.log('PHP error: ' + response.description);
					} else if (response.status === 2){
						$('#signupEmail-status').css("background-image", "url(img/signup/no.png)");
						$('#errorEmail').html('Not a valid email address.');
					} else if (response.status === 3){
						$('#signupUsername-status').css("background-image", "url(img/signup/no.png)");
						$('#errorUsername').html('Invalid characters in username');
					}		
				}		
			});
		}
	}
}

var signupRegister = function(){
	var signupUsername  = $('#signupUsername').val();
	var signupEmail  = $('#signupEmail').val();
	var signupPassword  = $('#signupPassword').val();
	var signupConfirmPassword  = $('#signupConfirmPassword').val();

	var data = {signupUsername:signupUsername, signupEmail:signupEmail, signupPassword:signupPassword, signupConfirmPassword:signupConfirmPassword};	
					
	if (signupUsername !== '' && signupEmail !== '' && signupPassword !== '' && signupConfirmPassword !==''){
		if (signupPassword === signupConfirmPassword){
			if ($('#errorUsername').text() !== 'Username is taken'){
				if ($('#errorEmail').text() !== 'Email already registered. <a href="">Forgot password?</a>'){
					console.log('All is good can send ajax now');
					var data = {signupUsername:signupUsername, signupEmail:signupEmail, signupPassword:signupPassword, signupConfirmPassword:signupConfirmPassword};	
					$.ajax({
						url: 'php/signup.php',
						dataType: 'json',
						type: 'post',
						data: data,
						success: function(response){
							// 2 = one of fields (username, email, password or confirmpassword are missing)
							// 3 = passwords do not match
							// 4 = username taken
							// 5 = email address taken
							// 6 = email address invalid (via PHP method)
							// 7 = username contains invalid characters
							if (response.status === 1){
								//console.log('player added to DB. PlayerID is ' + response.description);								
								window.location.href = '/zombiezed/vadim-test/game.php'; //redirect to game.php									
							} else if (response.status === 2){
								console.log(response.description);
							} else if (response.status === 3){
								console.log(response.description);
							} else if (response.status === 4){
								console.log(response.description);
							} else if (response.status === 5){
								console.log(response.description);
							} else if (response.status === 6){
								console.log(response.description);
							} else if (response.status === 7){
								console.log(response.description);
							}
						}		
					});
				}				
			}			
		}
	}
	
	if(signupUsername === ''){
		$('#signupUsername-status').css("background-image", "url(img/signup/no.png)");
		$('#errorUsername').html('Please provide username');
	}
	if(signupEmail === ''){
		$('#signupEmail-status').css("background-image", "url(img/signup/no.png)");
		$('#errorEmail').html('Please provide email');
	}
	if(signupPassword === '' || signupConfirmPassword ===''){
		$('#signupConfirmPassword-status').css("background-image", "url(img/signup/no.png)");
		$('#errorPassword').html('Please specify password twice');
	}


};


var signupLogin = function(){
	console.log('signupLogin() launched');

	$('#form-signup, #register-button, #player-login').addClass('blur');

	$('#player-login-dialog')
		.dialog(
			{ 
				draggable: false,
				resizable: false,
				modal: true,
				width: 370,
				height: 275,
				closeOnEscape: true,
				//dialogClass: "no-close", //ensures there is a close button
				position: { my: "center center", at: "center", of: ".globalContent", within: ".globalContent"},
				close: function(event, ui){
					console.log('dialogue closed');
					$('#form-signup, #register-button, #player-login').removeClass('blur');
				},
				buttons: { "Login": function() { 
				//$(this).dialog("close"); } 
					console.log('capture form data and launch AJAX');
				} }  //
			}); //creates the dialogue
};

/*
AJAX SERIALISE

var tent = {
	name: 'tent',
  level: 1,
  cost: 100
};

var well = {
	name: 'well',
	level: 1,
  cost: 50,
  build: function(){
  	//do stuff  
  }
}

var buildings = [tent, well];

console.log(buildings);

buildingsSer = JSON.stringify(buildings);

console.log(buildingsSer);
console.log(typeof(buildingsSer));

var BuildingsParsed = JSON.parse(buildingsSer);
console.log(BuildingsParsed);

*/

