<?php

function createAppointmentLog($transaction_id, $type, $call_time, $appoint_time, $status, $payment_type, $coming_status, $remark, $people, $call_duration, $authorize = 0, $payment_date, $contract_date, $tranfer_status)
{
	/*$sql ="INSERT INTO tranfer_appointment_log(transaction_id, type, call_time, appoint_time, status, payment_type, coming_status, remark, people, call_duration, create_time, authorize, payment_date, contract_date)  VALUES 
	('$transaction_id', '$type', '$call_time', '{$appoint_time}' ,'{$status}', '{$payment_type}', '{$coming_status}', '{$remark}', '{$people}', '{$call_duration}', GETDATE(), {$authorize}, {$payment_date}, {$contract_date}); SELECT SCOPE_IDENTITY()";*/
	//SELECT SCOPE_IDENTITY() AS ins_id
	//echo $sql;
	/*$result = DB_query($GLOBALS['connect'],converttis620($sql));
	if($result){
        sqlsrv_next_result($result); 
        sqlsrv_fetch($result); 
        $created_id = sqlsrv_get_field($result, 0); 
        return $created_id;
    }else{
        return false;
    }*/
    $sql ="INSERT INTO tranfer_appointment_log(transaction_id, type, call_time, appoint_time, status, payment_type, coming_status, remark, people, call_duration, create_time, authorize, payment_time, contract_time, tranfer_status)  VALUES 
	('$transaction_id', '$type', '$call_time', '{$appoint_time}' ,'{$status}', '{$payment_type}', '{$coming_status}', '{$remark}', '{$people}', '{$call_duration}', GETDATE(), {$authorize}, '{$payment_date}', '{$contract_date}' , {$tranfer_status}); SELECT SCOPE_IDENTITY() AS ins_id";
	//echo $sql;
	$result = DB_query($GLOBALS['connect'],converttis620($sql));
	$row = DB_fetch_array($result);
	if($result){
		return $row['ins_id'];
	}else
		return false;
}

function createAppointment($transaction_id, $type, $call_time, $appoint_time, $status, $payment_type, $coming_status, $remark, $people, $call_duration, $authorize = 0, $payment_time, $contract_time, $tranfer_status)
{
	
	$create_log_id = createAppointmentLog($transaction_id, $type, $call_time, $appoint_time, $status, $payment_type, $coming_status, $remark, $people, $call_duration , $authorize , $payment_time, $contract_time, $tranfer_status);
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

function findAppointmentLogPromotion($appointment_log_id, $promotion_id, $type)
{
	$sql = "SELECT * FROM tranfer_appointment_promotion WHERE appointment_id = '$appointment_log_id' AND promotion_id = '$promotion_id' AND promotion_type = '$type' ";
	$result = DB_query($GLOBALS['connect'],converttis620($sql));
	$row = DB_fetch_array($result);
	if(isset($row['id']))
		return $row;
	else
		return false;
}

function updateAppointmentLogPromotion($id, $appointment_log_id, $promotion_id, $type)
{

}

function deleteAppointmentLogPromotion($appointment_log_id)
{
	$sql = "DELETE FROM tranfer_appointment_promotion WHERE appointment_id = '$appointment_log_id'";
	$result = DB_query($GLOBALS['connect'],converttis620($sql));
	if($result)
		return true;
	else
		return false;
}

function createAppointmentLogPromotion($appointment_log_id, $promotion_id, $type)
{
	//need to change to insert or update
	$sql = "INSERT INTO tranfer_appointment_promotion(appointment_id, promotion_id, promotion_type)  VALUES 
	('$appointment_log_id', '$promotion_id', '$type'); SELECT SCOPE_IDENTITY()";
	//echo $sql;
	$search_row = findAppointmentLogPromotion($appointment_log_id, $promotion_id, $type);
	if($search_row)
		return $search_row['id'];
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



function getAppointmentTypes()
{
	return array(
		0 => 'โทรศัพท์',
		1 => 'ส่งข้อความ',
		2 => 'ส่งอีเมล์'
	);
}

function getAppointmentStatus()
{
	return array(
		0 => 'ไม่รับโทรศัพท์',
		1 => 'รับโทรศัพท์',
		2 => 'สายไม่ว่าง'
	);
}

function getAppointmentPaymentTypes($isArray = false)
{
	$sql = "SELECT * FROM status_preapprove";
	$result = DB_query($GLOBALS['connect'],converttis620($sql));
	$answer = array();
	while($row = DB_fetch_array($result))
	{
		$obj  = new stdClass;
		$obj->name = convertutf8( $row['name_status_preapprove'] );
		$obj->id = $row['id_status_preapprove'];
		if($isArray)
			array_push($answer, $obj);
		else
			$answer[$obj->id] = $obj;

	}


	// ดึงจาก Pre Approve
	return $answer;
}

function getAppointmentComingStatus()
{
	return array(
		0 => 'มาแน่นอน',
		1 => 'ไม่มา',
		2 => 'ยังไม่ตัดสินใจ'
	);
}

function convertRowToAppoint($row)
{
	$appoint = new stdClass;
	foreach($row as $key => $value)
	{
		if($key != 'call_time' && $key != 'appoint_time')
			$appoint->$key = convertutf8($value);
		else
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

function getAuthorizes()
{
	return array(
		0 => "ไม่มอบฉันทะ",
		1 => "มอบฉันทะ"
	);
}

function findPreApproveStatusByItemId($itemId)
{
	$sql = "SELECT name_status_preapprove FROM  preapprove INNER JOIN status_preapprove on status_preapprove.id_status_preapprove = preapprove.status_preapprove WHERE itemid = {$itemId}";
	$result = DB_query($GLOBALS['connect'],converttis620($sql));
	$row = DB_fetch_array($result);
	return $row['name_status_preapprove'];
}


function getAuthorizeStatus()
{
	$sql ="SELECT * FROM appointment_reason1";
	$result = DB_query($GLOBALS['connect'],converttis620($sql));
	$answers = array();
	while($row = DB_fetch_array($result))
	{
	//	print_r($row);
		$answer = new stdClass;
		$answer->name = convertutf8($row['appoint_reason1_name']);
		$answer->id = $row['appoint_reason1_id'];
		array_push($answers, $answer);
	}
	return $answers;
}
	
?>