<?php 
require_once('class.db.php');


class Player extends Database {

	public function validateUsername($db, $username){
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

	public function validateEmail($db, $email){
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
		$sql = 	"SELECT player_id, rank, password FROM players WHERE username = :username LIMIT 1";
		$stmt = $pdo->prepare($sql);
			$username = $username;
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if(isset($result['password'])){
			if(password_verify($password, $result['password'])){
				$_SESSION['player_id'] = $result['player_id'];
				$_SESSION['player_username'] = $username;
				$_SESSION['player_rank'] = $result['rank'];
				
				$sql = "UPDATE players SET last_login_date = :dateandtime WHERE player_id = :id";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':dateandtime', date("Y-m-d H:i:s"), PDO::PARAM_STR);
				$stmt->bindParam(':id', $result['player_id'], PDO::PARAM_INT);
				$stmt->execute();

				$result = 'loginOK'; //response code to JS
				return $result;
			} else {
				$result = 'loginNotOK'; //response code to JS
				return $result;
			}
		} else {
			$result = 'loginNotOK'; //response code to JS
			return $result;
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
		return true;
	}

	public function sendActivationEmail(){

	}

	public function confirmSignup(){


	}

	public function validateAndSignup($db, $username, $email, $password, $confirmPassword){		
		if(!isset($username) || empty($username)){ 
			$response = 'usernameMissing';
			return $response;

			} else if(preg_match("/[^A-Za-z0-9]/", $username)){
				$response = 'usernameInvalid';
				return $response;

			} else if(!isset($email) || empty($email)){
				$response = 'emailMissing';
				return $response;

			} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$response = 'emailInvalid';
				return $response;	

			} else if (!isset($password) || empty($password)){
				$response = 'passwordMissing';
				return $response;

			} else if(!isset($confirmPassword) || empty($confirmPassword)){
				$response = 'confirmPasswordMissing';
				return $response;
		
			} else if (strcmp($password, $confirmPassword) !== 0){
				$response = 'passwordMismatch';
				return $response;			
			
			} else if(strcmp($password, $username) === 0){
				$response = 'passwordSameUsername';
				return $response;

			} else if (strlen($password) < 5){
				$response = 'passwordShort';
				return $response;
			
			} else {

				if (!$this -> validateUsername($db, $username)){
					if (!$this -> validateEmail($db, $email)){
						$pdo = new PDO("mysql:host=$db->host;dbname=$db->name", $db->user, $db->pass);						
						$sql = "INSERT INTO players (username, email, password, signup_ip, signup_date, last_login_date, activation_string) VALUES (:username, :email, :password, :signup_ip, :signup_date, :last_login_date, :activation_string)";
						$stmt = $pdo->prepare($sql);
						
							$username = $username;
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
						$pdo = null;
						
//TO DO 				//send activation email
						
						$response = 'signupOK';
						return $response;
					} else {
						$response = 'emailExists';
						return $response;
					}
				} else {
					$response = 'usernameExists';
					return $response;
				}
			}


	}
}


?>

