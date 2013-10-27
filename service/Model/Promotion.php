<?php

function findAllPromotionFromItemId($itemId)
{
	$pro_ax = findPromotionAx($itemId);
	$pro_preapprove = findAllPromotionsPreapprove($itemId);
	$promotions = array();
	/*foreach($pro_ax as $ax)
	{
		array_push($promotions, $ax);
	}*/
	foreach ($pro_preapprove as $pro) {
		array_push($promotions, $pro);
	}
	return $promotions;
}


function findPromotionAx($itemId)
{
	//always baht
	//back cash = ? 
	//PromotionAx
	$sql = "SELECT * FROM Promotion_AX 
		INNER JOIN 
	";

}

function findAllPromotionsAxByRecId($rec_id)
{
	return array();
}


function findAllPromotionsPreapprove($itemId, $invoiceAccount)
{
	/*
	* INNER JOIN เช่อมืี่บอก Type
	 LEFT JOIN เชื่อม Payment ON type = 4
	*/
	//table: PreapPromo
	 // LEFT JOIN Goods_Promotion ON Goods_Promotion.id_item_promotion = Item_Promotion.id_item_promotion
	$sql = "SELECT *  FROM PreapPromo
	 INNER JOIN Master_Promotion ON Master_Promotion.id_promotion = PreapPromo.id_promotion
	 LEFT JOIN master_promotion_item ON master_promotion_item.id_promotion = Master_Promotion.id_promotion
	 LEFT JOIN Item_Promotion ON Item_Promotion.id_item_promotion = master_promotion_item.id_item_promotion
	 LEFT JOIN Discount_Promotion ON Discount_Promotion.id_item_promotion = master_promotion_item.id_item_promotion 

	 WHERE itemID = '$itemId' AND InvoiceAccount = '$invoiceAccount'";
	$result = DB_query($GLOBALS['connect'],converttis620($sql));
//	echo $sql;
	$promotions = array();
	while($row = DB_fetch_array($result))
	{
		$promotion = convertPromotionPreApproveRowToPromotion($row);
		array_push($promotions, $promotion);
	}
	return $promotions;
}

function findAllPromotionsPreapproveById($id)
{

}

function findAllPromotionPreapproveFromAppoinmentId($appointment_id)
{
	$sql = "SELECT * FROM tranfer_appointment_promotion 
		INNER JOIN PreapPromo ON PreapPromo.id_pro_pre = tranfer_appointment_promotion.promotion_id
		INNER JOIN Master_Promotion ON Master_Promotion.id_promotion = PreapPromo.id_promotion
		LEFT JOIN master_promotion_item ON master_promotion_item.id_promotion = Master_Promotion.id_promotion
		LEFT JOIN Item_Promotion ON Item_Promotion.id_item_promotion = master_promotion_item.id_item_promotion
		LEFT JOIN Discount_Promotion ON Discount_Promotion.id_item_promotion = master_promotion_item.id_item_promotion 
		
		WHERE
			appointment_id = '$appointment_id'
	";	
	//echo $sql;
	$result = DB_query($GLOBALS['connect'],converttis620($sql));
	$promotions = array();
	while($row = DB_fetch_array($result))
	{

		$promotion = convertPromotionPreApproveRowToPromotion($row);
		array_push($promotions, $promotion);
	}
	
	return $promotions;
	
}


function convertPromotionRowAxToPromotion($row)
{
	$promotion = new stdClass;
	$promotion->row = $row;
	$promotion->type = getPromotionType('Ax');
	foreach($row as $key => $value)
	{
		$promotion->$key = $value;
	}
	return $promotion;
}

function convertPromotionPreApproveRowToPromotion($row)
{
	$promotion = new stdClass;
	
	$promotion->type = getPromotionType('Preapprove');
	$promotion->type_name = 'pre_approve';

	foreach($row as $key => $value)
	{
		
		if(is_string($value))
		{
			$promotion->$key = convertutf8($value);
		}else
			$promotion->$key = $value;
	}
	//$promotion->row = $row;
	$promotion->id = $promotion->id_pro_pre;
	if(isRowSpacialDiscount($promotion))
		$promotion->spacial_discount = $promotion->Price;
	else
		$promotion->spacial_discount = 0;
	if($promotion->id_type_Price == getPromotionPreapproveType("%"))
		$promotion->is_discount_percent = true;
	else
		$promotion->is_discount_percent = false;

	if(isset($promotion->Code_item))
		$promotion->payment_id = $promotion->Code_item;
	else
		$promotion->payment_id = null;
	return $promotion;
}

