<?php

function createAppointmentLog($transaction_id, $type, $call_time, $appoint_time, $status, $payment_type, $coming_status, $remark, $people, $call_duration)
{
	$sql ="INSERT INTO tranfer_appointment_log(transaction_id, type, call_time, appoint_time, status, payment_type, coming_status, remark, people, call_duration, create_time)  VALUES 
	('$transaction_id', '$type', '$call_time', '{$appoint_time}' ,'{$status}', '{$payment_type}', '{$coming_status}', '{$remark}', '{$people}', '{$call_duration}', GETDATE()); SELECT SCOPE_IDENTITY()";
	//echo $sql;
	$result = DB_query($GLOBALS['connect'],converttis620($sql));
	if($result){
        sqlsrv_next_result($result); 
        sqlsrv_fetch($result); 
        $created_id = sqlsrv_get_field($result, 0); 
        return $created_id;
    }else{
        return false;
    }
}

function createAppointment($transaction_id, $type, $call_time, $appoint_time, $status, $payment_type, $coming_status, $remark, $people, $call_duration)
{
	
	$create_log_id = createAppointmentLog($transaction_id, $type, $call_time, $appoint_time, $status, $payment_type, $coming_status, $remark, $people, $call_duration);
	$sql = "IF EXISTS (SELECT * FROM tranfer_appointment WHERE transaction_id='$transaction_id')
	    UPDATE tranfer_appointment SET log_id = '$create_log_id' WHERE transaction_id='$transaction_id'
	ELSE
	    INSERT INTO tranfer_appointment(log_id, transaction_id) VALUES ('$create_log_id', '$transaction_id')";
	
	$result = DB_query($GLOBALS['connect'],converttis620($sql));
	if($result){
        return true;
    }else{
        return false;
    }
}


function findAppointmentByUnitId($unit_id)
{
	$sql = "SELECT tranfer_appointment.id as main_id, * FROM tranfer_appointment  INNER JOIN tranfer_appointment_log  on tranfer_appointment.log_id = tranfer_appointment_log.id WHERE tranfer_appointment.transaction_id = '{$unit_id}'";
	$result = DB_query($GLOBALS['connect'],converttis620($sql));
	$row = DB_fetch_array($result);
	if(isset($row['id'])){
		$appoint = convertRowToAppoint($row);
		return $appoint;
	}
	else
		return false;
}

function findAppointmentLogById($id)
{
	$sql = "SELECT * FROM tranfer_appointment_log WHERE id = $id";
	//echo $sql;
	$result = DB_query($GLOBALS['connect'],converttis620($sql));
	$row = DB_fetch_array($result);
	if(isset($row['id'])){
		$appoint = convertRowToAppoint($row);
		return $appoint;
	}
	else
		return false;
}

function findAllAppointmentLogByUnitId($unit_id)
{
	$sql = "SELECT * FROM tranfer_appointment_log WHERE transaction_id = '$unit_id'";
	$result = DB_query($GLOBALS['connect'],converttis620($sql));
	//echo $sql;
	$appoints = array();
	
	while($row = DB_fetch_array($result))
	{
		$appoint = convertRowToAppoint($row);
		array_push($appoints, $appoint);
	}
	return $appoints;
}

function deleteAppointmentLog($appointment_id)
{
	$sql = "DELETE FROM tranfer_appointment_log WHERE id = $appointment_id";
	$result = DB_query($GLOBALS['connect'],converttis620($sql));
	if($result)
		return true;
	else
		return false;
}

function updateAppointmentLog($appointment_id)
{

}

function getAppointmentTypes()
{
	return array(
		0 => 'โทรนัด',
		1 => 'นัดเจอ',
		2 => 'Email'
	);
}

function getAppointmentStatus()
{
	return array(
		0 => 'ไม่รับโทรศัพท์',
		1 => 'รับโทรศัพท์'
	);
}

function getAppointmentPaymentTypes()
{
	return array(
		0 => 'โอนสด',
		1 => 'สินเชื่อ'
	);
}

function getAppointmentComingStatus()
{
	return array(
		0 => 'มานะ',
		1 => 'ไม่มา'
	);
}

function convertRowToAppoint($row)
{
	$appoint = new stdClass;
	foreach($row as $key => $value)
	{
		$appoint->$key = $value;
	}
	return $appoint;
}

function date_normalizer($d){ 
	if($d instanceof DateTime){ 
		return $d->getTimestamp(); 
	} else { 
		return strtotime($d); 
	} 
}


?>