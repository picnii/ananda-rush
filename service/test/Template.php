<?php
	$payments = getPayments();
	foreach($payments as $payment)
		$payment->order = rand(1, 10);
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
		assertContains($template_id, $payment_ids);
	}

	$templates = findAllTemplates();
	$template_ids = array();
	for($i =0; $i < count($templates); $i++)
		array_push($template_ids, $templates[$i]->id);
	assertContains($template->id, $template_ids );

	//test Template Payment
	$del_template_payment_id = $template->payments[0]->id;
	deleteTemplatePayment($template->id, $template->payments[0]->id);
	//must not found template payment id in template
	$template = findTemplateById($template->id);
	//put all id in array so we could use assertContain
	$template_payment_ids = array();
	for($i =0; $i < count($template->payments); $i++)
		array_push($template_payment_ids, $template->payments[$i]->id);
	assertNotContains($del_template_payment_id, $template_payment_ids);
	
	$order_payment = 9999;
	createTemplatePayment($template->id, $del_template_payment_id, $order_payment);
	$template = findTemplateById($template->id);
	//put all id in array so we could use assertContain
	$template_payment_ids = array();
	for($i =0; $i < count($template->payments); $i++)
		array_push($template_payment_ids, $template->payments[$i]->id);
	assertContains($del_template_payment_id, $template_payment_ids);


	deleteTemplate($template->id);
	$del_template = findTemplateById($create_template_id);
	assertEquals(false, $del_template);

	//check if there are TemplatePaymentLeft?*/
?>
