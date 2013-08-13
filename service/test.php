<?php //require('init.php'); 


$testResults = array();
$testFile = "UnitTest/VariableUnitTest.php";
include $testFile;
//done all thing and return $resultUnitTest[];

/*$specFile = "UnitSpec/VariableSpec.php";
include $specFile;


for($i = 0; $i < count($resultUnitTest); $i++)
{
	$resultU = $resultUnitTest[$i];
	$specU = $specUnitTest[$i];
	if($resultU == $specU)
		array_push($testResults, true);
	else
		array_push($testResults, false);
}
*/
$testAllResult = json_encode($testResults);

?>
<!doctype html>

<head>
	<link href="../lib/bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet"/>
	<script type="text/javascript" src="../lib/jquery.min.js"></script>

	<script type="text/javascript" src="../lib/bootstrap/js/bootstrap.min.js"></script>
	
    
</head>
<body>
	Unit Test
	<pre>
	<?php echo $testAllResult; ?>
	</pre>
</body>

</html>