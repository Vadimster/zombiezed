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
						
						$_SESSION['player_id'] = $player_id; //writing global variable to be used in further requests.


						//ADD STARTING PLAYER STATS

						$population_start = 3;
						$food_start = 20;
						$water_start = 15;

						$sql = "INSERT INTO players_stats_test (id, population, food, water) VALUES (:player_id, :population_start, :food_start, :water_start)";
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':player_id', $player_id, PDO::PARAM_INT);
						$stmt->bindParam(':population_start', $population_start, PDO::PARAM_INT);
						$stmt->bindParam(':food_start', $food_start, PDO::PARAM_INT);
						$stmt->bindParam(':water_start', $water_start, PDO::PARAM_INT);
						$stmt->execute();				

						//CREATE MAP IN DB
						$pdo->beginTransaction();						
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 1, 0, 0, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 2, 0, 1, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 3, 0, 2, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 4, 0, 3, 2, 0, 1)"); //gate
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 5, 0, 4, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 6, 0, 5, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 7, 0, 6, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 8, 1, 0, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 9, 1, 1, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 10, 1, 2, 3, 0, 1)"); //grass						
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 11, 1, 3, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 12, 1, 4, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 13, 1, 5, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 14, 1, 6, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 15, 2, 0, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 16, 2, 1, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 17, 2, 2, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 18, 2, 3, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 19, 2, 4, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 20, 2, 5, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 21, 2, 6, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 22, 3, 0, 2, 0, 1)"); //gate
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 23, 3, 1, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 24, 3, 2, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 25, 3, 3, 3, 1, 1)"); //HQ
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 26, 3, 4, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 27, 3, 5, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 28, 3, 6, 2, 0, 1)"); //gate
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 29, 4, 0, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 30, 4, 1, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 31, 4, 2, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 32, 4, 3, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 33, 4, 4, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 34, 4, 5, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 35, 4, 6, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 36, 5, 0, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 37, 5, 1, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 38, 5, 2, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 39, 5, 3, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 40, 5, 4, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 41, 5, 5, 3, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 42, 5, 6, 1, 0, 1)"); //defences	
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 43, 6, 0, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 44, 6, 1, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 45, 6, 2, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 46, 6, 3, 2, 0, 1)"); //gate
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 47, 6, 4, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 48, 6, 5, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map_test (player_id, tile_id, tile_y, tile_x, tile_type, tile_level, tile_health) VALUES ('$player_id', 49, 6, 6, 1, 0, 1)"); //defences
						$pdo->commit();

						//SET RESPONSE STATUS
						$json['status'] = 1;						

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