<?php
//rawee@ananda.co.th
	$transaction_ids = array(1302, 1304);
	/*$units = findAllUnits();
	$transaction_ids = array();
	foreach ($units as $unit) {
		# code...
		array_push($transaction_ids, $unit->id);
	}*/
	$rows = fetchBillInformation($transaction_ids);
	
	$bills = getVariableUnits($rows);
	//print_r($bills);
	
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
		if(isset($bill->comp_addno))
			$company_address = "เลขที่ {$bill->comp_addno} ซอย {$bill->comp_soi} ถนน {$bill->comp_road} ตำบล {$bill->comp_tumbon} อำเภอ {$bill->comp_distinct} จังหวัด {$bill->comp_province} {$bill->comp_zipcode}";
		else
		{
			echo "company_address wrong at {$bill->transaction_id}:";
			print_r($bill);
			echo "end error;";
		}
		assertEquals(true, is_string($company_address));
	}

	//chdir(directory)eck for company tel

	//check for company fax

	//check for unit no
	foreach($bills as $bill)
	{
		if(isset($bill->master_UnitNo))
			$unit_number = $bill->master_UnitNo.' ';
		else if(isset($bill->UnitNo))
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
		if(isset($bill->master_SalesName))
			$sale_name = $bill->master_SalesName;
		if(isset($bill->SalesName))
			$sale_name = $bill->SalesName;
		else
			echo "sale name wrong at{$bill->transaction_id}:";
		assertEquals(true, is_string($sale_name));
	}

	//check for เรียน sale phone number
	foreach($bills as $bill)
	{
		if(isset($bill->Mobile))
			$sale_mobile = $bill->Mobile;
		else
			echo "sale mobile wrong at{$bill->transaction_id}:";
		assertEquals(true, is_string($sale_mobile));
	}

	//price at contract
	foreach($bills as $bill)
	{

		if(isset($bill->SellPrice))
			$sellPrice = $bill->SellPrice;
		if(isset($bill->DiscAmount))
			$discount = $bill->DiscAmount;
		if(isset($sellPrice) && isset($discount))
			$price_at_contract = $sellPrice - $discount;
		else
			echo "price at contract wrong at{$bill->transaction_id}:";
		assertEquals(true, is_numeric($price_at_contract));
	}

	//price per sqm
	foreach($bills as $bill)
	{

		if(isset($bill->master_Sqm))
			$area = $bill->master_Sqm;
		elseif(isset($bill->Sqm))
			$area = $bill->Sqm;
		elseif(isset($bill->SQM))
			$area = $bill->SQM;

		if(isset($bill->master_BasePrice))
			$price = $bill->master_BasePrice;
		else if(isset($bill->BasePrice))
			$price = $bill->BasePrice;

		if(isset($price) && isset($area))
			$price_per_sqm = $area / $price;
		else
			echo "price per sqm wrong at{$bill->transaction_id}:";
		assertEquals(true, is_numeric($price_per_sqm));
	}

	//Spacial Discount
	foreach($bills as $bill)
	{

		if(isset($bill->master_Sqm))
			$area = $bill->master_Sqm;
		elseif(isset($bill->Sqm))
			$area = $bill->Sqm;
		elseif(isset($bill->SQM))
			$area = $bill->SQM;

		if(isset($bill->master_BasePrice))
			$price = $bill->master_BasePrice;
		else if(isset($bill->BasePrice))
			$price = $bill->BasePrice;

		if(isset($price) && isset($area))
			$price_per_sqm = $area / $price;
		else
			echo "price per sqm wrong at{$bill->transaction_id}:";
		assertEquals(true, is_numeric($price_per_sqm));
	}

	//Area at contract
	foreach($bills as $bill)
	{
		if(isset($bill->master_HOUSESIZE))
			$area = $bill->master_HOUSESIZE;
		elseif(isset($bill->HOUSESIZE))
			$area = $bill->HOUSESIZE;
		if(isset($area) )
			$area = $area ;
		else
			echo "price per sqm wrong at{$bill->transaction_id}:";
		assertEquals(true, is_numeric($area), array("pice per sqm wrong"=>$bill->transaction_id));
	}

	foreach($bills as $bill)
	{
		$result = getIsBank($bill);
		print_r($result);
		$bank = getBanksVariable($bill);

		if($result)
		{
			assertEquals(true, is_numeric($bank->BankLoanRoom), "bank loan room {$bill->transaction_id}");
			assertEquals(true, is_numeric($bank->BankLoanInsurance), "BankLoanInsurance {$bill->transaction_id}");
			assertEquals(true, is_numeric($bank->BankLoanDecorate), "BankLoanDecorate {$bill->transaction_id}");
			assertEquals(true, is_numeric($bank->BankLoanMulti), $bank);
			assertEquals(true, is_numeric($bank->BankLoanOther), "BankLoanOther {$bill->transaction_id}");
			assertEquals(true, is_numeric($bank->SumBankLoan), "SumBankLoan {$bill->transaction_id}");
			$sum_state = $bank->BankLoanInsurance > 0 || $bank->BankLoanRoom > 0 || $bank->BankLoanDecorate > 0 || $bank->BankLoanMulti > 0 || $bank->BankLoanOther;
			if($sum_state)
				assertEquals(true, $bank->SumBankLoan > 0 , $bank);
		}else
		{
			assertEquals(true, $bank->BankLoanRoom == 0 || $bank->BankLoanRoom == '-', $bank);
			assertEquals(true, $bank->BankLoanInsurance == 0 || $bank->BankLoanInsurance == '-', $bank);
			assertEquals(true, $bank->BankLoanDecorate == 0 || $bank->BankLoanDecorate == '-', $bank);
			assertEquals(true, $bank->BankLoanMulti == 0 || $bank->BankLoanMulti == '-', $bank);
			assertEquals(true, $bank->SumBankLoan == 0 || $bank->SumBankLoan == '-', $bank);
			assertEquals(true, $bank->BankLoanOther == 0 || $bank->BankLoanOther == '-', $bank);
		}
		
	}

	echo "start";
	$unit_ids = array();
	$unit_ids[0] = 4019;
	$transactions = findAllTransaction($unit_ids);
	print_r($transactions);
/*
	//print_r($bills);
	foreach ($bills as $bill) {
		# code...
		$bill_data = convertSaleDataToBill($bill, 20);
		print_r($bill_data);
		echo "------";
	}

	/*
	$unit_ids = $transaction_ids;

	$transactions = findAllLastTransactionsByUnitIds("id",$unit_ids);

	$test_transaction_id = 2935;
	$transaction_ids = array();
	$transaction_ids[0] = $test_transaction_id;
	$transactions  = findAllTransaction($transaction_ids);
	//print_r(json_decode($transactions[0]['payments']));
	//print_r(json_encode($transactions));
	for($i =0; $i < count($transactions); $i++)
	{		# code...
			$transactions[$i]['payments'] = json_decode($transactions[$i]['payments']);
			$transactions[$i]['variables'] = json_decode($transactions[$i]['variables']);
	}
//	print_r(json_encode($transactions));



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