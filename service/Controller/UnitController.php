<?php
	require_once('util.php');
	function actionSearch($q)
	{
		$search_query = $q;
		
		$units = findAllUnitsByQuery($q);
		if($q=="*")
		{
			array_push($units, getSampleUnit());
			array_push($units, getSampleUnit());
			array_push($units, getSampleUnit());
		}
		return $units;
	}

	function actionUnits($unit_ids)
	{
		$units = getUnits($unit_ids);
		return $units;
	}

	function actionTestSearch($q)
	{
		return array(
			'q'=>$q,
			'sql'=>getWhereClauseFromQuery($q)
		);
	}

?>