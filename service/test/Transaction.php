<?php

	$transaction_ids = array(1352, 1302, 1307, 1311);
	$rows = fetchBillInformation($transaction_ids);
	print_r($rows);

	$units = findAllUnits();
	$unit_index = rand(0, count($units) - 1);
	$unit_ids = array(
		$units[rand(0, count($units) - 1)]->id,
		$units[rand(0, count($units) - 1)]->id,
		$units[rand(0, count($units) - 1)]->id,
		$units[rand(0, count($units) - 1)]->id,
		$units[rand(0, count($units) - 1)]->id,
		$units[rand(0, count($units) - 1)]->id
	);

	//Match Transaction Simulate;
	$templates = findAllTemplates();
	$template_id = $templates[count($templates) - 1]->id;
	$template = findTemplateById($template_id);
	//print_r($template);
	$payments_json = json_encode($template->payments);
	//print_r($payments_json);
	$sales_data = getSaleDatas($unit_ids);
	print_r($sales_data);
	//echo "<br/>";
	$variable_units = getVariableUnits($sales_data);
	for($i = 0; $i < count($unit_ids); $i++)
	{
		$unit_id =  $variable_units[$i]->unit_id;
		$variables = $variable_units[$i];
		$variables_json = json_encode($variables);
		//echo $variables_json;
		//echo "<br/>";
		$result_create = createTransaction($unit_id, $template->id, $payments_json, $variables_json);
		assertEquals(true, is_numeric($result_create));
	}

	/*View Latest Transaction
	*
	*/
	$transactions = findAllLastTransactionsByUnitIds($unit_ids);
	assertEquals(count($unit_ids), count($transactions));
	print_r($unit_ids);
	foreach ($transactions as $tran) {
		# code...

	}
	//print_r($transactions);

	$allTransactions = findAllLastTransactions("id");
	//print_r($allTransactions);
	for($i =0; $i < count($allTransactions);$i++)
	{
		$selected_transaction_id = $allTransactions[$i]['id'];
		deleteTransactionById($selected_transaction_id);
	}
	
	//print_r($selected_transaction);
	/*
	* Change Variables Units
	*/
	/*$selected_unit_ids = array(
		rand(0, count($units) - 1),
		rand(0, count($units) - 1),
		rand(0, count($units) - 1)
	);
	$variables_update_req = array(
		"bankLoan"=>50,
		"owner"=>90,
	);
	$selected_transactions = findAllLastTransactionsByUnitIds($selected_unit_ids);
	foreach ($selected_transactions as $transaction) {
		# code...
		$variables_transaction = getVariablesFromTransaction($transaction);
		$variables_update = getUpdateVariables($variables_transaction, $variables_update_req);
		$variables_update_json = json_encode($variables_update);
		createTransaction($transaction->unit_id, $transaction->template_id, $transaction->payments_json, $variables_update_json);
	}	

	/*
	* Change Payment Units
	*/

	/*
	* Have Tranfer Units
	*/

	
?>