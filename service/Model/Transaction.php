
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
       // print_r($data);
        /*
        * Promotion
        */
        //if(isset($data['main_appointment_log_id']))
        $invoice_account = $data['InvoiceAccount'];
        $item_id =  $rs["ItemId"];
        $data['promotions'] = findAllPromotionFromUnitId($transaction_id, $invoice_account);

        $data['responsible_user_info'] = findResponsibleUser($item_id);

       //$data['promotions_should'] = $data['promotions'][0];
      //  $data['promotions'] = $data['promotions'][0];
            //$data['promotions'] = findAllPromotionPreapproveFromAppoinmentId($data['main_appointment_log_id']);

        //get company info
        if(isset($data['master_CompanyCode']))
        {
            $data['CompanyCode'] = $data['master_CompanyCode'];
        }
        $comp_data = findCompanyInfo($data);
        //print_r($data);       
        if(isset($comp_data))
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
    $sql = "SELECT *, m.projID as project_code, tap.id as main_appointment_log_id , tapl.payment as appoint_payment, tapl.promotion_co, mp.repayment_bank 
    FROM master_transaction as m LEFT JOIN Sale_Transection as s on m.ItemId = s.ItemID 
    LEFT JOIN master_project as mp ON m.projID = mp.proj_code    
    LEFT JOIN tranfer_appointment as tap ON tap.transaction_id = m.transaction_id
    LEFT JOIN tranfer_appointment_log as tapl ON tapl.id = tap.log_id
    WHERE m.transaction_id = {$transaction_id}";
    
    $result = DB_query($GLOBALS['connect'],$sql);
    $row =  DB_fetch_array($result);
    //print_r( $row );
    //$row['promotions'] = findAllPromotionPreapproveFromAppoinmentId($row['main_appointment_log_id']);
   // $row['promotions'] = findAllPromotionFromUnitId($transaction_id);
     $row['q_type'] = 'oldInfo';

     if(isset($row['transaction_id']) || $row[0] == $transaction_id)
        return $row;
    else
        return null;

}

