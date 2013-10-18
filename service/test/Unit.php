<?php
	
	$units = findAllUnits();
	//print_r($units);
	//got Unit > 1
	assertEquals(true, count($units) > 1 );
	//got ItemId
	$pick_index = rand(1, count($units) - 1);
	$pick_unit =  $units[$pick_index];
	//print_r($units);
	//print_r($units[35]);
	$unit = findUnitById($pick_unit->id);

	assertEquals($pick_unit->item_id, $unit->item_id);
	assertEquals($pick_unit->floor, $unit->floor);
	assertEquals($pick_unit->project_id, $unit->project_id);
	assertEquals($pick_unit->company_code, $unit->company_code);
	assertEquals($pick_unit->item_name, $unit->item_name);
	assertEquals($pick_unit->item_no, $unit->item_no);
	assertEquals($pick_unit->room_no, $unit->room_no);
	assertEquals($pick_unit->sqm, $unit->sqm);
	assertEquals($pick_unit->door, $unit->door);
	assertEquals($pick_unit->direction, $unit->direction);
	assertEquals($pick_unit->base_price, $unit->base_price);
	assertEquals($pick_unit->sell_price, $unit->sell_price);
	assertEquals($pick_unit->status, $unit->status);
	assertEquals($pick_unit->building, $unit->building);
	assertEquals($pick_unit->bu_id, $unit->bu_id);
	assertEquals($pick_unit->house_size, $unit->house_size);
	assertEquals($pick_unit->land_size, $unit->land_size);

	$search_units = findAllUnitsByQuery(array(
		"master_transaction.Floor" => "12A"
	));
	//print_r($search_units);
	
	for($i =0; $i < count($search_units); $i++){
		$search_unit = $search_units[$i];
		assertEquals("12A", $search_unit->floor);
	}

	$search_units = findAllUnitsByQuery(array(
		"master_transaction.Floor" => "12A",
		"Status" => "Available"
	));
	for($i =0; $i < count($search_units); $i++){
		$search_unit = $search_units[$i];
		assertEquals("12A", $search_unit->floor);
		assertEquals("Available", $search_unit->status);
	}

	$search_unit = new Stdclass;
	$search_unit->item_id = "TH01C106001";
	$q = convertSearchObjectToRow($search_unit);
	$ops = array();
	$ops['ItemId'] = 'ALIAS master_transaction.ItemId';
	$search_units = findAllUnitsByQuery($q, $ops);
	assertEquals(1, count($search_units));
	$result_search_unit = $search_units[0];
	assertEquals("TH01C106001", $result_search_unit->item_id );
	assertEquals("IDEO Mobi Sathorn Unit No.06-01:Room TypeDP-F1-2", $result_search_unit->item_name);
	

	$search_unit = new Stdclass;
	$search_unit->project_id = "TH01";
	$search_unit->floor = "6";
	$q = convertSearchObjectToRow($search_unit);
	//print_r($q);
	//print_r(findAllUnitsByQuery($q, true));
	$ops = array();
	$ops['ProjID'] = 'ALIAS master_transaction.ProjID';
	$ops['Floor'] = 'ALIAS master_transaction.Floor';
	$search_units = findAllUnitsByQuery($q, $ops);
	assertEquals(true, count($search_units)>= 1);
	foreach($search_units as $unit)
	{
		assertEquals("TH01", $unit->project_id);
		assertEquals("6", $unit->floor);
	}

?>