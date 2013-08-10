<?php
include '../system/allfunction.php';

function getParamsFromSearchQuery($q)
{
    $arr = explode(".", $q);
    $answer = array();
    for($i=0 ;$i < count($arr); $i++)
    {
        $split_str = explode("=", $arr[$i]);
        $key = $split_str[0];
        $value = $split_str[1];
        $answer[$key] = $value;
    }
    return $answer;
}

function getWhereClauseFromQuery($q)
{
    if($q == "*")
        return "";
    $params = getParamsFromSearchQuery($q);
    $sql = "WHERE ";
    $isFirst = true;
    foreach ($params as $key => $value)
    {
        if($isFirst)
            $isFirst = false;
        else
            $sql = $sql." AND ";
        $sql = $sql." {$key} = {$value}";
    }
    return $sql;
}


foreach (glob("../Model/*.php") as $filename)
{
    include $filename;
}

class VariableTest extends PHPUnit_Framework_TestCase{
    
    public $var_id1;
    public $var_id2;
   public function testCreateVariable()    {    
        $this->var_id1 = $test_result = createVariable("TEST Xank", "xestBank", "Nope", 0, "COOL");
        $var1 = findVariableById($this->var_id1);
        $this->assertEquals($var1['name'], "TEST Xank");
        $this->var_id2 = $test_result = createVariable("TEST Tank", "testBank", "Nope", 0, 500.5);
        $var2 = findVariableById($this->var_id2);
        $this->assertEquals($var2['name'], "TEST Tank");
      //  $test_result = createVariable("TEST Tank", "testBank", "Nope", 0, 500.5);
        //$this->assertEquals(false, $test_result);
    }

    public function testFindVariable()
    {
        $test_result = findVariableById($this->var_id1);
        $this->assertEquals($test_result['name'], "TEST Tank");
        $this->assertEquals($test_result['type'], 0);
        $this->assertEquals($test_result['value'], 500.5);
        

    }

    public function testUpdateVariable()
    {
        $test_bank_variable = findVariableByCodename("testBank");
        $this->assertEquals($test_bank_variable['name'], "TEST Tank");
        $test_bank_result = updateVariable($test_bank_variable['id'], 0, array(
            'name' => "No Test"
        ));
        $after_update_variable = findVariableByCodename("testBank");
        $this->assertEquals($test_bank_variable['name'], 0, "No Test");
    }

    public function testDeleteVariable()
    {
        $test_result1 = findVariableByCodename("testBank");
        deleteVariable($test_result1['id']);
        $this->assertEquals(false, findVariableByCodename("testBank"));
        $test_result2 = findVariableByCodename("xestBank");
        deleteVariable($test_result2['id']);
        $this->assertEquals(false, findVariableByCodename("xestBank"));

    }
}
?>

