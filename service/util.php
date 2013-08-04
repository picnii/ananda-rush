<?php

function getPaymentsByTemplateId($template_id)
{
	$payments =  array();
	$payments[0] = new stdClass;
	$payments[0]->order = 1;
	$payments[0]->name = "ค่าห้องชุดส่วนที่ต้องชำระ";
	$payments[0]->description = "*อาจมีเพิ่ม/ลดตามพื้นที่จริง";
	$payments[0]->formulas = array(
			"",
			"",
			"{priceOnContact} - {paidAmount}"
		);
	return $payments;
}

?>