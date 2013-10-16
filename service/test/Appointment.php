<?php

$types = getAppointmentTypes();
$payment_types = getAppointmentPaymentTypes();
$payment_status = getAppointmentStatus();
$payment_coming_status = getAppointmentComingStatus();

$rand_selected = 0;//rand(0, count($types)-1);



$transaction_id = 1835;
$type =  1;
$call_time = "2013-08-20 01:09:54.0";
$appoint_time = "2013-08-20 01:09:54.0";
$call_duration = "20:34";
$status = 1;
$payment_type = 1;
$coming_status = 1;
$remark = "I dont know that should i type";
$people = "sompop";
$contract_time = "2013-08-20 01:09:54.0";
$payment_time = "2013-08-20 01:09:54.0";
$authorize = 0;

$create_log_id = createAppointmentLog($transaction_id, $type, $call_time, 
	$appoint_time, $status, $payment_type, 
	$coming_status, $remark, $people, $call_duration, $authorize, $payment_time, $contract_time);

assertEquals(true, is_numeric($create_log_id));

$appoint_log = findAppointmentLogById($create_log_id);
assertEquals($transaction_id, $appoint_log->transaction_id);
assertEquals($type, $appoint_log->type);
assertEquals($call_time, date("Y-m-d H:i:s.0", date_normalizer($appoint_log->call_time) ) );
assertEquals($appoint_time, date("Y-m-d H:i:s.0", date_normalizer($appoint_log->appoint_time)) );
assertEquals($call_duration, $appoint_log->call_duration);
assertEquals($status, $appoint_log->status);
assertEquals($payment_type, $appoint_log->payment_type);
assertEquals($coming_status, $appoint_log->coming_status);
assertEquals($remark, $appoint_log->remark);
assertEquals($people, $appoint_log->people);

$delete_result = deleteAppointmentLog($create_log_id);
assertEquals(true, $delete_result);

$result = createAppointment($transaction_id, $type, $call_time, $appoint_time, $status, $payment_type, $coming_status, $remark, $people, $call_duration, 0, $payment_time, $contract_time);
assertEquals(true, $result );

$appoint = findAppointmentByUnitId($transaction_id);
assertEquals($transaction_id, $appoint->transaction_id);
assertEquals($type, $appoint->type);
assertEquals($call_time, date("Y-m-d H:i:s.0", date_normalizer($appoint->call_time) ) );
assertEquals($appoint_time, date("Y-m-d H:i:s.0", date_normalizer($appoint->appoint_time)) );
assertEquals($call_duration, $appoint->call_duration);
assertEquals($status, $appoint->status);
assertEquals($payment_type, $appoint->payment_type);
assertEquals($coming_status, $appoint->coming_status);
assertEquals($remark, $appoint->remark);
assertEquals($people, $appoint->people);

$logs = findAllAppointmentLogByUnitId($transaction_id);
//print_r($logs);

//print_r($create_log_id);
?>