<?php

	$response_header = "
<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"
xmlns:x=\"urn:schemas-microsoft-com:office:excel\"
xmlns=\"http://www.w3.org/TR/REC-html40\">
 
<head>
<meta http-equiv=Content-Type content=\"text/html; charset=utf8\"/>
<meta name=ProgId content=Excel.Sheet/>
<meta name=Generator content=\"Microsoft Excel 11\"/>
 
<!--[if gte mso 9]><xml>
 <x:excelworkbook>
  <x:excelworksheets>
   <x:excelworksheet>
    <x:name>** WORKSHEET NAME **</x:name>
    <x:worksheetoptions>
     <x:selected></x:selected>
     <x:freezepanes></x:freezepanes>
     <x:frozennosplit></x:frozennosplit>
     <x:splithorizontal>** FROZEN ROWS + 1 **</x:splithorizontal>
     <x:toprowbottompane>** FROZEN ROWS + 1 **</x:toprowbottompane>
     <x:splitvertical>** FROZEN COLUMNS + 1 **</x:splitvertical>
     <x:leftcolumnrightpane>** FROZEN COLUMNS + 1**</x:leftcolumnrightpane>
     <x:activepane>0</x:activepane>
     <x:panes>
      <x:pane>
       <x:number>3</x:number>
      </x:pane>
      <x:pane>
       <x:number>1</x:number>
      </x:pane>
      <x:pane>
       <x:number>2</x:number>
      </x:pane>
      <x:pane>
       <x:number>0</x:number>
      </x:pane>
     </x:panes>
     <x:protectcontents>False</x:protectcontents>
     <x:protectobjects>False</x:protectobjects>
     <x:protectscenarios>False</x:protectscenarios>
    </x:worksheetoptions>
   </x:excelworksheet>
  </x:excelworksheets>
  <x:protectstructure>False</x:protectstructure>
  <x:protectwindows>False</x:protectwindows>
 </x:excelworkbook>
</xml>< ![endif]-->
 
</head>
<body>

	";

	$response_footer = "

</body>
</html>

	";

	$reportAction = array('reportPromotions', 'reportTranfer');

	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		if(gotAction($action, $reportAction))
			require_once 'Controller/ReportController.php';


		if($action == 'reportPromotions')
		{
			$response = actionReportPromotions($_GET['q']);
		}

    if($action == 'reportTranfer')
    {
      $response = actionReportTranfer();
    }

	}




?>