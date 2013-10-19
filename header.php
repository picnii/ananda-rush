<div id='warpper1'>
    <div id='header'>
        <div id='top_nav_left'>Hi <?php echo $_SESSION['ananda_username']; ?></div>
        <div id='top_nav_right'><a href='./'>Log out</a></div>
        <div id='top_nav_right'>|</div>
        <div id='top_nav_right'>My Profile</div>
    </div>
</div>

<div id='warpper'>
<div id='Logo'></div>
 <nav>
	<ul>
		<li><a href="main.php"><font color="#FFF">หน้าหลัก</font></a></li>
		<li><a href="master/menu_project.php"><font color="#FFF">Master Data</font></a></li>
		<li><a href="loan/Search_preapp.php"><font color="#FFF">Preapprove</font></a></li>
		<li><a href="#"><font color="#FFF">นัดหมาย</font></a>
			<ul>
				<li><a href="appointment/appoint_preapproveconfirm.php">นัดหมาย Preapprove</a></li>
				<li><a href="appointment/tmp_appoint.php">นัดหมายงานตรวจรับ</a></li>
				<li><a href="#">นัดหมายโอน</a>
				<li><a href="#">นัดหมายตรวจ Defect ห้อง</a>
					<!--<ul>
						<li><a href="#">HTML</a></li>
						<li><a href="#">CSS</a></li>
					</ul>-->
				</li>
			</ul>
		</li>
		<li><a href="webservice/inspectionlist.php"><font color="#FFF">การตรวจรับ</font></a></li>
		<li><a href="Promotion/main_Promotion.php"><font color="#FFF">โปรโมชั่น</font></a></li>
		<li><a href="#"><font color="#FFF">การโอน</font></a>
			<ul>
				<li><a href="#">นัดโอน</a></li>
				<li><a href="#">ใบค่าใช้จ่าย</a></li>
				<li><a href="#">สร้างใบค่าใช้จ่าย</a>
					<!--<ul>
						<li><a href="#">HTML</a></li>
						<li><a href="#">CSS</a></li>
					</ul>-->
				</li>
			</ul>
		</li>
		<li><a href="#"><font color="#FFF">รายงาน</font></a>
			<ul>
				<li><a href="loan/loan_report.php">รายงานระบบ Preapprove</a></li>
				<li><a href="#">รายงานระบบตรวจรับ</a></li>
				<li><a href="#">รายงานระบบนัดหมาย</a>
					<!--<ul>
						<li><a href="#">HTML</a></li>
						<li><a href="#">CSS</a></li>
					</ul>-->
				</li>
			</ul>
		</li>
		
	</ul>
</nav> 
</div>