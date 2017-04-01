<?php 

class Stats extends Database {

	public function fetch($db){
		$response = array();
		$pdo = new PDO("mysql:host=$db->host;dbname=$db->name", $db->user, $db->pass);
			$value = $_SESSION['player_id'];
		$sql = "SELECT * FROM players_stats WHERE player_id = :value";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':value', $value, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$response['rank'] = $result['rank'];  	
			$response['leadership'] = $result['ld'];
			$response['leadershipGained'] = $result['ld_per_turn'];
				$response['ld_base_pop'] = $result['ld_base_pop']; //from base popualation
			$response['population'] = $result['population'];
			$response['food'] = $result['food'];


		$sql = "SELECT * FROM calendar LIMIT 1";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$response['season'] = $result['season']; 
			$response['month'] = $result['month'];
			$response['week'] = $result['week'];
			    
		error_log(print_r($response, TRUE)); 

			    
		return $response;
	}
}

?>

