<?php
	require_once('util.php');
	//use for preview what bills will be liked
	function actionRoomType()
	{
		
		return getRoomTypes();
		
	}

	function actionProjectsList()
	{

		return getProjectsList();
	}
	//testBill();
	//header('Content-Type: text/html; charset=utf-8');
	//actionBill(1);*/

	function getRoomTypes()
	{
		$types = array();
		$types[0] = new stdClass;
		$types[0]->name ="1 BED";
		$types[0]->value = 1;
		$types[1] = new stdClass;
		$types[1]->name ="2 BED";
		$types[1]->value = 2;
		return $types;
	}

	function getProjectsList()
	{
		$projects = array();
		$projects[0] = new stdClass;
		$projects[0]->name ="CONDO Ratchada";
		$projects[0]->value = 1;
		$projects[1] = new stdClass;
		$projects[1]->name ="CONDO BANA";
		$projects[1]->value = 2;
		return $projects;
	}
?>