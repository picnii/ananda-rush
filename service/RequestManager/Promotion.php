<?php
	//
	//actionPromotion($itemId, $invoiceAcount)
    $promotionAction = array('promotions', 'selectPromotion');

	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $promotionAction))
			require_once 'Controller/PromotionController.php';
		if($action == 'promotions')
			$response = actionPromotion($_GET['itemId'], $_GET['invoiceAccount']);
	}

	if(isset($reqBody->action))
	{

		

	}

?>