function isRowSpacialDiscount($promotion)
{
	if($promotion->id_Type_item_promotion == 4 || $promotion->id_Type_item_promotion == 5)
	{
		return true;
	}
		return false;
}


function getPromotionTypes()
{
	return array(
		0 => "Ax",
		1 => "Preapprove"
	);
}

function getPromotionType($type)
{
	$types = getPromotionTypes();
	$hashT = array();
	foreach ($types as $key => $value) {
		# code...
		$hashT[$value]  = $key;
	}
	return $hashT[$type];
}

function getPromotionPreapproveTypes()
{
	return array(
		1 => "เงินสด",
		2 => "%"
	);
}

function getPromotionPreapproveType($typename)
{
	$types = getPromotionPreapproveTypes();
	$hashT = array();
	foreach ($types as $key => $value) {
		# code...
		$hashT[$value]  = $key;
	}
	return $hashT[$typename];
}

function getPromotionItemPreapproveTypes()
{
	return array(
		1 => "ส่วนลด",
		2 => "สิ่งของ",
		3 => "Cash Back",
		4 => "ส่วนลดค่าใช้จ่ายวันโอน",
		5 => "ส่วนลดพิเศษ"
	);
}

function createUnitPromotion($unit_id, $promotion_id, $promotion_type)
{

}

function getSelectedPromotions($unit_id)
{

}

/**
*  New Era Promotion
*/

function createPromotion($name, $type, $amount, $option1, $option2)
{
	$sql = "INSERT INTO promotion_master (name, reward_id, amount, option1, option2)
			VALUES ('$name', {$type->id}, $amount, '$option1', '$option2');
			SELECT SCOPE_IDENTITY() as ins_id;
	";
	//echo $sql;
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	$row = DB_fetch_array($result);
	if($result)
	{
		return $row['ins_id'];
	}else
		return false;
	/*if($result){
        sqlsrv_next_result($result); 
        sqlsrv_fetch($result); 
        $promotion_id = sqlsrv_get_field($result, 0);
        return $promotion_id; 
    }else
     	return false;*/

}

function findPromotionById($id)
{
	$sql = "SELECT * FROM promotion_master WHERE id = $id";
	//echo $sql;
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	$row = DB_fetch_array($result);
	$row['name'] = convertutf8($row['name'] );
	if($row)
		return $row;
	else
		return null;
}

function findAllPromotion()
{
	$sql = "SELECT * FROM promotion_master";
	$promotions = array();
	$result = DB_query($GLOBALS['connect'], convertutf8($sql));
	while($row = DB_fetch_array($result))
	{
		$row['name'] = convertutf8($row['name']);
		array_push($promotions, $row);
	}
	return $promotions;
}

function findAllPromotionAx()
{
	$sql = "SELECT Promotion_AX.*, promotion_ax_type.type_id FROM Promotion_AX LEFT JOIN promotion_ax_type on promotion_ax_type.id = Promotion_AX.RECID";

	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	$answer = array();
	while($row = DB_fetch_array($result))
	{
		$row['ITEMNAME'] = convertutf8($row['ITEMNAME']);
		array_push($answer, $row);
	}
	return $answer;
}

function findAllPromotionAxByItemId($itemId)
{
	//$sql = "SELECT Promotion_AX.*, promotion_ax_type.type_id FROM Promotion_AX LEFT JOIN promotion_ax_type on promotion_ax_type.id = Promotion_AX.RECID  WHERE ITEMID = '{$itemId}'";
	$sql = "SELECT Promotion_AX.*, promotion_ax_type.type_id, promotion_ax_type.issue FROM Promotion_AX INNER JOIN Sale_Transection ON Sale_Transection.SO = Promotion_AX.SO  LEFT JOIN promotion_ax_type on promotion_ax_type.id = Promotion_AX.RECID  WHERE Sale_Transection.ITEMID = '{$itemId}'";
//	echo $sql;	
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	$answer = array();
	while($row = DB_fetch_array($result))
	{
		$promotion = new stdClass;
		$promotion->id = $row['RECID'];
		$promotion->name = convertutf8( $row['ITEMNAME'] );
		$promotion->amount = $row['Cash Promotion'];
		$promotion->is_select = $row['SELECT PROMOTION'];
		$promotion->quantity = $row['QTY'];
		$promotion->issue = $row['issue'];
		$promotion->type_id = $row['type_id'];
		array_push($answer, $promotion);
	}
	return $answer;	
}
	
