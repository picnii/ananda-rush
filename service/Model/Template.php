<?php
	function findTemplateByTransactionId($transaction_id)
	{
		$template_id = 5;
		$payments = getPaymentsByTemplateId($template_id);
        $SQL  = "select template_id from tranfer_transaction where id = $transaction_id";
        $result = DB_query($GLOBALS['connect'],$SQL);
        $row = DB_fetch_array($result);
		return array(
			"template_id"=>$row['template_id'],
			"payments"=>$payments
		);
	}

	function findAllTemplates()
	{
		$SQL = "SELECT * FROM tranfer_template";
		$result = DB_query($GLOBALS['connect'],$SQL);
		$templates = array();
		while($row = DB_fetch_array($result))
		{
			$template = _getTemplateFromRow($row);
			array_push($templates, $template);
		}
		return $templates;
	}

	function _getTemplateFromRow($row)
	{
		$template = new stdClass;
		$template->id =  $row['id'];
		$template->name =  $row['name'];
		$template->color = $row['description'];
		return $template;
	}

	function findTemplate($id)
	{
		$SQL = "SELECT * FROM tranfer_template WHERE id = {$id}";
		$result = DB_query($GLOBALS['connect'], $SQL);
		$row = DB_fetch_array($result);
		$template = _getTemplateFromRow($row);
		$template->payments = getPaymentsByTemplateId($template->id);
		return $template;
	}

	function createTemplate($name, $description, $payments)
	{
		//create
		/*$template_id = 99;
		$is_show = 0;
        $SQL  = "INSERT INTO tranfer_template(name,description,is_show)  VALUES ('$name', '$description','$is_show'); SELECT SCOPE_IDENTITY()";
        $result = DB_query($GLOBALS['connect'],$SQL);
        if($result){
            /*sqlsrv_next_result($result); 
            sqlsrv_fetch($result); 
            $template_id = sqlsrv_get_field($result, 0); 
            return $template_id;
            return true;
        }else{
            return false;
        }*/
        $result = array();
        $result['template_id'] =  $template_id = _createTemplate($name, $description, true);

		for($i = 0; $i < count($payments) ;$i++)
		{
			createTemplatePayment($template_id, $payments[$i]->id, $payments[$i]->order);
		}
		return true;
	}

	function _createTemplate($name, $description, $is_show)
	{
		 $SQL  = "INSERT INTO tranfer_template(name,description,is_show)  VALUES ('$name', '$description','$is_show');SELECT SCOPE_IDENTITY()";
		 $result = DB_query($GLOBALS['connect'],$SQL);
        if($result){
            sqlsrv_next_result($result); 
            sqlsrv_fetch($result); 
            $template_id = sqlsrv_get_field($result, 0); 
            return $template_id;
        }else{
            return false;
        }
	}

	function createTemplatePayment($template_id, $payment_id, $order)
	{
		$SQL = "INSERT INTO tranfer_template_payment(template_id, payment_id, orders) VALUES($template_id, $payment_id, $order)";
		$result = DB_query($GLOBALS['connect'],$SQL);
        if($result){
        	return true;
        }else
        	return false;
	}

	function updateTemplate($template_id, $name, $description)
	{	
		$sql = "UPDATE tranfer_template SET name = '{$name}', description = '{$description}' WHERE id = {$template_id}";
		$result = DB_query($GLOBALS['connect'],$sql);
         if($result){
           
            return true;
        }else{
            return false;
        }
	}

	function deleteTemplate($template_id)
	{
		//return result of delete
        $SQL  = " DELETE FROM tranfer_template WHERE id = $template_id ";
        $result = DB_query($GLOBALS['connect'],$SQL);
		return true;
	}

	function deleteTemplatePayment($template_id, $payment_id)
	{
		$SQL = "DELETE FROM tranfer_template_payment WHERE template_id = {$template_id} AND payment_id = {$payment_id}";
		$result = DB_query($GLOBALS['connect'],$SQL);
		return true;
	}

?>