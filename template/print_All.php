<title></title>
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="bootstrap/js/bootstrap.js" ></script>
		<link href="style.css" rel="stylesheet" type="text/css" />
		<meta charset="utf-8" />
	</head>
<section ng-controller="BillPrintCtrl" class="container">
	<style type="text/css">  

/* css ส่วนสำหรับการแบ่งหน้าข้อมูลสำหรับการพิมพ์ */  
@media all  
{  
    .page-break { display:none; }  
    .page-break-no{ display:none; }  
}  
@media print  
{  
    .page-break { display:block;height:1px; page-break-before:always; }  
    .page-break-no{ display:block;height:1px; page-break-after:avoid; }   
}  
</style>  
	<!--<div ng-repeat="bill in bills">
	<!--- Start Loop Print-->	
		<head>
	
	<body>
	<?php for($i=1;$i<=3;$i++){ ?>  
	<div class="page-break<?=($i==1)?"-no":""?>">&nbsp;</div>  
	<div align="left">Page <?=$i?></div>
		<div class="container" >
		<div class="row" >
			<div class="span12" align="center"> <h3>IDEO MORPH 38 </h3></div>
			<div  class="span12" align="center">
				<strong><p>บริษัท อนันดา ดีเวลลอปเม้นท์ จำกัด (มหาชน)</p>
				<p>เลขที่ 99/1 หมู่ที่ 14 ตำบลบางพลีใหญ่ อำเภอบางพลี จังหวัดสมุทรปราการ 10540 </p>
				<p>โทร.02-3171155 ต่อ 102-109ม121 โทรสาร.02-3160180-1</p></strong>
			</div>
		</div>
		<div style="border-bottom: groove"></div>
		</div>
		<div class="container" ><br><br>
			<div class="row">
				<font size="2">
					<div class="span1"><b>วันที่</b></div>
					<div class="span5" style="border-bottom: groove;">5 สิงหาคม 2556</div>
					<div class="span1"><b>จาก</b></div>
					<div class="span5" style="border-bottom: groove;">คุณตุ๊กตา</div><br>
					<div class="span1"><b>วันนัดโอน</b></div>
					<div class="span5" style="border-bottom: groove;">10 สิงหาคม 2556</div>
					<div class="span1"><b>เวลา :</b></div>
					<div class="span5" style="border-bottom: groove;">10.00 น.</div><br>
					<div class="span1"><b>เรียน</b></div>
					<div class="span5" style="border-bottom: groove;">คุณ นริรทร์  ลีลาภรณ์</div>
					<div class="span1"><b>โทร. :</b></div>
					<div class="span5" style="border-bottom: groove;">-</div><br>
					<div class="span1"><b>เรื่อง</b></div>
					<div class="span7" style="border-bottom: groove;">รายละเอียดค่าใช้จ่ายต่าง ๆ เกี่ยวกับการโอนกรรมสิทธิ์ห้องชุุดเลขที่</div>
					<div class="span2" align="right">(พื้นที่ตามโฉนด  อช.</div>
					<div class="span1" style="border-bottom:groove;" align="center">52.50</div>ตรม.)
					<br><br>
				</div>
				
				<div class="container" >
					<div class="row" >
						<div class="span5">ราคาห้องชุด (พื้นที่ตามสัญญา <b><u>52.50</u></b> ตารางเมตร)</div>
						<div class="span1">บ้านเลขที่  </div>
						<div class="span3"><u><b>88/86</b></u> </div>
						<div class="span1">เป็นเงิน</div>
						<div class="span2" align="center">8,320,000.00</div>
						
						<div class="span5"><b><u>หัก</u></b> พื้นที่ลด  -  ตรม</div>
						<div class="span4">(ราคา  158,476.19 บ/ตรม  8,320,000.00 บาท)</div>
						<div class="span1">เป็นเงิน</div>
						<div class="span2" align="center">-</div>
						
						<div class="span9"><b><u>หัก </u></b>ชำระเงินจอง + ทำสัญญา</div>
						<div class="span1">เป็นเงิน</div>
						<div class="span2" align="center">50,000.00</div>
						
						<div class="span12"><b><u>บวก</u></b>  ค่าก่อสร้างเพิ่ม/ลด</div>
						<div class="span9">ส่วนลด ณ วันโอน</div>
						<div class="span1">เป็นเงิน</div>
						<div class="span2" style="border-bottom-style:groove;" align="center">-</div>
						
						<div class="span9" align="center"><b>คงเหลือค่าห้องชุด</b></div>
						<div class="span1">เป็นเงิน</div>
						<div class="span2" style="border-bottom-style:double;" align="center">8,270,000.00</div>
						
						<div class="span5">หัก  สินเชื่อธนาคารยูโอบี</div>
						<div class="span4">(วงเงินจำนองรวม 8,200,000 เบี้ยประกัน/อื่น ๆ 200,000)</div>
						<div class="span1">เป็นเงิน</div>
						<div class="span2" style=" border-bottom-style:solid;" align="center">8,500,000.00</div>
						
						<b>
						<div class="span9" align="center" style="padding-top:30px;">ยอดคงหลือหลังหักสินเชื่อ</div>
						<div class="span1" style="padding-top:30px;">เป็นเงิน</div>
						<div class="span2" align="center" style="padding-top:30px; background-color:#CCCCCC;">230,000.00</div>
						</b>
					</div>
				</div>
				
				<br>
			   
					<table border = "1" align="center" width ="100%" hight ="30%"  cellspacing = "0"  style="font-size:13px" >
             			 <thead>
		                        <tr>
		                          <th align="center" style="font-size:16px" colspan="5"><h4>ค่าใช้จ่ายและค่าธรรมเนียม ณ วันโอนกรรมสิทธิ์</h4></th>
		                         </tr>
		                 </thead>
                    	 <thead>
		                        <tr>
								  <th width="5%">No.</th>
		                          <th width="56%" align="center">ค่าใช้จ่าย/ผู้ชำระ</th>
		                          <th width="10%">สินเชื่อธนาคาร</th>
		                          <th width="10%">บริษัทชำระแทน</th>
		                          <th width="10%">ลูกค้าชำระเอง</th>
		                        </tr>
		                 </thead>
		                          <tr>
		                                    <td align="center" style="">1.</td>
											<td style="padding-left:10px;">ค่าห้องส่วนที่เหลือ </td>
		                                    <TD align="center">8,270,000.00</TD>
		                                    <TD align="center">-</TD>
		                                    <TD align="center">-</TD>
		                                 
		                          </tr>
		                          <tr>
								  			<td align="center">2.</td>
		                                    <td style="padding-left:10px;">ค่ามิเตอร์ไฟฟ้า (Morph 38-15A 3,250B./Asthton 30A 6,150 B.)</td>
		                                    <TD align="center">6,150.00</TD>
		                                    <TD align="center">-</TD>
		                                    <TD align="center">6,150.00</TD>
		                          </tr>
		                          <tr>
		                                    <td align="center">3.</td>
											<td style="padding-left:10px;">ค่ามิเตอร์น้ำ</td>
		                                    <TD align="center">-</TD>
		                                    <TD align="center">-</TD>
		                                    <TD align="center">-</TD>
		                          </tr>
		                          <tr>
		                                    <td align="center">4.</td>
											<td style="padding-left:10px;">ค่าส่วนกลางเก็ยล่วงหน้า 1 ปี</td>
		                                    <TD></TD>
		                                    <TD align="center">31,500.00</TD>
		                                    <TD align="center">-</TD>
		                          </tr>
		                          <tr>
		                                    <td align="center">5.</td>
											<td style="padding-left:10px;">เงินสมทบกองทุนส่วนกลาง  (500บาท/ตรม)</td>
		                                    <TD align="center">26,250.00</TD>
		                                    <TD align="center">26,250.00</TD>
		                                    <TD align="center">26,250.00</TD>
		                          </tr>
		                          <tr>
		                                    <td align="center">6.</td>
											<td style="padding-left:10px;">ค่าธรรมเนียมโอน 1% ของราคาประเมิน+อากร   4,116,848 ประมาณ</td>
		                                    <TD align="center">-</TD>
		                                    <TD align="center">41,319.00</TD>
		                                    <TD align="center">-</TD>
		                                   
		                          </tr>
		                          <tr>
		                                    <td align="center">7.</td>
											<td style="padding-left:10px;">ค่าธรรมเนียมจดจำนอง  1%  ยอดสินเชื่อ +	 อากร  8,700,000  ประมาณ</td>
		                                    <TD align="center">-</TD>
		                                    <TD align="center">-</TD>
		                                    <TD align="center" >87,250</TD>
		                                    
		                          </tr>
		                          <tr>
		                                    <td align="center">8.</td>
											<td style=" padding-left:10px;"></td>
		                                    <td></td>
		                                    <td></td>
		                                    <td></td>
		                                   
		                          </tr>
								   <tr>
		                                    <td align="center">9.</td>
											<td style="padding-left:10px;">&nbsp;</td>
		                                    <td></td>
		                                    <td></td>
		                                    <td></td>
		                                   
		                          </tr>
								   <tr>
		                                    <td align="center">10.</td>
											<td style="padding-left:10px;">&nbsp;</td>
		                                    <td></td>
		                                    <td></td>
		                                    <td></td>
		                                   
		                          </tr>
		                          <tr>
		                                    <td  align="center">11.</td>
											<td style="padding-left:10px;"></td>
		                                    <td> </td>
		                                    <td> </td>
		                                    <td> </td>
		                                   
		                          </tr>
		                          <tr bgcolor="#FFFFCC">
								  			<td></td>
		                                    <td align="center"><strong>รวมค่าใช้จ่ายทั้งสิ้น (บาท)</strong> </td>
		                                    <td align="center"><strong>8,500,000.00</strong></td>
		                                    <td align="center"><strong>99,069.00</strong></td>
		                                    <td align="center"><strong>67,150.00</strong></td>
		                                    
		                          </tr>
		                        
					</table>
					
					<br>
					
					<div><h4>การแยกเช็คสั่งจ่าย</h4></div>
						<div class="row">
							<font size="2">
						<div class="span11"><u>รายการชำระเงินที่ลูกค้าต้องเตรียมมาในวันโอนกรรมสิทธิ์ ดังนี้</u></div>
						<div class="span11">1. กรณีโอนสดแคชเชียร์เช็กสั่งจ่าย <b>"บริษัท อนันดา ดีเวลลอปเม้นท์ ทู จำกัด"</b> </div>
						<div class="span1" align="right">6,150.00</div>
						<div class="span3">2. ชำระค่าส่วนกลาง </div>
						<div class="span8">เป็นแคชเชียร์เช็คสั่งจ่าย <b>"นิติบุคคลอาคารชุด ไอดีโอ มอร์ฟ 38 คอนโดมอเนียม อาคาร B"</b></div>
						<div class="span1" align="right">-</div>
						<div class="span3">3. ชำระเงินสมทบกองทุนส่วนกลาง</div>
						<div class="span8">เป็นแคชเชียร์เช็คสั่งจ่าย <b>"นิติบุคคลอาคารชุด ไอดีโอ มอร์ฟ38 คอนโดมิเนียม อาคาร B"</b></div>
						<div class="span1" align="right">26,250.00</div>
						<div class="span7">4.ชำระค่าธรรมเนียมโอน 1% กรณีกู้+ค่าจำนองอีก 1% เป็นแคชเชียร์เช็คสั่งจ่าย </div>
						<div class="span4"><b>"กระทรวงการคลัง"</b></div>
						<div class="span1" align="right">86,250.00</div>
						<div class="span10" align="right"><b>และแบ่งเป็นเงินสด</b></div>
						<div class="span2" align="right" style="border-bottom-style:groove;">1,000.00</div>
						<div class="span10" align="right"><b>รวมเป็นเงินที่ลูกค้าต้องชำระ</b></div>
						<div class="span2" align="right" style="border-bottom-style:double"><b>67,150.00</b></div>
						
		                </div>
					
						<br><b><u>*หมายเหตุ</u></b> ขอเป็นแคชเชียร์เช็คที่ทำให้ในกรุงเทพหรือปริมณฑลเท่านั้น ถ้าเป็นต่างจังหวัดต้องเป็นดร๊าฟ ที่่สามารถขึ้นเงินได้ทุกสาขาทั่วประเทศ เท่านั้น<br>
						<p align ="center">***********************************************</p><br>
						<u><b>Note :-</b></u>ในวันโอนกรรมสิทธิ์ <b>กรุณามาพร้อมคู่สมรสของท่าน</b> พร้อมทั้งนำหลักฐานสำคัญมาด้วย ดังนี้<br>
						1. บัตรประจำตัวประชาชน และทะเบียนบ้าน ฉบับจริง และสำเนา 3 ชุด<br>
						2. ใบทะเบียนสมรส / หนังสือยินยอมคู่สมรส / ใบหย่า /ใบมรณะบัตร (ถ้ามี)<br>
						3. ใบเปลี่ยนชื่อ , สกุล(ถ้ามี)<br><br>
			
						<strong>
							บัญชี &nbsp;"บริษัท อนันดา ดีเวลลอปเม้น ทู จำกัด " ธ.กรุงเทพ/สาขาบางพลี(ออมทรัพย์)เลขที่ 216-4-15529-9 SWIFT CODE: BKKBTHBK <br>
							หรือ &nbsp;&nbsp;&nbsp;"บริษัท อนันดา ดีเวลลอปเม้น ทู จำกัด " ธ.กรุงไทย/สาขาบางพลี(ออมทรัพย์)เลขที่ 254-0-18199-6 SWIFT CODE: KRTHTHBK<br>
							บัญชี:&nbsp; "นิติบุคคลอาคารชุด ไอดีโอ มอร์ฟ38 คอนโดมิเนียม" ธนาคาร...................สาขา.........................................(ออมทรัพย์)เลขที่....................................<br><br>

						</strong>

			</font>
			</div>
		</div> 
		<?php } ?> 
	</body>
		
	<!-- pagebreak -->
	<hr/>
	</div>



</section>