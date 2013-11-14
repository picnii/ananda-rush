<?php
    require_once('util.php');
    //$isReport = true;    
    //use for preview what bills will be liked
	function getReportTranfer($unitID)
	{
		/*
		$data = array(
			'unit'=>'1',
			'appoint'=>'asd',
			'logs'=> 'cool'
		);
		*/
		$bills = getSaleDatas(array(1302, 1303, 1304));

        $datas = array();
        foreach ($bills as $key => $value) {
            # code...
            $data = new stdClass;

            //print_r($bills);

            $bill = $value;

            // id & owner section
            $data->id = $key;

            if(isset($bill->ItemID))
                $data->itemNumber = $bill->ItemID;
            else
                $data->itemNumber = '?';

            if(isset($bill->plangNumber))
                $data->plangNumber = $bill->plangNumber;
            else
                $data->plangNumber = '?';

            $data->customerName = convertUTF8(getCustomerNameFromSaleData($bill));

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

            array_push($datas, $data);
        }

		return $datas;
	}

    function getLandInfo($bill){
        $landInfo = new stdClass;

        $landInfo->deedNumber = isset($bill->deedNumber) ? $bill->deedNumber : '?';
        $landInfo->landNumber = isset($bill->landNumber) ? $bill->landNumber : '?';
        $landInfo->surveyNumber = isset($bill->surveyNumber) ? $bill->surveyNumber : '?';
        $landInfo->landSize = getAreaOnContractFromSaleData($bill);
        $landInfo->estimateLandPricePerUnit = isset($bill->estimateLandPricePerUnit) ? $bill->estimateLandPricePerUnit : '?';
        $landInfo->estimateLandPriceSum = isset($bill->estimateLandPriceSum) ? $bill->estimateLandPriceSum : '?';

        return $landInfo;
    }

    function getHouseInfo($bill){
        $houseInfo = new stdClass;

        $houseInfo->number = isset($bill->IVZ_PROJSALESTITLEDEEDNUMBER) ? $bill->IVZ_PROJSALESTITLEDEEDNUMBER : '?';
        $houseInfo->type = getItemTypeFromSaleData($bill);
        $houseInfo->area =  getAreaFromSaleData($bill);
        $houseInfo->pricePerArea =  5850;
        $houseInfo->estimatePriceSum = $houseInfo->area * $houseInfo->pricePerArea;
        $houseInfo->priceSum = $houseInfo->estimatePriceSum - $houseInfo->depreciate;
        $houseInfo->built = 2555;
        $houseInfo->old = 2556 - $houseInfo->built;
        $houseInfo->depreciate = $houseInfo->estimatePriceSum * 0.01 * $houseInfo->old;

        return $houseInfo;
    }

    function getFenceInfo($bill){
        $fenceInfo = new stdClass;

        $fenceInfo->area = 30;
        $fenceInfo->pricePerArea = 1400;
        $fenceInfo->estimatePriceSum = $fenceInfo->area * $fenceInfo->pricePerArea;
        $fenceInfo->depreciate = $fenceInfo->estimatePriceSum * 0.01;
        $fenceInfo->priceSum = $fenceInfo->estimatePriceSum - $fenceInfo->depreciate;

        return $fenceInfo;
    }

    function getCompanyPaymentInfo($data){
        $paymentInfo = new stdClass;

        $paymentInfo->transferFee = $data->land->estimateLandPriceSum * 0.01;
        $paymentInfo->tax = $data->salePrice * 0.01;
        $paymentInfo->businessFee = $data->salePrice * 0.033;
        $paymentInfo->summationFee = $paymentInfo->transferFee + $paymentInfo->tax + $paymentInfo->businessFee;

        $paymentInfo->waterMeter = 7020;
        $paymentInfo->powerMeter = 6550;
        $paymentInfo->contribution = 3000;
        $paymentInfo->powerMeterNet = $paymentInfo->powerMeter - $paymentInfo->contribution;

        return $paymentInfo;
    }

    function getPromotionInfo($bill){
        $promotionInfo = new stdClass;

        //print_r($bill->promotions);

        return $promotionInfo;
    }

?>