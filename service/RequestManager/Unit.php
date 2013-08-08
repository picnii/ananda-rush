<?php

    $unitActions = array('units', 'unitTest');

	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $unitActions))
			require_once 'Controller/UnitController.php';
		if($action == 'units' && isset($_GET['q']))
			$response = actionSearch($_GET['q']);
		if($action == 'unitTest' && isset($_GET['q']))
			$response = actionTestSearch($_GET['q']);	
	
	}

	if(isset($_POST['action']))
	{
		$action = $_POST['action'];
		if(gotAction($action, $unitActions))
			require_once 'Controller/UnitController.php';
		if($action == 'units' && isset($_POST['unit_ids']))
			$response = actionUnits($_POST['unit_ids']);
	}

?>