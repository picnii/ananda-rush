<?php

    $billActions = array('bills', 'bill','createBills');
/* Bill Service */

	if(isset($_POST['action']))
	{
		$action = $_POST['action'];
		if(gotAction($action, $billActions))
			include 'Controller/BillController.php';
		if($action == 'bills')
			$response = actionBills($_POST['unit_ids'], $_POST['template_id']);
		else if($action =='createBills')
			$response = actionCreateBills($_POST['unit_ids'] , $_POST['template_id']);

		
	}

	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $billActions))
			include 'Controller/BillController.php';
		if($action == 'bill')
			$response = actionBill($_GET['unit_id'], $_GET['template_id']);
		
	
	}

?>