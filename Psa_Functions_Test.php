<?php

include_once 'psa_init.php';
include_once PSA_BASE_DIR . '/wri/asfunctions.php'; 

class Psa_Functions_Test extends PHPUnit_Framework_TestCase{


	//protected function setUp(){

	//	run_sql_file();
	//}
	
	public function ntestPSA_CFG1(){

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
	 * @expectedException PSA_Exception
	 */
	public function ntestPSA_CFG2(){
	
		$cfg = PSA_CFG();
		$this->assertArrayHasKey('logging', $cfg);
		$cfg['eeee'] = 123;
		PSA_CFG('eeee');
	}


	public function ntestPSA_CFG3(){

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
		
		$this->assertEquals(PSA_CFG1()['aaa'], 123);
		$this->assertEquals(PSA_CFG1('aaa'), 123);
	}
	
	
	public function testNew1(){
	
		bla()->aaa = 123;
		bla()->bbb = 345;
		
		$o = bla();
		
		$this->assertEquals($o->aaa, 123);
		$this->assertEquals($o->bbb, 345);
		
		bla('in1')->aaa = 11;
		bla('in2')->aaa = 22;
		
		$this->assertEquals(bla('in1')->aaa, 11);
		$this->assertEquals(isset(bla('in2')->bbb), false);
	}
	
	
	public function testNew2(){
	
		run_sql_file();
		bla2('in1','psa');
		bla2('in1')->restore();
	}
	
	/**
	 * @expectedException Psa_Exception
	 */
	public function testNew3(){
	
		run_sql_file();
		bla2('in1','psa');
		bla2('in1','www');
	}
	
	
	


}

function bla2($instance_name = null){
	
	// no arguments for constructor
	if(func_num_args() <= 1)
		return getInstance('Psa_User', $instance_name);
	
	// with constructor arguments
	$args = func_get_args();
	array_shift($args);
	return call_user_func_array('getInstance', array('Psa_User', $instance_name, $args));
}


function bla($instance_name = null){
	// no arguments for constructor
	if(func_num_args() <= 1)
		return getInstance('stdClass', $instance_name);
	
	// with constructor arguments
	$args = func_get_args();
	array_shift($args);
	return call_user_func_array('getInstance', array('stdClass', $instance_name, $args));
}


