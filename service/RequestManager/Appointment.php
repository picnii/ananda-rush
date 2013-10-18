<?php
	$appointActions = array('appoint', 'createAppoint','deleteAppointLog', 'updateAppointLog', 'appointTest', 'getAppointmentPaymentTypes', 'reportAppoinment', 'getAppointAuthorizeStatus');

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
		if($action == 'getAppointAuthorizeStatus')
			$response = actionGetAppointAuthorizeStatus();
		if($action == 'reportAppoinment')
		{
			require_once 'Controller/ReportController.php';
			$response = actionReportAppoinment();
		}

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