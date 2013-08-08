<?php
	require_once('util.php');
	
	function actionVariablesType()
	{
		return getVariablesType();
	}

	function actionVariables($type=null)
	{
		/*if($type ==null)
			return array(
				getSampleVariable(),
				getSampleVariable(),
				getSampleVariable(),
				getSampleVariable(),
				getSampleVariable(),
				getSampleVariable()
			);
		else
			return array(
				getSampleVariable(),
				getSampleVariable(),
				getSampleVariable()
			);*/
		return findAllVariables($type);
	}

	function actionVariable($codeNameOrId)
	{
		if(is_numeric($codeNameOrId))
			return findVariableById($codeNameOrId);
		else
			return findVariableByCodeName($codeNameOrId);
	}

	function actionUpdateVariable($variable_id, $value)
	{
		return true;
	}

	function actionCreateVariable($name, $codename, $description, $type, $value)
	{
		return createVariable($name, $codename, $description, $type, $value);
	}

	function actionDeleteVariable($variable_id)
	{
		return deleteVariable($variable_id);
	}


?>