
<?php

$json = array(
	"status" => null,
	"description" => null
);

$pdo = new PDO('mysql:host=localhost;dbname=vadimlv_zombiezed_test', 'vadimlv_zombie', 'h7G5r4D3K'); //opening connection
	
if($_POST['id'] === 'signupUsername'){	
	$sql = 	"SELECT count(*) FROM players_test WHERE username = :value";
	$stmt = $pdo->prepare($sql);
	$value = filter_input(INPUT_POST, 'val', FILTER_SANITIZE_STRING); //sanitise
	$stmt->bindParam(':value', $value, PDO::PARAM_STR);
	$stmt->execute();
	$rows = $stmt->fetchColumn();
	if($rows > 0){
		$json['status'] = 1; //username found in the DB
	} elseif($rows == 0){
		$json['status'] = 0; //username not found in DB
	} else{
		$json['status'] = 'error';
		$json['description'] = 'error in SQL trying to look up username in DB';
	}
}elseif($_POST['id'] === 'signupEmail'){	
	$sql = 	"SELECT count(*) FROM players_test WHERE email = :value";
	$stmt = $pdo->prepare($sql);
	$value = filter_input(INPUT_POST, 'val', FILTER_SANITIZE_STRING); //sanitise
	$stmt->bindParam(':value', $value, PDO::PARAM_STR);
	$stmt->execute();
	$rows = $stmt->fetchColumn();
	if($rows > 0){
		$json['status'] = 1; //email found in the DB
	} elseif($rows == 0){
		$json['status'] = 0; //email not found in DB
	} else{
		$json['status'] = 'error';
		$json['description'] = 'error in SQL trying to look up email in DB';
	}
}else{
	$json['status'] = 'error';
	$json['description'] = 'error in validator_signup.php; invalid HTML element id passed to php';
}

$pdo = null; //closing connection

echo json_encode($json);









?>