<?php

    $paymentActions = array('payments','createPayment','updatePayment', 'deletePayment');

	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $paymentActions))
			require_once 'Controller/PaymentController.php';
		if($action == 'payments')
			$response = actionPayments();
	}

	if(isset($reqBody->action))
	{

		$action = $reqBody->action ;
		if(gotAction($action, $paymentActions))
			require_once 'Controller/PaymentController.php';
		if($reqBody->action == 'createPayment')
			$response = actionCreatePayment($reqBody);
		else if($reqBody->action == 'updatePayment')
			$response = actionUpdatePayment($reqBody);
		else if($reqBody->action == 'deletePayment')
			$response = actionDeletePayment($reqBody);

	}

?>