<?php
	require_once('util.php');
	//use for preview what bills will be liked
	function actionBills($unit_ids, $template_id)
	{

		$sale_datas = getSaleDatas($unit_ids);
		$bills = array();
		foreach($sale_datas as $sale_data)
		{
			$bill = convertSaleDataToBill($sale_data);
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
		$sale_data = getSaleDatas($unit_ids);
		//print_r($sale_data);
		$variable_units = $sale_data;//getVariableUnits($sales_data);
		//print_r($variable_units);
		for($i = 0;$i < count($unit_ids); $i++)
		{
			
			$unit_id =  $variable_units[$i]->unit_id;
			$variables = $variable_units[$i];
			$variables_json = json_encode($variables);
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
		$bill = convertSaleDataToBill($sale_data);
		$template = findTemplateById($template_id);
		$bill->payments = $template->payments;
		return $bill;
	}

	function testBill()
	{
		$sampleJson = '{"variables":[{"documentName":{"name":"ชื่อเอกสาร","value":"ใบประเมินการค่าใช้จ่ายโอนกรรมสิทธิ์"}},{"companyAddress":{"name":"ที่อยู่","value":"เลขที่ 99/4 หมุ่ที่ 14 ตำบลบางพลีใหญ่ อำเภอบางพลี จังหวัดสมุทรปราการ 10540"}},{"companyPhone":{"name":"โทร","value":"02-3171155 ต่อ 102-109, 121"}},{"companyFax":{"name":"โทรสาร","value":"02-3160180-1"}},{"unitNumber":{"name":"UNIT NO.","value":"MR9-0502"}},{"customerName":{"name":"ลูกค้า","value":"ปุณณตา ดิษฐพงศา"}},{"documentDate":{"name":"วันที่","value":"29 กรกฎาคม 2556"}},{"from":{"name":"จาก","value":"คุณตุ๊กตา085-488-2578/จั่น089-2027962"}},{"payDate":{"name":"วันนัดโอน","value":"15 ค่ำเดือน 11"}},{"payTime":{"name":"เวลา","value":"10.00 น"}},{"subject":{"name":"เรื่อง","value":"รายละเอียดค่าใช้จ่ายต่างๆเกี่ยวกับการโอนกรรมสิทธิ์ห้องชุดเลขที่ M38-A0504"}},{"tel":{"name":"โทร","value":"082-452-3991"}},{"contractSpace":{"name":"พื้นที่ตามสัญญา","value":35.39}},{"houseAddress":{"name":"บ้านเลขที่","value":"88/239"}},{"houseNumber":{"name":"บ้านเลขที่","value":"TH05-2-105002"}},{"noticeDate":{"name":"วันที่แจ้ง","value":"29 กรกฎาคม 2556"}},{"pricePerArea":{"name":"ราคาต่อตารางเมตร","value":106238.10}},{"priceOnContact":{"name":"ราคาตามสัญญา","value":2231000}},{"specialDiscount":{"name":"หัก ส่วนลดพิเศษ","value":0}},{"additionalAreaPrice":{"name":"พื้นที่เพิ่ม (ลด)","value":null}},{"paidAmount":{"name":"หัก ชำระแล้ว","value":307000.00}},{"paidDate":{"name":"วันที่ชำระ","value":"30/7/56"}},{"actualSpace":{"name":"พื้นที่จริง","value":null}},{"bankLoanRoom":{"name":"อนุมัติค่าห้อง","value":0}},{"bankLoanOther":{"name":"อนุมัติวงเงินอื่นๆ","value":0}},{"electricMeter":{"name":"มิเตอร์ไฟฟ้า","value":3250}},{"commonFeeCharge":{"name":"ชำระส่วนกลาง","value":"*ขึ้นอยู่กับพื้นที่จริง"}},{"commonFeeFund":{"name":"เงินสมทบกองทุนส่วนกลาง","value":"*ขึ้นอยู่กับพื้นที่จริง"}},{"feeForMinistryOfFinance":{"name":"ค่าธรรมเนียมสำหรับกระทรวงการคลัง","value":30000}},{"feeForTranferCash":{"name":"ค่าธรรมเนียมเงินสด","value":20000}}],"paymentTypes":["ธนาคาร","บริษัท","ลูกค้า"],"payments":[{"order":1,"name":"ค่าห้องชุดส่วนที่ต้องชำระ","description":"*อาจมีเพิ่ม/ลดตามพื้นที่จริง","formulas":["","","{priceOnContact} - {paidAmount}"]},{"order":2,"name":"ค่ามิเตอร์ไฟฟ้า","description":"15Amp","formulas":["","","{electricMeter}"]},{"order":3,"name":"ค่ามิเตอร์ไฟฟ้า","description":"asdasd","formulas":["","","#*ขึ้นกลับพื้นที่จริง"]}]}';
		print_r(json_decode($sampleJson));
	}

	function loadBill($transaction_row)
	{
		
	}

	function convertSaleDataToBill($data)
	{
		$bill = getSampleBill();
		
		$variable = getBillVariable('AppointmentMonth', 'เดือนวันที่นัดโอน', 'มีนาคม 2556');

		if(isset($data->comp_name_th))
			$company_name = $data->comp_name_th;
		else
			$company_name = '-';

		if(isset($data->master_UnitNo))
			$unit_number = $data->master_UnitNo.' ';
		else if(isset($data->UnitNo))
			$unit_number = $data->UnitNo.' ';
		else
			$unit_number = '-';

		array_push($bill->variables, $variable);
		$variable = getBillVariable('UnitNumber', 'UNIT NO.', $unit_number);

		array_push($bill->variables, $variable);
		$variable = getBillVariable('CompanyName', 'ที่อยู่', $company_name);
		array_push($bill->variables, $variable);
		$variable = getBillVariable('HouseNumber', 'บ้านเลขที่',  '-');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('HouseType', 'แบบบ้าน',  '-');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('HouseSize', 'พื้นที่ใช้สอย',  '-');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('DocumentDate', 'วันที่แจ้ง',  '14 มีนาคม 2556');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('SaleName', 'ชื่อผู้ติดต่อ',  'คุณสุกัญญา');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayDate', 'วันที่นัดโอน',  '29 มีนาคม 2556');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayTime', 'เวลาที่นัดโอน',  '10.00 น.');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('CustomerName', 'ชื่อูลกค้า',  'คุณขจิต  ล้วนพิชญ์พงศ์');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('CustomerTel', 'เบอร์โทรลูกค้า',  '-');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PriceOnContract', 'ราคาตามสัญญา',  '6,970,000');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PricePerArea', 'ราคาต่อตารางเมตร',  '133,550');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('SpacialDiscount', 'หักส่วนลดพิเศษ',  '300,000');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('BnakLoanName', 'ชื่อธนาคาร',  'ธนาคารกรุงไทย จำกัด มหาชน');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('ContractOfSpace', 'พื้นที่ตามสัญญา',  '52.19');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('BankLoanRoom', 'อนุมัติค่าห้อง',  '6,100,000');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('ActualSpace', 'พื้นที่จริง',  '52.50');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PaidAmount', 'หักชำระแล้ว',  '6,970,200');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('BankLoanOther', 'อนุมัติวงเงินอื่น ๆ ',  '-');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('SumBankLoan', 'วงเงินจำนองรวม',  '6,200,000');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayCheckBank', 'เช็คสั่งจ่ายธนาคาร',  '-');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayCheckAnanda', 'เช็คสั่งจ่ายอนันดา',  '-');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayCommonFeeCharge', 'ชำระส่วนกลาง',  '-');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayCommonFeeFund', 'ชำระค่าสมทบ',  '-');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayFeeForMinistryOfFinance', 'ชำระค่าธรรมเนียม',  '99,322.00');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayFeeForTranferCash', 'แบ่งจ่ายเงินสด',  '1,000');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('FinalCustomerPayment', 'รวมเป็นเงินที่ต้องชำระ',  '100,322.00');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('BankLoanInsurance', 'อนุมัติวงเงินค่าประกัน',  '100,000.00');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('DifferenOfSpace', 'ส่วนต่างพื้นที่',  '0.31');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PriceDateOfPayment', 'ราคาห้องชุด ณ วันโอน',  '-');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('BankLoanMulti', 'อนุมัติวงเงินเอนกประสงค์',  '-');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('BankLoanDecorate', 'อนุมัติวงเงินตกแต่ง',  '-');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PriceRoomOfPayment', 'ค่าห้องชุดที่ต้องชำระ',  '6,970,000');
		array_push($bill->variables, $variable);
		
		$bill->variables[11]->contractSpace->value = $sale_data->sqm;
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

	function getBillVariable($codename, $description, $value)
	{
		$variable = new stdClass;
		$variable->$codename = new stdClass;

		$variable->$codename->name = $description;
		$variable->$codename->value = $value;

		return $variable;
	}


	function getSampleBill()
	{
		$sample = new stdClass;

		$sample->variables = array();
		$sample->variables[0] = new stdClass;
		$sample->variables[0]->documentName  = new stdClass;
		$sample->variables[0]->documentName->name = 'ชื่อเอกสาร';
		$sample->variables[0]->documentName->value = 'ใบประเมินการค่าใช้จ่ายโอนกรรมสิทธิ์';

		$sample->variables[1] = new stdClass;
		$sample->variables[1]->companyAddress  = new stdClass;
		$sample->variables[1]->companyAddress->name = 'ที่อยู่';
		$sample->variables[1]->companyAddress->value = 'เลขที่ 99/4 หมุ่ที่ 14 ตำบลบางพลีใหญ่ อำเภอบางพลี จังหวัดสมุทรปราการ 10540';

		$sample->variables[2] = new stdClass;
		$sample->variables[2]->companyPhone  = new stdClass;
		$sample->variables[2]->companyPhone->name = 'โทร';
		$sample->variables[2]->companyPhone->value = '02-3171155 ต่อ 102-109, 121';

		$sample->variables[3] = new stdClass;
		$sample->variables[3]->companyFax  = new stdClass;
		$sample->variables[3]->companyFax->name = 'โทรสาร';
		$sample->variables[3]->companyFax->value = '02-3160180-1';

		$sample->variables[4] = new stdClass;
		$sample->variables[4]->customerName  = new stdClass;
		$sample->variables[4]->customerName->name = 'ลูกค้า';
		$sample->variables[4]->customerName->value = 'ปุณณตา ดิษฐพงศา';

		$sample->variables[5] = new stdClass;
		$sample->variables[5]->documentDate  = new stdClass;
		$sample->variables[5]->documentDate->name = 'วันที่';
		$sample->variables[5]->documentDate->value = '29 กรกฎาคม 2556';

		$sample->variables[6] = new stdClass;
		$sample->variables[6]->from  = new stdClass;
		$sample->variables[6]->from->name = 'จาก';
		$sample->variables[6]->from->value = 'คุณตุ๊กตา085-488-2578/จั่น089-2027962';

		$sample->variables[7] = new stdClass;
		$sample->variables[7]->payDate  = new stdClass;
		$sample->variables[7]->payDate->name = 'วันนัดโอน';
		$sample->variables[7]->payDate->value = '15 ค่ำเดือน 11';

		$sample->variables[8] = new stdClass;
		$sample->variables[8]->payTime  = new stdClass;
		$sample->variables[8]->payTime->name = 'เวลา';
		$sample->variables[8]->payTime->value = '10.00 น';

		$sample->variables[9] = new stdClass;
		$sample->variables[9]->payTime  = new stdClass;
		$sample->variables[9]->payTime->name = 'เวลา';
		$sample->variables[9]->payTime->value = '10.00 น';

		$sample->variables[10] = new stdClass;
		$sample->variables[10]->tel  = new stdClass;
		$sample->variables[10]->tel->name = 'โทร';
		$sample->variables[10]->tel->value = '082-452-3991';

		$sample->variables[11] = new stdClass;
		$sample->variables[11]->contractSpace  = new stdClass;
		$sample->variables[11]->contractSpace->name = 'พื้นที่ตามสัญญา';
		$sample->variables[11]->contractSpace->value = 35.39;

		$sample->variables[12] = new stdClass;
		$sample->variables[12]->houseAddress  = new stdClass;
		$sample->variables[12]->houseAddress->name = 'บ้านเลขที่';
		$sample->variables[12]->houseAddress->value = "TH05-2-105003";

		$sample->variables[13] = new stdClass;
		$sample->variables[13]->noticeDate  = new stdClass;
		$sample->variables[13]->noticeDate->name = 'วันที่แจ้ง';
		$sample->variables[13]->noticeDate->value = '30 กรกฎาคม 2556';

		$sample->variables[14] = new stdClass;
		$sample->variables[14]->pricePerArea  = new stdClass;
		$sample->variables[14]->pricePerArea->name = 'ราคาต่อตารางเมตร';
		$sample->variables[14]->pricePerArea->value = 102238.10;

		$sample->variables[15] = new stdClass;
		$sample->variables[15]->priceOnContact  = new stdClass;
		$sample->variables[15]->priceOnContact->name = 'ราคาตามสัญญา';
		$sample->variables[15]->priceOnContact->value = 2731000;

		$sample->variables[16] = new stdClass;
		$sample->variables[16]->specialDiscount  = new stdClass;
		$sample->variables[16]->specialDiscount->name = 'หัก ส่วนลดพิเศษ';
		$sample->variables[16]->specialDiscount->value = 0;

		$sample->variables[17] = new stdClass;
		$sample->variables[17]->additionalAreaPrice  = new stdClass;
		$sample->variables[17]->additionalAreaPrice->name = 'พื้นที่เพิ่ม (ลด)';
		$sample->variables[17]->additionalAreaPrice->value = null;

		$sample->variables[18] = new stdClass;
		$sample->variables[18]->paidAmount  = new stdClass;
		$sample->variables[18]->paidAmount->name = 'หัก ชำระแล้ว';
		$sample->variables[18]->paidAmount->value = 307000.00;

		$sample->variables[19] = new stdClass;
		$sample->variables[19]->paidDate  = new stdClass;
		$sample->variables[19]->paidDate->name = 'วันที่ชำระ';
		$sample->variables[19]->paidDate->value = "30/7/56";

		$sample->variables[20] = new stdClass;
		$sample->variables[20]->actualSpace  = new stdClass;
		$sample->variables[20]->actualSpace->name = 'พื้นที่จริง';
		$sample->variables[20]->actualSpace->value = null;

		$sample->variables[21] = new stdClass;
		$sample->variables[21]->bankLoanRoom  = new stdClass;
		$sample->variables[21]->bankLoanRoom->name = 'อนุมัติค่าห้อง';
		$sample->variables[21]->bankLoanRoom->value = 0;

		$sample->variables[22] = new stdClass;
		$sample->variables[22]->bankLoanOther  = new stdClass;
		$sample->variables[22]->bankLoanOther->name = 'อนุมัติวงเงินอื่นๆ';
		$sample->variables[22]->bankLoanOther->value = 0;

		$sample->variables[23] = new stdClass;
		$sample->variables[23]->electricMeter  = new stdClass;
		$sample->variables[23]->electricMeter->name = 'มิเตอร์ไฟฟ้า';
		$sample->variables[23]->electricMeter->value = 3250;

		$sample->variables[24] = new stdClass;
		$sample->variables[24]->commonFeeCharge  = new stdClass;
		$sample->variables[24]->commonFeeCharge->name = 'ชำระส่วนกลาง';
		$sample->variables[24]->commonFeeCharge->value = 5000;

		$sample->variables[25] = new stdClass;
		$sample->variables[25]->commonFeeFund  = new stdClass;
		$sample->variables[25]->commonFeeFund->name = 'เงินสมทบกองทุนส่วนกลาง';
		$sample->variables[25]->commonFeeFund->value = 2000;

		$sample->variables[26] = new stdClass;
		$sample->variables[26]->feeForMinistryOfFinance  = new stdClass;
		$sample->variables[26]->feeForMinistryOfFinance->name = 'ค่าธรรมเนียมสำหรับกระทรวงการคลัง';
		$sample->variables[26]->feeForMinistryOfFinance->value = 30000;

		$sample->variables[27] = new stdClass;
		$sample->variables[27]->feeForTranferCash  = new stdClass;
		$sample->variables[27]->feeForTranferCash->name = 'ค่าธรรมเนียมเงินสด';
		$sample->variables[27]->feeForTranferCash->value = 20000;

		$sample->paymentTypes = array("ธนาคาร", "บริษัท", "ลูกค้า");

		$sample->payments = getPaymentsByTemplateId(5);
		
		return $sample;	
	}
	
	//testBill();
	//header('Content-Type: text/html; charset=utf-8');
	//actionBill(1);*/
?>