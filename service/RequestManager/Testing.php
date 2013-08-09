<?php
if($_GET['action'] == 'test')
{

	//do test here
	include "util.php";
	//include "Model/Variable.php";
	//$result = findVariableById(34);//findVariableByCodename("testBank");

	$payments = array();
	$payments[0] = new stdClass;
	$payments[0]->id = 2;
	$payments[0]->order = 1;
	$payments[1] = new stdClass;
	$payments[1]->id = 4;
	$payments[1]->order = 2;
	$payments[2] = new stdClass;
	$payments[2]->id = 5;
	$payments[2]->order = 3;

	//$response = _createTemplatePayment(4, 2, 1);

	$response = createTemplate('test createtemplate', 'kak', $payments);
//print_r($GLOBALS['connect']);



}
?>