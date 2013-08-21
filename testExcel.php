<?php

header('Content-type: application/msexcel');

// It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="report.xls"');

?>

<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
 
<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252"/>
<meta name=ProgId content=Excel.Sheet/>
<meta name=Generator content="Microsoft Excel 11"/>
 
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
 
<table>
 
<thead>
   ** TABLE HEADER ROWS GO HERE **
   <tr>
    <tr>
		<th rowspan="3" style=" width:50px;">?????</th>
		<th rowspan="3" style="width:100px;">Item No.</th>
		<th rowspan="3" style="width:80px;">???????<br>??????</th>
		<th rowspan="3" width="200px">??????????</th>
		<th rowspan="3" width="200px">??????????</th>
		<th colspan="6" style="background-color: #FF6699">??????</th>
		<th colspan="3" style="width:300px; background-color:#0099FF;">????(???????)</th>
		<th rowspan="3" style="width:100px;">??????????????</th>
      	</tr>
		
		<tr>
		<th rowspan="2" style="width:100px; background-color:#FFCC99;">????</th>
		<th rowspan="2" style="width:100px; background-color:#FFCC99;">??????</th>
		<th rowspan="2" style="width:100px; background-color:#FFCC99;">?????????</th>
		<th style="width:100px; background-color:#FFFF66">???????????</td>
		<th colspan="2" style="width:200px; background-color:#FF99FF">?????????????</th>
		<th  rowspan="2" style="width:100px; background-color: #CCFFFF;">??????????</th>
	   <th  rowspan="2" style="width:100px; background-color: #CCFFFF;">???????</th>
	   <th  rowspan="2" style="width:100px; background-color: #CCFFFF;">?????????????</th>
		</tr>
		
		 <tr>	
	   <th style="background-color:#FFFF99;">???.</th>
	   <th style="width:100px; background-color: #FFCCCC;">??????? ??</th>
	   <th style="width:100px; background-color: #FFCCCC;">???(???)</th>
	   
	   </tr>
   </tr>
</thead>
 
<tbody>
   ** TABLE DATA ROWS GO HERE **
   <tr>
      <td>Data Cell</td>
      ...
   </tr>
</tbody>
 
</table>
 
</body>
</html>
