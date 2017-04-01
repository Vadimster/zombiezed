<?php

require_once(__DIR__.'/class.calendar.php');
require_once(__DIR__.'/class.calendar.php');
require_once(__DIR__.'/class.leadership.php');

class Turn extends Database {

	public function process($db){

		error_log('Trun->progess() started');

		//1. GLOBAL IMPACT CALCULATION

			//1.1 update calendar & weather
			$calendarObject = new Calendar;
			$calendar = $calendarObject -> update($db);

			error_log(print_r($calendar, TRUE)); //here some value to be returned to save the weather and modifiers for LOCAL impact calculation


		//2. LOCAL IMPACT CALCULATION

			//get all playerIDs into an array

			$pdo = new PDO("mysql:host=$db->host;dbname=$db->name", $db->user, $db->pass);
			$sql = 	"SELECT player_id FROM players";
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$allPlayers = $stmt->fetchAll(PDO::FETCH_ASSOC);

			//calculate impact for each player in array 
			$leadershipObject = new Leadership;

			foreach( $allPlayers as $row ) {
				$playerID = $row['player_id'];
				
				$sql = "SELECT population FROM players_stats WHERE player_id = $playerID"; //get ALL relevant info from DB for a player. This SQL needs to be expanded severely in the future to include joins, etc.
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
				$playerData = $stmt->fetch(PDO::FETCH_ASSOC);
					
					$leadershipData = $leadershipObject -> calculate($playerData, $playerID); 
		    		error_log(print_r($leadershipData, TRUE));

		    		$leadership = $leadershipData['totalLeadership'];
		    		$leadershipBasePopulation = $leadershipData['basePopulation'];

		    		error_log($leadershipBasePopulation);

					

					//TO DO: calculate rank to see if player progressed up the rand or regressed	


					$pdo->beginTransaction();
						$pdo->exec("UPDATE players_stats SET ld = $leadership, ld_per_turn = $leadership, ld_base_pop = $leadershipBasePopulation  WHERE player_id = $playerID"); //save MODIFIERS here too!
					$pdo->commit();	


			} //end of foreach loop
			$pdo = null;


	} //end of process($db)

} //end of class ?> 