function findCompanyInfo($row_transaction)
{
    $comp_code_lower = $row_transaction['CompanyCode'];
    $comp_code_sql = strtoupper($comp_code_lower);

    $sql = "SELECT master_company.*, master_company_bank.*, master_company_bank_2.bank_id as bank_id2, master_company_bank_2.bank_info as bank_info2 from master_company
          LEFT JOIN master_company_bank ON master_company_bank.company_id = master_company.comp_id
          LEFT JOIN master_company_bank_2 ON master_company_bank_2.company_id = master_company.comp_id
          WHERE comp_code = '{$comp_code_sql}'";
    
    //echo "<br/>";
    $result = DB_query($GLOBALS['connect'],$sql);
    $row =  DB_fetch_array($result);
   
     if(isset($row['comp_code']))
    {
        $bank_name_row = getBankInfo($row['bank_id']);
        $row['company_bank_name'] = $bank_name_row['bank_name'];
        $bank_name_row2 = getBankInfo($row['bank_id2']);
        $row['company_bank_name2'] = $bank_name_row2['bank_name'];
        //print_r($row);
        return $row;
    }
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
    //echo $SQL;
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
                    $SQL1 ="select  s.*,p.id_preapprove as Preapp_id_preapprove,p.itemid as Preapp_itemid,p.appoint_reason2_id as Preapp_appoint_reason2_id";
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
                     $SQL1.=",b.id_preapprove_bank,b.bank_code,b.Branch,b.status_user_select,ar.appoint_reason1_id as preapp_appoint_reason1_id,ar.appoint_reason1_name as preapp_appoint_reason1_name,";
                     $SQL1.="pri.id_preapprove_bank as priceApp_id_preapprove_bank,pri.id_credit_approval,cr.id_credit_approval,cr.name_credit_approval,mp.* ";
                     $SQL1.=" ,mp.repayment_bank ";
                     $SQL1.=" ,tapl.payment_type , tapl.appoint_time, tapl.people, tapl.call_time, tap.id as main_appointment_log_id, tapl.payment as appoint_payment , tapl.promotion_co  ";
                     //tap.id as main_appointment_log_id 

                     $SQL1.="from Sale_Transection s ";
                     $SQL1.="inner join preapprove p on p.itemid = s.itemID and p.InvoiceAccount = s.InvoiceAccount ";
                     $SQL1.="inner join master_transaction t on p.itemid = t.ItemId ";
                     $SQL1.="inner join preapprove_bank b on p.itemid = b.itemID and p.InvoiceAccount = b.InvoiceAccount ";
                     $SQL1.="inner join appointment_reason1 ar on p.appoint_reason1_id = ar.appoint_reason1_id ";
                     $SQL1.="LEFT join Price_Approve pri on b.id_preapprove_bank = pri.id_preapprove_bank ";
                     $SQL1.="LEFT join credit_approval_type cr on pri.id_credit_approval = cr.id_credit_approval ";
                     $SQL1.="inner join master_project mp on mp.proj_code = t.projID ";

                     $SQL1.="LEFT JOIN tranfer_appointment tap on tap.transaction_id = t.transaction_id ";
                     $SQL1.="LEFT JOIN tranfer_appointment_log tapl on tapl.id = tap.log_id ";


                     $SQL1.="where p.id_preapprove = '".$pre_id."' and b.status_user_select = '2' order by p.lastupdate DESC ";
                     $res = DB_query($GLOBALS['connect'],$SQL1);
                     $row = DB_num_rows($res);
                     $rt =  DB_fetch_array($res);

                     /**
                     * Old Promotion
                     */
                     //$rt['promotions'] = findAllPromotionPreapproveFromAppoinmentId($rt['main_appointment_log_id']);
                    // $rt['promotions'] = findAllPromotionFromUnitId($rt['transaction_id']);
                     $data = array();
                  //   echo $SQL1;
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

                        $SQL.=" ,tapl.payment_type , tapl.appoint_time, tapl.people, tapl.call_time, tap.id as main_appointment_log_id  ";

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
                         /*
                         * Old Promotion
                         */
                         //$dt['promotions'] = findAllPromotionPreapproveFromAppoinmentId($dt['main_appointment_log_id']);
                         //$dt['promotions'] = findAllPromotionFromUnitId($dt['transaction_id']);
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
       // $bill->promotions = $sale_data['promotions'];
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
	$SQL  = "INSERT INTO tranfer_transaction(unit_id,template_id,create_time, payments, variables)  VALUES ('$unit_id', '$template_id',GETDATE(), '{$payments_json}' ,'{$variables_json}'); SELECT SCOPE_IDENTITY() as ins_id";

  //  echo $SQL;
    //echo "<br/><br/>";
     $result = DB_query($GLOBALS['connect'],converttis620($SQL));
    if($result){
        $row = DB_fetch_array($result);
        $created_id = $row['ins_id'];
        return $created_id;
    }else{
        return false;
    }
}

