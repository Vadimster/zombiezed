<?php
session_start();
require_once('db.php');
$json = array(
		"status" => null, 
			// 1 = OK. Player found in DB.
			// 2 = username or password blank
			// 3 = Username or Password incorrect						
		"description" => null
	);

if($_POST['username'] == "" || $_POST['password'] == ""){
	$json['status'] = 2;
	$json['description'] = "Username or password missing";
} else { 
	
	$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass); //opening connection
	$sql = 	"SELECT id FROM players_test WHERE username = :username AND password= :password LIMIT 1";
	$stmt = $pdo->prepare($sql);
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);	
	$password_hash = md5($_POST['password']);
	$stmt->bindParam(':username', $username, PDO::PARAM_STR);
	$stmt->bindParam(':password', $password_hash, PDO::PARAM_STR);
	$stmt->execute();	
	$result = $stmt->fetch(PDO::FETCH_ASSOC);	

	if(isset($result["id"])){
		$json['status'] = 1; //username found in the DB
		$json['description'] = "Login OK";
		$player_id = $result["id"];
		$_SESSION['player_id'] = $player_id;
		$sql = "UPDATE players_test SET last_login_date = :value WHERE id = :id";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':value', date("Y-m-d H:i:s"), PDO::PARAM_STR);
		$stmt->bindParam(':id', $player_id, PDO::PARAM_INT);
		$stmt->execute();
	} else {
		$json['status'] = 3; //username not found in the DB
		$json['description'] = "Incorrect username or password";
	}

$pdo = null;

}

echo json_encode($json);
exit();
?>