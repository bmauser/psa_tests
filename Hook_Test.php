<?php

include_once 'psa_init.php';


class Hook_Test extends PHPUnit_Framework_TestCase{


	public function testHooksByType(){

		try{
			psa_run_hooks(array('TestHook' => array('psa_main' => array('testtesttest'))), false);
		}
		catch(Exception $e){
			$this->assertEquals('testtesttest', $e->getMessage());
			return;
		}

		$this->fail();
	}

}