function findAllLastTransactions($selector = "*", $unit_ids = null)
{
    $sql = "SELECT {$selector} FROM tranfer_transaction  INNER JOIN master_transaction on master_transaction.transaction_id = tranfer_transaction.unit_id WHERE id IN ( SELECT MAX(id) as id from tranfer_transaction  GROUP BY unit_id, template_id )";
    //echo $sql;
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

function findAllLastTransactionsByUnitIds($selector = "*",$unit_ids)
{
    $sql = "SELECT {$selector}
        FROM tranfer_transaction
         INNER JOIN master_transaction on master_transaction.transaction_id = tranfer_transaction.unit_id
        WHERE id IN
        (
        SELECT MAX(id) as id from tranfer_transaction WHERE ".getIdClauseFromParams($unit_ids, 'unit_id')." GROUP BY unit_id, template_id
        )
        ";
    //echo $sql;
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
    //echo $SQL;
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
function findAllTransaction($transaction_ids=null)
{
    if($transaction_ids == null)
        $SQL = "SELECT * FROM tranfer_transaction";
    else
	   $SQL = "SELECT * FROM tranfer_transaction WHERE ".getIdClauseFromParams($transaction_ids, 'id');
    

    $result = DB_query($GLOBALS['connect'],$SQL);
   // echo $SQL;;
    $numrow = DB_num_rows($result);
	if($numrow > 0){
        $data = array(); 
        while($res =  DB_fetch_array($result))
		{
            array_push($data,$res);
            //test
            //$test = mssql_fetch_row($result);
            //echo "test";
          //  print_r($test);
           // print_r($res);
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
            }
            if($args['unit_id'] != ""){
                $sql.="unit_id='".$args['unit_id']."', ";
            }
            if($args['template_id'] != ""){
                $sql.="template_id='".$args['template_id']."', ";
            }
            if($args['is_tranfer'] != "")
            {
                $sql.="is_tranfer = ".$args['is_tranfer'];
                $sql.=", tranfer_time = '".$args['tranfer_time']."'";
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
            $project_name = $bill->proj_name_th;
        else
            $project_name = '?';
        return $project_name;
    }

    function getRegulationFromSaleData($bill)
    {
        $project_id = $bill->proj_id;
        $sql = "SELECT name FROM regulation INNER JOIN regulation_masterproject ON regulation_masterproject.id_regulation = regulation.id WHERE id_master_project = {$project_id} ";
        $result = DB_query($GLOBALS['connect'],$sql);
        $row = DB_fetch_array($result);
        return $row['name'];
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
        if(isset($bill->RoomNo))
            $master_UnitNo = $bill->RoomNo;
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
        else if(isset($bill->HOUSESIZE))
              $master_HOUSESIZE = $bill->HOUSESIZE;
        else
            $master_HOUSESIZE = '?';
        return $master_HOUSESIZE;
    }

    function getCustomerNameFromSaleData($bill)
    {
       
        if(isset($bill->people))
            $SalesName = $bill->people;
        else if(isset($bill->SalesName) && is_string($bill->SalesName))
            $SalesName = $bill->SalesName;
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
    /*     if(isset($bill->SQM))
            $SQM = $bill->SQM;*/
            $SQM = getAreaOnContractFromSaleData($bill);
        if(!is_numeric($SQM))
            return '?';
        if(getPriceOnContractFromSaleData($bill) != '?')
            return getPriceOnContractFromSaleData($bill)/$SQM;
        return '?';
    }

    function getDiscountSaleData($bill)
    {
        /*$sum = 0;
        foreach ($bill->promotions as $promotion) {
            # code...
            if(!isset($promotion->payment_id))
            {
                $sum += $promotion->spacial_discount;
            }
        }
        return $sum;*/
        $sum = 0;
        $types = getPromotionRewardTypes();
       
        foreach ($bill->promotions as $promotion)
        {
         //   print_r($promotion);
            if($promotion['type_id'] == $types['spacial']->id)
                $sum += $promotion['amount'];
        }
        return $sum;
    }

    function getAreaOnContractFromSaleData($bill)
    {
      //  print_r($bill);
         if(isset($bill->master_LANDSIZE))
            $master_LANDSIZE = $bill->master_LANDSIZE;
        else if(isset($bill->LANDSIZE))
              $master_LANDSIZE = $bill->LANDSIZE;
        else
            $master_LANDSIZE = '?';
        //echo $master_LANDSIZE;
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

        return getAreaFromSaleData($bill);// - getAreaOnContractFromSaleData($bill) ;;
       /* $diff =  getAreaFromSaleData($bill) - getAreaFromSaleData($bill);
        if($diff < 0)
            return (0 - $diff);
        else
            return $diff;*/
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
        return getPriceAtPaydate($bill) - getSettAmount($bill) - getDiscountSaleData($bill);
   }

   function getIsBank($bill)
   {
       // print_r($bill);
        $reason_id = $bill->Preapp_appoint_reason1_id;

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

        $bank_name = '-';
        $return_bank = new stdClass;
        $firstBank = $banks[0];
        if($banks)
        {
            $variable = getBillVariable('BankLoanName', 'ชื่อธนาคาร',   $firstBank['master_bank_name']);
            //print_r($banks)
        }
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
                     $bank_name = $bank['master_bank_name'];
                }
                else if($bank['id_credit_approval'] == 2)
                {
                     $variable = getBillVariable('BankLoanInsurance', 'อนุมัติวงเงินค่าประกัน',  $bank['Price_Approve']);
                     $banks_variable_flag['BankLoanInsurance'] = true;
                     $return_bank->BankLoanInsurance =  $bank['Price_Approve'];
                     array_push($bill->variables, $variable);
                     $bank_name = $bank['master_bank_name'];
                }else if($bank['id_credit_approval'] == 3)
                {
                     $variable = getBillVariable('BankLoanDecorate', 'อนุมัติวงเงินตกแต่ง',  $bank['Price_Approve']);
                     $banks_variable_flag['BankLoanDecorate'] = true;
                     $return_bank->BankLoanDecorate =  $bank['Price_Approve'];
                     array_push($bill->variables, $variable);
                     $bank_name = $bank['master_bank_name'];
                }else if($bank['id_credit_approval'] == 5)
                {
                    $variable = getBillVariable('BankLoanMulti', 'อนุมัติวงเงินเอนกประสงค์',  $bank['Price_Approve']);
                    $banks_variable_flag['BankLoanMulti'] = true; 
                    $return_bank->BankLoanMulti =  $bank['Price_Approve'];
                    array_push($bill->variables, $variable);  
                    $bank_name = $bank['master_bank_name'];
                }else if($bank['id_credit_approval'] == 6)
                {
                    /*$variable = getBillVariable('SumBankLoan', 'วงเงินจำนองรวม',  $bank['Price_Approve']);
                    $banks_variable_flag['SumBankLoan'] = true;  
                     $return_bank->SumBankLoan =  $bank['Price_Approve']; 
                    array_push($bill->variables, $variable);*/
                }else
                {
                    array_push( $bank_other_loans,  $bank['Price_Approve']);        
                    $bank_name = $bank['master_bank_name'];
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
        $return_bank->BankLoanOther =  $bank_sum;

        foreach($banks_variable_flag as $key => $value)
        {
            $var_flag = $banks_variable_flag[$key];
            if(!$var_flag)
            {
                if($banks)
                    $variable = getBillVariable($key, '-',  0);
                else
                    $variable = getBillVariable($key, '-', '-');
                if($key != "SumBankLoan" && $key != "BankLoanOther")
                {
                     $return_bank->$key = 0;
                     array_push($bill->variables, $variable);
                }
               
            }
        }
        $sum_bank =0;
        foreach ($return_bank as $key => $value) {
            # code...
            $sum_bank += $return_bank->$key;
        }
        $return_bank->SumBankLoan = $sum_bank;
        $variable = getBillVariable('SumBankLoan', '-',  $sum_bank);
        array_push($bill->variables, $variable);
        $return_bank->BankLoanName = $bank_name;
        
        $variable = getBillVariable('BankLoanName', 'ชื่อธนาคาร',  $bank_name);
        array_push($bill->variables, $variable);
        
        $return_bank->SumBankDiff = $sum_bank - $return_bank->BankLoanRoom;
        //$variable = getBillVariable('SumBankDiff', '-',  $return_bank->SumBankDiff);
        $variable = getBillVariable('SumBankDiff', '-',  0);

        array_push($bill->variables, $variable);

       // $return_bank->test = "sompo";
        if(isset($return_bank))
            return $return_bank;
        else
            return array('test'=>"wrong");
       
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
     //return $bill->appoint_time->format('Y/m/d');
        $d = $bill->call_time;
        if($d instanceof DateTime){ 
            return convertDateThai($d->format('d m Y')); 
        } else { 
            //$answer = new DateTime(strtotime($d))
            return convertDateThai(date('d m Y', strtotime($d))); 
        }
        
    }else 
        $d = date('d m Y');
      return convertDateThai($d);;
   }   

   function getAppointDate($bill)
   {
    if(isset($bill->appoint_time))
    {
     //return $bill->appoint_time->format('Y/m/d');
        $d = $bill->appoint_time;
        if($d instanceof DateTime){ 
            return convertDateThai($d->format('d m Y')); 
            
        } else { 
            //$answer = new DateTime(strtotime($d))
            return convertDateThai(date('d m Y', strtotime($d))); 
        }
        
    }else 
      return "-";
   }

   function convertDateThai($date)
   {
        $split_arr = split(" ", $date);
        switch($split_arr[1])
        {
            case "01":
                $split_arr[1] = "มกราคม";
            break;
            case "02":
                $split_arr[1] = "กุมภาพันธ์";
            break;
            case "03":
                $split_arr[1] = "มีนาคม";
            break;
            case "04":
                $split_arr[1] = "เมษายน";
            break;
            case "05":
                $split_arr[1] = "พฤษภาคม";
            break;
            case "06":
                $split_arr[1] = "มิถุนายน";
            break;
            case "07":
                $split_arr[1] = "กรกฎาคม";
            break;
            case "08":
                $split_arr[1] = "สิงหาคม";
            break;
            case "09":
                $split_arr[1] = "กันยายน";
            break;
            case 10:
                $split_arr[1] = "ตุลาคม";
            break;
            case 11:
                $split_arr[1] = "พฤศจิกายน";
            break;
            case 12:
                $split_arr[1] = "ธันวาคม";
            break;

        }
        $answer ='';
        foreach ($split_arr as $key => $value) {
            # code...
            $answer = $answer.$value.' ';
        }
        return $answer;
   }

   function getAppointTime($bill)
   {
    if(isset($bill->appoint_time))
    {
     //return $bill->appoint_time->format('Y/m/d');
        $d = $bill->appoint_time;
        if($d instanceof DateTime){ 
            return $d->format('H:i'); 
        } else { 
            //$answer = new DateTime(strtotime($d))
            return date('H:i', strtotime($d)); 
        }
        
    }else 
      return "-";
    
   }

   function getEstimatePrice($bill)
   {
        if(isset($bill->IVZ_ESTIMATEPRICE))
            return $bill->IVZ_ESTIMATEPRICE;
        else
            return false;
   }

   function getBasePrice($bill)
   {
        if(isset($bill->BasePrice))
            return $bill->BasePrice;
        else
            return false;
   }

   function getBillVariable($codename, $description, $value)
    {
        $variable = new stdClass;
        $variable->$codename = new stdClass;
        $variable->$codename->name = $description;

        if(!mb_detect_encoding($value,'utf8') || $codename == "CustomerName" || $codename == "ProjectName")
            $variable->$codename->value = convertutf8($value);
        else
            $variable->$codename->value = $value;
        return $variable;
    }

    function convertSaleDataToBill($data, $template_id)
    {
        $bill = getSampleBill($template_id);
        
        foreach($data->variables as $key => $value)
        {
            //print_r($data->variables[$key]);
            $variable = getBillVariable($key, $data->variables[$key]->name, $data->variables[$key]->value);
            array_push($bill->variables, $variable);
        }

        
        $variable = getBillVariable('AppointmentMonth', 'เดือนวันที่นัดโอน', '13-15 กันยายน 2556');
        array_push($bill->variables, $variable);
        $variable = getBillVariable('UnitNumber', 'UNIT NO.', getUnitNumberFromSaleData($data));

        array_push($bill->variables, $variable);
        $variable = getBillVariable('CompanyName', 'ชื่อบริษัท', getCompanyNameFromSaleData($data));
        array_push($bill->variables, $variable);
        $variable = getBillVariable('companyAddress', 'ที่อยู่', getCompanyAddressFromSaleData($data));
        array_push($bill->variables, $variable);
        $variable = getBillVariable('companyPhone', 'เบอร์โทร', getCompanyTelFromSaleData($data));
        array_push($bill->variables, $variable);
        $variable = getBillVariable('companyFax', 'Fax', getCompanyFaxFromSaleData($data));
        array_push($bill->variables, $variable);
        $variable = getBillVariable('HouseNumber', 'บ้านเลขที่',  getCustomerHouseAddress($data));
        array_push($bill->variables, $variable);
        $variable = getBillVariable('HouseType', 'แบบบ้าน',  getItemTypeFromSaleData($data));
        array_push($bill->variables, $variable);
        $variable = getBillVariable('HouseSize', 'พื้นที่ใช้สอย',  getAreaFromSaleData($data));
        array_push($bill->variables, $variable);
        $variable = getBillVariable('DocumentDate', 'วันที่แจ้ง',  getCallTime($data));
        array_push($bill->variables, $variable);
        $variable = getBillVariable('SaleName', 'ชื่อผู้ติดต่อ',  '--');
        array_push($bill->variables, $variable);
        $variable = getBillVariable('PayDate', 'วันที่นัดโอน',   getAppointDate($data));
        array_push($bill->variables, $variable);
        $variable = getBillVariable('PayTime', 'เวลาที่นัดโอน',  getAppointTime($data));
        array_push($bill->variables, $variable);
        
        $variable = getBillVariable('CustomerName', 'ชื่อูลกค้า',  getCustomerNameFromSaleData($data));
        array_push($bill->variables, $variable);

        $variable = getBillVariable('CustomerTel', 'เบอร์โทรลูกค้า',  getCustomerMobileFromSaleData($data));
        array_push($bill->variables, $variable);
        $variable = getBillVariable('PriceOnContract', 'ราคาตามสัญญา',  getPriceOnContractFromSaleData($data));
        array_push($bill->variables, $variable);
        $variable = getBillVariable('PricePerArea', 'ราคาต่อตารางเมตร',  getPricePerAreaSaleData($data));
        array_push($bill->variables, $variable);

        $variable = getBillVariable('SpacialDiscount', 'หักส่วนลดพิเศษ',  getDiscountSaleData($data));
        array_push($bill->variables, $variable);
        
        $variable = getBillVariable('ContractOfSpace', 'พื้นที่ตามสัญญา',  getAreaOnContractFromSaleData($data));
        array_push($bill->variables, $variable);

        $variable = getBillVariable('DifferenOfSpace', 'ส่วนต่างพื้นที่',  getAreaDiffFromSaleData($data));
        array_push($bill->variables, $variable);

        //getBasePrice($bill)
        $variable = getBillVariable('BasePrice', 'baseprice',  getBasePrice($data));
        array_push($bill->variables, $variable);

        /*print_r($data->promotions);
        $variable = getBillVariable('Promotions', 'promotion',  $data->promotions);
        array_push($bill->variables, $variable);*/

        $isBankPay = getIsBank($data);

        if($isBankPay)
        {   
          
            $bank = getBanksVariable($data);
            foreach ($bank as $key => $value) {
                # code...
                $variable = getBillVariable($key, $key,  $value);
                array_push($bill->variables, $variable);
            }
        }else
        {
            
            $variable = getBillVariable('BankLoanName', 'ชื่อธนาคาร',  '-');
            array_push($bill->variables, $variable);
            $variable = getBillVariable('BankLoanRoom', 'อนุมัติค่าห้อง',  '-');
            array_push($bill->variables, $variable);
            $variable = getBillVariable('BankLoanOther', 'อนุมัติวงเงินอื่น ๆ ',  '-');
            array_push($bill->variables, $variable);
            $variable = getBillVariable('SumBankLoan', 'วงเงินจำนองรวม',  '-');
            array_push($bill->variables, $variable);
            $variable = getBillVariable('BankLoanInsurance', 'อนุมัติวงเงินค่าประกัน',  '-');
            array_push($bill->variables, $variable);
            $variable = getBillVariable('BankLoanMulti', 'อนุมัติวงเงินเอนกประสงค์',  '-');
            array_push($bill->variables, $variable);
            $variable = getBillVariable('BankLoanDecorate', 'อนุมัติวงเงินตกแต่ง',  '-');
            array_push($bill->variables, $variable);
            $variable = getBillVariable('SumBankDiff', 'ผลต่างระหว่าง ค่าห้อง กับสินเชื่อรวม',  '-');
            array_push($bill->variables, $variable);
        }
        
        $variable = getBillVariable('ActualSpace', 'พื้นที่จริง',  getActualAreaFromSaleData($data));
        array_push($bill->variables, $variable);
        $variable = getBillVariable('PaidAmount', 'หักชำระแล้ว',  getSettAmount($data));
        array_push($bill->variables, $variable);
    
        
        $variable = getBillVariable('PayCheckBank', 'เช็คสั่งจ่ายธนาคาร',  getBankPayment($data));
        array_push($bill->variables, $variable);
        $variable = getBillVariable('PayCheckAnanda', 'เช็คสั่งจ่ายอนันดา',  getCompanyPayment($data));
        array_push($bill->variables, $variable);
        $variable = getBillVariable('PayCommonFeeCharge', 'ชำระส่วนกลาง',  '--');
        array_push($bill->variables, $variable);
        $variable = getBillVariable('PayCommonFeeFund', 'ชำระค่าสมทบ',  '--');
        array_push($bill->variables, $variable);

        $variable = getBillVariable('PayFeeForMinistryOfFinance', 'ชำระค่าธรรมเนียม',  '--');
        array_push($bill->variables, $variable);
        $variable = getBillVariable('PayFeeForTranferCash', 'แบ่งจ่ายเงินสด',  '--');
        array_push($bill->variables, $variable);
        $variable = getBillVariable('FinalCustomerPayment', 'รวมเป็นเงินที่ต้องชำระ',  '--');
        array_push($bill->variables, $variable);
        
        $variable = getBillVariable('PriceDateOfPayment', 'ราคาห้องชุด ณ วันโอน',  getPriceAtPaydate($data));
        array_push($bill->variables, $variable);
        
        $variable = getBillVariable('PriceRoomOfPayment', 'ค่าห้องชุดที่ต้องชำระ',  getPaymentPrice($data));
        array_push($bill->variables, $variable);
        
        $variable = getBillVariable('Repayment','ค่าปลอด',  getRepayment($data));
        array_push($bill->variables, $variable);

        $variable = getBillVariable('EstimatePrice','',  getEstimatePrice($data));
        array_push($bill->variables, $variable);

        $variable = getBillVariable('ProjectName','ค่าปลอด', getProjectNameFromSaleData($data));
        array_push($bill->variables, $variable);

         $variable = getBillVariable('Regulation','นิติ', getRegulationFromSaleData($data));
        array_push($bill->variables, $variable);

        //getRegulationFromSaleData($bill)

        if(isset( $data->master_transaction_id))
            $variable = getBillVariable('UnitId', '-',  $data->master_transaction_id);
        else
            $variable = getBillVariable('UnitId', '-',  $data->transaction_id);
        array_push($bill->variables, $variable);
        
        $variable = getBillVariable('WorkName', '-',  $data->work);
        array_push($bill->variables, $variable);

        $variable = getBillVariable("AppointPayment", "-", $data->appoint_payment);
        array_push($bill->variables, $variable);

         $variable = getBillVariable("PromotionCo", "-", $data->promotion_co);
        array_push($bill->variables, $variable);

        //$variable = getBillVariable('Promotions', '-',  $data->promotions);
        $bill->promotions = array();
        array_push($bill->promotions, $data->promotions);

        $repayment_bank = getBankInfo($data->repayment_bank);
        $variable = getBillVariable("RepaymentBank", "-", $repayment_bank['bank_name']);
        array_push($bill->variables, $variable);
        
        $variable = getBillVariable("CompanyBankName", "-", convertutf8($data->company_bank_name));
        array_push($bill->variables, $variable);

        $variable = getBillVariable("CompanyBankInfo", "-", convertutf8($data->bank_info));
        array_push($bill->variables, $variable);

         $variable = getBillVariable("CompanyBankName2", "-", convertutf8($data->company_bank_name2));
        array_push($bill->variables, $variable);

        $variable = getBillVariable("CompanyBankInfo2", "-", convertutf8($data->bank_info2));
        array_push($bill->variables, $variable);

        $variable = getBillVariable("ResponsibleName", "-", convertutf8($data->responsible_user_info['name_th']) );
        array_push($bill->variables, $variable);

        $variable = getBillVariable("ResponsibleLastName", "-", convertutf8($data->responsible_user_info['surname_th']) );
        array_push($bill->variables, $variable);

        $variable = getBillVariable("ResponsibleNameEn", "-", convertutf8($data->responsible_user_info['name_en']) );
        array_push($bill->variables, $variable);

         $variable = getBillVariable("ResponsibleLastNameEn", "-", convertutf8($data->responsible_user_info['surname_en']) );
        array_push($bill->variables, $variable);

        $variable = getBillVariable("ResponsibleTel", "-", convertutf8($data->responsible_user_info['tel']) );
        array_push($bill->variables, $variable);

        $variable = getBillVariable("ResponsibleFax", "-", convertutf8($data->responsible_user_info['fax']) );
        array_push($bill->variables, $variable);

         $variable = getBillVariable("ResponsibleMobile", "-", convertutf8($data->responsible_user_info['mobile']) );
        array_push($bill->variables, $variable);

        $variable = getBillVariable("ResponsibleEmail", "-", convertutf8($data->responsible_user_info['email']) );
        array_push($bill->variables, $variable);




        $promotions = array();
        foreach ($data->promotions as $promotion) {
             
            array_push($promotions, convertPromotionData($promotion));
        }
        
       
        foreach ($data->promotions as $promotion) {
            # code...
            foreach($bill->payments as $payment)
            {
                if($promotion['payment_id'] == $payment->id)
                {
                    
                    $payment->promotion = $promotion;
                    
                }
            }
            
        }

        return $bill;
    }

    function getSampleBill($template_id)
    {
        $sample = new stdClass;

        $sample->variables = array();
        $sample->paymentTypes = array("ธนาคาร", "บริษัท", "ลูกค้า");

        $sample->payments = getPaymentsByTemplateId($template_id);
        
        return $sample; 
    }

    function getBankInfo($bank_id)
    {
        $sql = "SELECT bank_name FROM master_bank WHERE bank_id = {$bank_id}";
        $result = DB_query($GLOBALS['connect'],$sql);
        $row = DB_fetch_array($result);
        return $row;
    }

    function findResponsibleUser($item_id)
    {

        $sql = "SELECT userprofile.* FROM cs_responsible_room LEFT JOIN userprofile ON userprofile.userid = cs_responsible_room.id_userprofile WHERE itemid = '{$item_id}'";
         $result = DB_query($GLOBALS['connect'],$sql);
        $row = DB_fetch_array($result);
        return $row;
    }

?>