function findPromotionAxTypeById($id)
{
	$sql = "SELECT * FROM promotion_ax_type WHERE id = '{$id}'";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	$row = DB_fetch_array($result);
	if(isset($row['id']))
		return $row;
	else
		return false;
}

function createPromotionAxType($reqBody)
{
	$answer = array();
	foreach ($reqBody->promotions as $key => $promotion) {
		# code...
		if(!findPromotionAxTypeById($promotion->RECID))
			$sql = "INSERT INTO promotion_ax_type (id, type_id, option1, option2) VALUES('{$promotion->RECID}', {$promotion->type_id}, '{$promotion->option1}', '{$promotion->option2}')";
		else
			$sql = "UPDATE promotion_ax_type SET type_id = {$promotion->type_id}, option1 = '{$promotion->option1}', option2 = '{$promotion->option2}' WHERE id = '{$promotion->RECID}' ";




		$result = DB_query($GLOBALS['connect'], converttis620($sql));
		array_push($answer, $result);
	}
	return $answer;
	
}

function deletePromotionAxType($reqBody)
{
	$answer = array();
	foreach ($reqBody->promotions as $key => $promotion) {
		# code...
		$sql = "DELETE FROM promotion_ax_type WHERE id = '{promotion->RECID}'";
		$result = DB_query($GLOBALS['connect'], converttis620($sql));
		array_push($answer, $result);
	}
	return $answer;
}

function updatePromotion($id, $name, $type,  $amount, $option1, $option2)
{
	$sql = "UPDATE promotion_master SET name = '$name', reward_id = {$type->id}, amount = $amount, option1 = '$option1', option2 = '$option2' WHERE id = $id";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	if($result)
		return true;
	else
		return false;
}

function deletePromotionById($id)
{
	$sql = "DELETE FROM promotion_master WHERE id = $id";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	if($result)
		return true;
	else
		return false;
}	

function createCondition($promotion_id, $condition)
{
	$sql = "INSERT INTO promotion_condition (promotion_id, project_id, phase_id, date_from, date_to)
			VALUES ({$promotion_id}, {$condition->project_id}, {$condition->phase_id}, '{$condition->from}', '{$condition->to}');
			SELECT SCOPE_IDENTITY() as ins_id;
	";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	if($result){
      /*  sqlsrv_next_result($result); 
        sqlsrv_fetch($result); 
        $promotion_id = sqlsrv_get_field($result, 0);
        return $promotion_id; */
        $row = DB_fetch_array($result);
        return $row['ins_id'];
    }else
     	return false;
}

function updateCondition($id, $promotion_id, $condition)
{
	$sql = "UPDATE promotion_condition SET promotion_id = {$promotion_id}, project_id = {$condition->project_id}, phase_id = {$condition->phase_id}, date_from = '{$condition->from}', date_to = '{$condition->to}' WHERE id = $id";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	if($result)
		return true;
	else
		return false;
}

function findConditionById($id)
{
	$sql = "SELECT * FROM promotion_condition LEFT JOIN promotion_master ON promotion_master.id = promotion_id WHERE promotion_condition.id = {$id}";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	$row = DB_fetch_array($result);
	if($row)
		return $row;
	else
		return null;
}

function deleteConditionById($id)
{
	$sql = "DELETE FROM promotion_condition WHERE id = {$id}";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	if($result)
		return true;
	else
		return false;
}

function findAllCondition()
{
	$conditions = array();
	$sql = "SELECT promotion_condition.*, promotion_master.name, promotion_master.reward_id, 
			promotion_master.amount, promotion_master.option1, promotion_master.option2, master_project.* 
			FROM promotion_condition LEFT JOIN promotion_master ON promotion_id = promotion_master.id 
			LEFT JOIN master_project ON project_id = master_project.proj_id";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	while($row = DB_fetch_array($result))
	{
		$row['name'] = convertutf8($row['name']);
		array_push($conditions, $row);
	}
	return $conditions;
}


