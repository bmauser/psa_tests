<?php

include_once 'psa_init.php';


class Router_Test extends PHPUnit_Framework_TestCase{


	public function testExplodeUrl(){

		$r = new Router();

		$exp[] = 'mycontroller';
		$exp[] = 'mymethod';
		$exp[] = 'abc';
		$exp[] = '123';

		$url_arr = $r->explodeUrl('/mycontroller/mymethod/abc/123/');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		$url_arr = $r->explodeUrl('mycontroller/mymethod/abc/123/');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		$url_arr = $r->explodeUrl('mycontroller/mymethod/abc/123');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		$url_arr = $r->explodeUrl('  /mycontroller/mymethod/abc/123');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		$url_arr = $r->explodeUrl('  /mycontroller/mymethod/abc/123    ');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		$url_arr = $r->explodeUrl('/mycontroller/mymethod/abc/123?aaaa=rrerw/ssdsd');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		Reg()->basedir_web = '/aaaa/bbbb';
		$url_arr = $r->explodeUrl('/aaaa/bbbb/mycontroller/mymethod/abc/123?aaaa=rrerw/ssdsd');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		Reg()->basedir_web = 'aaaa/bbbb';
		$url_arr = $r->explodeUrl('/aaaa/bbbb/mycontroller/mymethod/abc/123?aaaa=rrerw/ssdsd');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		Reg()->basedir_web = '/aaaa/bbbb/';
		$url_arr = $r->explodeUrl('/aaaa/bbbb/mycontroller/mymethod/abc/123?aaaa=rrerw/ssdsd');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		$exp = array();

		$url_arr = $r->explodeUrl('');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		$url_arr = $r->explodeUrl('     ');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));

		$url_arr = $r->explodeUrl('/');
		$this->assertEquals(json_encode($exp), json_encode($url_arr));
	}


	public function testGetDispatchData(){

		$r = new Router();

		$exp['controller'] = 'Mycontroller_Controller';
		$exp['action'] = 'mymethod_action';
		$exp['arguments'] = array('abc', '123');

		$dt = $r->getDispatchData('/mycontroller/mymethod/abc/123/');
		$this->assertEquals(json_encode($exp), json_encode($dt));

		$exp['controller'] = 'Default_Controller';
		$exp['action'] = 'default_action';
		$exp['arguments'] = array();

		$dt = $r->getDispatchData('');
		$this->assertEquals(json_encode($exp), json_encode($dt));

		$dt = $r->getDispatchData('/');
		$this->assertEquals(json_encode($exp), json_encode($dt));
	}


	public function testDispach(){

		Reg()->basedir_web = '';

		$r = new Router();
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

		$r = new Router();

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
