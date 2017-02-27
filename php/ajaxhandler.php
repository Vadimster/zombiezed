<?php 
session_start();
/* this page handles all AJAX requests and calls relevant methods of classes */
require_once(__DIR__.'/classes/class.db.php');
require_once(__DIR__.'/classes/class.player.php');
require_once(__DIR__.'/classes/class.map.php');
require_once(__DIR__.'/classes/class.stats.php');


if(!empty($_POST)){ //will run code if POST is not empty.
	
	if(isset($_POST['action']) && $_POST['action'] === 'signup'){	//REQUEST 1: validate client input and add player to DB
		$player = new Player();
		$db = new Database();
		$result = $player -> validateAndSignup($db, $_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirmPassword']);
		if ($result){
			echo json_encode($result);
		} else {
			error_log('Error. Nothing returned by $player -> validateAndSignup()');
		}
	
	} else if (isset($_POST['action']) && $_POST['action'] === 'login'){  //REQUEST 2: validate exitense of user in DB and login into game if OK.
		$player = new Player();
		$db = new Database();
		$result = $player -> login($db, $_POST['username'], $_POST['password']);
		if ($result){
			echo json_encode($result);
		} else {
			error_log('Error. Nothing returned by $player -> login()');
		}

	} else if (isset($_POST['action']) && $_POST['action'] === 'logout'){
		$player = new Player();
		$result = $player -> logout(); //returns 'true'
		if($result){
			echo json_encode($result);
		} else {
			error_log('Error. Nothing returned by $player -> logout()');
		}	
	} 


} else if (!empty($_GET)) {  //will run code if GET is not empty. Accessing by URL directly, for example, would also count as GET request
	
	if (isset($_GET['action']) && $_GET['action'] === 'logout'){
		$player = new Player();
		$result = $player -> logout(); //returns 'true'
		if($result){
			echo json_encode($result);
		} else {
			error_log('Error. Nothing returned by $player -> logout()');
		}	

	} else if (isset($_GET['action']) && $_GET['action'] === 'checkExistence'){
		$player = new Player();
		$db = new Database();
		$response = array();

		if($_GET['element'] === 'username'){			
			$result = $player -> validateUsername($db, $_GET['value']);
			if($result){ 
				$response['msg'] = 'usernameExists';
			} else {
				$response['msg'] = 'usernameOK';
			}

		} else if ($_GET['element'] === 'email'){
			$result = $player -> validateEmail($db, $_GET['value']);
			if($result){
				$response['msg'] = 'emailExists';
			} else{
				$response['msg'] = 'emailOK';
			}
		}
		echo json_encode($response);

	} else if (isset($_GET['action']) && $_GET['action'] === 'getMap'){
		$db = new Database();
		$map = new Map();
		$result = $map -> fetch($db);

		if ($result){
			echo json_encode($result);
		} else {
			error_log('Error. Nothing returned by $map -> fetch()');
		}

	} else if (isset($_GET['action']) && $_GET['action'] === 'getStats'){
		$db = new Database();
		$stats = new Stats();
		$result = $stats -> fetch($db);

		if ($result){
			echo json_encode($result);
		} else {
			error_log('Error. Nothing returned by $player -> fetch()');
		}

	}				
	
} else { //Request is neither GET nor POST or either GET or POST requests are empty.
	echo '<p>This page is visited incorrectly. Sorri-ta.</p>';
}

?>