function findMatchPromotion($condition, $join = false)
{
	$answers = array();
	if(isset($condition->id))
	{
		return $answers;
	}else if(isset($condition->unit_id))
	{
		$where_sql = "WHERE unit_id = {$condition->unit_id}";
		$sql = "SELECT promotion_condition_unit.*, promotion_master.reward_id as type_id, promotion_master.option1, promotion_master.option2, promotion_master.name FROM promotion_condition_unit LEFT JOIN promotion_condition ON promotion_condition.id = promotion_condition_unit.condition_id LEFT JOIN promotion_master ON promotion_master.id = promotion_condition.promotion_id {$where_sql}";
		
		$result = DB_query($GLOBALS['connect'], converttis620($sql));
		while($row = DB_fetch_array($result))
		{
			$row['name'] = convertutf8($row['name']);
		
			array_push($answers, $row);
		}
		return $answers;
	}else
	{
		$conditions = array();
		$sql = "SELECT promotion_condition_unit.*, promotion_master.reward_id as type_id, promotion_master.option1, promotion_master.option2, promotion_master.name FROM promotion_condition_unit LEFT JOIN promotion_condition ON promotion_condition.id = promotion_condition_unit.condition_id LEFT JOIN promotion_master ON promotion_master.id = promotion_condition.promotion_id";

		$result = DB_query($GLOBALS['connect'], converttis620($sql));
		while($row = DB_fetch_array($result))
		{
			$row['name'] = convertutf8($row['name']);
			//print_r($row);
			array_push($answers, $row);
		}
		return $answers;
	}
}

function matchPromotion($condition_id, $unit_ids)
{
	$result_ids = array();
	$condition = findConditionById($condition_id);
	
	for($i =0; $i < count($unit_ids); $i++)
	{
		$unit_id = $unit_ids[$i];
		
		$amount = $condition['amount'];
		$sql = "INSERT INTO promotion_condition_unit(condition_id, unit_id, amount) 
			VALUES({$condition_id}, {$unit_id}, {$amount});SELECT SCOPE_IDENTITY() as ins_id;";
		$result = DB_query($GLOBALS['connect'], converttis620($sql));
		if($result){
	        //sqlsrv_next_result($result); 
	        //sqlsrv_fetch($result); 
	        $row = DB_fetch_array($result);
	        $create_id = $row['ins_id'];
	        array_push($result_ids,  $create_id);
	    }
	}
	return $result_ids;
}

function unMatchPromotion($ids)
{
	for($i =0; $i <count($ids); $i++)
	{
		$id = $ids[$i];
		$sql = "DELETE FROM promotion_condition_unit WHERE id = {$id} ";
		$result = DB_query($GLOBALS['connect'], converttis620($sql));
		if($result)
			continue;
		else
			return false;
	}
	return true;
}

function unMatchPromotionByConditionId($condition_id){
	$sql = "DELETE FROM promotion_condition_unit WHERE condition_id = {$condition_id} ";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	if($result)
		return true;
	else
		return false;
}

function getCountConditionUnit($condition_id){
	$sql = "SELECT COUNT(*) AS rows FROM promotion_condition_unit WHERE condition_id = {$condition_id}";
	
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	$row = DB_fetch_array($result);
	if($row)
		return $row['rows'];
	else
		return 0;
}

function findUnitByPromotionConditionId($condition_id){
	$units = array();
	$sql = "SELECT unit_id FROM promotion_condition_unit WHERE condition_id = {$condition_id}";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	while($row = DB_fetch_array($result))
	{
		array_push($units, $row['unit_id']);
	}
	return $units;
}
//for bill
function findAllPromotionFromUnitId($id)
{
	
	//convertPromotionData
	$condition = new stdClass;
	$condition->unit_id = $id;


	$answer = array();
	$promotions = findMatchPromotion($condition);
	
	foreach ($promotions as $key => $promotion) {
		# code...

		if($promotion['is_select'] || $promotion['issue'])
			array_push($answer, convertPromotionData($promotion));
	}

	$unit = findUnitById($id);
	$item_id = $unit->item_id;

	$promotion_ax_types = findAllPromotionAxByItemId($item_id);
	foreach ($promotion_ax_types as $key => $promotion) {
		//print_r($promotion);
		$promotion = objectToArray($promotion);
		if($promotion['is_select'] || $promotion['issue'])
			array_push($answer, convertPromotionData($promotion));
	}
	//print_r($answer);
	/*
//$promotion->row = $row;
	$promotion->id = $row['id'];
	if($promotion->type->id == $types['spacial']->id || $promotion->type->id == $types['discount']->id )
		$promotion->spacial_discount = $row['amount'];
	else
		$promotion->spacial_discount = 0;

	if($promotion->type->id == $types['discount']->id && $payment_types['percent']->id == $row['option2'])
		$promotion->is_discount_percent = true;
	else
		$promotion->is_discount_percent = false;

	if($promotion->type->id == $types['discount']->id)
		$promotion->payment_id = $row['option1'];
	else
		$promotion->payment_id = null;

	*/
	//$answer = $answer[0];
	//print_r($answer);

	
	return objectToArray($answer);
}

