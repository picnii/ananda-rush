<?php

	$payment_id = createPayment("Test Payment", "-", array(
			"","","{bankLoan}*5"
		), array(
			"1","1","1"
		), false, false);

	assertEquals(is_numeric($payment_id), true);

	$payment = findPaymentById($payment_id);
	assertEquals( $payment->name, "Test Payment");

	$payment->name = "Bye";
	updatePayment($payment->id, $payment->name, $payment->description, $payment->formulas, $payment->is_shows, $payment->is_add_in_cheque, $payment->is_compare_with_repayment);
	$payment_update = findPaymentById($payment->id);
	assertEquals( $payment->name, "Bye");

	$payments = getPayments();
	$payment_ids = array();
	for($i =0; $i < count($payments); $i++)
		array_push($payment_ids, $payments[$i]->id );
	assertContains($payment->id, $payment_ids);


	deletePayment($payment->id);
	$payment_del_result = findPaymentById($payment->id);
	assertEquals(false, $payment_del_result);

?>