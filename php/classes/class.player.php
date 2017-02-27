<?php

class Player extends Database {

	public function validateUsername($db, $username){ //Checks if username already exists in DB
		$pdo = new PDO("mysql:host=$db->host;dbname=$db->name", $db->user, $db->pass);
		$sql = 	"SELECT count(*) FROM players WHERE username = :value";
		$stmt = $pdo->prepare($sql);
			$value = $username;
		$stmt->bindParam(':value', $value, PDO::PARAM_STR);
		$stmt->execute();
		$rows = $stmt->fetchColumn();
		if($rows > 0){
			return true;
		} else {
			return false;
		}
		$pdo = null;
	}

	public function validateEmail($db, $email){ //Checks if email already exists in DB
		$pdo = new PDO("mysql:host=$db->host;dbname=$db->name", $db->user, $db->pass);
		$sql = 	"SELECT count(*) FROM players WHERE email = :value";
		$stmt = $pdo->prepare($sql);
			$value = $email;
		$stmt->bindParam(':value', $value, PDO::PARAM_STR);
		$stmt->execute();
		$rows = $stmt->fetchColumn();
		if($rows > 0){
			return true;
		} else {
			return false;
		}
		$pdo = null;
	}

	public function login($db, $username, $password){
		$pdo = new PDO("mysql:host=$db->host;dbname=$db->name", $db->user, $db->pass);
		$sql = 	"SELECT player_id, password FROM players WHERE username = :username LIMIT 1";
		$stmt = $pdo->prepare($sql);
			$username = $username;
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		if(isset($result['password'])){
			if(password_verify($password, $result['password'])){
				$_SESSION['player_id'] = $result['player_id'];
				$_SESSION['player_username'] = $username;
				
				$sql = "UPDATE players SET last_login_date = :dateandtime WHERE player_id = :id";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':dateandtime', date("Y-m-d H:i:s"), PDO::PARAM_STR);
				$stmt->bindParam(':id', $result['player_id'], PDO::PARAM_INT);
				$stmt->execute();

				$response = array();
				$response['msg'] = 'loginOK';
				return $response;
			} else {
				$response = array();
				$response['msg'] = 'loginNotOK';
				return $response;
			}
		} else {
			$response = array();
			$response['msg'] = 'loginNotOK';
			return $response;
		}
		$pdo = null;
	}

	public function logout(){
		
		$_SESSION = array(); // Unset all of the session variables.

		if (ini_get("session.use_cookies")) { // If it's desired to kill the session, also delete the session cookie.  Note: This will destroy the session, and not just the session data!
		    $params = session_get_cookie_params();
		    setcookie(session_name(), '', time() - 42000,
		        $params["path"], $params["domain"],
		        $params["secure"], $params["httponly"]
		    );
		}
		
		session_destroy(); // Finally, destroy the session.
		$response = array();
		$response['msg'] = true;
		return $response;
	}

	public function sendActivationEmail(){

	}

	public function confirmSignup(){

	}

		public function validateAndSignup($db, $username, $email, $password, $confirmPassword){		
		if(!isset($username) || empty($username)){ 
			$response = array();
			$response['msg'] = 'usernameMissing';
			return $response;

			} else if(preg_match("/[^A-Za-z0-9]/", $username)){
				$response = array();
				$response['msg'] = 'usernameInvalid';
				return $response;
			
			} else if (strlen($username) > 15){
				$response = array();
				$response['msg'] = 'usernameLong';
				return $response;

			} else if(!isset($email) || empty($email)){
				$response = array();
				$response['msg'] = 'emailMissing';
				return $response;

			} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$response = array();
				$response['msg'] = 'emailInvalid';
				return $response;	

			} else if (!isset($password) || empty($password)){
				$response = array();
				$response['msg'] = 'passwordMissing';
				return $response;

			} else if(!isset($confirmPassword) || empty($confirmPassword)){
				$response = array();
				$response['msg'] = 'confirmPasswordMissing';
				return $response;
		
			} else if (strcmp($password, $confirmPassword) !== 0){
				$response = array();
				$response['msg'] = 'passwordMismatch';
				return $response;			
			
			} else if(strcmp($password, $username) === 0){
				$response = array();
				$response['msg'] = 'passwordSameUsername';
				return $response;

			} else if (strlen($password) < 5){
				$response = array();
				$response['msg'] = 'passwordShort';
				return $response;
			
			} else {

				if (!$this -> validateUsername($db, $username)){
					if (!$this -> validateEmail($db, $email)){
						$pdo = new PDO("mysql:host=$db->host;dbname=$db->name", $db->user, $db->pass);						
						$sql = "INSERT INTO players (username, email, password, signup_ip, signup_date, last_login_date, activation_string) VALUES (:username, :email, :password, :signup_ip, :signup_date, :last_login_date, :activation_string)";
						$stmt = $pdo->prepare($sql);
						
							$username = $username;
							$rank = 1;
							$password_hash = password_hash($password, PASSWORD_DEFAULT);						
							$email = $email;
							$signup_ip = $_SERVER['REMOTE_ADDR'];	
							$activation_string = md5($username.$email);
						
						$stmt->bindParam(':username', $username, PDO::PARAM_STR);
						$stmt->bindParam(':password', $password_hash, PDO::PARAM_STR);
						$stmt->bindParam(':email', $email, PDO::PARAM_STR);
						$stmt->bindParam(':signup_ip', $signup_ip, PDO::PARAM_STR);
						$stmt->bindParam(':signup_date', date("Y-m-d H:i:s"), PDO::PARAM_STR);
						$stmt->bindParam(':last_login_date', date("Y-m-d H:i:s"), PDO::PARAM_STR);
						$stmt->bindParam(':activation_string', $activation_string, PDO::PARAM_STR);		
						$stmt->execute();
						$player_id = $pdo->lastInsertId();				
						$_SESSION['player_id'] = $player_id;

						

						$sql = "INSERT INTO players_stats (player_id, rank, leadership, population, food) VALUES (:player_id, :rank_start, :leadership_start, :population_start, :food_start)";
						$stmt = $pdo->prepare($sql);

							$rank_start = 0;
							$leadership_start = 1;
							$population_start = 3;
							$food_start = 20;

						$stmt->bindParam(':player_id', $player_id, PDO::PARAM_INT);
						$stmt->bindParam(':rank_start', $rank_start, PDO::PARAM_INT);
						$stmt->bindParam(':leadership_start', $leadership_start, PDO::PARAM_INT);						
						$stmt->bindParam(':population_start', $population_start, PDO::PARAM_INT);
						$stmt->bindParam(':food_start', $food_start, PDO::PARAM_INT);
						$stmt->execute();

						$pdo->beginTransaction();						
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 1, 0, 0, 0, 5, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 2, 0, 1, 0, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 3, 0, 2, 0, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 4, 0, 3, 0, 9, 0, 1)"); //gate
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 5, 0, 4, 0, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 6, 0, 5, 0, 1, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 7, 0, 6, 0, 6, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 8, 1, 0, 0, 4, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 9, 1, 1, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 10, 1, 2, 0, 13, 0, 1)"); //grass						
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 11, 1, 3, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 12, 1, 4, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 13, 1, 5, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 14, 1, 6, 0, 3, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 15, 2, 0, 0, 4, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 16, 2, 1, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 17, 2, 2, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 18, 2, 3, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 19, 2, 4, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 20, 2, 5, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 21, 2, 6, 0, 3, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 22, 3, 0, 0, 11, 0, 1)"); //gate
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 23, 3, 1, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 24, 3, 2, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 25, 3, 3, 0, 14, 1, 1)"); //HQ
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 26, 3, 4, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 27, 3, 5, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 28, 3, 6, 0, 12, 0, 1)"); //gate
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 29, 4, 0, 0, 4, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 30, 4, 1, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 31, 4, 2, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 32, 4, 3, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 33, 4, 4, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 34, 4, 5, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 35, 4, 6, 0, 3, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 36, 5, 0, 0, 4, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 37, 5, 1, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 38, 5, 2, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 39, 5, 3, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 40, 5, 4, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 41, 5, 5, 0, 13, 0, 1)"); //grass
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 42, 5, 6, 0, 3, 0, 1)"); //defences	
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 43, 6, 0, 0, 7, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 44, 6, 1, 0, 2, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 45, 6, 2, 0, 2, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 46, 6, 3, 0, 10, 0, 1)"); //gate
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 47, 6, 4, 0, 2, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 48, 6, 5, 0, 2, 0, 1)"); //defences
						$pdo->exec("INSERT INTO players_map (player_id, tile_id, tile_y, tile_x, tile_class, tile_type, tile_level, tile_health) VALUES ('$player_id', 49, 6, 6, 0, 8, 0, 1)"); //defences
						$pdo->commit();						

						$pdo = null;
						
//TO DO 				//send activation email; call relevant function of this Class. Display message on frontent.


						
						$response = array();
						$response['msg'] = 'signupOK';
						return $response;
					} else {
						$response = array();
						$response['msg'] = 'emailExists';
						return $response;
					}

				} else {
					$response = array();
					$response['msg'] = 'usernameExists';
					return $response;
				}
			}
	}

}

?>