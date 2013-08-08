<?php
foreach (glob("Controller/*.php") as $filename)
{
	    include $filename;
}
if($_GET['action'] == 'test')
{

	//do test here



}
?>