<?php
	require_once('util.php');
	function actionPromotion($itemId, $invoiceAcount)
	{
		return findAllPromotionsPreapprove($itemId, $invoiceAcount);
	}

	function actionSelecPromotion($unit_id, $promotion_id, $type)
	{

	}




?>