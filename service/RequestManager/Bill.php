<?php

    $billActions = array('bills', 'bill','createBills', 'billPayment', 'transactions');
/* Bill Service */

	if(isset($_POST['action']))
	{
		$action = $_POST['action'];
		if(gotAction($action, $billActions))
			require_once 'Controller/BillController.php';
		if($action == 'bills')
			$response = actionBills($_POST['unit_ids'], $_POST['template_id']);
		else if($action =='createBills')
			$response = actionCreateBills($_POST['unit_ids'] , $_POST['template_id']);

		
	}

	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $billActions))
			require_once 'Controller/BillController.php';
		if($action == 'bill')
			$response = actionBill($_GET['unit_id'], $_GET['template_id']);
		if($action == 'billPayment')
			$response = actionGetPaymentIds();
		if($action == 'transactions')
			$response = actionTransactions(array(2157, 2158));
		if($action == 'testtest')
			$response = array('cool');
	}

	if(isset($reqBody->action))
	{

		$action = $reqBody->action ;
		if(gotAction($action, $billActions))
			require_once 'Controller/BillController.php';
		if($reqBody->action == 'createBills')
		{
			$response = actionCreateBills($reqBody->unit_ids, $reqBody->template_id);		
		}
		if($reqBody->action == 'bills')
		{
			$response = actionBills($reqBody->unit_ids, $reqBody->template_id);		
		}

	}

?>