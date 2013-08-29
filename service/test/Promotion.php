<?php

	$units = findAllUnits();
	$unit_test = $units[3];
	//print_r($unit_test->item_id);
	//print_r($unit_test);
	$promotions_raw = findAllPromotionsPreapprove('TH04C207009', 'C0013056');
	$promotions = array();
	foreach ($promotions_raw as $key => $value) {
		# code...
		$promotion =convertPromotionPreApproveRowToPromotion($value);
		array_push($promotions, $promotion);
	}
	print_r($promotions);
?>