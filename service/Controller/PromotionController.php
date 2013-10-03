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

	function actionCreateCondition($promotion_id, $condition)
	{
		$answer = new stdClass;
		$answer->condition_id = createCondition($promotion_id, $condition);
		return $answer;
	}

	function actionDeleteCondition($condition_id)
	{
		return deleteConditionById($condition_id);
	}

	function actionConditions()
	{
		return findAllCondition();
	}

	function actionMatchPromotion($condition_id, $unit_ids)
	{
		return matchPromotion($condition_id, $unit_ids);
	}

?>