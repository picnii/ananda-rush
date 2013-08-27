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
        $transaction_id = $rs['transaction_id'];
        if($tran_item){
            $pre_id = findPreID($tran_item);
        }
        if($pre_id){
            $data = findInformation($pre_id);
       //     echo "new-data no.. {$data['transaction_id']} code:{$data['CompanyCode']} ..";
        }else{
            $data = findOldInformation($transaction_id);
        //    echo "old-data no.. {$data['transaction_id']} code:{$data['CompanyCode']} ..";
        }

        //get company info
        if(isset($data['master_CompanyCode']))
        {
            $data['CompanyCode'] = $data['master_CompanyCode'];
        }
        $comp_data = findCompanyInfo($data);
       
     //   print_r($comp_data);
        foreach ($comp_data as $key => $value) {
            # code...

            $data[$key] = $comp_data[$key];
        }

       /*print_r($data);
            echo "<br/>";*/
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

function findOldInformation($transaction_id)
{
    $sql = "SELECT *, m.projID as project_code FROM master_transaction as m LEFT JOIN Sale_Transection as s on m.ItemId = s.ItemID 
    LEFT JOIN master_project as mp ON m.projID = mp.proj_code    
    LEFT JOIN tranfer_appointment as tap ON tap.transaction_id = mp.id
    LEFT JOIN tranfer_appointment_log as tapl ON tapl.id = tap.log_id
    WHERE transaction_id = {$transaction_id}";

    $result = DB_query($GLOBALS['connect'],$sql);
    $row =  DB_fetch_array($result);
     $row['q_type'] = 'oldInfo';
     if(isset($row['transaction_id']))
        return $row;
    else
        return null;

}

function findCompanyInfo($row_transaction)
{
    $comp_code_lower = $row_transaction['CompanyCode'];
    $comp_code_sql = strtoupper($comp_code_lower);

    $sql = "SELECT * from master_company WHERE comp_code = '{$comp_code_sql}'";
    //echo $sql;
    //echo "<br/>";
    $result = DB_query($GLOBALS['connect'],$sql);
    $row =  DB_fetch_array($result);

     if(isset($row['comp_code']))
        return $row;
    else
        return null;
}


function findBankLoanInfo($pre_approve_bank_id)
{
   $SQL = "select b.id_preapprove_bank as preBank_id_preapprove_bank,b.bank_code as preBank_bankcode,pri.id_preapprove_bank";
    $SQL.=",b.bank_contactname as preBank_bank_contactname,b.date_reque as preBank_date_reque,b.id_preapprove1 as preBank_id_preapprove1";
    $SQL.=",b.remak as preBank_remak,b.id_status_Approve as preBank_id_status_Approve,b.status_user_select as preBank_status_user_select";
    $SQL.=",b.date_Approve as preBank_date_Approve,b.remak_More as preBank_remak_More,b.csnote_bank as preBank_csnote_bank";
    $SQL.=",b.id_Reason_bank as preBank_id_Reason_bank,b.want_loan as preBank_want_loan";
    $SQL.=",b.Branch as preBank_Branch,b.appoint_reason1_id as preBank_appoint_reason1_id";
    $SQL.=",b.id_status_import as preBank_id_status_import,b.itemID as preBank_itemID,b.InvoiceAccount as preBank_InvoiceAccount";
    $SQL.=",pri.id_credit_approval,pri.Price_Approve,pri.lastupdate as price_update,pri.createdate as price_createdate,";
    $SQL.="cr.id_credit_approval as credit_id_credit_approval,cr.name_credit_approval,cr.lastupdate as credit_lastupdate, cr.seq,";
    $SQL.="ar.appoint_reason1_id,ar.appoint_reason1_name,ar.appoint_reason_type_id,";
    $SQL.="mb.bank_id,mb.bank_code as master_bank_code,mb.bank_name as master_bank_name,mb.bank_branch as master_bank_branch,";
    $SQL.="mb.bank_contactname,mb.bank_contactphone,sp.id_status_Approve,sp.name_status_Approve,";
    $SQL.="ts.id_type_select,ts.name_type_select ";
    $SQL.="from preapprove_bank b ";
    $SQL.="left join Price_Approve pri on b.id_preapprove_bank = pri.id_preapprove_bank ";
    $SQL.="left join credit_approval_type cr on pri.id_credit_approval = cr.id_credit_approval ";
    $SQL.="left join appointment_reason1 ar on b.appoint_reason1_id = ar.appoint_reason1_id ";
    $SQL.="left join master_bank mb on b.bank_code = mb.bank_code ";
    $SQL.="join status_Approve sp on b.id_status_Approve = sp.id_status_Approve ";
    $SQL.="join Type_Select ts on b.status_user_select = ts.id_type_select ";
    $SQL.="where b.id_preapprove_bank ='".$pre_approve_bank_id."' ";
    $res = DB_query($GLOBALS['connect'],$SQL);
    $numrows = DB_num_rows($res);

    $result = new StdClass;
    $result->banks = array();
    while($row =  DB_fetch_array($res))
    {
       array_push($result->banks , $row);
    }
    
    return $result;
    /*if($numrows >0){
        return $basnk;
    }*/
}



