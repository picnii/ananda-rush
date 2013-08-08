<?php
include '../system/allfunction.php';
foreach (glob("../Model/*.php") as $filename)
{
    include $filename;
}
include '../util.php';

class VariableTest extends PHPUnit_Framework_TestCase{
   public function testCreateVariable()    {    
        $test_result = createVariable("TEST Xank", "xestBank", "Nope", 0, "COOL");
        $this->assertEquals(true, $test_result);
        $test_result = createVariable("TEST Tank", "testBank", "Nope", 0, 500.5);
        $this->assertEquals(true, $test_result);
      //  $test_result = createVariable("TEST Tank", "testBank", "Nope", 0, 500.5);
        //$this->assertEquals(false, $test_result);
    }

    public function testFindVariable()
    {
        $test_result = findVariableByCodename("testBank");
        $this->assertEquals($test_result['name'], "TEST Tank");
        $this->assertEquals($test_result['type'], 0);
        $this->assertEquals($test_result['value'], 500.5);
        $find_id = $test_result['id'];
        $test_result2 = findVariableById($find_id);
        $this->assertEquals($test_result2['type'], $test_result['type']);
        $this->assertEquals($test_result2['name'], $test_result['name']);
        $this->assertEquals($test_result2['value'], $test_result['value']);

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

