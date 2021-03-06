<?php

include_once 'psa_init.php';


class Psa_Validator_Test extends PHPUnit_Framework_TestCase{


	public function testBetween(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_between('456', 2, 555));
		$this->assertEquals(true, $v->check_between(456, null, 456));
		$this->assertEquals(false, $v->check_between(1, 2, 3));
		$this->assertEquals(true, $v->check_between(1, 0, null));
		$this->assertEquals(false, $v->check_between('', 0, null));
	}


	public function testLenbetween(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_lenbetween('456', 2, 10));
		$this->assertEquals(true, $v->check_lenbetween(456, null, 3));
		$this->assertEquals(false, $v->check_lenbetween('aaaa', 2, 3));
		$this->assertEquals(true, $v->check_lenbetween(1, 0, null));
		$this->assertEquals(false, $v->check_lenbetween('', 2, null));
	}


	public function testInvalues(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_invalues('456', array(456, 123, 555)));
		$this->assertEquals(false, $v->check_invalues('111', array(456, 123, 555)));
		$this->assertEquals(false, $v->check_invalues('', array(456, 123, 555)));
	}


	public function testInt(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_int(0));
		$this->assertEquals(false, $v->check_int(array()));
		$this->assertEquals(true, $v->check_int(123));
		$this->assertEquals(true, $v->check_int('13548'));
		$this->assertEquals(false, $v->check_int(1565.545));
		$this->assertEquals(false, $v->check_int(true));
		$this->assertEquals(true, $v->check_int(+123));
		$this->assertEquals(true, $v->check_int(-45687));
		$this->assertEquals(false, $v->check_int('+123'));
		$this->assertEquals(true, $v->check_int('-45687'));
		$this->assertEquals(false, $v->check_int(''));
	}


	public function testID(){

		$v = new Psa_Validator();
		$this->assertEquals(false, $v->check_id('0'));
		$this->assertEquals(true, $v->check_id('123'));
		$this->assertEquals(false, $v->check_id(0));
		$this->assertEquals(false, $v->check_id(125.5));
		$this->assertEquals(false, $v->check_id('sdasdas'));
		$this->assertEquals(false, $v->check_id(true));
		$this->assertEquals(false, $v->check_id(''));
	}


	public function testDate(){

		$v = new Psa_Validator();
		$this->assertEquals(false, $v->check_date('22.22.2222', 'mm.dd.yyyy'));
		$this->assertEquals(true, $v->check_date('11/30/2008', 'mm/dd/yyyy'));
		$this->assertEquals(true, $v->check_date('30-01-2008', 'dd-mm-yyyy'));
		$this->assertEquals(true, $v->check_date('2008 01 30', 'yyyy mm dd'));
		$this->assertEquals(false, $v->check_date('29#02#2009', 'dd#mm#yyyy'));
		$this->assertEquals(false, $v->check_date('20asdfasdf', 'yyyy mm dd'));
		$this->assertEquals(false, $v->check_date('2008 01 30', 'yyyy mmddd'));
		$this->assertEquals(false, $v->check_date('', ''));
	}


	public function testEmail(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_email('asdasdasd@asdasdasd.com'));
		$this->assertEquals(false, $v->check_email('@asdasdasd.com'));
		$this->assertEquals(false, $v->check_email('@'));
		$this->assertEquals(true, $v->check_email('a@u.com'));
		$this->assertEquals(false, $v->check_email('asdasdh asd@asdasdasd.comrerv'));
		$this->assertEquals(false, $v->check_email(''));
		$this->assertEquals(true, $v->check_email('sdfsdfsdf.dsfsdf@eeee.longtldlongtldlongtldlongtld'));
	}


	public function testIP4(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_ip4('1.1.1.1'));
		$this->assertEquals(true, $v->check_ip4('0.0.0.0'));
		$this->assertEquals(true, $v->check_ip4('0.0.1.1'));
		$this->assertEquals(true, $v->check_ip4('255.255.255.255'));
		$this->assertEquals(false, $v->check_ip4('1.1.1.256'));
		$this->assertEquals(true, $v->check_ip4('145.145.17.0'));
		$this->assertEquals(false, $v->check_ip4('sdfsdfsdf'));
		$this->assertEquals(false, $v->check_ip4('10.0.0.011'));
		$this->assertEquals(false, $v->check_ip4('10.0.0.0xa'));
		$this->assertEquals(false, $v->check_ip4(''));
	}


	public function testFloat(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_float('12345.4568'));
		$this->assertEquals(true, $v->check_float('12345'));
		$this->assertEquals(false, $v->check_float('12345.4568u'));
		$this->assertEquals(true, $v->check_float(12345.4568));
		$this->assertEquals(true, $v->check_float(0));
		$this->assertEquals(true, $v->check_float('-6544.64554'));
		$this->assertEquals(true, $v->check_float(+54654.645564));
		$this->assertEquals(true, $v->check_float('+54654.645564'));
		$this->assertEquals(false, $v->check_float(''));
	}


	public function testString(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_string('yxcyxcyxc \\asd"'));
		$this->assertEquals(true, $v->check_string('__------__'));
		$this->assertEquals(true, $v->check_string('5345345'));
		$this->assertEquals(true, $v->check_string('-**-654'));
		$this->assertEquals(true, $v->check_string(+5645684));
		$this->assertEquals(true, $v->check_string(6546546.5645));
		$this->assertEquals(false, $v->check_string(array()));
		$this->assertEquals(false, $v->check_string(''));
	}


	public function testAlpha(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_alpha('asdfeUIZLK'));
		$this->assertEquals(false, $v->check_alpha('asdfeUIZ123LK'));
		$this->assertEquals(false, $v->check_alpha(''));
	}


	public function testNum(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_num('13545'));
		$this->assertEquals(false, $v->check_num('13545.sdfsdf'));
		$this->assertEquals(false, $v->check_num('13545.54646'));
		$this->assertEquals(false, $v->check_num('-13545'));
		$this->assertEquals(false, $v->check_num('-13545.5'));
		$this->assertEquals(false, $v->check_num('+13545'));
		$this->assertEquals(false, $v->check_num(''));
	}


	public function testAlphanum(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_alphanum('asdfeUIZLK'));
		$this->assertEquals(true, $v->check_alphanum('asd98787feUIZLK'));
		$this->assertEquals(true, $v->check_alphanum('4654564654'));
		$this->assertEquals(false, $v->check_alphanum(' 4654564654_--'));
		$this->assertEquals(false, $v->check_alphanum('_'));
		$this->assertEquals(true, $v->check_alphanum(4654));
		$this->assertEquals(false, $v->check_alphanum(''));
	}


	public function testDomainsafe(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_domainsafe('asd23sda23'));
		$this->assertEquals(false, $v->check_domainsafe('asd23s--da23'));
		$this->assertEquals(true, $v->check_domainsafe('asd23s-da23'));
		$this->assertEquals(false, $v->check_domainsafe('asd23s-.da23'));
		$this->assertEquals(false, $v->check_domainsafe('google.com'));
		$this->assertEquals(false, $v->check_domainsafe('goo323$'));
		$this->assertEquals(false, $v->check_domainsafe(''));
	}


	public function testHostname(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_hostname('asasd.asfasd.asfsdf'));
		$this->assertEquals(false, $v->check_hostname('asasd.asfasd.asfsdf_'));
		$this->assertEquals(false, $v->check_hostname(''));
		$this->assertEquals(false, $v->check_hostname('asasd.asfa--sd.asfsdf_'));
		$this->assertEquals(true, $v->check_hostname('asasd.asfasd.longtldlongtldlongtld'));
	}


	public function testRegex(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_regex('456', '/^[1-9]+$/'));
		$this->assertEquals(false, $v->check_regex(456, '/^[1-3]+$/'));
		$this->assertEquals(false, $v->check_regex('', '/^[1-3]+$/'));
	}


	public function testURL(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_url('http://docs.google.com/#spreadsheets'));
		$this->assertEquals(true, $v->check_url('http://www.google.hr/search?num=100&hl=hr&newwindow=1&client=firefox-a&rls=org.mozilla%3Aen-US%3Aofficial&hs=Qjt&q=aaa&btnG=Tra%C5%BEi&meta='));
		$this->assertEquals(false, $v->check_url('sdfcsfsdffd'));
		$this->assertEquals(false, $v->check_url('http://google.com.'));
		$this->assertEquals(true, $v->check_url('http://aaaa.longtldlongtldlongtld'));
	}


	public function testCallback(){

		$v = new Psa_Validator();

		$this->assertEquals(true, $v->check_callback(1, 'test_callback'));
		$this->assertEquals(false, $v->check_callback(-1, 'test_callback'));

		$temp = test_callback(1);

		$v->required(1, 'callback', 'test_callback');

		try{
			$v->required(-1, 'callback', 'test_callback', 'Custom message');
		}
		catch(Psa_Validation_Exception $e){
			$this->assertEquals('Custom message', $e->getMessage());
		}
	}


	public function testEqual(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_equal(1, 1));
		$this->assertEquals(true, $v->check_equal('1', 1));
		$this->assertEquals(true, $v->check_equal('01aaa', 1));
		$this->assertEquals(false, $v->check_equal('0', 'a'));
		$this->assertEquals(true, $v->check_equal(0, 'a'));
	}


	public function testIdentical(){

		$v = new Psa_Validator();
		$this->assertEquals(true, $v->check_identical(1, 1));
		$this->assertEquals(false, $v->check_identical('1', 1));
		$this->assertEquals(false, $v->check_identical('01aaa', 1));
		$this->assertEquals(false, $v->check_identical('0', 'a'));
		$this->assertEquals(false, $v->check_identical(0, 'a'));
	}

	// public function testIP6(){
	// }
	public function testRequired(){

		$v = new Psa_Validator();
		$v->required('123', 'between', 2, 500);

		try{
			$v->required('', 'between', 2, 500);
		}catch(Psa_Validation_Exception $e){
			;
		}
	}


	public function testOptional(){

		$v = new Psa_Validator();
		$v->optional('123', 'lenbetween', 2, 500);
		$v->optional('', 'lenbetween', 2, 500);
		$v->optional(null, 'email');
	}


	public function testCustomMessges(){

		$v = new Psa_Validator();

		try{
			$v->optional(123, 'email', 'Email is invalid --');
		}catch(Psa_Validation_Exception $e){
			$this->assertEquals('Email is invalid --', $e->getMessage());
		}

		try{
			$v->required('123', 'between', 2, 500, 'aaa');
		}catch(Psa_Validation_Exception $e){
			$this->assertEquals('aaa', $e->getMessage());
		}
	}


	public function testCustomMessgesRequired(){

		$v = new Psa_Validator();

		try{
			$v->required('', 'int', 'aaa');
		}catch(Psa_Validation_Exception $e){
			$this->assertEquals('Value for int is required. aaa', $e->getMessage());
		}

		try{
			$v->required('', 'int', 'aaa "%v" bbb');
		}catch(Psa_Validation_Exception $e){
			$this->assertEquals('Value for int is required. aaa "" bbb', $e->getMessage());
		}

		try{
			$v->required('', 'int');
		}catch(Psa_Validation_Exception $e){
			$this->assertEquals('Value for int is required.', $e->getMessage());
		}
	}


	public function testArrays(){

		$v = new Psa_Validator();
		try{
			$v->required(array(2, 5, 8, 501), 'between_array', 2, 500);
		}catch(Psa_Validation_Exception $e){
			$this->assertEquals('501 is not in between 2 and 500', $e->getMessage());
		}

		try{
			$v->required(array(2, 5, 8, 'asdasdasd'), 'int_array', 'zzz');
		}catch(Psa_Validation_Exception $e){
			$this->assertEquals('zzz', $e->getMessage());
		}
	}


	public function testNoExceptions(){

		$v = new Psa_Validator(true);

		$v->required(array(2, 5, 8, 501), 'between_array', 2, 500);
		$v->required(array(2, 5, 8, 'asdasdasd'), 'int_array', 'zzz');

		$errors = $v->get_errors();

		$this->assertEquals(501, $errors[0]['value']);
		$this->assertEquals('asdasdasd', $errors[1]['value']);
	}


	public function testInstanceof(){

		$v = new Psa_Validator();

		$a = new stdClass();

		try{
			$v->required($a, 'instanceof', 'aaa');
		}catch(Psa_Validation_Exception $e){
			$this->assertEquals('Value is not an instance of aaa', $e->getMessage());
		}

		$v->required($a, 'instanceof', 'stdClass');

		$u = new Psa_User(123);
		$uu = new Psa_User(456);
		$v->required($u, 'instanceof', 'Psa_User');
		$v->required($u, 'instanceof', $uu);
	}
}

function test_callback($a){

	if($a > 0)
		return true;
	return false;
}
