<?php
	require_once('util.php');
	function actionPromotion($itemId, $invoiceAcount)
	{
		return findAllPromotionsPreapprove($itemId, $invoiceAcount);
	}

	function actionSelecPromotion($unit_id, $promotion_id, $type)
	{

	}

	/**
	* New Era
	*/

	function actionPromotionTypes()
	{
		//print_r(getPromotionRewardTypes());
		return getPromotionRewardTypes(true);
	}

	function actionPromotionPhases()
	{
		return getPhases(true);
	}

	function actionPromotionPaymentTypes()
	{
		return getDiscountTypes(true);
	}

	function actionCreatePromotion($promotion)
	{
		$answer = new stdClass;
		$types = getPromotionRewardTypes();
		if($types['discount']->id == $promotion->type->id)
			$answer->promotion_id = createPromotion(
				$promotion->name,
				$promotion->type,
				$promotion->amount,
				$promotion->payment->id,
				$promotion->paymentType->id
			);
		else if($types['stuff']->id == $promotion->type->id)
			$answer->promotion_id = createPromotion(
				$promotion->name,
				$promotion->type,
				$promotion->amount,
				$promotion->item,
				null
			);
		else
			$answer->promotion_id = createPromotion(
				$promotion->name,
				$promotion->type,
				$promotion->amount,
				null,
				null
			);
		return $answer;
	}

	function actionPromotions($q = "*")
	{
		return findAllPromotion();
	}


?>