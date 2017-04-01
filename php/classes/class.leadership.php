<?php 

require_once(__DIR__.'/class.consandmods.php');

//calculate and return leadership value to be saved for the player
class Leadership extends Database { 

	public function calculate($playerData, $playerID){
		error_log('Leadership -> calculate() started');

		$result = array();

		$result['basePopulation'] = round($playerData['population'] * _LeadershipPerPopulation, 1); //round value down to 1 decimal points. formatted as DECIMAL(3,1) in DB, so can be max 99,9
		//$result['bonusAttendance'] = round($playerData['population'] * 0.0776, 1); //TO DO - calcluate as a bonus if player logged in previously

		//calculate total leadership by adding everything in the result array
		$leadership = array_sum($result); 
		$result['totalLeadership'] = $leadership; 

		return $result; //return array
	}
}

?>