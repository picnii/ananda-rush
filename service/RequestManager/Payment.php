<?php

    $paymentActions = array('payments');

	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $paymentActions))
			include 'Controller/PaymentController.php';
		if($action == 'payments')
			$response = actionPayments();
		
	
	}

?>