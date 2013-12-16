<?php

include_once 'psa_init.php';


class Psa_Router_Test extends PHPUnit_Framework_TestCase{


	public function testExplodeUrl(){

		$r = new Psa_Router();

		$exp[] = 'mycontroller';
		$exp[] = 'mymethod';
		$exp[] = 'abc';
		$exp[] = '123';

		$url_arr = $r->explode_url('/mycontroller/mymethod/abc/123/');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		$url_arr = $r->explode_url('mycontroller/mymethod/abc/123/');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		$url_arr = $r->explode_url('mycontroller/mymethod/abc/123');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		$url_arr = $r->explode_url('  /mycontroller/mymethod/abc/123');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		$url_arr = $r->explode_url('  /mycontroller/mymethod/abc/123    ');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		$url_arr = $r->explode_url('/mycontroller/mymethod/abc/123?aaaa=rrerw/ssdsd');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		Psa_Registry::get_instance()->basedir_web = '/aaaa/bbbb';
		$url_arr = $r->explode_url('/aaaa/bbbb/mycontroller/mymethod/abc/123?aaaa=rrerw/ssdsd');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		Psa_Registry::get_instance()->basedir_web = 'aaaa/bbbb';
		$url_arr = $r->explode_url('/aaaa/bbbb/mycontroller/mymethod/abc/123?aaaa=rrerw/ssdsd');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		Psa_Registry::get_instance()->basedir_web = '/aaaa/bbbb/';
		$url_arr = $r->explode_url('/aaaa/bbbb/mycontroller/mymethod/abc/123?aaaa=rrerw/ssdsd');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		$exp = array();

		$url_arr = $r->explode_url('');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		$url_arr = $r->explode_url('     ');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		$url_arr = $r->explode_url('/');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));
	}


	public function testGetDispatchData(){

		$r = new Psa_Router();

		$exp['controller'] = 'Mycontroller_Controller';
		$exp['action'] = 'mymethod_action';
		$exp['arguments'] = array('abc', '123');

		$dt = $r->get_dispatch_data('/mycontroller/mymethod/abc/123/');
		$this->assertEquals(json_encode($exp), json_encode($dt));

		$exp['controller'] = 'Default_Controller';
		$exp['action'] = 'default_action';
		$exp['arguments'] = array();

		$dt = $r->get_dispatch_data('');
		$this->assertEquals(json_encode($exp), json_encode($dt));

		$dt = $r->get_dispatch_data('/');
		$this->assertEquals(json_encode($exp), json_encode($dt));
	}


	public function testDispach(){

		Psa_Registry::get_instance()->basedir_web = '';

		$r = new Psa_Router();
		try{
			$r->dispach('TestClass', 'test_method', array('1234'));
		}catch(Exception $e){
			$this->assertEquals('1234', $e->getMessage());
		}

		$_SERVER["REQUEST_URI"] = '/test/test/abc/123/';
		try{
			$r->dispach();
		}catch(Exception $e){
			$this->assertEquals('abc', $e->getMessage());
		}

		// test return
		$this->assertEquals('test_method1', $r->dispach('TestClass', 'test_method1', array('aaabbbcc')));
	}


	public function testDispachUnexising(){

		$r = new Psa_Router();

		// unexisting method
		try{
			$r->dispach('TestClass', 'test_method_unexisitng', array('1234'));
		}
		catch(Exception $e){
			$this->assertEquals(102, $e->getCode());
		}

		// unexisting class
		try{
			$r->dispach('TestClass_unexisitng', 'test_method_unexisitng', array('1234', '789'));
		}
		catch(Exception $e){
			$this->assertEquals(101, $e->getCode());
		}
	}
}


class TestClass{


	public function test_method($msq){

		throw new Exception($msq);
	}

	public function test_method1(){

		return 'test_method1';
	}
}


class Test_Controller{


	public function test_action($msq){

		throw new Exception($msq);
	}
}
