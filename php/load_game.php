<?php
session_start();
require_once('db.php');


$json = array( //this is AJAX response
		"status" => null, 
		"username" => null,
		"playerID" => null
	);


if(isset($_SESSION['player_id'])){
	$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass); //opening connection
	$sql = 	"SELECT id, username FROM players_test WHERE id = :value LIMIT 1";
	$stmt = $pdo->prepare($sql);
	$value = $_SESSION['player_id'];
	$stmt->bindParam(':value', $value, PDO::PARAM_INT);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$json['status'] = 1;
	$json['username'] = $result['username'];
	$json['playerID'] = $result['id'];


	$pdo = null;//close DB connection
} else {
	$json['status'] = 0;
}

echo json_encode($json);

?>