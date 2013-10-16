<?php

    $unitActions = array('units', 'unitTest', 'unit');

	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $unitActions))
			require_once 'Controller/UnitController.php';
		if($action == 'units' && isset($_GET['q']))
			if(!(isset($_GET['from']) && isset($_GET['to'])  ))
				$response = actionSearch($_GET['q']);
			else
				$response = actionSearch($_GET['q'], $_GET['from'], $_GET['to']);
		if($action == 'unitTest' && isset($_GET['q']))
			$response = actionTestSearch($_GET['q']);	
		if($action == 'unit')
			$response = actionUnit($_GET['unit_id']);
	
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