<?php
if($_GET['action'] == 'test')
{

	//do test here
	include "util.php";
	//include "Model/Variable.php";
	//$result = findVariableById(34);//findVariableByCodename("testBank");
	$result = findAllVariables();
	$result = deleteVariable(50);
	$response = $result;
}
?>