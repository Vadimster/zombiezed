<?php

require_once(__DIR__.'/class.weather.php');

class Turn extends Database {

	public function process($db){

		error_log('Trun->progess() started');

		//1. GLOBAL IMPACT CALCULATION

			//1. update metheorology
			$weatherObject = new Weather;
			$weather = $weatherObject -> update($db);

			error_log(print_r($weather, TRUE));

	}

}

?>