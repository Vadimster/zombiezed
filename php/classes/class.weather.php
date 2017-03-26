<?php

//fetach and update season then return season
//calcluate weather modifiers for reosurces and mission then return modifiers.


class Weather extends Database{

	public function update($db){
		error_log('weather->update() started');

		$pdo = new PDO("mysql:host=$db->host;dbname=$db->name", $db->user, $db->pass);
		$sql = 	"SELECT * FROM weather LIMIT 1";
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			$weather =[ //this array is returned back to Turn object to be used in further calculations
				'week' => $result['week'],
				'month' => $result['month'],
				'season' => $result['season'],
			];

			if($weather['week'] < 4){
				$weather['week']++;
				$week = $weather['week'];
				$sql = 	"UPDATE weather SET week = $week";
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
				
			} else {
			
				$weather['week'] = 1;
			
				if ($weather['month'] === 'January'){
					$weather['month'] = 'February';
				
				} else if($weather['month'] === 'February'){
					$weather['month'] = 'March';
					$weather['season'] = 'Spring';
				
				} else if($weather['month'] === 'March'){
					$weather['month']  = 'April';
				
				} else if ($weather['month'] === 'April' ) { 
					$weather['month']  = 'May';
				
				} else if ($weather['month'] === 'May'){
					$weather['month']  = 'June';
					$weather['season'] = 'Summer';
				
				} else if ($weather['month'] === 'June'){
					$weather['month']  = 'July';
				
				} else if ($weather['month'] === 'July'){
					$weather['month']  = 'August';
				
				} else if ($weather['month'] == 'August'){
						$weather['month']  = 'September';
						$weather['season'] = 'Autumn';
				
				} else if ($weather['month'] === 'September'){
					$weather['month']  = 'October';	
				
				} else if ($weather['month'] === 'October'){
					$weather['month']  = 'November';
				
				} else if ($weather['month'] === 'November'){
					$weather['month']  = 'December';
					$weather['season'] = 'Winter';
				
				} else { //month is December
					$weather['month']  = 'January';
				}

				$week = $weather['week'];
				$month = $weather['month'];
				$season = $weather['season'];
				$sql = 	"UPDATE weather SET week = $week, month = '$month', season = '$season'"; //strongs must be in single quotes if used in sql statement directly without binding.					
				$stmt = $pdo->prepare($sql);
				$stmt->execute();			

			}

			return $weather;
			$pdo = null;

	}


}


?>
