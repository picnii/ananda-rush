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
	//table: PreapPromo
	$sql = "SELECT * FROM PreapPromo
	 INNER JOIN Master_Promotion ON Master_Promotion.id_promotion = PreapPromo.id_promotion
	 INNER JOIN master_promotion_item ON master_promotion_item.id_promotion = Master_Promotion.id_promotion
	 INNER JOIN Item_Promotion ON Item_Promotion.id_item_promotion = master_promotion_item.id_item_promotion
	 WHERE itemID = '$itemId' AND InvoiceAccount = '$invoiceAccount'";
	$result = DB_query($GLOBALS['connect'],converttis620($sql));
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
?>