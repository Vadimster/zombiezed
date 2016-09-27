<?php

//PHP Version 5.3.29

//Database credentials

	 $dbHost = 'localhost';
	 $dbName = 'vadimlv_zombiezed_test';
	 $dbUser = 'vadimlv_zombie';
	 $dbPass = 'h7G5r4D3K';

/*

DB schema

	table players_test

		id 				| smallint, autoincrement, primary key		
		username 		| varchar (10)
		password 		| char(32), MD5
		email 			| varchar(50)
		signup_ip		| varchar(45) 
		signup_date		| datetime  
		last_login_date	| datetime

	
	table players_stats_test


		id 				| smallint, autoincrement, primary key
		population		| smallint
		food 			| int
		water 			| int

*/



/*  PDO

Suppose Ineed to access my DB using some variable, e.g. $_POST['id']

1. Create PDO object which holds credentials

	$pdo = new PDO('mysql:host=example.com;dbname=database', 'user', 'password');  

2. Create SQL qquery

	$sql = 	"SELECT name FROM users WHERE id = :id"

2. Prepare a statement

	$stmt = $pdo->prepare($sql);   / alternatively can use this notation: $stmt = $pdo->prepare('UPDATE users SET name = :name WHERE id = :id');

3. Filter data prior to quering the DB

	$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);  // <-- filter your data first   or FILTER_SANITIZE_STRING

4. Sanitise data

	$stmt->bindParam(':id', $id, PDO::PARAM_INT); // <-- Automatically sanitized for SQL by PDO, binds variable to the prepared statement

5, execute the statement

	$stmt->execute();



Useful things:

a) Count the number of results

$stmt->execute();
$results = $stmt->fetchAll();
$rows = count($results);

// $stmt->fetch() will fetch one row, and fetchAll() will fetch the entire resultset. Does not work if there is only one row returned.

*/



?>