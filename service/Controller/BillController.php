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
		
		foreach($data->variables as $key => $value)
		{
			//print_r($data->variables[$key]);
			$variable = getBillVariable($key, $data->variables[$key]->name, $data->variables[$key]->value);
			array_push($bill->variables, $variable);
		}
	

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

		if(isset($data->ItemType))
			$item_type = $data->ItemType;
		else
			$item_type = "-";

		if(isset($data->master_HOUSESIZE))
			$house_size = $data->master_HOUSESIZE;
		else if(isset($data->HOUSESIZE))
			$house_size = $data->HOUSESIZE;

		if(isset($data->master_LANDSIZE))
			$land_size = $data->master_LANDSIZE;
		else if(isset($data->LANDSIZE))
			$land_size = $data->LANDSIZE;

		if(isset($data->master_SalesName))
			$sale_name = $data->master_SalesName;
		else if(isset($data->SalesName))
			$sale_name = $data->SalesName;
		else
			$sale_name = "-";

		if(isset($data->Mobile))
			$mobile = $data->Mobile;
		else
			$mobile = "-";

		if(isset($data->SellPrice))
			$sellPrice = $data->SellPrice;
		if(isset($data->DiscAmount))
			$discount = $data->DiscAmount;

		if(isset($sellPrice) && isset($discount))
			$price_at_contract = $sellPrice - $discount;
		else
			$price_at_contract = "-";

		if(isset($data->master_Sqm))
			$area = $data->master_Sqm;
		elseif(isset($data->Sqm))
			$area = $data->Sqm;
		elseif(isset($data->SQM))
			$area = $data->SQM;

		if(isset($data->master_BasePrice))
			$price = $data->master_BasePrice;
		else if(isset($data->BasePrice))
			$price = $data->BasePrice;

		if(isset($price) && isset($area))
			$price_per_sqm = $area / $price;
		else
			$price_per_sqm = '-';

		if(!isset($discount))
			$discount = '-';

		if(!isset($area))
			$area = '-';

		if(isset($data->banks))
			$isBankPay = true;
		else
			$isBankPay = false;

		if(isset($house_size ))
			$actual_space = $house_size;
		else
			$actual_space = "-";

		if(isset($house_size ) && isset($land_size))
			$diff_space = $land_size - $house_size;
		else
			$diff_space = "-";

		$variable = getBillVariable('AppointmentMonth', 'เดือนวันที่นัดโอน', 'มีนาคม 2556');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('UnitNumber', 'UNIT NO.', $unit_number);

		array_push($bill->variables, $variable);
		$variable = getBillVariable('CompanyName', 'ที่อยู่', $company_name);
		array_push($bill->variables, $variable);
		$variable = getBillVariable('HouseNumber', 'บ้านเลขที่',  '--');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('HouseType', 'แบบบ้าน',  $item_type);
		array_push($bill->variables, $variable);
		$variable = getBillVariable('HouseSize', 'พื้นที่ใช้สอย',  $house_size);
		array_push($bill->variables, $variable);
		$variable = getBillVariable('DocumentDate', 'วันที่แจ้ง',  '--');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('SaleName', 'ชื่อผู้ติดต่อ',  '--');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayDate', 'วันที่นัดโอน',  '--');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayTime', 'เวลาที่นัดโอน',  '--');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('CustomerName', 'ชื่อูลกค้า',  $sale_name);
		array_push($bill->variables, $variable);
		$variable = getBillVariable('CustomerTel', 'เบอร์โทรลูกค้า',  $mobile);
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PriceOnContract', 'ราคาตามสัญญา',  $price_at_contract);
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PricePerArea', 'ราคาต่อตารางเมตร',  $price_per_sqm);
		array_push($bill->variables, $variable);
		$variable = getBillVariable('SpacialDiscount', 'หักส่วนลดพิเศษ',  $discount);
		array_push($bill->variables, $variable);
		
		$variable = getBillVariable('ContractOfSpace', 'พื้นที่ตามสัญญา',  $area);
		array_push($bill->variables, $variable);

		$variable = getBillVariable('DifferenOfSpace', 'ส่วนต่างพื้นที่',  $diff_space);
		array_push($bill->variables, $variable);

		if($isBankPay)
		{
			$variable = getBillVariable('BankLoanName', 'ชื่อธนาคาร',  'สมภพ');
			array_push($bill->variables, $variable);
			$variable = getBillVariable('BankLoanRoom', 'อนุมัติค่าห้อง',  '6,100,000');
			array_push($bill->variables, $variable);
			$variable = getBillVariable('BankLoanOther', 'อนุมัติวงเงินอื่น ๆ ',  '-');
			array_push($bill->variables, $variable);
			$variable = getBillVariable('SumBankLoan', 'วงเงินจำนองรวม',  '6,200,000');
			array_push($bill->variables, $variable);
			$variable = getBillVariable('BankLoanInsurance', 'อนุมัติวงเงินค่าประกัน',  '100,000.00');
			array_push($bill->variables, $variable);
			$variable = getBillVariable('BankLoanMulti', 'อนุมัติวงเงินเอนกประสงค์',  '-');
			array_push($bill->variables, $variable);
			$variable = getBillVariable('BankLoanDecorate', 'อนุมัติวงเงินตกแต่ง',  '-');
			array_push($bill->variables, $variable);
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
		
		$variable = getBillVariable('ActualSpace', 'พื้นที่จริง',  $actual_space);
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PaidAmount', 'หักชำระแล้ว',  '6,970,200');
		array_push($bill->variables, $variable);
	
		
		$variable = getBillVariable('PayCheckBank', 'เช็คสั่งจ่ายธนาคาร',  '--');
		array_push($bill->variables, $variable);
		$variable = getBillVariable('PayCheckAnanda', 'เช็คสั่งจ่ายอนันดา',  '--');
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
		
		
		$variable = getBillVariable('PriceDateOfPayment', 'ราคาห้องชุด ณ วันโอน',  '-');
		array_push($bill->variables, $variable);
		
		$variable = getBillVariable('PriceRoomOfPayment', 'ค่าห้องชุดที่ต้องชำระ',  '--');
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
		$sample->paymentTypes = array("ธนาคาร", "บริษัท", "ลูกค้า");

		$sample->payments = getPaymentsByTemplateId(5);
		
		return $sample;	
	}
	
	//testBill();
	//header('Content-Type: text/html; charset=utf-8');
	//actionBill(1);*/
?>