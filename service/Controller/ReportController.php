<?php
	require_once('util.php');
    $isReport = true;
	
    function actionReportTranfer($unitID=null)
    {
        return convertToTranferTable(getReportTranfer($unitID));
    }

    function convertToTranferTable($response)
    {
        $header = array('ลำดับ', 'Item no.', 'แปลงขายเลขที่', 'ชื่อลูกค้า', 'กรรมสิทธิ์', 'โฉนด', 'เลขที่ดิน', 'หน้าสำรวจ', 'เนื้อที่ดิน ตรว.', 'ตรว. ละ', 'รวม (ที่ดิน)', 
            'บ้านเลขที่', 'แบบบ้าน', 'พื้นที่ใช้สอย (ตรม.)', 'ตรม. ละ', 'ราคาประเมิณ', 'หักค่าเสื่อม', 'รวม', 'ออกเมื่อวันที่', 'ปี', 
            'พื้นที่รั้ว (ตรม.)', 'ตรม. ละ', 'ราคาประเมิณ', 'หักค่าเสื่อม', 'รวม', 
            'รวมราคาประเมิณ', 'ราคาขาย (ที่ดิน + บ้าน)', 'ค่าปลอด KK', 'ค่าโอน', 'ภาษี', 'ธุรกิจเฉพาะ', 'รวมค่าใช้จ่าย', 'ค่ามิเตอร์น้ำ', 'ค่ามิเตอร์ไฟ', 'หักสมทบ', 'หลังหักเงินค่าสมทบ', 
            'ส่วนลดพิเศษ', 'มูลค่า', 'รวมสุทธิ', 'สำนักงานที่ดิน', 'วันที่นัดโอนกรรมสิทธิ์', 'สถานะการปลอดโฉนด', 'เวลา', 'จำนวน', 'วันที่โอนจริง', 
            'สถานะลูกค้า', 'สถานะรับโอน', 'ธนาคาร', 'สาขา', 'วงเงินค่าห้อง', 'วงเงินอื่น', 'วงเงินจำนองรวม', 'มอบ/ไปเอง', 'CS BU', 'remark');
        print_r($response);

        //create table tag
        $table = "<table>";
        //create tr in head using header
        $table = $table."<thead>";
        $table = $table."<tr>";
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
        foreach ($response as $key => $row) {
            # code...

            //$unit = findUnitById($row['unit_id']);

            $table = $table."<tr>";
         
            $table = $table."<td>";
            $table = $table.$row->id;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->itemNumber;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->plangNumber;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->customerName;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->owner;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->land->deedNumber;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->land->landNumber;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->land->surveyNumber;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->land->landSize;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->land->estimateLandPricePerUnit;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->land->estimateLandPriceSum;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->house->number;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->house->type;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->house->area;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->house->pricePerArea;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->house->estimatePriceSum;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->house->depreciate;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->house->priceSum;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->house->built;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->house->old;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->fence->area;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->fence->pricePerArea;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->fence->estimatePriceSum;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->fence->depreciate;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->fence->priceSum;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->estimatePriceSum;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->salePrice;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->loanRepayment;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->companyPayment->transferFee;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->companyPayment->tax;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->companyPayment->businessFee;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->companyPayment->summationFee;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->companyPayment->waterMeter;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->companyPayment->powerMeter;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->companyPayment->contribution;
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row->companyPayment->powerMeterNet;
            $table = $table."</td>";

            /*
            $table = $table."<td>";
            $table = $table.$row->promotion;
            $table = $table."</td>";
            */
                   
            $table = $table."</tr>";
        }

        $table = $table."</tbody>";
        //end table tag
        $table = $table."</table>";
        return $table;
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
            else if($row['option2'] == $promotion_discount_types['percent']->id || $row['type_id'] ==  $promotion_types['spacialarea']->id )
                $table = $table.$row['amount']."%";
            else
                $table = $table.$row['amount'];
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$promotion_phases[$row['phase']]->name;
            $table = $table."</td>";

            $table = $table."<td>";
            if($row['type_id'] == $promotion_types['spacial']->id || $row['type_id'] ==  $promotion_types['spacialarea']->id )
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

             $table = $table."<td>";
                $table = $table.$promotion_types[$row['type_id']]->name;
            $table = $table."</td>";

             $table = $table."</td>";

             $table = $table."<td>";
                $table = $table.$row['is_select'];
            $table = $table."</td>";

            $table = $table."</tr>";
        }

        $table = $table."</tbody>";
        //end table tag
        $table = $table."</table>";
        return $table;
    }




?>