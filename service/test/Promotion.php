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


?>