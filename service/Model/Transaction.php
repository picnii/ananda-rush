<?php
/*
* $args = array(
	"unit_ids" => array(),
	"template_id"=> 2
  )
*/

function fetchBillInformation($transaction_ids)
{
    $sql = "select transaction_id,ItemId from master_transaction where ";
    for($i = 0; $i < count($transaction_ids); $i++)
    {
        $id = $transaction_ids[$i];
        $sql .= "transaction_id = {$id}";   
        if(!($i + 1 == count($transaction_ids)))
            $sql .= " OR ";
    }
    $result = DB_query($GLOBALS['connect'],$sql);
    $dt = array();
    while($rs =  DB_fetch_array($result)){
        $tran_item = $rs["ItemId"];
        if($tran_item){
            $pre_id = findPreID($tran_item);
        }
        if($pre_id){
            $data = findInformation($pre_id);
        }
        array_push($dt,$data);
    }
    return $dt;
}
function findPreID($tran_item){
    if($tran_item){
         $SQL = "select s.*,(select top 1 pre.id_preapprove from preapprove pre where pre.itemid = s.ItemID and pre.InvoiceAccount = s.InvoiceAccount order by pre.lastupdate DESC) ";
         $SQL.="as pre_id from Sale_Transection s where s.ItemID = '".$tran_item."'";
         $result = DB_query($GLOBALS['connect'],$SQL);
         $numrow = DB_num_rows($result);
         $rw =  DB_fetch_array($result);
     }
     return $rw["pre_id"];
}
function findInformation($pre_id)
{    
            if($pre_id){
                    $SQL1 = "select  s.*,p.*,t.*";
                     $SQL1.=",b.id_preapprove_bank,b.bank_code,b.Branch,pri.id_preapprove_bank,pri.id_credit_approval,cr.id_credit_approval,cr.name_credit_approval ";
                     $SQL1.="from Sale_Transection s ";
                     $SQL1.="inner join preapprove p on p.itemid = s.itemID and p.InvoiceAccount = s.InvoiceAccount ";
                     $SQL1.="inner join master_transaction t on p.itemid = t.ItemId ";
                     $SQL1.="inner join preapprove_bank b on p.itemid = b.itemID and p.InvoiceAccount = b.InvoiceAccount ";
                     $SQL1.="inner join Price_Approve pri on b.id_preapprove_bank = pri.id_preapprove_bank ";
                     $SQL1.="inner join credit_approval_type cr on pri.id_credit_approval = cr.id_credit_approval ";
                     $SQL1.="where p.id_preapprove = '".$pre_id."' order by p.lastupdate DESC ";
                     $res = DB_query($GLOBALS['connect'],$SQL1);
                     $row = DB_num_rows($res);
                     $rt =  DB_fetch_array($res);
                     if($rt["id_preapprove"] != ''){   
                            return $rt;
                     }elseif($rt["id_preapprove"] == ''){
                         $SQL = "select  s.*,p.*,t.transaction_id,t.ItemId,t.ItemName,t.Floor,t.UnitNo,t.RoomNo,t.Status,t.HOUSESIZE,t.LANDSIZE ";
                         $SQL.="from Sale_Transection s ";
                         $SQL.="inner join preapprove p on p.itemid = s.itemID and p.InvoiceAccount = s.InvoiceAccount ";
                         $SQL.="inner join master_transaction t on p.itemid = t.ItemId ";
                         $SQL.="where p.id_preapprove = '".$pre_id."' order by p.lastupdate DESC ";
                         $rs = DB_query($GLOBALS['connect'],$SQL);
                         $dt =  DB_fetch_array($rs);
                         return $dt;
                     }
            }
}
function getSaleDatas($unit_ids)
{
    //print_r($unit_ids);
    $bill_datas = fetchBillInformation($unit_ids);
   // print_r($bill_datas);
    $sale_datas = array();
    foreach ($bill_datas as $bill) {
        # code...
        
        $sale_data = new StdClass;
        $sale_data->unit_id = $bill['transaction_id'];
        $sale_data->unit_number = $bill['UnitNo'];
        $sale_data->item_id = $bill['ItemId'];
        $sale_data->item_name = $bill['ItemName'];
        $sale_data->project_name = $bill['ProjectName'];
        $sale_data->floor = $bill['Floor'];
        $sale_data->room_no = $bill['RoomNo'];
        $sale_data->email = $bill['Email'];
        $sale_data->address = $bill['Address'];
        $sale_data->delivery_address = $bill['DeliveryAdress'];
        $sale_data->item_type = $bill['ItemType'];
        $sale_data->invoice_account = $bill['InvoiceAccount'];
        $sale_data->door = $bill['Direction'];
        $sale_data->so = $bill['SO'];
        $sale_data->so_status = $bill['SOStatus'];
        $sale_data->company = $bill['Company'];
        $sale_data->sale_name = $bill['SalesName'];
        $sale_data->phone = $bill['Phone'];
        $sale_data->mobile = $bill['Mobile'];
        $sale_data->disc_amount = $bill['DiscAmount'];
        $sale_data->base_price = $bill['BasePrice'];
        $sale_data->sell_price = $bill['SellPrice'];
        $sale_data->sqm = $bill['SQM'];
        $sale_data->sett_amount = $bill['SETTAMOUNT'];
        $sale_data->outstanding = $bill['OUTSTANDING'];
        array_push($sale_datas, $sale_data);
    }
    return $sale_datas;
}

