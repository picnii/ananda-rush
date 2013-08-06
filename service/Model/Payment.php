<?php
function getPayments()
{

	$payments =  array();
	for($i =0; $i< 10; $i++)
	{
		$payments[$i] = new stdClass;
		$payments[$i]->id = $i;
		$payments[$i]->name = "ค่าห้องชุดส่วนที่ต้องชำระ {$i}";
		$payments[$i]->description = "*อาจมีเพิ่ม/ลดตามพื้นที่จริง";
		$payments[$i]->formulas = array(
				"",
				"250",
				"{priceOnContact} - {paidAmount}"
			);
	}
	return $payments;

}

function createPayment($name, $description, $formulas)
{

	return $payment_id;
}

function updatePayment($payment_id, $args)
{
	return $payment_id;
}

function deletePayment($payment_id)
{
	
}


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