<?php 
require_once('class.db.php');

class Lobby extends Database {

	public function initiateGame($db){ //interface which is called by dispatch (ajaxhandler.php)
	/* This function will 
		Query the DB to see if player is already registered in a game.
		If regiseterd, then
			If active game -> join game & update full game state in the client
			If not active game, then
				If player is host -> keep waiting
				If player is guest -> join game & update full game state in the client
		If player not registered in a game, then
			Query DB to see if there are any inactive games in place
			If an inactive games found ->  join game & update full game state in the client
			If no inactive games found -> create an inactive game, become host and keep waiting. */

		$pdo = new PDO("mysql:host=$db->host;dbname=$db->name", $db->user, $db->pass);
			$sql = 	"SELECT game_id, game_active, host_player FROM games WHERE host_player = :playerID OR guest_player = :playerID LIMIT 1";
			$stmt = $pdo->prepare($sql);		
			$playerID = $_SESSION['player_id'];
			$stmt->bindParam(':playerID', $playerID, PDO::PARAM_INT);
			$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		if($result > 0){
			error_log("PlayerID $playerID is found in a game");
			if($result['game_active']){
				error_log("PlayerID $playerID is found in an active game");
				//JOIN GAME, GET and UPDATE full game state to client

				//$array = $this -> joinGame($db, $gameID);
				//$result['msg'] = 'gameJoined';
				//$result['playerToMove'] = $array['player_to_move'];
				//error_log($array['player_to_move']);
				//$result['hostPlayer'] = $array['host_player'];
				//return to ajax full game state (game_turn, first move, opponent (host_player))				
				//return $result;

			} else {
				error_log("PlayerID $playerID is found in an INactive game");

				if($result['host_player'] === $playerID){
					error_log('Player is a HOST');
					// Keep waiting.
					//Compare UNIX timestampt since last action in the game?
				} else { // A game cannot be created without a HOST (host_player cannot be NULL), therefore the only option is that player is a GUEST
					error_log('Player is a GUEST');
					//JOIN GAME & update full game state to client
				}

			}

		} else {
			error_log("PlayerID $playerID is NOT found in a game");
			$sql = 	"SELECT game_id FROM games WHERE game_active = 0 LIMIT 1";
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if($result>0){
				error_log('Pending inactive gameID ' .$result['game_id']. ' found. Will now join');

			} else {
				error_log('No pending games found. Will HOST a new game');				
				$result = array();
				$result['msg'] = 'gameCreated';
				$result['gameID'] = $this -> createGame($db);
				return $result;
			}
		}
		$pdo = null;	


	}

	private function playerInGameCheck($db){ //check if player is already registered in a game 
		$pdo = new PDO("mysql:host=$db->host;dbname=$db->name", $db->user, $db->pass);
		$sql = 	"SELECT game_id FROM games WHERE host_player = :value OR guest_player = :value";
		$stmt = $pdo->prepare($sql);
			$value = $_SESSION['player_id'];
		$stmt->bindParam(':value', $value, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$pdo = null;
		if(isset($result['game_id'])){
			return $result['game_id'];
		} else {
			return false;
		}
	}

	private function findPendingGame($db){
		$pdo = new PDO("mysql:host=$db->host;dbname=$db->name", $db->user, $db->pass);
		$sql = 	"SELECT game_id FROM games WHERE game_active = 0";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$pdo = null;		

		if(isset($result['game_id'])){
			return $result['game_id'];
		} else {
			return false;
		}
	}

	private function joinGame($db, $gameID){
		$playerID = $_SESSION['player_id'];
		$pdo = new PDO("mysql:host=$db->host;dbname=$db->name", $db->user, $db->pass);
			//TO DO decide who moves first.
		$sql = "SELECT host_player FROM games WHERE game_id = '$gameID'";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$hostPlayerID = $result['host_player'];

		$players = array($playerID, $hostPlayerID);
		$playerToMoveKey = array_rand($players); //returns a KEY of a random element, not the valur of it!
		$playerToMove = $players[$playerToMoveKey];

		$sql = "UPDATE games SET game_active = 1, player_to_move = '$playerToMove', guest_player = '$playerID' WHERE game_id = '$gameID'";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		

		$sql = "SELECT player_to_move, game_turn, host_player FROM games WHERE game_id = '$gameID'";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$pdo = null;
		$result['msg'] = 'game joined'; //adding a new attribute to an array
		//error_log($result['player_to_move']);
		$string = implode(" ", $result); //turning array into a string
		error_log($string);

		//return $result;
		//return to ajax full game state (game_turn, first move, opponent (host_player))
	}

	private function createGame($db){
		$pdo = new PDO("mysql:host=$db->host;dbname=$db->name", $db->user, $db->pass);		
		$sql = 	"INSERT INTO games (game_id, game_created, host_player) VALUES (:gameID, :timestamp, :hostPlayer)";
		$stmt = $pdo->prepare($sql);
			$gameID = $this->createGameID();
			$hostPlayer = $_SESSION['player_id'];
			$timestamp = time();
		$stmt->bindParam(':gameID', $gameID, PDO::PARAM_STR);
		$stmt->bindParam(':hostPlayer', $hostPlayer, PDO::PARAM_INT);
		$stmt->bindParam(':timestamp', $timestamp, PDO::PARAM_INT);
		$stmt->execute();
		$pdo = null;
		return $gameID;		
	}

	private function createGameID(){
		$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$gameIDLength = 15;
		$gameID = null;
		$max = strlen($characters) - 1;
		for ($i = 0; $i < $gameIDLength; $i++) {
      		$gameID .= $characters[mt_rand(0, $max)];
 		}
 		return $gameID;
	}

	
}

?>
