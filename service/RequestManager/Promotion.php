<?php
	//
	//actionPromotion($itemId, $invoiceAcount)
    $promotionAction = array('promotions', 'selectPromotion', 'promotionTypes', 'promotionPhases', 'promotionPaymentTypes', 'createPromotion', 'listPromotions', 'createCondition', 'listConditions', 'deleteCondition', 'matchPromotion', 'findAllUnitPromotion', 'countAllUnitPromotion', 'promotion', 'updatePromotion', 'deletePromotion', 'listAx', 'deletePromotionAx', 'createPromotionAx', 'findAllPromotionFromCondition' ,'doTestPromotion', 'findAllPrePromotionFromItemId', 'findAllPromotionAxByItemId', 'updatePrePromotion', 'updateTranferPromotion', 'updateAxPromotion', 'createConfirmPromotion', 'deleteConfirmPromotion', 'isConfirmPromotion');

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
		if($action == 'findAllUnitPromotion')
			$response = actionFindAllUnitPromotion($_GET['condition_id']);
		if($action == 'countAllUnitPromotion')
			$response = actionCountAllUnitPromotion($_GET['condition_id']);
		if($action == 'promotion')
			$response = actionFindPromotion($_GET['id']);
		if($action == 'listAx')
			$response = actionFindAllPromotionAx();
		if($action == 'findAllPromotionFromCondition')
			$response = actionFindAllPromotionFromCondition($_GET);
		if($action == 'doTestPromotion')
			$response = actionTestPromotion();
		if($action == 'findAllPrePromotionFromItemId')
			$response = actionFindAllPrePromotionFromItemId($_GET['item_id']);
		if($action == 'findAllPromotionAxByItemId')
			$response = actionFindAllPromotionAxByItemId($_GET['item_id']);


		
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
		if($action == 'updatePromotion')
			$response = actionUpdatePromotion($reqBody->promotion->id, $reqBody->promotion);
		if($action == 'deletePromotion')
			$response = actionDeletePromotion($reqBody->promotion->id);
		if($action == 'createPromotionAx')
			$response = actionCreatePromotionAx($reqBody);
		if($action == 'deletePromotionAx')
			$response = actionDeletePromotionAx($reqBody);
		if($action == 'findAllPromotionFromCondition')
			$response = actionFindAllPromotionFromCondition($reqBody->condition);
		if($action == 'updatePrePromotion')
			$response = actionUpdatePrePromotion($reqBody->promotion);
		if($action == 'updateTranferPromotion')
			$response = actionUpdateTranferPromotion($reqBody->promotion);
		if($action == 'updateAxPromotion')
			$response = actionUpdateAxPromotion($reqBody->promotion);
		
		if($action == 'createConfirmPromotion')
			$response = actionCreateConfirmPromotion($reqBody->promotion);
		if($action == 'deleteConfirmPromotion')
			$response = actionDeleteConfirmPromotion($reqBody->promotion);
		if($action == 'isConfirmPromotion')
			$response = actionIsConfirmPromotion($reqBody->promotion);
		//'createConfirmPromotion', 'deleteConfirmPromotion', 'findConfirmPromotion'
	}

?>