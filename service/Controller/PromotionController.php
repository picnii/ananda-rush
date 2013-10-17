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

	function actionFindPromotion($id)
	{
		//$answer = new stdClass;
		return findPromotionById($id);
		//return $answer;
	}

	function actionUpdatePromotion($id, $promotion)
	{
		$answer = new stdClass;
		$types = getPromotionRewardTypes();
		if($types['discount']->id == $promotion->type->id)
			$answer->promotion_id = updatePromotion(
				$id,
				$promotion->name,
				$promotion->type,
				$promotion->amount,
				$promotion->payment->id,
				$promotion->paymentType->id
			);
		else if($types['stuff']->id == $promotion->type->id)
			$answer->promotion_id = updatePromotion(
				$id,
				$promotion->name,
				$promotion->type,
				$promotion->amount,
				$promotion->item,
				null
			);
		else
			$answer->promotion_id = updatePromotion(
				$id,
				$promotion->name,
				$promotion->type,
				$promotion->amount,
				null,
				null
			);
		return $answer;
	}

	function actionDeletePromotion($promotion_id)
	{
		$answer = new stdClass;
		$answer->delete = deletePromotionById($promotion_id);
		return $answer;
	}

	function actionCreateCondition($promotion_id, $condition)
	{
		$answer = new stdClass;
		$answer->condition_id = createCondition($promotion_id, $condition);
		return $answer;
	}

	function actionDeleteCondition($condition_id)
	{
		$answer = new stdClass;
		$answer->unmatch = unMatchPromotionByConditionId($condition_id);
		$answer->delete = deleteConditionById($condition_id);
		return $answer;
	}

	function actionConditions()
	{
		return findAllCondition();
	}

	function actionMatchPromotion($condition_id, $unit_ids)
	{
		return matchPromotion($condition_id, $unit_ids);
	}

	function actionFindAllUnitPromotion($condition_id)
	{
		$answer = new stdClass;
		$answer->units = findUnitByPromotionConditionId($condition_id);
		return $answer;
	}

	function actionCountAllUnitPromotion($condition_id)
	{

		$answer = new stdClass;
		$answer->count = getCountConditionUnit($condition_id);
		return $answer;
	}

	function actionFindAllPromotionAx()
	{
		return findAllPromotionAx();
	}

	function actionFindAllPromotionAxByItemId($itemId)
	{
		return findAllPromotionAxByItemId($itemId);
	}



	function actionCreatePromotionAx($reqBody)
	{
		$answer = new stdClass;
		$answer->reqBody = $reqBody;
		$answer->result =  createPromotionAxType($reqBody);
		return $answer;
	}

	function actionDeletePromotionAx($reqBody)
	{
		return deletePromotionAxType($reqBody);
	}

	function actionFindAllPromotionFromCondition($getParams)
	{
		$condition = new stdClass;
		if(isset($getParams['unit_id']))
			$condition->unit_id = $getParams['unit_id'];
		return findMatchPromotion($condition, true);
	}

	function actionTestPromotion()
	{
		$condition = new stdClass;
		$condition->unit_id = 1302;
		return findMatchPromotion($condition);
	}

	function actionFindAllPrePromotionFromItemId($itemId)
	{
		return findAllPromotionPreapproveFromItemId($itemId);
	}

	function actionUpdatePrePromotion($promotion)
	{
		return updatePromotionPreapprove($promotion->id, $promotion->is_select, $promotion->issue);
	}

	function actionUpdateTranferPromotion($promotion)
	{
		return updatePromotionTranfer($promotion->id, $promotion->is_select, $promotion->issue);
	}

	function actionCreateConfirmPromotion($promotion)
	{
		$log_id =  createPromotionConfirmLog($promotion->name, $promotion->amount, $promotion->unit_id, $promotion->type, $promotion->ref_type, $promotion->ref_id, $promotion->option1, $promotion->option2);
		$create_id = createPromotionConfirm($log_id, $promotion->unit_id, $promotion->type, $promotion->ref_id, $promotion->ref_id);
		//update issue
		return $create_id;		
	}

	function actionDeleteConfirmPromotion($promotion)
	{
		deletePromotionConfirm( $promotion->unit_id, $promotion->ref_id, $promotion->ref_type);
		//update issue
	}

	function actionIsConfirmPromotion($promotion)
	{
		$proConfirm = findPromotionConfirm($promotion->unit_id, $promotion->ref_id, $promotion->ref_type);
		$answer = new stdClass;
		$answer->promotion = $proConfirm;
		$answer->result = false;
		if(isset($proConfirm['unit_id']))
			$answer->result = true;
		
		return $answer;
	}
?>