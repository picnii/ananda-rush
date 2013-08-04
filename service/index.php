<?php
	$response = array("Error"=> "none service exists");
	

	$billActions = array('bills', 'bill');
	$templateActions = array('templates', 'template');

	/* util */
	function gotAction($action, $actions)
	{
		for($i =0; $i< count($actions); $i++)
		{
			if($action == $actions[$i])
				return true;
		}
		return false;
	}
	/* Bill Service */

	if(isset($_POST['action']))
	{
		$action = $_POST['action'];
		if(gotAction($action, $billActions))
			include 'bill.php';
		if($action == 'bills')
			$response = actionBills($_POST['unit_ids'], $_POST['template_id']);
		
	}

	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $billActions))
			include 'bill.php';
		if($action == 'bill')
			$response = actionBill($_GET['unit_id'], $_GET['template_id']);

	}

	/* Template Service */


	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $templateActions))
			include 'template.php';
		if($action == 'templates')
			$response = actionTemplates();
		if($action == 'template')
			$response = actionTemplate($_GET['template_id']);

	}


	$response = json_encode($response);
	echo $response;
?>