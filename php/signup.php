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
			// 7 = invalid characters in usernmae
		"description" => 100
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
			} else if(preg_match("/[^A-Za-z0-9]/", $_POST['signupUsername'])){
				$json['status'] = 7; 
				$json['description'] = "Invalid characters in username";
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
						//all is good let us proceed with adding player into DB
						$sql = "INSERT INTO players_test (username, password, email, signup_ip, signup_date, last_login_date) VALUES (:username, :password, :email, :signup_ip, :signup_date, :last_login_date)";
						$stmt = $pdo->prepare($sql);
							$username = filter_input(INPUT_POST, 'signupUsername', FILTER_SANITIZE_STRING);							
							$password_hash = md5($_POST['signupPassword']);
							$email = filter_input(INPUT_POST, 'signupEmail', FILTER_SANITIZE_STRING);
							$signup_ip = $_SERVER['REMOTE_ADDR'];						
						$stmt->bindParam(':username', $username, PDO::PARAM_STR);
						$stmt->bindParam(':password', $password_hash, PDO::PARAM_STR);
						$stmt->bindParam(':email', $email, PDO::PARAM_STR);
						$stmt->bindParam(':signup_ip', $signup_ip, PDO::PARAM_STR);
						$stmt->bindParam(':signup_date', date("Y-m-d H:i:s"), PDO::PARAM_STR);
						$stmt->bindParam(':last_login_date', date("Y-m-d H:i:s"), PDO::PARAM_STR);
						$stmt->execute();
						$player_id = $pdo->lastInsertId(); //get player ID in the DB

							/* Below is more weight code with SELECT query and parameterisation. But since 'id' column reflects each INSERT and autoincrements then it makes sense to utilise lastInsertId() directly
							$id = $pdo->lastInsertId();
							$stmt = $pdo->prepare("SELECT id FROM players_test WHERE id= :id");
							$stmt->bindParam(':id', $id);
							$stmt->execute();
							$result = $stmt->fetch(PDO::FETCH_ASSOC);
							$player_id = $result["id"];
							*/
						
						$_SESSION['player_id'] = $player_id;


						//ADD PLAYER STATS
						//CREATE MAP IN DB	

						$json['status'] = 1;
						$json['description'] = $player_id;


						//send welcome email
						ini_set( 'display_errors', 1 );
					    error_reporting( E_ALL );					
					    $to = "vadimke@gmail.com"; //$_POST['signupEmail']
					    $subject = "Welcome to ZombieZed!";
					    $message = "
					    	<html>
					    		<head>
									<title>HTML email</title>
								</head>
								<body>
									<p>Welcome, ".$_POST['signupUsername']."!</p>
								</body>
					    	</html>
					    ";

					    $headers = "From: game@zombiezed.com\r\n";
							$headers.= "MIME-Version: 1.0\r\n"; 
							$headers.= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
							$headers.= "X-Priority: 1\r\n"; 
					    mail($to,$subject,$message, $headers);

					}
				}
			}
		}
		$pdo = null; 
	}

	echo json_encode($json);

?>