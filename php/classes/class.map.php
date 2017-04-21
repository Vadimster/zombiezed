<?php

class Map extends Database {

	public function fetch($db){
		$response = array();
		$pdo = new PDO("mysql:host=$db->host;dbname=$db->name", $db->user, $db->pass);
			$value = $_SESSION['player_id'];
		$sql = "SELECT * FROM players_map WHERE player_id = :value";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':value', $value, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $row) {
			$response["map"][] = array(
		        "tile_id" => $row["tile_id"],
		        "tile_y" => $row["tile_y"],
		        "tile_x" => $row["tile_x"],
				"tile_class" => $row["tile_class"],
				"tile_category" => $row["tile_category"],				
				"tile_type" => $row["tile_type"],
		        "tile_level" => $row["tile_level"],
		        "tile_health" => $row["tile_health"]
				);  		
		}

		return $response;
	}








}

?>