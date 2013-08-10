<?php
	$payments = getPayments();
	$create_template_id = createTemplate("Test Template", "white", $payments);
	assertEquals(true, is_numeric($create_template_id));

	$template = findTemplateById($create_template_id);
	assertEquals("Test Template", $template->name);
	assertEquals("white", $template->color);

	//put all id in array so we could use assertContain
	$payment_ids = array();
	$template_payment_ids = array();
	for($i =0; $i < count($payments); $i++)
		array_push($payment_ids, $payments[$i]->id);
	for($i =0; $i < count($template->payments); $i++)
		array_push($template_payment_ids, $template->payments[$i]->id);

	for($i=0; $i < count($template_payment_ids);$i++)
	{
		$template_id = $template_payment_ids[$i];
		assertContain($template_id, $payment_ids);
	}

	//$templates = findAllTemplates();
	//print_r($templates);
	deleteTemplate($template_id);

?>
