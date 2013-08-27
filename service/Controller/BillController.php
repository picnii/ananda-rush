<?php
	require_once('util.php');
	//use for preview what bills will be liked
	function actionBills($unit_ids, $template_id)
	{

		$sale_datas = getSaleDatas($unit_ids);
		$bills = array();
		$template = findTemplateById($template_id);
		
		foreach($sale_datas as $sale_data)
		{
			$bill = convertSaleDataToBill($sale_data, $template_id);
			$bill->payments = $template->payments;
			array_push($bills, $bill);
		}
		/*$samples[0] = getSampleBill();
		$samples[1] = getSampleBill();
		$samples[2] = getSampleBill();*/

		return $bills;
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
		$transaction = updateTransaction($transaction_id, $args);
		$sample = getSampleBill();
		return $sample;
	}

	function actionBill($unit_id, $template_id)
	{
		$unit_ids = array();
		$unit_ids[0] = $unit_id;
		$sale_datas = getSaleDatas($unit_ids);
		//print_r($sale_datas);
		//echo "<br/><br/>";
		$sale_data = $sale_datas[0];
		$bill =array();
		$bill = convertSaleDataToBill($sale_data, $template_id);
		$template = findTemplateById($template_id);
		$bill->payments = $template->payments;
		//return $bill;*/
		return $bill;
	}

	function actionTransactions($unit_ids)
	{
		$bills = findAllTransaction($unit_ids);
		return $bills;
	}

	function actionAllTransactions()
	{
		return findAllLastTransactions("tranfer_transaction.id, master_transaction.itemId, master_transaction.transaction_id as unit_id");
	}


	function testBill()
	{
		$sampleJson = '{"variables":[{"documentName":{"name":"ชื่อเอกสาร","value":"ใบประเมินการค่าใช้จ่ายโอนกรรมสิทธิ์"}},{"companyAddress":{"name":"ที่อยู่","value":"เลขที่ 99/4 หมุ่ที่ 14 ตำบลบางพลีใหญ่ อำเภอบางพลี จังหวัดสมุทรปราการ 10540"}},{"companyPhone":{"name":"โทร","value":"02-3171155 ต่อ 102-109, 121"}},{"companyFax":{"name":"โทรสาร","value":"02-3160180-1"}},{"unitNumber":{"name":"UNIT NO.","value":"MR9-0502"}},{"customerName":{"name":"ลูกค้า","value":"ปุณณตา ดิษฐพงศา"}},{"documentDate":{"name":"วันที่","value":"29 กรกฎาคม 2556"}},{"from":{"name":"จาก","value":"คุณตุ๊กตา085-488-2578/จั่น089-2027962"}},{"payDate":{"name":"วันนัดโอน","value":"15 ค่ำเดือน 11"}},{"payTime":{"name":"เวลา","value":"10.00 น"}},{"subject":{"name":"เรื่อง","value":"รายละเอียดค่าใช้จ่ายต่างๆเกี่ยวกับการโอนกรรมสิทธิ์ห้องชุดเลขที่ M38-A0504"}},{"tel":{"name":"โทร","value":"082-452-3991"}},{"contractSpace":{"name":"พื้นที่ตามสัญญา","value":35.39}},{"houseAddress":{"name":"บ้านเลขที่","value":"88/239"}},{"houseNumber":{"name":"บ้านเลขที่","value":"TH05-2-105002"}},{"noticeDate":{"name":"วันที่แจ้ง","value":"29 กรกฎาคม 2556"}},{"pricePerArea":{"name":"ราคาต่อตารางเมตร","value":106238.10}},{"priceOnContact":{"name":"ราคาตามสัญญา","value":2231000}},{"specialDiscount":{"name":"หัก ส่วนลดพิเศษ","value":0}},{"additionalAreaPrice":{"name":"พื้นที่เพิ่ม (ลด)","value":null}},{"paidAmount":{"name":"หัก ชำระแล้ว","value":307000.00}},{"paidDate":{"name":"วันที่ชำระ","value":"30/7/56"}},{"actualSpace":{"name":"พื้นที่จริง","value":null}},{"bankLoanRoom":{"name":"อนุมัติค่าห้อง","value":0}},{"bankLoanOther":{"name":"อนุมัติวงเงินอื่นๆ","value":0}},{"electricMeter":{"name":"มิเตอร์ไฟฟ้า","value":3250}},{"commonFeeCharge":{"name":"ชำระส่วนกลาง","value":"*ขึ้นอยู่กับพื้นที่จริง"}},{"commonFeeFund":{"name":"เงินสมทบกองทุนส่วนกลาง","value":"*ขึ้นอยู่กับพื้นที่จริง"}},{"feeForMinistryOfFinance":{"name":"ค่าธรรมเนียมสำหรับกระทรวงการคลัง","value":30000}},{"feeForTranferCash":{"name":"ค่าธรรมเนียมเงินสด","value":20000}}],"paymentTypes":["ธนาคาร","บริษัท","ลูกค้า"],"payments":[{"order":1,"name":"ค่าห้องชุดส่วนที่ต้องชำระ","description":"*อาจมีเพิ่ม/ลดตามพื้นที่จริง","formulas":["","","{priceOnContact} - {paidAmount}"]},{"order":2,"name":"ค่ามิเตอร์ไฟฟ้า","description":"15Amp","formulas":["","","{electricMeter}"]},{"order":3,"name":"ค่ามิเตอร์ไฟฟ้า","description":"asdasd","formulas":["","","#*ขึ้นกลับพื้นที่จริง"]}]}';
		print_r(json_decode($sampleJson));
	}

	function loadBill($transaction_row)
	{
		
	}

	function convertSaleDataToBill($data, $template_id)
	{
		$bill = getSampleBill($template_id);
		
		foreach($data->variables as $key => $value)
		{
			//print_r($data->variables[$key]);
			$variable = getBillVariable($key, $data->variables[$key]->name, $data->variables[$key]->value);
			array_push($bill->variables, $variable);
		}

		$isBankPay = getIsBank($data);
		$variable = getBillVariable('AppointmentMonth', 'เดือนวันที่นัดโอน', 'มีนาคม 2556');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('UnitNumber', 'UNIT NO.', getUnitNumberFromSaleData($data));

		array_push($bill->variables, $variable);
		$variable = getBillVariable('CompanyName', 'ชื่อบริษัท', getCompanyNameFromSaleData($data));
		array_push($bill->variables, $variable);
		$variable = getBillVariable('companyAddress', 'ที่อยู่', getCompanyAddressFromSaleData($data));
		array_push($bill->variables, $variable);
		$variable = getBillVariable('companyPhone', 'เบอร์โทร', getCompanyTelFromSaleData($data));
		array_push($bill->variables, $variable);
		$variable = getBillVariable('companyFax', 'Fax', getCompanyFaxFromSaleData($data));
		array_push($bill->variables, $variable);
		$variable = getBillVariable('HouseNumber', 'บ้านเลขที่',  getCustomerHouseAddress($data));
		array_push($bill->variables, $variable);
		$variable = getBillVariable('HouseType', 'แบบบ้าน',  getItemTypeFromSaleData($data));
		array_push($bill->variables, $variable);
		$variable = getBillVariable('HouseSize', 'พื้นที่ใช้สอย',  getAreaFromSaleData($data));
		array_push($bill->variables, $variable);
		$variable = getBillVariable('DocumentDate', 'วันที่แจ้ง',  getCallTime($data));
		array_push($bill->variables, $variable);
		$variable = getBillVariable('SaleName', 'ชื่อผู้ติดต่อ',  '--');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayDate', 'วันที่นัดโอน',   getAppointDate($data));
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayTime', 'เวลาที่นัดโอน',  getAppointTime($data));
		array_push($bill->variables, $variable);
		
		$variable = getBillVariable('CustomerName', 'ชื่อูลกค้า',  $data->SalesName );
		array_push($bill->variables, $variable);

		$variable = getBillVariable('CustomerTel', 'เบอร์โทรลูกค้า',  getCustomerMobileFromSaleData($data));
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PriceOnContract', 'ราคาตามสัญญา',  getPriceOnContractFromSaleData($data));
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PricePerArea', 'ราคาต่อตารางเมตร',  getPricePerAreaSaleData($data));
		array_push($bill->variables, $variable);
		$variable = getBillVariable('SpacialDiscount', 'หักส่วนลดพิเศษ',  getDiscountSaleData($data));
		array_push($bill->variables, $variable);
		
		$variable = getBillVariable('ContractOfSpace', 'พื้นที่ตามสัญญา',  getAreaOnContractFromSaleData($data));
		array_push($bill->variables, $variable);

		$variable = getBillVariable('DifferenOfSpace', 'ส่วนต่างพื้นที่',  getAreaDiffFromSaleData($data));
		array_push($bill->variables, $variable);

		if($isBankPay)
		{
			
			getBanksVariable($bill);
		}else
		{
			$variable = getBillVariable('BankLoanName', 'ชื่อธนาคาร',  '-');
			array_push($bill->variables, $variable);
			$variable = getBillVariable('BankLoanRoom', 'อนุมัติค่าห้อง',  '-');
			array_push($bill->variables, $variable);
			$variable = getBillVariable('BankLoanOther', 'อนุมัติวงเงินอื่น ๆ ',  '-');
			array_push($bill->variables, $variable);
			$variable = getBillVariable('SumBankLoan', 'วงเงินจำนองรวม',  '-');
			array_push($bill->variables, $variable);
			$variable = getBillVariable('BankLoanInsurance', 'อนุมัติวงเงินค่าประกัน',  '-');
			array_push($bill->variables, $variable);
			$variable = getBillVariable('BankLoanMulti', 'อนุมัติวงเงินเอนกประสงค์',  '-');
			array_push($bill->variables, $variable);
			$variable = getBillVariable('BankLoanDecorate', 'อนุมัติวงเงินตกแต่ง',  '-');
			array_push($bill->variables, $variable);
		}
		
		$variable = getBillVariable('ActualSpace', 'พื้นที่จริง',  getActualAreaFromSaleData($data));
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PaidAmount', 'หักชำระแล้ว',  getSettAmount($data));
		array_push($bill->variables, $variable);
	
		
		$variable = getBillVariable('PayCheckBank', 'เช็คสั่งจ่ายธนาคาร',  getBankPayment($data));
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayCheckAnanda', 'เช็คสั่งจ่ายอนันดา',  getCompanyPayment($data));
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayCommonFeeCharge', 'ชำระส่วนกลาง',  '--');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayCommonFeeFund', 'ชำระค่าสมทบ',  '--');
		array_push($bill->variables, $variable);

		$variable = getBillVariable('PayFeeForMinistryOfFinance', 'ชำระค่าธรรมเนียม',  '--');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayFeeForTranferCash', 'แบ่งจ่ายเงินสด',  '--');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('FinalCustomerPayment', 'รวมเป็นเงินที่ต้องชำระ',  '--');
		array_push($bill->variables, $variable);
		
		$variable = getBillVariable('PriceDateOfPayment', 'ราคาห้องชุด ณ วันโอน',  getPriceAtPaydate($data));
		array_push($bill->variables, $variable);
		
		$variable = getBillVariable('PriceRoomOfPayment', 'ค่าห้องชุดที่ต้องชำระ',  getPaymentPrice($data));
		array_push($bill->variables, $variable);
		
		$variable = getBillVariable('Repayment','ค่าปลอด',  getRepayment($data));
		array_push($bill->variables, $variable);

		$variable = getBillVariable('EstimatePrice','',  getEstimatePrice($data));
		array_push($bill->variables, $variable);

		$variable = getBillVariable('ProjectName','ค่าปลอด', getProjectNameFromSaleData($data));
		array_push($bill->variables, $variable);


		

		return $bill;
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

	function getSampleBill($template_id)
	{
		$sample = new stdClass;

		$sample->variables = array();
		$sample->paymentTypes = array("ธนาคาร", "บริษัท", "ลูกค้า");

		$sample->payments = getPaymentsByTemplateId($template_id);
		
		return $sample;	
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
		return array(
			"meters"=>$meters,
			"share_payment_id" => $share_payment_id,
			"tranfer_payment_id" => $tranfer_payment_id,
			"share_fund_payment_id" => $share_fund_payment_id,
			"room_payment" => $room_payment
		);
	}
	
	//testBill();
	//header('Content-Type: text/html; charset=utf-8');
	//actionBill(1);*/
?>