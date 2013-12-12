<?php
    require_once('util.php');
    //$isReport = true;    
    //use for preview what bills will be liked

    function getPromotionsHeader()
    {

        $promotions = findAllPromotion();
        $cashPromos = array('Cashback AX', 'ส่วนลด AX', 'ส่วนลดพิเศษ AX');
        foreach ($promotions as $key => $value) {
            # code...
            if($value['reward_id'] > 0)
            {
                array_push($cashPromos, $value['name']);
            }
        }
        //print_r('======================================================================================\n');
        //print_r($cashPromos);
        //print_r('======================================================================================\n');
        return $cashPromos;
    }

	function getReportTranfer($unitIds)
	{
        if(!isset($unitIds))
            $unitIds = array(1302, 1303, 1304, 3094, 3095, 3096);
		$bills = getSaleDatas($unitIds);
        //$bills = getSaleDatas(getAllTransactionIds(20));
        //print_r(getAllTransactionIds());

        $promoHeaders = getPromotionsHeader();

        $datas = array();
        foreach ($bills as $key => $value)
        {
            $data = new stdClass;

            //print_r($bills);

            $bill = $value;

            // id & owner section
            $data->id = $key + 1;

            if(isset($bill->ItemID))
                $data->itemNumber = $bill->ItemID; //ok
            else
                $data->itemNumber = '?';

            if(isset($bill->plangNumber))
                $data->plangNumber = $bill->plangNumber;
            else
                $data->plangNumber = '?';

            $data->customerName = convertUTF8(getCustomerNameFromSaleData($bill));

            if(isset($bill->people))
                $data->owner = $bill->people; // ok
            else
                $data->owner = $data->customerName;

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
            $data->promotion = getPromotionInfo($bill, $promoHeaders);
            $data->transferInfo = getTransferInfo($bill);
            $data->customerInfo = getCustomerInfo($bill);

            array_push($datas, $data);
        }

		return $datas;
	}

    function getLandInfo($bill){
        $landInfo = new stdClass;

        $landInfo->deedNumber = isset($bill->deedNumber) ? $bill->deedNumber : '?';
        $landInfo->landNumber = isset($bill->landNumber) ? $bill->landNumber : '?';
        $landInfo->surveyNumber = isset($bill->surveyNumber) ? $bill->surveyNumber : '?';
        $landInfo->landSize = getAreaOnContractFromSaleData($bill) / 4;
        $landInfo->estimateLandPriceSum = isset($bill->estimateLandPriceSum) ? $bill->estimateLandPriceSum : 0;
        $landInfo->estimateLandPricePerUnit = isset($bill->estimateLandPricePerUnit) ? $bill->estimateLandPricePerUnit : $landInfo->estimateLandPriceSum / $landInfo->landSize;

        return $landInfo;
    }

    function getHouseInfo($bill){
        $houseInfo = new stdClass;

        $houseInfo->number = isset($bill->IVZ_PROJSALESTITLEDEEDNUMBER) ? $bill->IVZ_PROJSALESTITLEDEEDNUMBER : '?';
        $houseInfo->type = getItemTypeFromSaleData($bill);
        $houseInfo->area =  getAreaFromSaleData($bill);
        $houseInfo->pricePerArea =  5850;
        $houseInfo->estimatePriceSum = $houseInfo->area * $houseInfo->pricePerArea;
        $houseInfo->built = 2555;
        $houseInfo->old = 2556 - $houseInfo->built;
        $houseInfo->depreciate = $houseInfo->estimatePriceSum * 0.01 * $houseInfo->old;
        $houseInfo->priceSum = $houseInfo->estimatePriceSum - $houseInfo->depreciate;

        return $houseInfo;
    }

    function getFenceInfo($bill){
        $fenceInfo = new stdClass;

        $fenceInfo->area = 0;
        $fenceInfo->pricePerArea = 1400;
        $fenceInfo->estimatePriceSum = $fenceInfo->area * $fenceInfo->pricePerArea;
        $fenceInfo->depreciate = $fenceInfo->estimatePriceSum * 0.01;
        $fenceInfo->priceSum = $fenceInfo->estimatePriceSum - $fenceInfo->depreciate;

        return $fenceInfo;
    }

    function getCompanyPaymentInfo($data){
        $paymentInfo = new stdClass;

        $paymentInfo->transferFee = $data->estimatePriceSum * 0.01;
        $paymentInfo->tax = $data->salePrice * 0.01;
        $paymentInfo->businessFee = $data->salePrice * 0.033;
        $paymentInfo->summationFee = $paymentInfo->transferFee + $paymentInfo->tax + $paymentInfo->businessFee;

        $paymentInfo->waterMeter = 7020;
        $paymentInfo->powerMeter = 6550;
        $paymentInfo->contribution = 3000;
        $paymentInfo->powerMeterNet = $paymentInfo->powerMeter - $paymentInfo->contribution;

        return $paymentInfo;
    }

    function getPromotionInfo($bill, $headers){
        $promotionInfo = array();

        foreach ($headers as $h => $header) {
            $promotionInfo[$header] = 0;
            foreach ($bill->promotions as $k => $promo) {
                if($promo['name'] == $header)
                    $promotionInfo[$header] += $promo['amount'];
                else if ($promo['type_id'] == $h)
                    $promotionInfo[$header] += $promo['amount'];
            }
        }
        //print_r($bill);

        return $promotionInfo;
    }

    function getTransferInfo($bill)
    {
        $transferInfo = new stdClass;

        $transferInfo->landOfficeName = '?';
        $transferInfo->appointmentDate = getAppointDate($bill);
        $transferInfo->status = '?';
        $transferInfo->appointmentTime = getAppointTime($bill);
        $transferInfo->amount = '?';
        $transferInfo->transferDate = '?';

        return $transferInfo;
    }

    function getCustomerInfo($bill)
    {
        $customerInfo = new stdClass;

        $customerInfo->maritalStatus = '?';
        $customerInfo->transferMethod = '?';
        $customerInfo->bank = '?';
        $customerInfo->branch = '?';
        $customerInfo->bankLoanRoom = '?';
        $customerInfo->bankLoanOther = '?';
        $customerInfo->bankLoanSum = '?';
        $customerInfo->howToTransfer = '?';
        $customerInfo->csbu = '?';
        $customerInfo->remark = '?';

        return $customerInfo;
    }

?>