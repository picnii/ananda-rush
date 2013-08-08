<?php

    $variableActions = array('variables', 'variable', 'variablesType', 'createVariable', 'deleteVariable');

	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $variableActions))
			require_once 'Controller/VariableController.php';
		if($action == 'variablesType')
			$response = actionVariablesType();
		if($action == 'variables' && isset($_GET['type']))
			$response = actionVariables($_GET['type']);	
		else if($action =="variables")
			$response = actionVariables();
		if($action == "variable")
			$response = actionVariable($_GET['q']);
	}	

	if(isset($_POST['action']))
	{
		$action = $_POST['action'];
		if(gotAction($action, $variableActions))
			require_once 'Controller/VariableController.php';
	
		
	}

	if(isset($reqBody->action))
	{

		$action = $reqBody->action ;
		if(gotAction($action, $variableActions))
			require_once 'Controller/VariableController.php';
		if($reqBody->action == 'createVariable')
		{
			$response = actionCreateVariable($reqBody->name, $reqBody->name, '', $reqBody->type, $reqBody->value);		
		}
		if($reqBody->action == "deleteVariable")
			$response = actionDeleteVariable($reqBody->id);

	}


?>