function findAllPromotionPreapproveFromItemId($itemId)
{
	$sql = "SELECT * FROM PreapPromo WHERE ItemID = '{$itemId}'";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	$promotions = array();
	while($row = DB_fetch_array($result))
	{

		$promotion = findPromotionById($row['id_promotion']);
		$answer = new stdClass;
		$answer->id = $row['id_pro_pre'];
		$answer->item_id = $row['itemID'];
		$answer->name = $promotion['name'];
		$answer->preapprove_id = $row['id_preapprove'];
		$answer->amount = $row['amount'];
		$answer->issue = $row['issue'];
		$answer->is_select = $row['is_select'];
		array_push($promotions, $answer);
	}

	return $promotions;
}

function updatePromotionPreapprove($promotion_id, $is_select, $issue)
{
	$sql = "UPDATE PreapPromo SET PreapPromo.is_select = {$is_select}, issue = {$issue} WHERE id_pro_pre = $promotion_id";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	return $sql;
}

function updatePromotionAx($rec_id,$is_select,$issue)
{
	$sql = "UPDATE Promotion_AX SET [SELECT PROMOTION] = {$is_select} WHERE RECID = {$rec_id}";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));

	$sql2 = "UPDATE promotion_ax_type SET issue = {$issue} WHERE id = {$rec_id}";
	$result = DB_query($GLOBALS['connect'], converttis620($sql2));

	return "sql1 = {$sql}, sql2 = {$sql2}";
}

function updatePromotionTranfer($promotion_id, $is_select, $issue)
{
	$sql = "UPDATE promotion_condition_unit SET is_select = {$is_select}, issue = {$issue} WHERE id = $promotion_id";
	$sql2 = "UPDATE promotion_ax_type SET issue = {$issue} WHERE id = {$promotion_id}";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	$result = DB_query($GLOBALS['connect'], converttis620($sql2));
	return $sql;
}

function createPromotionConfirmLog($name, $amount, $unit_id, $type, $promotion_ref_type, $promotion_ref_id, $option1='', $option2='')
{
	$sql = "INSERT INTO promotion_confirm_log (name, amount, unit_id, create_time, type, promotion_ref_type, promotion_ref_id, option1, option2)
			VALUES ( '{$name}', {$amount}, {$unit_id}, GETDATE(), $type, $promotion_ref_type, $promotion_ref_id, '{$option1}', '$option2')
			;SELECT SCOPE_IDENTITY() AS ins_id;
	";
	//echo $sql;
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	$row = DB_fetch_array($result);
	return $row['ins_id'];
}

function createPromotionConfirm($log_id, $unit_id, $type, $promotion_ref_id, $promotion_ref_type)
{
	
	$sql ="IF EXISTS (SELECT * FROM promotion_confirm WHERE unit_id='$unit_id' AND promotion_ref_id = $promotion_ref_id AND promotion_ref_type = $promotion_ref_type)
	    UPDATE promotion_confirm SET promotion_confirm_log_id = $log_id WHERE unit_id='$unit_id' AND promotion_ref_id = $promotion_ref_id AND promotion_ref_type = $promotion_ref_type
	ELSE
	    INSERT INTO promotion_confirm (promotion_confirm_log_id, unit_id, type, promotion_ref_id, promotion_ref_type)
			VALUES ($log_id, $unit_id, $type, $promotion_ref_id, $promotion_ref_type);";
	
	$result = DB_query($GLOBALS['connect'], converttis620($sql));

	return $result;
}