function getVariableUnits($sale_datas)
{
    return $sale_datas;
}

$id = 22;
function createTransaction($unit_id, $template_id, $payments_json, $variables_json)
{

    /*
    *IF EXISTS (SELECT * FROM Table1 WHERE Column1='SomeValue')
    UPDATE Table1 SET (...) WHERE Column1='SomeValue'
ELSE
    INSERT INTO Table1 VALUES (...)
    */
	$SQL  = "INSERT INTO tranfer_transaction(unit_id,template_id,create_time, payments, variables)  VALUES ('$unit_id', '$template_id',GETDATE(), '{$payments_json}' ,'{$variables_json}'); SELECT SCOPE_IDENTITY()";
    //echo $SQL;
    //echo "<br/><br/>";
     $result = DB_query($GLOBALS['connect'],$SQL);
    if($result){
        sqlsrv_next_result($result); 
        sqlsrv_fetch($result); 
        $created_id = sqlsrv_get_field($result, 0); 
        return $created_id;
    }else{
        return false;
    }
}

function findAllLastTransactions($selector = "*")
{
    $sql = "SELECT {$selector} FROM tranfer_transaction WHERE id IN ( SELECT MAX(id) as id from tranfer_transaction  GROUP BY unit_id, template_id ) ";
    $result = DB_query($GLOBALS['connect'],$sql);
    /*$numrow = DB_num_rows($result);
    if(!($numrow > 0))
        return array();*/
    $transactions = array();
    while($row = DB_fetch_array($result))
    {
       //$test = convertToTransaction($row);
       //print_r($test);
        array_push($transactions, $row);
    }
    return $transactions;
}

function findAllLastTransactionsByUnitIds($unit_ids)
{
    $sql = "SELECT *
        FROM tranfer_transaction
        WHERE id IN
        (
        SELECT MAX(id) as id from tranfer_transaction WHERE ".getIdClauseFromParams($unit_ids, 'unit_id')." GROUP BY unit_id, template_id
        )
        ";
    $result = DB_query($GLOBALS['connect'],$sql);
    /*$numrow = DB_num_rows($result);
    if(!($numrow > 0))
        return array();*/
    $transactions = array();
    while($row = DB_fetch_array($result))
    {
        array_push($transactions, convertToTransaction($row));
    }
    return $transactions;
}

function findLastTransactionByUnitId($unit_id)
{
    $sql = "SELECT * FROM tranfer_transaction WHERE id IN ( SELECT MAX(id) as id from tranfer_transaction WHERE unit_id = {$unit_id}  GROUP BY unit_id, template_id ) ";
    $result = DB_query($GLOBALS['connect'],$sql);
    $row = DB_fetch_array($result);
    if(!isset($row['id']))
        return false;
    else 
        return convertToTransaction($row);
}

function convertToTransaction($row)
{
    $transaction = new stdClass;
    foreach($row as $key =>$value)
    {
        $transaction->$key = $row[$key];
    }
    return $transaction;
}




function findTransactionById($id)
{
     $SQL  = "select * from tranfer_transaction where id = $id";
	 $result = DB_query($GLOBALS['connect'],$SQL);
     $numrow = DB_num_rows($result);
	 $row = DB_fetch_array($result);
     if($numrow > 0){
        return $row;
    }else{
       return false;
    }
}

