<?php

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
		signup_ip		| varchar(45)  // $_SERVER['REMOTE_ADDR']
		signup_date		| datetime  // date("Y-m-d H:i:s")
		last_login_date	| datetime
*/



?>