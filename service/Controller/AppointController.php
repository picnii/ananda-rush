<?php
	require_once('util.php');
	//use for preview what bills will be liked
	function actionAppoint($itemId)
	{
		$units = findAllUnitsByQuery(array(
			'master_transaction.ItemId'=>$itemId
		));
		$unit = $units[0];
		$appoint = findAppointmentByUnitId($unit->id);
		$logs = findAllAppointmentLogByUnitId($unit->id);
		foreach($logs as $log)
		{
			$types = getAppointmentTypes();
			$log->type = $types[$log->type];

			$status = getAppointmentStatus();
			$log->status = $status[$log->status];

			$payment_types = getAppointmentPaymentTypes();
			$log->payment_type = $payment_types[$log->payment_type]->name;

			$coming_status = getAppointmentComingStatus();
			$log->coming_status = $coming_status[$log->coming_status];

			$authorizes = getAuthorizes();
			$log->authorize = $authorizes[$log->authorize];
		}
		$appointUser = array('name'=>  $unit->customer_name );
		 $lastIndex = count($logs) - 1;
		if($lastIndex  >= 0)
		{
			$appointUser = array('name' => $logs[$lastIndex.""]->people );
		}

		$data = array(
			'unit'=>$unit,
			'appoint'=>$appoint,
			'logs'=> $logs,
			'appointUser'=> $appointUser
		);
		return $data;
	}

	function actionAppointTest($unit_id)
	{
		$appoint = findAppointmentByUnitId($unit_id);
		$promotions =findAllPromotionPreapproveFromAppoinmentId($appoint->main_id);
		//$transaction = 
		return array(
			'appoint' => $appoint,
			'promotions' => $promotions
		);
	}

	function actionCreateAppoint($unit_id, $reqBody)
	{
		if(isset($reqBody->appoint_time))
			$reqBody->appoint_datetime_str = $reqBody->appoint_date.' '.$reqBody->appoint_time.'.0';
		else
			$reqBody->appoint_datetime_str = $reqBody->appoint_date.' 00:00:00.0';
		$reqBody->call_datetime_str = $reqBody->call_date.' '.$reqBody->call_time.'.0';
		$reqBody->payment_date_str = $reqBody->payment_date.' 00:00:00.0';
		$reqBody->contract_date_str = $reqBody->contract_date.' 00:00:00.0';
//		$result = $reqBody;
		$result = createAppointment($unit_id, $reqBody->type, $reqBody->call_datetime_str, $reqBody->appoint_datetime_str, $reqBody->status, $reqBody->payment_type, $reqBody->coming_status, $reqBody->remark, $reqBody->people, $reqBody->call_duration, $reqBody->authorize, $reqBody->payment_date_str, $reqBody->contract_date_str, $reqBody->tranfer_status);
		$result_promotion  = array();
		$appointment = findAppointmentByUnitId($unit_id);
		$appointment_id = $appointment->main_id;
		//deleteAppointmentLogPromotion($appointment_id);
		//w8 for tranfer a new code
		/*foreach ($reqBody->promotions as $promotion) {
			# code...
			array_push($result_promotion, createAppointmentLogPromotion($appointment_id, $promotion->id, $promotion->type)) ;
		}*/
		
		return array(
			'result'=>$result,
			'appoint_id'=>$appointment_id,
			'result_promotion' => $result_promotion,
			'reqBody'=>$reqBody,
			'appoint'=>$appointment
		);
	}

	function actionGetAppointPaymentTypes()
	{
		return getAppointmentPaymentTypes(true);	
	}

	function actionGetAppointAuthorizeStatus()
	{
		return getAuthorizeStatus();
	}


?>