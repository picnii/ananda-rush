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
			SELECT SCOPE_IDENTITY();
	";
	//echo $sql;
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	if($result){
        sqlsrv_next_result($result); 
        sqlsrv_fetch($result); 
        $promotion_id = sqlsrv_get_field($result, 0);
        return $promotion_id; 
    }else
     	return false;

}

function findPromotionById($id)
{
	$sql = "SELECT * FROM promotion_master WHERE id = $id";
	//echo $sql;
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	$row = DB_fetch_array($result);
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
			SELECT SCOPE_IDENTITY();
	";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	if($result){
        sqlsrv_next_result($result); 
        sqlsrv_fetch($result); 
        $promotion_id = sqlsrv_get_field($result, 0);
        return $promotion_id; 
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
	$sql = "SELECT * FROM promotion_condition WHERE id = {$id}";
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
	$sql = "SELECT * FROM promotion_condition";
	$result = DB_query($GLOBALS['connect'], converttis620($sql));
	while($row = DB_fetch_array($result))
	{
		array_push($conditions, $row);
	}
	return $conditions;
}

function findMatchPromotion($condition)
{
	if(isset($condition->id))
	{

	}else
	{

	}
}

function matchPromotion($condition_id, $unit_ids)
{
	$result_ids = array();
	for($i =0; $i < count($unit_ids); $i++)
	{
		$unit_id = $unit_ids[$i];
		$sql = "INSERT INTO promotion_condition_unit(condition_id, unit_id) 
			VALUES($condition_id, $unit_id);SELECT SCOPE_IDENTITY();";
		$result = DB_query($GLOBALS['connect'], converttis620($sql));
		if($result){
	        sqlsrv_next_result($result); 
	        sqlsrv_fetch($result); 
	        array_push($result_ids,  sqlsrv_get_field($result, 0));
	    }
	}
	return $result_ids;
}

function unMathPromotion($ids)
{
	for($i =0; $i <count($ids); $i++)
	{
		$id = $ids[$i];
		$sql = "DELETE FROM promotion_condition_unit WHERE id = {$id} ";
		$result = DB_query($GLOBALS['connect'], converttis620($sql));
		
	}
	
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

?>