/*
* SELECT ONLY 1 row
*
*/
function findTransaction($q)
{

	$SQL = "SELECT * FROM tranfer_transaction WHERE where id = $q";
    $result = DB_query($GLOBALS['connect'],$SQL);
    $numrow = DB_num_rows($result);
	 $row = DB_fetch_array($result);
	if($numrow > 0){
        return array(
            'id'=>$row["id"],
            'unit_id'=>$row["unit_id"],
            'template_id'=>$row["template_id"],
            'create_time'=>$row["create_time"]
        );
    }else{
       return false;
    }
}

/*
*
*/
function findAllTransaction()
{
	$SQL = "SELECT * FROM tranfer_transaction";
    $result = DB_query($GLOBALS['connect'],$SQL);
    $numrow = DB_num_rows($result);
	if($numrow > 0){
        $data = array(); 
        while($res =  DB_fetch_array($result))
		{
            array_push($data,$res);
        }
        return $data;
    }else{
       return false;
    }
}

function updateTransaction($transaction_id, $args)
{
	//update transaction onl
	/*example $args = array(
		'create_time'=>'2010-12-20'
	)
	update only change field
	*/
            if($transaction_id != ""){
                $sql ="UPDATE tranfer_transaction SET ";
            }if($args['unit_id'] != ""){
                $sql.="unit_id='".$args['unit_id']."', ";
            }if($args['template_id'] != ""){
                $sql.="template_id='".$args['template_id']."', ";
            }
                $sql.="create_time= GETDATE() ";
                $sql.="WHERE id='".$transaction_id."' ";
                $rs = DB_query($GLOBALS['connect'],$sql); 
            if($rs){
                $SQL  = "SELECT * from tranfer_transaction where id = $transaction_id ";
                $result = DB_query($GLOBALS['connect'],$SQL);
                $row = DB_fetch_array($result);
                return array(
                    'id'=>$row["id"],
                    'unit_id'=>$row["unit_id"],
                    'template_id'=>$row["template_id"],
                    'create_time'=>$row["create_time"]
                );
            }else{
                return false;
            }
	
}

function findAllLastTransaction($unit_ids)
{
    //find all Lastest transaction from unit_id
    $SQL = "SELECT * FROM tranfer_transaction WHERE where unit_id = $unit_ids order by crate_time ";
    $result = DB_query($GLOBALS['connect'],$SQL);
    $numrow = DB_num_rows($result);
	if($numrow > 0){
         $data = array(); 
        while($res =  DB_fetch_array($result))
		{
            array_push($data,$res);
        }
        return $data;
    }else{
       return false;
    }
}

function deleteTransactionById($transaction_id)
{
    $SQL  = " DELETE FROM tranfer_transaction WHERE id = $transaction_id ";
    $result = DB_query($GLOBALS['connect'],$SQL);
	return true;
}

function deleteTransaction($q)
{
    $SQL  = " DELETE FROM tranfer_transaction WHERE id = $q ";
    $result = DB_query($GLOBALS['connect'],$SQL);
	return true;
}

//find lastes transaction in bill form
function findBillByUnitId($unit_id)
{
    return array(
        "transaction_id"=>20,
        "unit_id"=>$unit_id,
        "template_id"=>50,
        "payments"=>array(
            array(
                "name"=>"payment1",
                "description"=>"desc1",
                "formulas"=>array(
                    "",
                    "",
                    "{bankLoan}*2"
                ),
            )
        ),
        "variables"=>array(
            array(
                array("name"=>"bankLoan", "value"=>5000),
                array("name"=>"bankSide", "value"=>10000)
            )
        )
    );
}

function findBillByTransactionId($transaction_id)
{

    return array(
        "transaction_id"=>$transaction_id,
        "unit_id"=>50,
        "template_id"=>50,
        "payments"=>array(
            array(
                "name"=>"payment1",
                "description"=>"desc1",
                "formulas"=>array(
                    "",
                    "",
                    "{bankLoan}*2"
                ),
            )
        ),
        "variables"=>array(
            array(
                array("name"=>"bankLoan", "value"=>5000),
                array("name"=>"bankSide", "value"=>10000)
            )
        )
    );
}

function findAllBill($q)
{

}

?>