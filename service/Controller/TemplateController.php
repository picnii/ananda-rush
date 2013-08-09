<?php
	require_once 'util.php';
	function actionTemplates()
	{
		$templates = findAllTemplates();
		return $templates;
	}

	function actionTemplate($template_id)
	{
		$template = findTemplate($template_id);//getSampleTemplate();
		return $template;
	}

	function actionCreateTemplate($reqBody)
	{
		return createTemplate($reqBody->name, $reqBody->description, $reqBody->payments);
	}

	function actionUpdateTemplate($reqBody)
	{
		return updateTemplate($reqBody->id, $reqBody->name, $reqBody->description);
	}

	function actionDeleteTemplatePayment($reqBody)
	{
		return deleteTemplatePayment($reqBody->template_id, $reqBody->payment_id);
	}

	function actionCreateTemplatePayment($reqBody)
	{
		return createTemplatePayment($reqBody->template_id, $reqBody->payment_id, $reqBody->order);
	}

	function actionDeleteTemplate($reqBody)
	{
		return deleteTemplate($reqBody->id);
	}

	function getSampleTemplate()
	{
		$template = new StdClass;
		$template->id = 5;
		$template->name = "เท็มเพลตเกรียน";
		$template->color ="#FFACE0";
		$template->payments = getSamplePayments(3);//getPaymentsByTemplateId($template->id);

		return $template;
	}

?>