<?php

	//print_r(getPromotionRewardTypes());

	$types = getPromotionRewardTypes();

	$type = $types['cashback'];
	

	$promotion_id = createPromotion('ส่วนลดนะจ๊ะ', $type, 5000, null, null);
	echo "proid => $promotion_id";
	assertEquals(is_numeric($promotion_id), true);

	$promotion = findPromotionById($promotion_id);
	assertEquals('ส่วนลดนะจ๊ะ', $promotion['name']);

	deletePromotionById($promotion_id);

	$type = $types['discount'];

	$promotion_id = createPromotion('ลดค่าโอน', $type, 5000, 21, 1);
	$promotion = findPromotionById($promotion_id);

	deletePromotionById($promotion_id);
	print_r($promotion);


	//createPromotionConfirmLog($name, $amount, $unit_id, $type, $promotion_ref_type, $promotion_ref_id, $option1='', $option2='')
	$log_id = createPromotionConfirmLog('test', 50, 1302, 1, 1, 12 );
	$confirm_id = createPromotionConfirm($log_id, 1302, 1, 12, 1) ;
	print_r(array(
		'conId' =>$confirm_id,
		'logId' => $log_id
	));
	//deletePromotionConfirm($log_id, 1302);
?>