<?php
	require_once('util.php');
    $isReport = true;
	//use for preview what bills will be liked
	function actionReportAppoinment()
	{
		/*
		$data = array(
			'unit'=>'1',
			'appoint'=>'asd',
			'logs'=> 'cool'
		);
		*/
		$bills = getSaleDatas(array(1302));
		$data = new stdClass;

        //print_r($bills);

        $bill = $bills[0];

		// id & owner section
		$data->id = 1;

		if(isset($bill->ItemID))
            $data->itemNumber = $bill->ItemID;
        else
            $data->itemNumber = '?';

		if(isset($bill->plangNumber))
            $data->plangNumber = $bill->plangNumber;
        else
            $data->plangNumber = '?';

		$data->customerName = getCustomerNameFromSaleData($bill);

		if(isset($bill->people))
            $data->owner = $bill->people;
        else
            $data->owner = '?';

        $data->land = getLandInfo($bill);
        $data->house = getHouseInfo($bill);
        $data->fence = getFenceInfo($bill);
        $data->estimatePriceSum = $data->land->estimateLandPriceSum + $data->house->priceSum + $data->fence->priceSum;
        
        // about price section
		//if(isset($bill->IVZ_ESTIMATEPRICE))
        //    $data['estimatePriceSum'] = $bill->IVZ_ESTIMATEPRICE;
        //else
        //    $data['estimatePriceSum'] = '?';

        $data->salePrice = getPriceOnContractFromSaleData($bill);
        $data->loanRepayment = isset($bill->IVZ_LOANREPAYMENTMINIMUNAMT) ? $bill->IVZ_LOANREPAYMENTMINIMUNAMT : '?';
        $data->companyPayment = getCompanyPaymentInfo($data);
        $data->promotion = getPromotionInfo($bill);
        
		return $data;
	}


    function actionReportTranfer()
    {

    }



    function actionReportPromotions($q = "*")
    {
        if($q != "*")
        {
          $params = getParamsFromSearchQuery($q, 'master_transaction', array(
                'SalesName' => 'Sale_Transection',
                'SQM' => 'Sale_Transection',
                'Period' => 'appointment'
            ));
            $operators = array(
                'Sale_Transection.SalesName' => 'LIKE',
                'Sale_Transection.SQM' => 'BETWEEN',
                'appointment.Period' => 'PERIOD'
            );
            //print_r($params);
             $units = findAllUnitsByQuery($params, $operators);
             //print_r($units);
        }else
            $units = null;
        return convertToPromotionTable(getReportPromotions($units));
        //return "<table></table>";
    }

    function convertToPromotionTable($response)
    {
        $promotion_types = getPromotionRewardTypes();
        $promotion_phases = getPhases();
        $promotion_discount_types = getDiscountTypes();
        $header = array('Project', 'So No.',  'CreateDate ', 'Item ID', 'ID', "Name", "Qty", "Amount", "Phase", "หักค่าอช", "issue");
        //print_r($response);
        //create table tag

        $table = "<table>";
        //create tr in head using header
        $table = $table."<thead>";
        $table = $table."<tr style='background:#333'>";
        foreach ($header as $key => $value) {
            # code...
            $table = $table."<td>";
            $table = $table.$value;
            $table = $table."</td>";
        }
        $table = $table."</tr>";
        $table = $table."</thead>";
        //create content using response
        $table = $table."<tbody>";

        //print_r($response);
        foreach ($response as $key => $row) {
            # code...

           // $unit = findUnitById($row['unit_id']);

            $table = $table."<tr>";
         
            $table = $table."<td>";
            $table = $table.$row['proj_code'];
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row['SO'];
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row['create_time'];
            $table = $table."</td>";

            //item_id
            $table = $table."<td>";
            $table = $table.$row['item_id'];
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row['id'];
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row['name'];
            $table = $table."</td>";

            $table = $table."<td>";
            if($row['phase'] == $promotion_phases['ax']->id)
                 $table = $table.$row['qty'];
            else if($row['type_id'] == $promotion_types['stuff']->id)
                $table = $table.$row['amount'];
            else
                $table = $table."1";


            $table = $table."</td>";

             $table = $table."<td>";
             if($row['phase'] == $promotion_phases['ax']->id)
                $table = $table.$row['amount'];
             else if($row['type_id'] == $promotion_types['stuff']->id)
                $table = $table." ";
            else if($row['option2'] == $promotion_discount_types['percent']->id)
                $table = $table.$row['amount']."%";
            else
                $table = $table.$row['amount'];
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$promotion_phases[$row['phase']]->name;
            $table = $table."</td>";

            $table = $table."<td>";
            if($row['type_id'] == $promotion_types['spacial']->id )
                $table = $table."Yes";
            else
                $table = $table."No";
            $table = $table."</td>";

            $table = $table."<td>";
            if($row['issue'] != 1)
               $table = $table."No";
            else
                $table = $table."Yes";
            $table = $table."</td>";

            $table = $table."</tr>";
        }

        $table = $table."</tbody>";
        //end table tag
        $table = $table."</table>";
        return $table;
    }




?>