function findInformation($pre_id)
{    
            if($pre_id){
                    $SQL1 ="select DISTINCT top 1 s.*,p.id_preapprove as Preapp_id_preapprove,p.itemid as Preapp_itemid,p.appoint_reason2_id as Preapp_appoint_reason2_id";
                     $SQL1.=",p.appoint_reason1_id as Preapp_appoint_reason1_id,p.id_status as Preapp_id_status,p.id_status_Document as Preapp_id_status_Document";
                     $SQL1.=",p.remak_appoint_reason1 as Preapp_remak_appoint_reason1,p.remak_appoint_reason2 as Preapp_remak_appoint_reason2";
                     $SQL1.=",p.IVZ_ProjSalesContractId as Preapp_IVZ_ProjSalesContractId,p.InvoiceAccount as Preapp_InvoiceAccount";
                     $SQL1.=",p.SalesName as Preapp_SalesName,p.Attachment as Preapp_Attachment,p.preapp_id as Preapp_preapp_id";
                     $SQL1.=",p.csnote as Preapp_csnote,p.RoomNo as Preapp_RoomNo,p.Building as Preapp_Building,p.Floor as Preapp_Floor";
                     $SQL1.=",p.ItemType as Preapp_ItemType,p.ProjectName as Preapp_ProjectName,p.ProjID as Preapp_ProjID,p.lastupdate as Preapp_lastupdate";
                     $SQL1.=",t.transaction_id,t.CompanyCode as master_CompanyCode,t.ProjID as master_ProjID,t.Brand as master_Brand,t.ItemID as master_ItemID,t.ItemName as master_ItemName,";
                     $SQL1.="t.Floor as master_Floor,t.UnitNo as master_UnitNo,t.RoomNo as master_RoomNo,t.Sqm as master_Sqm,t.Door as master_Door,t.Direction as master_Direction,";
                     $SQL1.="t.BasePrice as master_BasePrice,t.SellPrice as master_SellPrice,t.Status as master_Status,t.IsMatrix as master_IsMatrix,t.ModifyBy as master_ModifyBy,t.ModifyDate as master_ModifyDate,";
                     $SQL1.="t.MatrixColor as master_MatrixColor,t.building as master_building,t.bu_id as master_bu_id,t.HOUSESIZE as master_HOUSESIZE,t.LANDSIZE as master_LANDSIZE";
                     $SQL1.=",t.IVZ_LOANREPAYMENTMINIMUNAMT, t.IVZ_LOANREPAYMENTPERC , t.IVZ_PROJSALESTITLEDEEDNUMBER, t.IVZ_ESTIMATEPRICE ";
                     $SQL1.=",b.id_preapprove_bank,b.bank_code,b.Branch,ar.appoint_reason1_id as preapp_appoint_reason1_id,ar.appoint_reason1_name as preapp_appoint_reason1_name,";
                     $SQL1.="pri.id_preapprove_bank,pri.id_credit_approval,cr.id_credit_approval,cr.name_credit_approval,mp.* ";

                     $SQL1.=" ,tapl.payment_type , tapl.appoint_time, tapl.people, tapl.call_time ";

                     $SQL1.="from Sale_Transection s ";
                     $SQL1.="inner join preapprove p on p.itemid = s.itemID and p.InvoiceAccount = s.InvoiceAccount ";
                     $SQL1.="inner join master_transaction t on p.itemid = t.ItemId ";
                     $SQL1.="inner join preapprove_bank b on p.itemid = b.itemID and p.InvoiceAccount = b.InvoiceAccount ";
                     $SQL1.="inner join appointment_reason1 ar on p.appoint_reason1_id = ar.appoint_reason1_id ";
                     $SQL1.="inner join Price_Approve pri on b.id_preapprove_bank = pri.id_preapprove_bank ";
                     $SQL1.="inner join credit_approval_type cr on pri.id_credit_approval = cr.id_credit_approval ";
                     $SQL1.="inner join master_project mp on mp.proj_code = t.projID ";

                     $SQL1.="LEFT JOIN tranfer_appointment tap on tap.transaction_id = t.id ";
                     $SQL1.="LEFT JOIN tranfer_appointment_log tapl on tapl.id = tap.log_id ";

                     $SQL1.="where p.id_preapprove = '".$pre_id."' order by p.lastupdate DESC ";
                     $res = DB_query($GLOBALS['connect'],$SQL1);
                     $row = DB_num_rows($res);
                     $rt =  DB_fetch_array($res);
                     $data = array();
                     if($rt["id_preapprove_bank"] != ''){
                        $pre_approve_bank_id = $rt["id_preapprove_bank"];
                        $bank = findBankLoanInfo($pre_approve_bank_id);
                        foreach ($rt as $key => $value) {
                            # code...
                            $data[$key] = $value;
                        }
                       // $data["rt"] = $rt;
                        foreach ($bank as $key => $value) {
                            # code...
                            $data[$key] = $value;
                        }
                      //  $data["bank"] = $bank;
                         
                        return $data;
                     }elseif($rt["id_preapprove_bank"] == ''){
                        $SQL ="select DISTINCT top 1 s.*,p.id_preapprove as Preapp_id_preapprove,p.itemid as Preapp_itemid,p.appoint_reason2_id as Preapp_appoint_reason2_id";
                         $SQL.=",p.appoint_reason1_id as Preapp_appoint_reason1_id,p.id_status as Preapp_id_status,p.id_status_Document as Preapp_id_status_Document";
                         $SQL.=",p.remak_appoint_reason1 as Preapp_remak_appoint_reason1,p.remak_appoint_reason2 as Preapp_remak_appoint_reason2";
                         $SQL.=",p.IVZ_ProjSalesContractId as Preapp_IVZ_ProjSalesContractId,p.InvoiceAccount as Preapp_InvoiceAccount";
                         $SQL.=",p.SalesName as Preapp_SalesName,p.Attachment as Preapp_Attachment,p.preapp_id as Preapp_preapp_id";
                         $SQL.=",p.csnote as Preapp_csnote,p.RoomNo as Preapp_RoomNo,p.Building as Preapp_Building,p.Floor as Preapp_Floor";
                         $SQL.=",p.ItemType as Preapp_ItemType,p.ProjectName as Preapp_ProjectName,p.ProjID as Preapp_ProjID,p.lastupdate as Preapp_lastupdate";
                         $SQL.=",t.transaction_id,t.CompanyCode as master_CompanyCode,t.ProjID as master_ProjID,t.Brand as master_Brand,t.ItemID as master_ItemID,t.ItemName as master_ItemName,";
                         $SQL.="t.IVZ_LOANREPAYMENTMINIMUNAMT, t.IVZ_LOANREPAYMENTPERC, t.IVZ_PROJSALESTITLEDEEDNUMBER, t.IVZ_ESTIMATEPRICE,";
                        $SQL.="t.Floor as master_Floor,t.UnitNo as master_UnitNo,t.RoomNo as master_RoomNo,t.Sqm as master_Sqm,t.Door as master_Door,t.Direction as master_Direction,";
                        $SQL.="t.BasePrice as master_BasePrice,t.SellPrice as master_SellPrice,t.Status as master_Status,t.IsMatrix as master_IsMatrix,t.ModifyBy as master_ModifyBy,t.ModifyDate as master_ModifyDate,";
                        $SQL.="t.MatrixColor as master_MatrixColor,t.building as master_building,t.bu_id as master_bu_id,t.HOUSESIZE as master_HOUSESIZE,t.LANDSIZE as master_LANDSIZE,mp.* ";

                        $SQL.=" ,tapl.payment_type , tapl.appoint_time, tapl.people, tapl.call_time ";

                         $SQL.="from Sale_Transection s ";
                         $SQL.="inner join preapprove p on p.itemid = s.itemID and p.InvoiceAccount = s.InvoiceAccount ";
                         $SQL.="inner join master_transaction t on p.itemid = t.ItemId ";
                          $SQL.="inner join master_project mp on mp.proj_code = t.projID ";

                        $SQL.="LEFT JOIN tranfer_appointment tap on tap.transaction_id = t.transaction_id ";
                        $SQL.="LEFT JOIN tranfer_appointment_log tapl on tapl.id = tap.log_id ";
                     

                         $SQL.="where p.id_preapprove = '".$pre_id."' order by p.lastupdate DESC ";
                         //echo $SQL;
                         $rs = DB_query($GLOBALS['connect'],$SQL);
                         $dt =  DB_fetch_array($rs);
                         return $dt;
                     }
            }
}

