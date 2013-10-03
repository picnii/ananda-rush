<?php
	//
	//actionPromotion($itemId, $invoiceAcount)
    $promotionAction = array('promotions', 'selectPromotion', 'promotionTypes', 'promotionPhases', 'promotionPaymentTypes', 'createPromotion', 'listPromotions', 'createCondition', 'listConditions', 'deleteCondition', 'matchPromotion');

	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $promotionAction))
			require_once 'Controller/PromotionController.php';
		if($action == 'promotions')
			$response = actionPromotion($_GET['itemId'], $_GET['invoiceAccount']);
		if($action == 'promotionTypes')
			$response = actionPromotionTypes();
		if($action == 'promotionPhases')
			$response = actionPromotionPhases();
		if($action == 'promotionPaymentTypes')
			$response = actionPromotionPaymentTypes();
		if($action == 'listPromotions')
			$response = actionPromotions();
		if($action == 'listConditions')
			$response = actionConditions();
		
	}
	if(isset($_POST['action']))
	{

		$action = $_POST['action'];

		if(gotAction($action, $promotionAction))
			require_once 'Controller/PromotionController.php';
		

	}

	if(isset($reqBody->action))
	{
		$action = $reqBody->action;

		if(gotAction($action, $promotionAction))
			require_once 'Controller/PromotionController.php';
		if($action == 'createPromotion')
			$response = actionCreatePromotion($reqBody->promotion);
		if($action == 'createCondition')
			$response = actionCreateCondition($reqBody->promotion_id, $reqBody->condition);
		if($action == 'deleteCondition')
			$response = actionDeleteCondition($reqBody->condition->id);
		if($action == 'matchPromotion')
			$response = actionMatchPromotion($reqBody->condition_id, $reqBody->unit_ids);
	}

?>