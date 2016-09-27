<?php 

//this page will serve validation process on player registration. It will check if values exist in DB and WILL NOT add player to DB. Addign player is done in signup.php  
	
	$json = array(
		"status" => null,
		// 1 = OK
		// 0 = value not found in DB
		// 2 = email not in valid format
		// 3 = username contains invalid characters
		"description" => null

	);

	require_once('db.php');
	$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass); //opening connection
		
	if($_POST['id'] === 'signupUsername'){	
		
		if (preg_match("/[^A-Za-z0-9]/", $_POST['val'])){
			$json['status'] = 3; //username contains invalid characters
		} else {
			$sql = 	"SELECT count(*) FROM players_test WHERE username = :value";
			$stmt = $pdo->prepare($sql);
			$value = filter_input(INPUT_POST, 'val', FILTER_SANITIZE_STRING); //create variable to be used in sql request and sanitise. INPUT_POST means to address $POST; 'val' is key of the key:value pair passed in POST
			$stmt->bindParam(':value', $value, PDO::PARAM_STR);
			$stmt->execute();
			$rows = $stmt->fetchColumn();
			if($rows > 0){
				$json['status'] = 1; //username found in the DB
			} elseif($rows == 0){
				$json['status'] = 0; //username not found in DB
			} else{
				$json['status'] = 'error';
				$json['description'] = 'error in SQL trying to look up username in DB';
			}
		}		

	}else if($_POST['id'] === 'signupEmail'){	
		if (!filter_var($_POST['val'], FILTER_VALIDATE_EMAIL)){ //check email validity
			$json['status'] = 2; //email is not in valid format
		} else {
			$sql = 	"SELECT count(*) FROM players_test WHERE email = :value";
			$stmt = $pdo->prepare($sql);
			$value = filter_input(INPUT_POST, 'val', FILTER_SANITIZE_STRING); //sanitise
			$stmt->bindParam(':value', $value, PDO::PARAM_STR);
			$stmt->execute();
			$rows = $stmt->fetchColumn();
			if($rows > 0){
				$json['status'] = 1; //email found in the DB
			} elseif($rows == 0){
				$json['status'] = 0; //email not found in DB
			} else{
				$json['status'] = 'error';
				$json['description'] = 'error in SQL trying to look up email in DB';
			}
		}

	}else{
		$json['status'] = 'error';
		$json['description'] = 'error in validator_signup.php; invalid HTML element id passed to php';
	}

	$pdo = null; //closing connection

	echo json_encode($json);
?>