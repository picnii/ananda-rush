<?php

    $variableActions = array('variables', 'variable', 'variablesType');

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


?>