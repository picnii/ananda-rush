<?php
	require_once('util.php');
	
	function actionVariablesType()
	{
		return getVariablesType();
	}

	function actionVariables($type=null)
	{
		if($type ==null)
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
			);
	}

	function actionVariable()
	{
		return getSampleVariable();
	}

	function actionUpdateVariable($variable_id, $value)
	{
		return true;
	}

	function actionCreateVariable()
	{
		return true;
	}

	function actionDeleteVariable()
	{
		return true;
	}


?>