<?php

function findAllPromotionFromItemId($itemId)
{
	$pro_ax = findPromotionAx($itemId);
	$pro_preapprove = findAllPromotionsPreapprove($itemId);
	$promotions = array();
	foreach($pro_ax as $ax)
	{
		array_push($promotions, $ax);
	}
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
	 WHERE itemID = '$itemId' AND InvoiceAccount = '$invoiceAccount'";
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
	$promotion->row = $row;
	$promotion->type = getPromotionType('Preapprove');
	foreach($row as $key => $value)
	{
		$promotion->$key = $value;
	}
	return $promotion;
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

?>