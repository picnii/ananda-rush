<?php
function getPayments()
{
    $SQL  = "SELECT * FROM tranfer_payment";
    $result = DB_query($GLOBALS['connect'],$SQL);
    
    $payments =  array();
    while($row = DB_fetch_array($result))
    {
        $payment = new stdClass;
        $payment->id = $row['id'];
        $payment->name = $row['name'];
        $payment->description = $row['description'];
        $payment->formulas = array(
            $row['formula1'],
            $row['formula2'],
            $row['formula3']
        );
        $payment->is_shows = array(
            $row['is_show1'].'',
            $row['is_show2'].'',
            $row['is_show3'].''
        );
        $payment->is_add_in_cheque = $row['is_add_in_cheque'];
        $payment->is_compare_with_repayment = $row['is_compare_with_repayment'];
        array_push($payments, $payment);
    }
	
	return $payments;

}

function getAllPayments()
{
    return array(
        getSamplePayments(),
         getSamplePayments(),
          getSamplePayments()

    );
}

function findPaymentById($id)
{
    $SQL = "SELECT * FROM tranfer_payment WHERE id = {$id}";
    $result = DB_query($GLOBALS['connect'],$SQL);
    $row = DB_fetch_array($result);
    if(!isset($row['id']))
        return false;

    $payment = new stdClass;
    $payment->id = $row['id'];
    $payment->name = $row['name'];
    $payment->description = $row['description'];
    $payment->formulas = array(
        $row['formula1'],
        $row['formula2'],
        $row['formula3']
    );
    $payment->is_shows = array(
        $row['is_show1'].'',
        $row['is_show2'].'',
        $row['is_show3'].''
    );
    $payment->is_add_in_cheque = $row['is_add_in_cheque'];
    $payment->is_compare_with_repayment = $row['is_compare_with_repayment'];

    return $payment;
}

function createPayment($name, $description, $formulas, $is_shows, $is_add_in_cheque, $is_compare_with_repayment)
{   
    $SQL = "INSERT INTO tranfer_payment(name,description,formula1,formula2,formula3,is_show1,is_show2,is_show3,is_add_in_cheque, is_compare_with_repayment)
         VALUES ('$name', '$description','{$formulas[0]}','{$formulas[1]}','{$formulas[2]}', '{$is_shows[0]}',  '{$is_shows[1]}', '{$is_shows[2]}','{$is_add_in_cheque}', '{$is_compare_with_repayment}');
         SELECT SCOPE_IDENTITY() ins_id
    ";
     $result = DB_query($GLOBALS['connect'],$SQL);
    if($result){
           $row = DB_fetch_array($result);
            return $row['ins_id']; 
            //return $payment_id;
    }else{
            return false;
    }
	
}

function updatePayment($payment_id, $name, $description, $formulas, $is_shows, $is_add_in_cheque, $is_compare_with_repayment)
{
    $sql = "UPDATE tranfer_payment SET 
        name = '{$name}', description = '{$description}',
        formula1 = '{$formulas[0]}', formula2 = '$formulas[1]', formula3 = '$formulas[2]',
        is_show1 = {$is_shows[0]}, is_show2 = $is_shows[1], is_show3 = $is_shows[2],
        is_add_in_cheque = {$is_add_in_cheque}, is_compare_with_repayment = {$is_add_in_cheque}
        WHERE id = {$payment_id}
    ";
    $rs = DB_query($GLOBALS['connect'],$sql); 
    if($rs >0)
        return true;
    else
        return false;
   
}

function deletePayment($payment_id)
{
	    $SQL  = " DELETE FROM tranfer_payment WHERE id = $payment_id ";
        $result = DB_query($GLOBALS['connect'],$SQL);
		return true;
}


