<?php
    $var_id1= createVariable("TEST Xank", "xestBank", "Nope", 0, "COOL");
    $var1 = findVariableById($var_id1);;
    assertEquals($var1['name'], "TEST Xank");

    $var_id2 = createVariable("TEST Tank", "testBank", "Nope", 0, 500.5);
    $var2 = findVariableById($var_id2);
    assertEquals($var2['name'], "TEST Tank");

    updateVariable($var_id1, 0, array(
        'name' => 'TEST Yo'
    ));
    $var1 = findVariableById($var_id1);
    assertEquals($var1['name'], 'TEST Yo');

    deleteVariable($var_id1);
    deleteVariable($var_id2);

    $var1 = findVariableById($var_id1);
    assertEquals($var1, false);

    $var2 = findVariableById($var_id2);
    assertEquals($var1, false);

?>