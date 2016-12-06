<?php 
session_start();
/* handles all AJAX requests and calls relevant methods of classes */
require 'classes/class.player.php';
//require_once('/Users/instantbank/Sites/pantheon/php/classes/class.db.php');
require_once('classes/class.db.php');

$json = array(); //response back to client

if($_SERVER['REQUEST_METHOD'] == 'POST'){ //all requests should reach this page as POST.
	
	if(isset($_POST['action']) && $_POST['action'] === 'signup'){	//REQUEST 1: validate client input and add player to DB
		$player = new Player();
		$db = new Database();
		$result = $player -> validateAndSignup($db, $_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirmPassword']);
		if ($result){
			$json['msg'] = $result;
			echo json_encode($json);
		} else {
			error_log('Error in $player -> validateAndSignup()');
		}
	
	} else if (isset($_POST['action']) && $_POST['action'] === 'login'){  //REQUEST 2: validate exitense of user in DB and login into game if OK.
		$player = new Player();
		$db = new Database();
		$result = $player -> login($db, $_POST['username'], $_POST['password']);
		if ($result){
			$json['msg'] = $result;
			echo json_encode($json);
		} else {
			error_log('Error in $player -> login()');
		}

	} else if (isset($_POST['action']) && $_POST['action'] === 'logout'){
		$player = new Player();

		$result = $player -> logout();
		if($result){
			$json['msg'] = $result;
			echo json_encode($json);
		} else {
			error_log('Error in $player -> logout()');
		}
	
	} else if (isset($_POST['action']) && $_POST['action'] === 'play'){
		//erver dolzhne provertij esj li dostupnaja igra. Esli estj - to podklu4itj novogo igroka, esli net, to sozdatj novuju igru i zhdatj podklj4enija drugogo igroka v te4enie 30 sekund. Esli ne podklu4ilsa - prerataj igru i vidaj o6ibki “Currently no players up for game, try later on.”

	}  



} else { //Request is not POST, show error on page.
	echo '<p>This page is visited incorrectly. Sorri-ta.</p>';
}




/*


*/



/*

				if(!empty($_POST['username']) && !empty($_POST['password'])){







				} 
*/

?>
