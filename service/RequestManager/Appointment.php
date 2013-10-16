<?php
	$appointActions = array('appoint', 'createAppoint','deleteAppointLog', 'updateAppointLog', 'appointTest', 'getAppointmentPaymentTypes');

	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $appointActions))
			require_once 'Controller/AppointController.php';
		if($action == 'appoint')
			$response = actionAppoint($_GET['itemId']);
		if($action == 'appointTest')
			$response = actionAppointTest($_GET['unit_id']);
		
		if($action == 'getAppointmentPaymentTypes')
			$response = actionGetAppointPaymentTypes();
	}

	if(isset($_POST['action']))
	{
		$action = $_POST['action'];
		if(gotAction($action, $appointActions))
			require_once 'Controller/AppointController.php';
		//if($action == 'createAppoint')
		//	$response = actionCreateAppoint();
	}

	if(isset($reqBody->action))
	{
		$action = $reqBody->action ;
		if(gotAction($action, $appointActions))
			require_once 'Controller/AppointController.php';
		if($reqBody->action == 'createAppoint')
		{
			$response = actionCreateAppoint($reqBody->unit_id, $reqBody);
		}
	}

?>