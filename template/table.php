<?php
header('Content-Type: text/html; charset=UTF-8');
header('Content-type: application/msexcel');

header("Content-type: application/octetstream");

// It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="report.xls"');

?>

<html>
	<head>
	<title></title>
		<link href="bill/bill/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="bill/bill/bootstrap/js/bootstrap.js" ></script>
		<link href="bill/bill/style.css" rel="stylesheet" type="text/css" />
		<meta charset="utf-8" />
	</head>
	<body>
	<table border="1" style="font-size:14px;">
		<font size="2">
   			** TABLE HEADER ROWS GO HERE **
   
    	<tr>
		<th rowspan="3" style=" width:50px;">ลำดับ</th>
		<th rowspan="3" style="width:100px;">Project No.</th>
		<th rowspan="3" style="width:100px;">Item No.</th>
		<th rowspan="3" style="width:80px;">Unit No.</th>
		<th rowspan="3" width="200px">ชื่อลูกค้า</th>
		<th rowspan="3" width="200px">กรรมสิทธิ์</th>
		<th rowspan="3" style="width:100px; background-color:#FFCC99;">โฉนด</th>
		<th rowspan="3" style="width:100px; background-color:#FFCC99;">เลขที่ดิน</th>
		<th rowspan="3" style="width:100px; background-color:#FFCC99;">หน้าสำรวจ</th>
		<th rowspan="3" style="width:100px; background-color:#FFCC99;">บ้านเลขที่</th>
		<th rowspan="3" style="width:100px; background-color:#FFCC99;">แบบห้อง/แบบบ้าน</th>
		<th colspan="3" style="background-color: #FF6699">พื้นที่</th>
		<th colspan="6" style="width:300px; background-color:#0099FF;">ราคา</th>
		<th colspan="4" style="width:100px;">คำนวนค่าปลอด</th>
		<th colspan="6" style="width:100px;">สินเชื่อลูกค้า</th>
		<th colspan="5" style="width:100px;">Promotion</th>
		<th colspan="5" style="width:100px;">สถานะลูกค้า</th>
		<th colspan="8" style="width:100px;">บริษัทเตรียมเงินวันโอนกรรมสิทธิ์</th>
		<th rowspan="3" style="width:100px;">สำนักงานที่ดิน</th>
		<th rowspan="3" style="width:100px;">วันที่นัดโอนกรรมสิทธิ์</th>
		<th rowspan="3" style="width:100px;">เวลา</th>
		<th rowspan="3" style="width:100px;">สถานะการปลอดโฉนด</th>
		<th rowspan="3" style="width:100px;">วันที่โอนจริง</th>
      	</tr>
		
		<tr>
		
		<th rowspan="2" style="width:100px; background-color:#FFFF66">พื้นที่ขายตามสัญญา</td>
		<th rowspan="2" style="width:200px; background-color:#FF99FF">พื้นที่ตามโฉนด</th>
		<th rowspan="2" style="width:100px; background-color: #CCFFFF;">พื้นที่เพิ่ม/ลด</th>
	   <th  rowspan="2" style="width:100px; background-color: #CCFFFF;">ราคา/ตรม.</th>
	   <th  rowspan="2" style="width:100px; background-color: #CCFFFF;">ราคาตามสัญญา</th>
	   <th  rowspan="2" style="width:100px; background-color: #CCFFFF;">บวก/หัก พื้นที่ เพิ่ม /ลด</th>
	   <th  rowspan="2" style="width:100px; background-color: #CCFFFF;">หักส่วนลดพิเศษ</th>
	   <th  rowspan="2" style="width:100px; background-color: #CCFFFF;">ราคาสุทธิตามโฉนด/อช.</th>
	   <th  rowspan="2" style="width:100px; background-color: #CCFFFF;">คงเหลือชำระวันโอน</th>
	   <th  rowspan="2" style="width:100px; ">Min.Amount</th>
	   <th  rowspan="2" style="width:100px; ">จาก@ ขั้นต่ำตรม.</th>
	   <th  rowspan="2" style="width:100px; ">จาก%net Price</th>
	   <th  rowspan="2" style="width:100px; ">ค่าปลอด</th>
	   <th colspan="3" style="width:100px; ">ธนาคาร</th>
	   <th colspan="3" style="width:100px; ">วงเงินสินเชื่อ</th>
	   <th  rowspan="2" style="width:100px; ">ฟรีค่าโอน 1%</th>
	   <th  rowspan="2" style="width:100px; ">ฟรีค่าส่วนกลาง</th>
	   <th  rowspan="2" style="width:100px; ">ฟรีเงินสมทบกองทุน</th>
	   <th  rowspan="2" style="width:100px; ">Cash back</th>
	   <th  rowspan="2" style="width:100px; ">ฟรีจำนอง</th>
	   <th  rowspan="2" style="width:100px; ">สถานภาพ</th>
	   <th  rowspan="2" style="width:100px; ">สถานะรับโอน</th>
	   <th  rowspan="2" style="width:100px; ">มอบ/ไปเอง</th>
	   <th  rowspan="2" style="width:100px; ">CS BU</th>
	   <th  rowspan="2" style="width:100px; ">Remarks</th>
	   <th  rowspan="2" style="width:100px; ">ค่าโอน 1%</th>
	   <th  rowspan="2" style="width:100px; ">ภาษี 1%</th>
	   <th  rowspan="2" style="width:100px; ">ธุรกิจเฉพาะ 3.3%</th>
	   <th  rowspan="2" style="width:100px; ">รวมค่าใช้จ่าย</th>
	   <th  rowspan="2" style="width:100px; ">ค่ามิเตอร์น้ำ(บาท)</th>
	   <th  colspan="3" style="width:100px; ">ค่าไฟฟ้า(บาท)</th>
		</tr>
		
		<tr>
			<th style=" width:100px;">ธนาคาร</th>
			<th style=" width:100px;">สาขา</th>
			<th style=" width:100px;">ผู้ติดต่อ</th>
			<th style=" width:100px;">วงเงินค่าห้อง</th>
			<th style=" width:100px;">วงเงินอื่น ๆ </th>
			<th style=" width:100px;">รวมวงเงินสินเชื่อ</th>
			<th style=" width:100px;">ค่ามิเตอร์</th>
			<th style=" width:100px;">หักสมทบ</th>
			<th style=" width:100px;">หลังหักเงินค่า</th>
		
		</tr>
		
		<tr>
			<td>1.</td> <!-->No.< !-->
			<td>TH03-2-101001</td> <!-->Item No< !-->
			<td>TH03C101001</td> <!-->Item No< !-->
			<td>A0101</td> <!-->Unit No.< !-->
			<td>กฤตยา  สุวรรณแก้ว</td> <!-->ชื่อลูกค้า< !-->
			<td>-</td> <!-->กรรมสิทธิ์ < !-->
			<td>104999</td><!-->โฉนด< !-->
			<td>965</td><!-->เลขที่ดิน< !-->
			<td>35388</td><!-->หน้าสำรวจ< !-->
			<td>2097/39</td><!-->บ้านเลขที่< !-->
			<td>104999</td><!-->โฉนด< !-->
			<td>Shop</td><!-->แบบห้อง/แบบบ้าน< !-->
			<td>95.81</td><!-->พื้นที่ตามสัญญา< !-->
			<td>-</td><!-->พื้นที่ตามโฉนด< !-->
			<td>95.81</td><!-->พื้นที่เพิ่ม/ลด< !-->
			<td>-</td><!-->ราคา/ตรม.< !-->
			<td>-</td><!-->ราคาตามสัญญา< !-->
			<td>-</td><!-->บวก/หักพื้นที่เพิ่ม/ลด< !-->
			<td>-</td><!-->หักส่วนลดพิเศษ< !-->
			<td>-</td><!-->ราคาสุทธิตามโฉนด< !-->
			<td>-</td><!-->คงเหลือชำระวันโอน< !-->
			<td>-</td><!-->Min. Amount< !-->
			<td>-</td><!-->จาก @ ขั้นต่ำตรมใ< !-->
			<td>-</td><!-->จาก%net Price< !-->
			<td>-</td><!-->ค่าปลอด< !-->
			<td>-</td><!-->ธนาคาร< !-->
			<td>-</td><!-->สาขา< !-->
			<td>-</td><!-->ผู้ติดต่อ< !-->
			<td>-</td><!-->วงเงินค่าห้อง< !-->
			<td>-</td><!-->วงเงินอื่น ๆ < !-->
			<td>-</td><!-->รวมวงเงินสินเชื่อ< !-->
			<td>-</td><!-->ฟรีค่าโอน 1%< !-->
			<td>-</td><!-->ฟรีค่าส่วนกลาง< !-->
			<td>-</td><!-->ฟรีค่าสมทบกองทุน< !-->
			<td>-</td><!-->Cash Back< !-->
			<td>-</td><!-->ฟรีจำนอง< !-->
			<td>-</td><!-->สถานภาพ< !-->
			<td>-</td><!-->สถานะรับโอน< !-->
			<td>-</td><!-->มอบ/ไปเอง< !-->
			<td>-</td><!-->CS BU< !-->
			<td>-</td><!-->remark< !-->
			<td>-</td><!-->ค่าโอน 1%< !-->
			<td>-</td><!-->ภาษี 1%< !-->	
			<td>-</td><!-->ธุรกิจเฉพาะ 1%< !-->
			<td>-</td><!-->รวมค่าใช้จ่าย< !-->
			<td>7,020</td><!-->คค่ามิเตอร์น้ำ(บาท)< !-->
			<td>6,550</td><!-->ค่ามิเตอร์ไฟฟ้า< !-->
			<td>3,000</td><!-->หักสมทบ< !-->
			<td>3,550</td><!-->หลังหักเงินค่าสมทบ< !-->
			<td>-</td><!-->สำนักงานที่ดิน< !-->
			<td>-</td><!-->ควันที่นัดโอนกรรมสิทธิ์< !-->
			<td>-</td><!-->คเวลาที่นัดโอน< !-->		
			<td>-</td><!-->คสถานะปลอดโฉนด< !-->
			<td>-</td><!-->วันที่โอนจริง< !-->
		</tr>
		
</font>

</table>
 
	</body