<?php

    $variableActions = array('variables', 'variable', 'variablesType', 'createVariable');

	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $variableActions))
			include 'Controller/VariableController.php';
		if($action == 'variablesType')
			$response = actionVariablesType();
		if($action == 'variables' && isset($_GET['type']))
			$response = actionVariables($_GET['type']);	
		else if($action =="variables")
			$response = actionVariables();
	}

	if(isset($_POST['action']))
	{
		$action = $_POST['action'];
		if(gotAction($action, $variableActions))
			include 'Controller/VariableController.php';
	
		if($action == 'createVariable')
			$response = actionCreateVariable($_POST['name'], $_POST['codename'], $_POST['description'], $_POST['type'], $_POST['value']);	
	}


?>