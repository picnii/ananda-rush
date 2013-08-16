<?php
	function findAllUnits($row_start=1, $row_end=50)
	{
		$sql = "SELECT  *
			FROM    ( SELECT    ROW_NUMBER() OVER ( ORDER BY transaction_id ) AS RowNum, *
			          FROM      master_transaction
			        ) AS RowConstrainedResult
			WHERE   RowNum >= {$row_start}
			    AND RowNum < {$row_end}
			ORDER BY RowNum";
		$result = DB_query($GLOBALS['connect'],$sql);
    
	    $units =  array();
	    while($row = DB_fetch_array($result))
	    {
	    	//print_r($row);
	    	if(!isset($row['transaction_id']))
	    		return array();
	    	$unit = convertUnitFromRow($row);
	    	array_push($units, $unit);
	    }
	    return $units;
	}

	function findUnitsFromPage($page, $row_per_page = 50)
	{
		$start = ($page - 1) * 50;
		if($start == 0)
			$start = 1;
		$end = $row_per_page * $page;
		
		return  findAllUnits($start, $end);
	}

	function getAllUnitRows()
	{
		$sql ="SELECT COUNT(*) AS row FROM master_transaction";
		$result = DB_query($GLOBALS['connect'],$sql);
		$row = DB_fetch_array($result);
		return ceil($row['row']);
	}

	function getPagesCount($row_per_page)
	{
		$rows = getAllUnitRows();
		$pages = $rows/$row_per_page;
		return ceil($pages);
	}

	function findUnitById($id)
	{
		$sql = "SELECT * FROM master_transaction WHERE transaction_id = {$id}";
		$result = DB_query($GLOBALS['connect'],$sql);
		$row = DB_fetch_array($result);
		if(!isset($row['transaction_id']))
	    	return false;
	    return convertUnitFromRow($row);
	}

	function convertUnitFromRow($row)
	{
		$unit = new stdClass;
		$unit->id = $row['transaction_id'];
		$unit->company_code = $row['CompanyCode'];
		$unit->project_id = $row['ProjID'];
		$unit->brand = $row['Brand'];
		$unit->item_id = $row['ItemId'];
		$unit->item_name = $row['ItemName'];
		$unit->floor = $row['Floor'].'';
		$unit->unit_no = $row['UnitNo'];
		$unit->room_no = $row['RoomNo'];
		$unit->sqm = $row['Sqm'];
		$unit->door = $row['Door'];
		$unit->direction = $row['Direction'];
		$unit->base_price = $row['BasePrice'];
		$unit->sell_price = $row['SellPrice'];
		$unit->status = $row['Status'];
		$unit->building = $row['building'];
		$unit->bu_id = $row['bu_id'];
		$unit->house_size = $row['HOUSESIZE'];
		$unit->land_size = $row['LANDSIZE'];
		return $unit;
	}

	function convertSearchObjectToRow($unit)
	{
		$row = array();
		if(isset($unit->id))
			$row['transaction_id'] = $unit->id;
		if(isset($unit->company_code))
			$row['CompanyCode']= $unit->company_code;
		if(isset($unit->project_id ))
			$row['ProjID']= $unit->project_id ;
		if(isset($unit->brand ))
			$row['Brand']= $unit->brand ;
		if(isset($unit->item_id))
			$row['ItemId']= $unit->item_id ;
		if(isset($unit->item_name))
			$row['ItemName']= $unit->item_name ;
		if(isset($unit->floor))
			$row['Floor']= $unit->floor ;
		if(isset($unit->unit_no))
			$row['UnitNo']= $unit->unit_no ;
		if(isset($unit->room_no))
			$row['RoomNo']= $unit->room_no ;
		if(isset($unit->sqm))
			$row['Sqm']= $unit->sqm ;
		if(isset($unit->door))
			$row['Door']= $unit->door ;
		if(isset($unit->direction))
			$row['Direction']= $unit->direction ;
		if(isset($unit->base_price))
			$row['BasePrice']= $unit->base_price ;
		if(isset($unit->sell_price))
			$row['SellPrice']= $unit->sell_price ;
		if(isset($unit->status))
			$row['Status']= $unit->status ;
		if(isset($unit->building))
			$row['building']= $unit->building ;
		if(isset($unit->bu_id))
			$row['bu_id']= $unit->bu_id ;
		if(isset($unit->house_size))
			$row['HOUSESIZE']= $unit->house_size ;
		if(isset($unit->land_size))
			$row['LANDSIZE']= $unit->land_size ;
		return $row;
	}

	function findAllUnitsByQuery($q, $isDebugMode = false)
	{
		$sql = "SELECT * FROM master_transaction ".getWhereClauseFromParams($q);
		//echo $sql;	
		//SELECT 
		$result = DB_query($GLOBALS['connect'],$sql);
	    $units =  array();
	    if($isDebugMode)
	    	echo $sql;
	    while($row = DB_fetch_array($result))
	    {
	    	if($isDebugMode)
	    		print_r($row);
	    	if(!isset($row['transaction_id']))
	    		return array();
	    	$unit = convertUnitFromRow($row);
	    	if($isDebugMode)
	    		print_r($unit);
	    	array_push($units, $unit);
	    }
	     if($isDebugMode)
	    	print_r($units);
	    return $units;

		//$samples = array(getSampleUnit(), getSampleUnit(), getSampleUnit());
		//return $samples;
	}

	function getUnits($unit_ids)
	{
		$sql = "SELECT * FROM master_transaction WHERE ";
		$isFirst =true;
		foreach ($unit_ids as $id) {
			# code...
			if($isFirst)
				$isFirst = false;
			else
				$sql .= " OR ";
			$sql .= "transaction_id = {$id}";
		}

		$result = DB_query($GLOBALS['connect'],$sql);
	    $units =  array();
	    while($row = DB_fetch_array($result))
	    {
	    	if(!isset($row['transaction_id']))
	    		return array();
	    	$unit = convertUnitFromRow($row);
	    	array_push($units, $unit);
	    }
	    return $units;
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