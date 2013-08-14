<?php

	$transaction_ids = array(1352, 1302, 1307, 1311, 1301);
	$rows = fetchBillInformation($transaction_ids);
	$bills = getVariableUnits($rows);

	//check for project name
	foreach($bills as $bill)
	{
		if(isset($bill->proj_name_th))
			$project_name = $bill->proj_name_en;
		assertEquals(true, is_string($project_name));
	}

	//check for company name
	foreach($bills as $bill)
	{
		if(isset($bill->comp_name_th))
			$company_name = $bill->comp_name_th;
		assertEquals(true, is_string($company_name));
	}

	//check for company address
	foreach($bills as $bill)
	{
		if(isset($bill->comp_addno) && isset($bill->comp_road) && isset($bill->comp_soi)  && isset($bill->comp_province)  && isset($bill->comp_tumbon)  && isset($bill->comp_distinct) && isset($bill->comp_zipcode) )
			$company_address = "เลขที่ {$bill->comp_addno} ซอย {$bill->comp_soi} ถนน {$bill->comp_road} ตำบล {$bill->comp_tumbon} อำเภอ {$bill->comp_distinct} จังหวัด {$bill->comp_province} {$bill->comp_zipcode}";
		else
			echo "company_address wrong at{$bill->transaction_id}:";
		assertEquals(true, is_string($company_address));
	}

	//check for company tel

	//check for company fax

	//check for unit no
	foreach($bills as $bill)
	{
		if(isset($bill->UnitNo))
			$unit_number = $bill->UnitNo.' ';
		else
			echo "unit_number wrong at{$bill->transaction_id}:";
		assertEquals(true, is_string($unit_number));
	}
	
	//check for house type
	foreach($bills as $bill)
	{
		if(isset($bill->ItemType))
			$item_type = $bill->ItemType;
		else
		{
			echo "house type wrong at {$bill->transaction_id}:";
		}
		assertEquals(true, is_string($item_type) || is_numeric($item_type));
	}
	

	//check for usage area
	foreach($bills as $bill)
	{
		if(isset($bill->Sqm))
			$contractSpace = $bill->sqm;
		else if(isset($bill->SQM))
			$contractSpace = $bill->SQM;
		else
			echo "contract space wrong at{$bill->transaction_id}:";
		assertEquals(true, is_numeric($contractSpace));
	}

	//check for เรียน salename
	foreach($bills as $bill)
	{
		if(isset($bill->SalesName))
			$sale_name = $bill->SalesName;
		else
			echo "sale name wrong at{$bill->transaction_id}:";
		assertEquals(true, is_string($sale_name));
	}


	print_r($bills);

	/*$units = findAllUnits();
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
	/*$transactions = findAllLastTransactionsByUnitIds($unit_ids);
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