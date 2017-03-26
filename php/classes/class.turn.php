<?php

require_once(__DIR__.'/class.calendar.php');

class Turn extends Database {

	public function process($db){

		error_log('Trun->progess() started');

		//1. GLOBAL IMPACT CALCULATION

			//1. update calendar & weather
			$calendarObject = new Calendar;
			$calendar = $calendarObject -> update($db);

			error_log(print_r($calendar, TRUE));

	}

}

?>