<?php require('init.php'); ?>
<!doctype html>
<html ng-app="ananda">
<head>
	<link href="lib/bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet"/>
	<script type="text/javascript" src="lib/jquery.min.js"></script>

	<script type="text/javascript" src="lib/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="lib/lightbox.js"></script>
	<script type="text/javascript" src="lib/angular.min.js"></script>
	<script type="text/javascript" src="lib/angular-strap.min.js"></script>
    <script type="text/javascript" src="lib/angular-resource.min.js"></script>
	<meta charset="utf-8"> 
    <script type="text/javascript" src="js/app.js"></script>
     <script type="text/javascript" src="js/init.js"></script>
    <script type="text/javascript" src="js/service.js"></script>
      <script type="text/javascript" src="js/Controller.js"></script>
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
	<div ng-view></div>
    <div id="json-get-data" class="hide"><?php echo $jsonGetData; ?></div>
    <div id="json-post-data"  class="hide"><?php echo $jsonPostData; ?></div>
    <div id="json-controller-data" class="hide">
    </div>
</body>

</html>