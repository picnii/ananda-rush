<?php
	function findAllUnitsByQuery($q)
	{
		$sql = "SELECT * FROM UNIT ".getWhereClauseFromQuery($q);
		//SELECT 
		
		$samples = array(getSampleUnit(), getSampleUnit(), getSampleUnit());
		return $samples;
	}

	function getUnits($unit_ids)
	{
		$samples = array(
			getSampleUnit(), getSampleUnit(),
			getSampleUnit(), getSampleUnit()
		);
		return $samples;
	}

	function getSampleUnit()
	{
		$unit = new stdClass;
		$unit->id = rand();
		$unit->client_name = "Mr.".rand();
		$unit->room_number = rand()."-D";
		if(rand(0,1))
			$unit->is_match_bill = false;
		else
			$unit->is_match_bill = true;
		return $unit;
	}

?>