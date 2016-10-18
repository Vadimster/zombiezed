<?php
session_start();
require_once('db.php');


$json = array( //this is AJAX response
		"status" => null, 
		"username" => null,
		"population" => null,
		"food" => null,
		"water" => null,
		"map" => null //this to be an array of tiles
	);

//$map = array();

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
	
	$sql = "SELECT * FROM players_map_test WHERE player_id = :value";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':value', $value, PDO::PARAM_INT);
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	foreach ($result as $row) {
		//$json["map"][] = $row; //adds all row as an object
		$json["map"][] = array( //add only selected attributes
			"tile_health" => $row["tile_health"],
	        "tile_id" => $row["tile_id"],
	        "tile_y" => $row["tile_y"],
	        "tile_x" => $row["tile_x"],
	        "tile_level" => $row["tile_level"],
	        "tile_type" => $row["tile_type"]
			);  		
	}

	$json['status'] = 1;
	$pdo = null;//close DB connection

echo json_encode($json);
?>