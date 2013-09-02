<?php require('init.php'); ?>
<!doctype html>
<html xmlns:ng="http://angularjs.org" id="ng-app" ng-app="ananda">
<head>
    <script type="text/javascript">
        if (!window.console) 
            console = function(data){} 
   </script>
	<link href="lib/bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet"/>
    <!--[if lte IE 8]>
      <script type="text/javascript/json-master/json2.js" src="lib/json-master/json2.js"></script>
    <![endif]-->
	<script type="text/javascript" src="lib/jquery.min.js"></script>

	<script type="text/javascript" src="lib/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="lib/lightbox.js"></script>
	<script type="text/javascript" src="lib/angular.min.js"></script>
	
    <script type="text/javascript" src="lib/angular-resource.min.js"></script>

 
     <script type="text/javascript" src="lib/datepicker/js/bootstrap-datepicker.js"></script> 
    <script type="text/javascript" src="lib/datepicker/js/bootstrap-timepicker.min.js"></script>
    <script type="text/javascript" src="lib/datepicker/js/locales/bootstrap-datepicker.th.js"></script>

    <link href="lib/datepicker/css/bootstrap-timepicker.min.css" type="text/css" rel="stylesheet"/>
    <link href="lib/datepicker/css/datepicker.css" type="text/css" rel="stylesheet"/>
    <script type="text/javascript" src="lib/angular-strap.min.js"></script>
	<meta charset="utf-8"> 
    <script type="text/javascript" src="js/app.js"></script>
     <script type="text/javascript" src="js/init.js"></script>
    <script type="text/javascript" src="js/service.js"></script>
      <script type="text/javascript" src="js/Controller.js"></script>
      <script type="text/javascript" src="js/BillController.js"></script>
      <!--[if lte IE 8]>
      <script>
        document.createElement('ng-include');
        document.createElement('ng-pluralize');
        document.createElement('ng-view');
 
        // Optionally these for CSS
        document.createElement('ng:include');
        document.createElement('ng:pluralize');
        document.createElement('ng:view');
      </script>
    <![endif]-->
    <style type="text/css">
    	.hide{
    		display: none;
    	}

        #BillCtrl header.header .head
        {
            padding:15px;
            text-align: center;
            border: 4px solid black;
            font-size: 20px;
        }

        #BillCtrl header.header .detail 
        {
            padding-top: 10px;
        }

        #BillCtrl header.header .detail p
        {
            text-align: center;
        }
    </style>
</head>
<body>
    <div id="main-menu" class="navbar">
      <div class="navbar-inner">
        <a class="brand" href="#">Tranfer System</a>
        <ul class="nav">
          <li class="active dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                สร้างใบค่าใช้จ่าย</a>
                <ul class="dropdown-menu">
                    <li><a href="#/templates">จัดการ Template</a></li>
                    <li> <a href="#/payments">จัดการ ค่าใช้จ่าย</a></li>
                     <li><a href="#/variables">จัดการ ตัวแปร</a></li>
                </ul>
            </li>
          <li><a href="#/transactions">ดูใบค่าใช้จ่าย</a></li>
          <li><a href="#">ดู Report</a></li>
        </ul>
      </div>
    </div>
	<div ng-view></div>
    <div id="json-get-data" class="hide"><?php echo $jsonGetData; ?></div>
    <div id="json-post-data"  class="hide"><?php echo $jsonPostData; ?></div>
    <div id="json-controller-data" class="hide">
    </div>
</body>

</html>