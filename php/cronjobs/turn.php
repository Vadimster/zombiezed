<?php
//this will run the cronjob
require_once('../classes/class.db.php');
require_once('../classes/class.turn.php');


$db = new Database();
$turn = new Turn();
$turn -> process($db);



?>