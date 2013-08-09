<?php
	require_once('util.php');
	function actionPayments()
	{
		return getPayments();//getSamplePayments(10);
	}

	function actionCreatePayment($reqBody)
	{
		$result = new stdClass;
		$result->result =createPayment($reqBody->name, $reqBody->description, $reqBody->formulas, $reqBody->is_shows, $reqBody->is_add_in_cheque, $reqBody->is_compare_with_repayment);
		return $result;
	}

	function actionUpdatePayment($reqBody)
	{
		
		$result = new stdClass;
		$result->result =updatePayment($reqBody->id, $reqBody->name, $reqBody->description, $reqBody->formulas, $reqBody->is_shows, $reqBody->is_add_in_cheque, $reqBody->is_compare_with_repayment);
		return $result;
	}

	function actionDeletePayment($reqBody)
	{
		$result = new stdClass;
		$result->result =deletePayment($reqBody->id);
		return $result;
	}

?>