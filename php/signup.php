<?php
session_start();
require_once('db.php');
//this page gets AJAX call from signup flow and will do basic input validation and will double check if username and email are taken by any chance now. If all is good it will create a new player record in DB. 
// POST contains: {signupUsername:signupUsername, signupEmail:signupEmail, signupPassword:signupPassword, signupConfirmPassword:signupConfirmPassword};	

	$json = array(
		"status" => null, 
			// 1 = OK. Player records added to DB.
			// 2 = one of fields (username, email, password or confirmpassword are missing)
			// 3 = passwords do not match
			// 4 = username taken
			// 5 = email address taken
			// 6 = email address invalid
		"description" => null
	);

	if($_POST['signupUsername'] == "" || $_POST['signupEmail'] == "" || $_POST['signupPassword'] == "" || $_POST['signupConfirmPassword'] == "" ){
		$json['status'] = 2;
		$json['description'] = "Please check your input, because username, email or password appears to be missing";
	} else { // all data required are passed
		if ($_POST['signupPassword'] !== $_POST['signupConfirmPassword']){
			$json['status'] = 3;
			$json['description'] = "Passwords do not match";
		} else {

			if (!filter_var($_POST['signupEmail'], FILTER_VALIDATE_EMAIL)){
				$json['status'] = 6; 
				$json['description'] = "Email address is invalid";
			} else {
			//will check if username is available in db
				$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass); //opening connection
				$sql = 	"SELECT count(*) FROM players_test WHERE username = :value";
				$stmt = $pdo->prepare($sql);
				$value = filter_input(INPUT_POST, 'signupUsername', FILTER_SANITIZE_STRING); 
				$stmt->bindParam(':value', $value, PDO::PARAM_STR);
				$stmt->execute();
				$rows = $stmt->fetchColumn();
				if($rows > 0){
					$json['status'] = 4; //username found in the DB
					$json['description'] = "Username is taken";
				} else {
					//username is available, will check email in DB
					$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass); //opening connection
					$sql = 	"SELECT count(*) FROM players_test WHERE email = :value";
					$stmt = $pdo->prepare($sql);
					$value = filter_input(INPUT_POST, 'signupEmail', FILTER_SANITIZE_STRING); 
					$stmt->bindParam(':value', $value, PDO::PARAM_STR);
					$stmt->execute();
					$rows = $stmt->fetchColumn();
					if($rows > 0){
						$json['status'] = 5; 
						$json['description'] = "Email address is taken";

					} else {
						//all is good let us proceed with player registration in DB
						$json['status'] = 1;
					}
				}
			}
		}
		$pdo = null; // THINK OF WHERE TO SET THIS
	}

	echo json_encode($json);

?>