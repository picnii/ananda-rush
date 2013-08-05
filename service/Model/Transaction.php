<?php
/*
* $args = array(
	"unit_ids" => array(),
	"template_id"=> 2
  )
*/
function createTransaction($unit_id, $template_id)
{
	$created_id = 22;
	//
	return $created_id;
}

function findTransactionById($id)
{
	return array(
		'id'=>20,
		'unit_payment_id'=>30,
		'template_id'=>1,
		'create_time'=>'2009-12-20',
		'unit_payment_unit_id'=>30
	);
}

/*
* SELECT ONLY 1 row
*
*/
function findTransaction($q)
{

	$sql = "SELECT * FROM transaction WHERE {$q}";
	return array(
		'id'=>20,
		'unit_payment_id'=>30,
		'template_id'=>1,
		'create_time'=>'2009-12-20',
		'unit_payment_unit_id'=>30
	);
}

/*
*
*/
function findAllTransaction($q)
{
	//if none $q return all 
	//if haave $q add where
}

function updateTransaction($transaction_id, $args)
{
	//update transaction onl
	/*example $args = array(
		'create_time'=>'2010-12-20'
	)
	update only change field
	*/
	return array(
		'id'=>20,
		'unit_payment_id'=>30,
		'template_id'=>1,
		'create_time'=>'2009-12-20',
		'unit_payment_unit_id'=>30
	);
}

function deleteTransactionById($transaction_id)
{

	return true;
}

function deleteTransaction($q)
{
	return true;
}

?>