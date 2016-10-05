<?php
session_start();
require_once('db.php');


$json = array( //this is AJAX response
		"status" => null, 
		"username" => null,
		"population" => null,
		"food" => null,
		"water" => null,
	);


	$value = $_SESSION['player_id'];

	$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass); //opening connection
	$sql = "SELECT username FROM players_test WHERE id = :value LIMIT 1";
	$stmt = $pdo->prepare($sql);	
	$stmt->bindParam(':value', $value, PDO::PARAM_INT);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);	
	$json['username'] = $result['username'];

	$sql = "SELECT * FROM players_stats_test WHERE id = :value LIMIT 1";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':value', $value, PDO::PARAM_INT);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$json['population'] = $result['population'];
	$json['food'] = $result['food'];
	$json['water'] = $result['water'];
	$json['status'] = 1;
	$pdo = null;//close DB connection

echo json_encode($json);
?>