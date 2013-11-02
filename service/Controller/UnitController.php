<?php
	require_once('util.php');
	function actionSearch($q, $from=null, $to=null)
	{
		$search_query = $q;
		
		if($q=="*" && $from== null && $to==null)
		{
			
			$units = findAllUnits();
		}
		else if($q == "*")
		{
			$units = findAllUnits($from, $to);
		
		}
		else
		{
		//	echo "q = {$q}";
			$params = getParamsFromSearchQuery($q, 'master_transaction', array(
				'SalesName' => 'Sale_Transection',
				'SQM' => 'Sale_Transection',
				'Period' => 'appointment'
			));
			$operators = array(
				'Sale_Transection.SalesName' => 'LIKE',
				'Sale_Transection.SQM' => 'BETWEEN',
				'appointment.Period' => 'PERIOD'
			);
			$units = findAllUnitsByQuery($params, $operators);
		}
		/*$units = findAllUnitsByQuery($q);
		if($q=="*")
		{
			array_push($units, getSampleUnit());
			array_push($units, getSampleUnit());
			array_push($units, getSampleUnit());
		}*/
		return $units;
	}

	function actionUnits($unit_ids)
	{

		$units = getUnits($unit_ids);
		return $units;
	}

	function actionUnit($unit_id)
	{
		$unit_ids = array();
		$unit_ids[0] = $unit_id;
		$units = getUnits($unit_ids);
		return $units[0];
	}

	function actionTestSearch($q)
	{
		return array(
			'q'=>$q,
			'sql'=>getWhereClauseFromQuery($q)
		);
	}

?>