<?php

	$units = findAllUnits();
	$unit_test = $units[3];
	//print_r($unit_test->item_id);
	//print_r($unit_test);invoiceAccount:C0009893
	$promotions_raw = findAllPromotionsPreapprove('TH01C106001', 'C0009893');
	$promotions = array();
	foreach ($promotions_raw as $key => $value) {
		# code...
		$promotion =convertPromotionPreApproveRowToPromotion($value);
		array_push($promotions, $promotion);
	}
	print_r($promotions);
?>