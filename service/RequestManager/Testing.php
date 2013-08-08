<?php
foreach (glob("Controller/*.php") as $filename)
{
	    include $filename;
}
if($_GET['action'] == 'test')
{

	//do test here
    function findTransactionById()
{           $id = 15;
     $SQL  = "select * from tranfer_transaction where id = $id";
    print_r($GLOBALS['connect']);
	 //$result = DB_query($GLOBALS['connect'],$SQL);
	/*  $row = DB_fetch_array($result);
     if($row > 0){
        return array(
            'id'=>$row["id"],
            'unit_payment_id'=>$row["unit_payment_id"],
            'template_id'=>$row["template_id"],
            'create_time'=>$row["create_time"]
        );
    }else{
       return false;
    } */
}
print_r($GLOBALS['connect']);



}
?>