function deletePromotionConfirm( $unit_id, $promotion_ref_id, $promotion_ref_type)
{
	$sql = "DELETE FROM promotion_confirm WHERE unit_id='$unit_id' AND promotion_ref_id = $promotion_ref_id AND promotion_ref_type = $promotion_ref_type";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	return $result;
}

function findPromotionConfirm( $unit_id, $promotion_ref_id, $promotion_ref_type)
{
	$sql = "SELECT * FROM promotion_confirm WHERE unit_id='$unit_id' AND promotion_ref_id = $promotion_ref_id AND promotion_ref_type = $promotion_ref_type";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	$row = DB_fetch_array($result);
	return $row;
}

function getPromotionRewardTypes($is_array = false)
{

	$types = array();
	//$item = array('id' => 0,  'code'=> 'stuff', 'name' => 'สิ่งของ');
	$item = new stdClass;
	$item->id =0;
	$item->code ='stuff';
	$item->name = 'สิ่งของ';
	
	$types[$item->id] = $item;
	if(!$is_array)
		$types[$item->code] = $item;
	$item = new stdClass;
	$item->id =1;
	$item->code ='cashback';
	$item->name = 'CashBack';
	$types[$item->id] = $item;
	if(!$is_array)
		$types[$item->code] = $item;
	$item = new stdClass;
	$item->id =2;
	$item->code ='discount';
	$item->name = 'ส่วนลด';
	$types[$item->id] = $item;
	if(!$is_array)
		$types[$item->code] = $item;
	$item = new stdClass;
	$item->id =4;
	$item->code ='spacial';
	$item->name = 'ส่วนลดพิเศษ';
	$types[$item->id] = $item;
	if(!$is_array)
		$types[$item->code] = $item;

	return $types;
}

function getPhases($is_array = false)
{
	$phases = array();
	$phase = new stdClass;
	$phase->id =0;
	$phase->code ='ax';
	$phase->name = 'Sale';
	$phases[$phase->id] = $phase;
	if(!$is_array)
		$phases[$phase->code] = $phase;

	$phase = new stdClass;
	$phase->id =1;
	$phase->code ='preapprove';
	$phase->name = 'Pre Approve';
	$phases[$phase->id] = $phase;
	if(!$is_array)
		$phases[$phase->code] = $phase;

	$phase = new stdClass;
	$phase->id =2;
	$phase->code ='tranfer';
	$phase->name = 'Tranfer';
	$phases[$phase->id] = $phase;
	if(!$is_array)
		$phases[$phase->code] = $phase;

	return $phases;
}

function getDiscountTypes($is_array = false)
{
	$types = array();
	$type = new stdClass;
	$type->id = 0;
	$type->code = 'fix';
	$type->name = "Fix";
	$types[$type->id] = $type;
	if(!$is_array)
		$types[$type->code] = $type;

	$type = new stdClass;
	$type->id = 1;
	$type->code = 'percent';
	$type->name = "Percent";
	$types[$type->id] = $type;
	if(!$is_array)
		$types[$type->code] = $type;

	return $types;

}

function convertPromotionData($row)
{
	$promotion = new stdClass;
	$types = getPromotionRewardTypes();
	$payment_types = getDiscountTypes();
	$promotion->type = $types[$row['type_id']];//getPromotionType('Preapprove');
	$ax_type= getPromotionType('Ax');
	$promotion->type_name =  $promotion->type->name;


	foreach($row as $key => $value)
	{
		
		if(is_string($value) && !mb_detect_encoding($value,'utf8'))
		{
			$promotion->$key = convertutf8($value);
		}else
			$promotion->$key = $value;
	}
	//$promotion->row = $row;
	$promotion->id = $row['id'];
	if($promotion->type->id == $types['spacial']->id || $promotion->type->id == $types['discount']->id )
		$promotion->spacial_discount = $row['amount'];
	else
		$promotion->spacial_discount = 0;

	if($promotion->type->id == $types['discount']->id && $payment_types['percent']->id == $row['option2'])
		$promotion->is_discount_percent = true;
	else
		$promotion->is_discount_percent = false;

	if($promotion->type->id == $types['discount']->id)
		$promotion->payment_id = $row['option1'];
	else
		$promotion->payment_id = null;

	return $promotion;
}

?>