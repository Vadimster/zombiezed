<?php

//fetach and update season then return season
//calcluate calendar modifiers for reosurces and mission then return modifiers.


class Calendar extends Database{

	public function getNextTurnTime($db){
		$pdo = new PDO("mysql:host=$db->host;dbname=$db->name", $db->user, $db->pass);
		$sql = "SELECT next_turn from calendar LIMIT 1";
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$response = array();
			$timestamp = time();
			$next_turn = $result['next_turn'];
			$response['serverTime'] = $timestamp;
			$response['nextTurn'] =  $next_turn;
			$response['timeDiff'] = $next_turn - $timestamp;//difference in seconds
			error_log($response['timeDiff']);
			return $response;
	}

	public function update($db){
		error_log('calendar->update() started');

	//SETTING TIMESTAMPS OF CURRENT AND NEXT TURN TIME. consider using a function for this.
		$timestamp = time();
		$next_turn_time = strtotime('+1 day', $timestamp);	//preparing timestamp for writing in DB			
		//$current_turn_time = date("Y-m-d H:i:s",$timestamp); //preparing timestamp for writing in DB
		
		//$next_turn_time = date("Y-m-d H:i:s", strtotime('+1 day', $timestamp));	//preparing timestamp for writing in DB			
		//error_log('Turn start time is ' . $current_turn_time);
		//error_log('next turn time is ' . $next_turn_time);


		$pdo = new PDO("mysql:host=$db->host;dbname=$db->name", $db->user, $db->pass);
		$sql = 	"SELECT * FROM calendar LIMIT 1";
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			$calendar =[ //this array is returned back to Turn object to be used in further calculations
				'week' => $result['week'],
				'month' => $result['month'],
				'season' => $result['season'],
			];

			if($calendar['week'] < 4){
				$calendar['week']++;
				$week = $calendar['week'];
				$sql = 	"UPDATE calendar SET next_turn = '$next_turn_time', week = $week";
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
				
			} else {
			
				$calendar['week'] = 1;
			
				if ($calendar['month'] === 'January'){
					$calendar['month'] = 'February';
				
				} else if($calendar['month'] === 'February'){
					$calendar['month'] = 'March';
					$calendar['season'] = 'Spring';
				
				} else if($calendar['month'] === 'March'){
					$calendar['month']  = 'April';
				
				} else if ($calendar['month'] === 'April' ) { 
					$calendar['month']  = 'May';
				
				} else if ($calendar['month'] === 'May'){
					$calendar['month']  = 'June';
					$calendar['season'] = 'Summer';
				
				} else if ($calendar['month'] === 'June'){
					$calendar['month']  = 'July';
				
				} else if ($calendar['month'] === 'July'){
					$calendar['month']  = 'August';
				
				} else if ($calendar['month'] == 'August'){
						$calendar['month']  = 'September';
						$calendar['season'] = 'Autumn';
				
				} else if ($calendar['month'] === 'September'){
					$calendar['month']  = 'October';	
				
				} else if ($calendar['month'] === 'October'){
					$calendar['month']  = 'November';
				
				} else if ($calendar['month'] === 'November'){
					$calendar['month']  = 'December';
					$calendar['season'] = 'Winter';
				
				} else { //month is December
					$calendar['month']  = 'January';
				}

				$week = $calendar['week'];
				$month = $calendar['month'];
				$season = $calendar['season'];				

				$sql = 	"UPDATE calendar SET next_turn = '$next_turn_time', week = $week, month = '$month', season = '$season'"; //strings must be in single quotes if used in sql statement directly without binding.					
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
			}

			return $calendar;
			$pdo = null;

	}


}


?>
