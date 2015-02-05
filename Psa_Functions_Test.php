<?php

include_once 'psa_init.php';


class Psa_Functions_Test extends PHPUnit_Framework_TestCase{


	//protected function setUp(){

	//	run_sql_file();
	//}
	
	public function testPSA_CFG1(){

		$cfg = PSA_CFG();
		$this->assertArrayHasKey('logging', $cfg);
		
		$cfg = &PSA_CFG();
		$cfg['test1'] = 123;
		$this->assertEquals(PSA_CFG()['test1'], 123);
		
		$cfg = &PSA_CFG();
		$cfg['test2']['test3'] = 456;
		$this->assertEquals(PSA_CFG('test2.test3'), 456);
		
		$cfg = &PSA_CFG('test2.test3');
		$cfg = 789;
		$this->assertEquals(PSA_CFG('test2.test3'), 789);
		$this->assertEquals(PSA_CFG()['test2']['test3'], 789);
		
		PSA_CFG()['test4']['test4'] = 777;
		$this->assertEquals(PSA_CFG('test4.test4'), 777);
		
	}
	
	
	/**
	 * @expectedException PSA_CFG_Exception
	 */
	public function testPSA_CFG2(){
	
		$cfg = PSA_CFG();
		$this->assertArrayHasKey('logging', $cfg);
		$cfg['eeee'] = 123;
		PSA_CFG('eeee');
	}


	public function testPSA_CFG3(){

		$arr['aaa'] = 1;
		$arr['bb']['cc'] = 2;
		$arr['bb']['oo'] = new stdClass();
		$arr['bb']['oo']->gg = 3;
		$arr['bb']['oo']->uu = 4;
		$arr['bb']['oo']->pp = array('ee' => 5);
		$arr['qq'] = new stdClass();
		$arr['qq']->ww = new stdClass();
		$arr['qq']->ww->uu = 6;
		
		PSA_CFG()['test'] = $arr;
		
		$this->assertEquals(PSA_CFG('test.aaa'), 1);
		$this->assertEquals(PSA_CFG('test.bb.cc'), 2);
		$this->assertEquals(PSA_CFG('test.bb.oo->gg'), 3);
		$this->assertEquals(PSA_CFG('test.bb.oo->uu'), 4);
		$this->assertEquals(PSA_CFG('test.bb.oo->pp.ee'), 5);
		$this->assertEquals(PSA_CFG('test.qq->ww->uu'), 6);		
	}


}