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

    function actionReportPromotions()
    {
        
        return convertToPromotionTable(getReportPromotions());
    }

    function convertToPromotionTable($response)
    {
        $header = array('Project', 'So No.', 'Project', 'CreateDate ');
        //print_r($response);
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

           // $unit = findUnitById($row['unit_id']);

            $table = $table."<tr>";
         
            $table = $table."<td>";
            $table = $table.$row['id'];
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row['amount'];
            $table = $table."</td>";

             $table = $table."<td>";
            $table = $table.$row['name'];
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table."hi jack";// $table.convertutf8($row['proj_name_th']);
            $table = $table."</td>";

            $table = $table."<td>";
            $table = $table.$row['SO'];
            $table = $table."</td>";
                   
            $table = $table."</tr>";
        }

        $table = $table."</tbody>";
        //end table tag
        $table = $table."</table>";
        return $table;
    }




?>