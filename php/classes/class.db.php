<?php 

//PHP Version on live & dev: 5.5
//MySQL version on live & dev:

//Database credentials

class Database {

	protected $host  = 'localhost';
	protected $name  = 'pantheon';
	protected $user  = 'root';
	protected $pass  = 'Uganda666!';
}

	  
/*

DB schema

	CREATE TABLE players (
		player_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		username VARCHAR(15) NOT NULL,
		email VARCHAR(50) NOT NULL,		
		`password` CHAR(255) NOT NULL,
		signup_ip VARCHAR(45) NOT NULL,
		signup_date DATETIME NOT NULL,
		last_login_date DATETIME NOT NULL,
		activation_string CHAR(32) NOT NULL,
		activated TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
	)
*/

?>