function getSaleDatas($unit_ids)
{
    //print_r($unit_ids);
    //echo "<br/></br>";
    $bill_datas = fetchBillInformation($unit_ids);
    $variables_unit =  getVariableUnits($bill_datas);
    return $variables_unit;
   // print_r($bill_datas);
    //echo "<br/></br>";
    /*$sale_datas = array();
    foreach ($variables_unit  as $bill) {
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
        foreach($variables as $var)
        {
            $key_name = $var['codename'];
            $sale_data->$key_name = $var['value'];
        }
        array_push($sale_datas, $sale_data);
    }
    return $sale_datas;*/
}

function getVariableUnits($sale_datas)
{
    $variables = findAllVariables();

    $bill_variables = array();
    foreach ($sale_datas as $sale_data) {
        # code...
        $bill = new stdClass;
        foreach($sale_data as $key => $value)
          $bill->$key = $sale_data[$key];
        $bill->variables = array();
        foreach ($variables as $var) {
            # code...
            $varname = $var['codename'];
          
            $variable = new stdClass;
            $bill->variables[$varname] = new stdClass;
            $bill->variables[$varname]->name = $var['name'];
            $bill->variables[$varname]->value = $var['value'];
             
        }
        array_push($bill_variables, $bill);
    }
    return $bill_variables;
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
     $result = DB_query($GLOBALS['connect'],converttis620($SQL));
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

/*
    *   Bill Data
    */
    function getProjectNameFromSaleData($bill)
    {
        if(isset($bill->proj_name_th))
            $project_name = $bill->proj_name_en;
        else
            $project_name = '?';
        return $project_name;
    }

    function getCompanyNameFromSaleData($bill)
    {
        if(isset($bill->comp_name_th))
            $comp_name_th = $bill->comp_name_th;
        else
            $comp_name_th = '?';
        return $comp_name_th;
    }

    function getCompanyAddressFromSaleData($bill)
    {
        if(isset($bill->comp_addno))
            $company_address = "เลขที่ {$bill->comp_addno} ซอย {$bill->comp_soi} ถนน {$bill->comp_road} ตำบล {$bill->comp_tumbon} อำเภอ {$bill->comp_distinct} จังหวัด {$bill->comp_province} {$bill->comp_zipcode}";
        else
            $company_address = '?';
        return $company_address;
    }

    function getCompanyTelFromSaleData($bill)
    {
        if(isset($bill->comp_tel))
            $comp_tel = $bill->comp_tel;
        else
            $comp_tel = '?';
        return $comp_tel;
    }

    function getCompanyFaxFromSaleData($bill)
    {
        if(isset($bill->comp_fax))
            $comp_fax = $bill->comp_fax;
        else
            $comp_fax = '?';
        return $comp_fax;
    }

    function getUnitNumberFromSaleData($bill)
    {
        if(isset($bill->master_UnitNo))
            $master_UnitNo = $bill->master_UnitNo;
        else
            $master_UnitNo = '?';
        return $master_UnitNo;
    }

    function getItemTypeFromSaleData($bill)
    {
        if(isset($bill->ItemType))
            $ItemType = $bill->ItemType;
        else
            $ItemType = '?';
        return $ItemType;
    }

    function getAreaFromSaleData($bill)
    {
         if(isset($bill->master_HOUSESIZE))
            $master_HOUSESIZE = $bill->master_HOUSESIZE;
        else
            $master_HOUSESIZE = '?';
        return $master_HOUSESIZE;
    }

    function getCustomerNameFromSaleData($bill)
    {
        if(isset($bill->SalesName) && is_string($bill->SalesName))
            $SalesName = converttis620($bill->SalesName);
        else
            $SalesName = '?';
    ;
        return $SalesName;
        //return "?";
    }

    function getCustomerMobileFromSaleData($bill)
    {
        if(isset($bill->Mobile))
            $Mobile = $bill->Mobile;
        else
            $Mobile = '?';
        return $Mobile;
    }

    function getPriceOnContractFromSaleData($bill)
    {
        if(isset($bill->SellPrice))
            $SellPrice = $bill->SellPrice;
        else
            $SellPrice = '?';
        return $SellPrice;
    }

    function getPricePerAreaSaleData($bill)
    {
         if(isset($bill->SQM))
            $SQM = $bill->SQM;
        else
            return '?';
        if(getPriceOnContractFromSaleData($bill) != '?')
            return getPriceOnContractFromSaleData($bill)/$SQM;
        return '?';
    }

    function getDiscountSaleData($bill)
    {
        return 0;
    }

    function getAreaOnContractFromSaleData($bill)
    {
         if(isset($bill->master_LANDSIZE))
            $master_LANDSIZE = $bill->master_LANDSIZE;
        else
            $master_LANDSIZE = '?';
        return $master_LANDSIZE;
    }

    function getAreaDiffFromSaleData($bill)
    {
        $HOUSESIZE = getAreaFromSaleData($bill);
        $LANDSIZE = getAreaOnContractFromSaleData($bill);
        $price_per_sqm = getPricePerAreaSaleData($bill);
        if($LANDSIZE != '?' && $HOUSESIZE != '?' && $price_per_sqm != '?')
            return ($HOUSESIZE - $LANDSIZE) * $price_per_sqm;
        else if($price_per_sqm != '?')
         return $HOUSESIZE * 500;
        else
            return '?';
    }

    function getActualAreaFromSaleData($bill)
    {
        return getAreaFromSaleData($bill);;
    }

   function getPriceAtPaydate($bill)
   {
        $sellprice = getPriceOnContractFromSaleData($bill);
        $price_diff = getAreaDiffFromSaleData($bill);
        $discount = getDiscountSaleData($bill);
       // echo "sellprice {$sellprice} , price_diff {$price_diff}, discount {$discount}";
       // if($sellprice!= '?' && $price_diff != '?' && $discount != '?')
             return $sellprice + $price_diff - $discount;
       // return '?';

   }

   function getSettAmount($bill)
   {
        if(isset($bill->SETTAMOUNT))
            $SETTAMOUNT = $bill->SETTAMOUNT;
        else
            $SETTAMOUNT = '?';
        return $SETTAMOUNT;
   }

   function getPaymentPrice($bill)
   {
        return getPriceAtPaydate($bill) - getSettAmount($bill);
   }

   function getIsBank($bill)
   {
        $reason_id = $bill->preapp_appoint_reason1_id;
        if(isset($bill->banks))
        {
            $select_banks = array();
            foreach($bill->banks as $bank)
            {
                if($bank['id_type_select'] == 2 && $reason_id == $bank['appoint_reason1_id'])
                    array_push($select_banks, $bank);
            }   
            if(count($select_banks) == 0)
                return false;
            else
            {
                return $select_banks;
            }
        }
        return false;
   }

   function getBanksVariable($bill)
   {
        $banks = getIsBank($bill);
        $return_bank = new stdClass;
        $firstBank = $banks[0];
        if($banks)
            $variable = getBillVariable('BankLoanName', 'ชื่อธนาคาร',   $firstBank['master_bank_name']);
        else
            $variable = getBillVariable('BankLoanName', 'ชื่อธนาคาร',  '-');
        array_push($bill->variables, $variable); 
        $banks_variable_flag = array(
            'BankLoanRoom' => false,
            'BankLoanInsurance' => false,
            'BankLoanOther' =>false,
            'SumBankLoan' =>false,
            'BankLoanInsurance' =>false, 
            'BankLoanMulti' =>false,
            'BankLoanDecorate' =>  false
        );
        $bank_other_loans = array();
        /*if($bill->transaction_id == 1854)
        {
            echo "tester";
           if($banks)
            echo "pass";
            else
                echo count($banks);
        }*/
        foreach($banks as $bank)
        {
            
            if($bank['id_credit_approval'] == 1)
            {
                 $variable = getBillVariable('BankLoanRoom', 'อนุมัติค่าห้อง',  $bank['Price_Approve']);
                 $banks_variable_flag['BankLoanRoom'] = true;
                 $return_bank->BankLoanRoom =  $bank['Price_Approve'];
                 array_push($bill->variables, $variable);
            }
            else if($bank['id_credit_approval'] == 2)
            {
                 $variable = getBillVariable('BankLoanInsurance', 'อนุมัติวงเงินค่าประกัน',  $bank['Price_Approve']);
                 $banks_variable_flag['BankLoanInsurance'] = true;
                 $return_bank->BankLoanInsurance =  $bank['Price_Approve'];
                 array_push($bill->variables, $variable);
            }else if($bank['id_credit_approval'] == 3)
            {
                 $variable = getBillVariable('BankLoanDecorate', 'อนุมัติวงเงินตกแต่ง',  $bank['Price_Approve']);
                 $banks_variable_flag['BankLoanDecorate'] = true;
                 $return_bank->BankLoanDecorate =  $bank['Price_Approve'];
                 array_push($bill->variables, $variable);
            }else if($bank['id_credit_approval'] == 5)
            {
                $variable = getBillVariable('BankLoanMulti', 'อนุมัติวงเงินเอนกประสงค์',  $bank['Price_Approve']);
                $banks_variable_flag['BankLoanMulti'] = true; 
                $return_bank->BankLoanMulti =  $bank['Price_Approve'];
                array_push($bill->variables, $variable);  
            }else if($bank['id_credit_approval'] == 6)
            {
                $variable = getBillVariable('SumBankLoan', 'วงเงินจำนองรวม',  $bank['Price_Approve']);
                $banks_variable_flag['SumBankLoan'] = true;  
                 $return_bank->SumBankLoan =  $bank['Price_Approve']; 
                array_push($bill->variables, $variable);
            }else
            {
                array_push( $bank_other_loans,  $bank['Price_Approve']);        
                //$variable = getBillVariable('BankLoanOther', 'อนุมัติวงเงินอื่น ๆ ',  '-');
            }
            
                /*$variable = getBillVariable('BankLoanOther', 'อนุมัติวงเงินอื่น ๆ ',  '-');
                array_push($bill->variables, $variable);
                $variable = getBillVariable('SumBankLoan', 'วงเงินจำนองรวม',  '-');
                array_push($bill->variables, $variable);
                $variable = getBillVariable('BankLoanInsurance', 'อนุมัติวงเงินค่าประกัน',  '-');
                array_push($bill->variables, $variable);
                $variable = getBillVariable('BankLoanMulti', 'อนุมัติวงเงินเอนกประสงค์',  '-');
                array_push($bill->variables, $variable);
                $variable = getBillVariable('BankLoanDecorate', 'อนุมัติวงเงินตกแต่ง',  '-');
                array_push($bill->variables, $variable);*/
        }

        $bank_sum = 0;
        foreach($bank_other_loans as $key => $value)
        {
           $bank_other_paid =  $bank_other_loans[$key];
           $bank_sum += $bank_other_paid;
        }
        $variable = getBillVariable('BankLoanOther', '-',  $bank_sum);
        array_push($bill->variables, $variable);
        $return_bank->BankLoanOther =  $bank['Price_Approve'];

        foreach($banks_variable_flag as $key => $value)
        {
            $var_flag = $banks_variable_flag[$key];
            if(!$var_flag)
            {
                if($banks)
                    $variable = getBillVariable($key, '-',  0);
                else
                    $variable = getBillVariable($key, '-', '-');
                array_push($bill->variables, $variable);
            }
        }
       
   }

   function getRepayment($bill)
   {
        $min_repayment = $bill->IVZ_LOANREPAYMENTMINIMUNAMT;
        $percent_reapayment = $bill->IVZ_LOANREPAYMENTPERC / 100;
     
        return max($min_repayment , $percent_reapayment * getPriceOnContractFromSaleData($bill));

   }

   function getSumBankPayments($bill)
   {
        return 0;
   }

   function getBankPayment($bill)
   {
        //case cash
       $answer =  getRepayment($bill) - getSumBankPayments($bill);
        if($answer < 0)
            return 0;
        else 
            return $answer;
   }

   function getRealBankPayment($bill)
   {
        return getRepayment($bill) - getSumBankPayments($bill);
   }

   function getCompareValueRepayment($bill)
   {
        return getPaymentPrice($bill);
   }

   function getCompanyPayment($bill)
   {
     
        $a = getCompareValueRepayment($bill);
        return $a - getRealBankPayment($bill);
        //case bank
   }

   function  getCustomerHouseAddress($bill)
   {
    if(isset($bill->IVZ_PROJSALESTITLEDEEDNUMBER))
        return $bill->IVZ_PROJSALESTITLEDEEDNUMBER;
    else
     return '?';
   }

   function getCallTime($bill)
   {
    if(isset($bill->call_time))
    {
     return $bill->call_time->format('Y/m/d H:i:s');
    }else 
      return "?";
   }   

   function getAppointDate($bill)
   {
    if(isset($bill->appoint_time))
    {
     return $bill->appoint_time->format('Y/m/d');
    }else 
      return "?";
   }

   function getAppointTime($bill)
   {
    if(isset($bill->appoint_time))
    {
     return $bill->appoint_time->format('H:i:s');
    }else 
      return "?";
   }

   function getEstimatePrice($bill)
   {
        if(isset($bill->IVZ_ESTIMATEPRICE))
            return $bill->IVZ_ESTIMATEPRICE;
        else
            return false;
   }

   function getBillVariable($codename, $description, $value)
    {
        $variable = new stdClass;
        $variable->$codename = new stdClass;
        $variable->$codename->name = $description;
        $variable->$codename->value = convertutf8($value);
        return $variable;
    }




?>