function isFixPaymentOrder($id)
{
    $ELECTRIC_BILL_15 = 6;
    $ELECTRIC_BILL_30 = 26;
    $ROOM_PAYMENT = 24;
    $WATER_METER = 25;
    $SUPPORT_FUND =  27;
    $TRANFER_FEE = 34;
    $LOAN_FEE = 42;
    $SHARE_PLACE_FEE = 18;
    $BANK_PAY_BACK = 47;
    $ARGORN = 41;
    $ARGORN_LOAN = 48;
    //order FROM MIN TO MAX
    $orders = array( $ROOM_PAYMENT,$ELECTRIC_BILL_15 , $ELECTRIC_BILL_30, $WATER_METER, $SHARE_PLACE_FEE, $SUPPORT_FUND, $TRANFER_FEE, $LOAN_FEE, $BANK_PAY_BACK, $ARGORN, $ARGORN_LOAN);

    $order_number = array( 1,2 , 2, 3, 4, 5, 6, 9, 7, 10, 8);
    //search for index in order
    for($i = 0; $i < count($orders); $i++) {
        # code...
        $value = $orders[$i];
        if($value == $id)
        {
            //$num = $i - count($orders) -1;
           // echo "found {$id} return $num";
            return  $order_number[$i];
        }
    }
/*found 6
found 18
found 24
found 25
found 27
found 34
not found41
found 42
found 47
found 48
found 6
found 18
found 24
found 25
found 27
found 34
not found41
found 42found 47found 48
*/
    //echo "not found{$id}";
    return false;
    // if have no index return false

    //if have index return index - length of orders



}

function getPaymentsByTemplateId($template_id)
{
    $SQL  = "select *,* from tranfer_payment INNER JOIN ";
    $SQL .="tranfer_template_payment on tranfer_template_payment.payment_id = tranfer_payment.id where tranfer_template_payment.template_id = $template_id";
	$result = DB_query($GLOBALS['connect'],$SQL);
    
    $payments =  array();
    $index = 0;
	while($row = DB_fetch_array($result) )
    {
        $payment = new stdClass;
        $payment->id = $row['id'];
        $fixOrder = isFixPaymentOrder($payment->id);
        //if($fixOrder)
            $payment->order = $fixOrder;
        //else
          //  $payment->order = 20;
        //hardcode
       // $payment->order += 12;
        $payment->name = $row['name'];
        $payment->description = $row['description'];
        $payment->formulas = array(
            $row['formula1'],
            $row['formula2'],
            $row['formula3']
        );
        $payment->is_shows = array(
            $row['is_show1'],
            $row['is_show2'],
            $row['is_show3']
        );
        $payment->is_add_in_cheque = $row['is_add_in_cheque'];
        $payment->is_compare_with_repayment = $row['is_compare_with_repayment'];
        array_push($payments, $payment);
    }

    /*for($i = 0; $i < count($payments); $i++)
    {
        for($j = 0; $j <count($payments); $j++)
        {
            if($payments[$i]->order < $payments[$j]->order)
            {
                //swap
                $tmp = $payments[$i]->order;
                $payments[$i]->order = $payments[$j]->order;
                $payments[$j]->order = $tmp;
            }
        }
        
    }*/

    for($i = 0 ; $i < count($payments); $i++)
    {
        $payments[$i]->number = ($i + 1);
    }
    //if(count($payments) >0){
    	return $payments;
    //}else{
      //  return false;
    //}
}

function getSamplePayments($count)
    {
        $payments =  array();
        for($i = 0; $i < $count ;$i++)
        {
            $payments[$i] = new stdClass;
            $payments[$i]->order = ($i + 1);
            $payments[$i]->id = rand();
            $payments[$i]->name = "Payment".rand();
            $payments[$i]->description = "*อาจมีเพิ่ม/ลดตามพื้นที่จริง";
            $payments[$i]->formulas = array(
                    "",
                    "",
                    "{priceOnContact} - {paidAmount}"
                );
            $payments[$i]->is_shows = array(
                    0,
                    0,
                    1
            ); 
        }

        return $payments;
    }

    function convertPaymentsToValues($payment, $search, $search_value)
    {
        $answer = array();

        foreach ($search as $key => $value) {
            # code...
            $search[$key] = '{'.$value.'}';
        }

        for($i =0; $i < count($payment->formulas); $i++)
        {
            $test = $payment->formulas[$i]; 
            $answer[$i] = str_replace($search, $search_value, $test);
            if(is_string( $answer[$i] ) && strlen($answer[$i] )> 1)
                eval("\$answer[\$i] = ".$answer[$i].";");
            //echo  strlen($answer[$i] );

            //if(is_string( $answer[$i] ))
              //  eval();
        }
        return $answer;
    }

?>
