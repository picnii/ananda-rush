<?php
	require_once('util.php');
	//use for preview what bills will be liked
	function actionBills($unit_ids, $template_id)
	{

		$sale_datas = getSaleDatas($unit_ids);
		$bills = array();
		$template = findTemplateById($template_id);
		//echo "hi";
		foreach($sale_datas as $sale_data)
		{

			$bill = convertSaleDataToBill($sale_data, $template_id);
			
			array_push($bills, $bill);
		}
		return $bills;
	}

	function actionTranfer($unit_id)
	{

	}

	function actionCreateTransactions($template_id, $bills)
	{
		$transaction_ids  = array();
		
		//$sale_datas = getSaleDatas($unit_ids);
		//print_r($sale_data);
		//$variable_units = $sale_datas;//getVariableUnits($sales_data);
		//print_r($variable_units);

		for($i = 0;$i < count($bills); $i++)
		{
			$bill = $bills[$i];
			$unit_id =  $bill->unit_id;
		//	$variables = $variable_units[$i];
		//	$bill = convertSaleDataToBill($variables, $template_id);
			$variables_json = json_encode($bill->variables);
			$payments_json = json_encode($bill->payments);
			//echo $variables_json;
			/*print_r(array(
					'unit_id'=>$unit_id,
					'template_id'=>$template_id,
					'payments_json'=>$payments_json,
					'variables_json'=>$variables_json
				));*/
			if(isset($unit_id) && isset($variables_json) && isset($payments_json))
			{
				
					$created_id = createTransaction($unit_id, $template_id, $payments_json, $variables_json);	
					array_push($transaction_ids, $created_id);
			}
		
			//$created_id = createTransaction($unit_ids[$i], $template_id);
			
			
		}
		return $transaction_ids;
	}

	function actionCreateBills($unit_ids, $template_id)
	{
		$transaction_ids  = array();
		$template = findTemplateById($template_id);
		$payments_json = json_encode($template->payments);
		$sale_datas = getSaleDatas($unit_ids);
		//print_r($sale_data);
		$variable_units = $sale_datas;//getVariableUnits($sales_data);
		//print_r($variable_units);

		for($i = 0;$i < count($unit_ids); $i++)
		{
			$unit_id =  $variable_units[$i]->transaction_id;
			$variables = $variable_units[$i];
			$bill = convertSaleDataToBill($variables, $template_id);

			$variables_json = json_encode($bill);
			//echo $variables_json;
			/*print_r(array(
					'unit_id'=>$unit_id,
					'template_id'=>$template_id,
					'payments_json'=>$payments_json,
					'variables_json'=>$variables_json
				));*/
			$created_id = createTransaction($unit_id, $template_id, $payments_json, $variables_json);
			//$created_id = createTransaction($unit_ids[$i], $template_id);
			array_push($transaction_ids, $created_id);
			
		}
		return $transaction_ids;
	}

	function actionDeleteBills($transaction_ids)
	{
		$results = array();
		for($i = 0;$i < count($transaction_ids); $i++)
		{
			$result = deleteTransactionById($transaction_ids[$i]);
			array_push($results, $result);
		}
		return $results;
	}	

	function actionUpdateBill($transaction_id, $args)
	{
		$args = objectToArray($args);
		$transaction = updateTransaction($transaction_id, $args);
		//$sample = getSampleBill();
		return $transaction ;
	}



	function actionBill($unit_id, $template_id)
	{
		$unit_ids = array();
		$unit_ids[0] = $unit_id;
		$sale_datas = getSaleDatas($unit_ids);
		
		//echo "<br/><br/>";
		$sale_data = $sale_datas[0];
		$bill =array();
		$bill = convertSaleDataToBill($sale_data, $template_id);
		$template = findTemplateById($template_id);
	//	$bill->payments = $template->payments;
		//return $bill;*/
		return $bill;
	}

	function actionUpdateAppointment()
	{

	}

	function actionTransactions($unit_ids)
	{
		$bills = findAllTransaction($unit_ids);

		for($i =0; $i < count($bills); $i++)
		{		# code...
				$uids = array();
				$uids[0] = $bills[$i]['unit_id'];
				$sale_datas = getSaleDatas($uids);
				$bills2 = array();
				//echo "hi";
				foreach($sale_datas as $sale_data)
				{

					$bill = convertSaleDataToBill($sale_data, 20);
					$bill->is_tranfer = $bills[$i]['is_tranfer'];	
					array_push($bills2, $bill);
				}
				$bills[$i]['payments'] = json_decode($bills[$i]['payments']);
				//$bills[$i]['variables'] = $bills[$i]['variables'];
				$bills[$i]['variables'] = $bills2[0]->variables;
		}
		//halt

			


		return $bills;
	}

	function actionSearchTransaction($q, $from=null, $to=null)
	{
		$search_query = $q;
		
		if($q=="*" && $from== null && $to==null)
		{
			
			$units = findAllUnits();
		}
		else if($q == "*")
		{
			$units = findAllUnits($from, $to);
		
		}
		else
		{
		//	echo "q = {$q}";
			$params = getParamsFromSearchQuery($q, 'master_transaction');
			$units = findAllUnitsByQuery($params);
		}
		$unit_ids = array();
		for( $i=0; $i < count($units);$i++)
		{
			array_push($unit_ids, $units[$i]->id)
			;
		}
		
		return findAllLastTransactionsByUnitIds("tranfer_transaction.id, master_transaction.itemId, master_transaction.transaction_id as unit_id, master_transaction.UnitNo as unit_number, tranfer_transaction.template_id, tranfer_transaction.is_tranfer, tranfer_transaction.tranfer_time", $unit_ids);
	}

	function actionViewTransaction($id)
	{
		$bill =  findTransactionById($id);
		$bill['variables'] = json_decode($bill['variables']);
		$bill['payments'] = json_decode($bill['payments']);
		return $bill;
	}

	function actionAllTransactions()
	{
		return findAllLastTransactions("tranfer_transaction.id, master_transaction.itemId, master_transaction.transaction_id as unit_id, master_transaction.UnitNo as unit_number, tranfer_transaction.template_id, tranfer_transaction.is_tranfer, tranfer_transaction.tranfer_time");
	}

	function actionViewTransactionByUnitId($unit_id)
	{
		return findLastTransactionByUnitId($unit_id);
	}


	function testBill()
	{
		$sampleJson = '{"variables":[{"documentName":{"name":"ชื่อเอกสาร","value":"ใบประเมินการค่าใช้จ่ายโอนกรรมสิทธิ์"}},{"companyAddress":{"name":"ที่อยู่","value":"เลขที่ 99/4 หมุ่ที่ 14 ตำบลบางพลีใหญ่ อำเภอบางพลี จังหวัดสมุทรปราการ 10540"}},{"companyPhone":{"name":"โทร","value":"02-3171155 ต่อ 102-109, 121"}},{"companyFax":{"name":"โทรสาร","value":"02-3160180-1"}},{"unitNumber":{"name":"UNIT NO.","value":"MR9-0502"}},{"customerName":{"name":"ลูกค้า","value":"ปุณณตา ดิษฐพงศา"}},{"documentDate":{"name":"วันที่","value":"29 กรกฎาคม 2556"}},{"from":{"name":"จาก","value":"คุณตุ๊กตา085-488-2578/จั่น089-2027962"}},{"payDate":{"name":"วันนัดโอน","value":"15 ค่ำเดือน 11"}},{"payTime":{"name":"เวลา","value":"10.00 น"}},{"subject":{"name":"เรื่อง","value":"รายละเอียดค่าใช้จ่ายต่างๆเกี่ยวกับการโอนกรรมสิทธิ์ห้องชุดเลขที่ M38-A0504"}},{"tel":{"name":"โทร","value":"082-452-3991"}},{"contractSpace":{"name":"พื้นที่ตามสัญญา","value":35.39}},{"houseAddress":{"name":"บ้านเลขที่","value":"88/239"}},{"houseNumber":{"name":"บ้านเลขที่","value":"TH05-2-105002"}},{"noticeDate":{"name":"วันที่แจ้ง","value":"29 กรกฎาคม 2556"}},{"pricePerArea":{"name":"ราคาต่อตารางเมตร","value":106238.10}},{"priceOnContact":{"name":"ราคาตามสัญญา","value":2231000}},{"specialDiscount":{"name":"หัก ส่วนลดพิเศษ","value":0}},{"additionalAreaPrice":{"name":"พื้นที่เพิ่ม (ลด)","value":null}},{"paidAmount":{"name":"หัก ชำระแล้ว","value":307000.00}},{"paidDate":{"name":"วันที่ชำระ","value":"30/7/56"}},{"actualSpace":{"name":"พื้นที่จริง","value":null}},{"bankLoanRoom":{"name":"อนุมัติค่าห้อง","value":0}},{"bankLoanOther":{"name":"อนุมัติวงเงินอื่นๆ","value":0}},{"electricMeter":{"name":"มิเตอร์ไฟฟ้า","value":3250}},{"commonFeeCharge":{"name":"ชำระส่วนกลาง","value":"*ขึ้นอยู่กับพื้นที่จริง"}},{"commonFeeFund":{"name":"เงินสมทบกองทุนส่วนกลาง","value":"*ขึ้นอยู่กับพื้นที่จริง"}},{"feeForMinistryOfFinance":{"name":"ค่าธรรมเนียมสำหรับกระทรวงการคลัง","value":30000}},{"feeForTranferCash":{"name":"ค่าธรรมเนียมเงินสด","value":20000}}],"paymentTypes":["ธนาคาร","บริษัท","ลูกค้า"],"payments":[{"order":1,"name":"ค่าห้องชุดส่วนที่ต้องชำระ","description":"*อาจมีเพิ่ม/ลดตามพื้นที่จริง","formulas":["","","{priceOnContact} - {paidAmount}"]},{"order":2,"name":"ค่ามิเตอร์ไฟฟ้า","description":"15Amp","formulas":["","","{electricMeter}"]},{"order":3,"name":"ค่ามิเตอร์ไฟฟ้า","description":"asdasd","formulas":["","","#*ขึ้นกลับพื้นที่จริง"]}]}';
		print_r(json_decode($sampleJson));
	}

	function loadBill($transaction_row)
	{
		
	}

	
	function getGlobalVariables()
	{
		//return array();
		return array(
			"AppointmentMonth"=>"เดือนวันที่นัดโอน",
			"UnitNumber"=>"UNIT NO.",
			"CompanyName"=>"ที่อยู่",
			"HouseNumber"=>"บ้านเลขที่",
			"HouseType"=>"แบบบ้าน",
			"HouseSize"=>"พื้นที่ใช้สอย",
			"DocumentDate"=>"วันที่แจ้ง",
			"SaleName"=>"ชื่อผู้ติดต่อ",
			"PayDate"=>"วันที่นัดโอน",
			"PayTime"=>"เวลาที่นัดโอน",
			"CustomerName"=>"ชื่อลูกค้า",
			"CustomerTel"=>"เบอร์โทรลูกค้า",
			"PriceOnContract"=>"ราคาตามสัญญา",
			"PricePerArea"=>"ราคาต่อตารางเมตร",
			"SpacialDiscount"=>"หักส่วนลดพิเศษ",
			"BankLoanName"=>"ชื่อธนาคาร/สาขา",
			"ContractOfSpace"=>"พื้นที่ตามสัญญา",
			"BankLoanRoom"=>"อนุมัติค่าห้อง",
			"ActualSpace"=>"พื้นที่จริง",
			"BankLoanInsurance"=>"อนุมัติวงเงินค่าประกัน",
			"DifferenOfSpace"=>"ส่วนต่างพื้นที่",
			"BankLoanDecorate"=>"อนุมัติวงเงินตกแต่ง",
			"PriceDateOfPayment"=>"ราคาห้องชุด ณ วันที่โอน",
			"BankloanMulti"=>"อนุมัติวงเงินเอนกประสงค์",
			"PaidAmount"=>"หักชำระแล้ว",
			"BankLoanOther"=>"อนุมิติวงเงินอื่น ๆ ",
			"PriceRoomOfPayment"=>"ค่าห้องชุดที่ต้องชำระ",
			"SumBankLoan"=>"วงเงินจำนองรวม",
			"PayCheckBank"=>"เช็คสั่งจ่ายธนาคาร",
			"PayCheckAnanda"=>"เช็คสั่งจ่ายอนันดา",
			"PayCommonFeeCharge"=>"ชำระค่าส่วนกลาง",
			"PayCommonFeeFund"=>"ชำระค่าสมทบ",
			"PayFeeForministryOfFinance"=>"ชำระค่าธรรมเนียม",
			"PayFeeForTranferCash"=>"แบ่งชำระเงินสด",
			"FinalCustomerPayment"=>"รวมเป็นเงินที่ต้องชำระ"
		);
	
	}

	function actionGetPaymentIds()
	{

		$meters = array(
			"electricMeter15Amp" => 6,
			"electricMeter30Amp" => 26,
			"waterMeter" => 25
		);

		$share_payment_id = 18;
		$tranfer_payment_id = 34;
		$share_fund_payment_id = 27;
		$room_payment = 24;
		$loan_payment = 42;
		$tax_payment = 48;
		$tax_loan_payment = 41;
		$niti_payment = 91;
		$loan_rama9_payment = 92;
		return array(
			"meters"=>$meters,
			"share_payment_id" => $share_payment_id,
			"tranfer_payment_id" => $tranfer_payment_id,
			"share_fund_payment_id" => $share_fund_payment_id,
			"room_payment" => $room_payment,
			"loan_payment_id" => $loan_payment,
			"tax_payment_id" => $tax_payment,
			"tax_loan_payment_id" => $tax_loan_payment,
			"niti_payment_id" => $niti_payment,
			"loan_rama9_id" => $loan_rama9_payment,
		);
	}
	
	//testBill();
	//header('Content-Type: text/html; charset=utf-8');
	//actionBill(1);*/
?>