<?php
	require_once 'util.php';
	function actionTemplates()
	{
		$templates = array();
		for($i =0; $i < 9;$i++)
		{
			$templates[$i] = new stdClass;
			$templates[$i]->id = $i + 1;
			$templates[$i]->name = 'รูปแบบที่ '.($i + 1);
			$templates[$i]->color = '#FACE'.$i.'0';
		}
		return $templates;
	}

	function actionTemplate($template_id)
	{
		$template = getSampleTemplate();
		return $template;
	}

	function actionUpdateTemplate($template_id, $args)
	{
		return true;
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