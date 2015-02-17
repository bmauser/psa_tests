<?php

include_once 'psa_init.php';


class User_Test extends PHPUnit_Framework_TestCase{


	protected function setUp(){

		run_sql_file();
	}


	/**
	 * @expectedException UserException
	 */
	public function testUnexisting(){

		$user = new User('unexisting');
		$user->restore();
	}


	public function testAuthorizeId(){

		$user = new User(1);
		$user->restore('id');
		$this->assertEquals('psa', $user->username);

		unset($user);

		$user = new User('1');
		$user->restore('id');
		$this->assertEquals('psa', $user->username);
	}


	public function testAuthorizeUsername(){

		$user = new User('psa');
		$user->restore('username');
		$this->assertEquals(1, $user->id);
	}


	public function testAuthorizeUsernamePass(){

		$user = new User('psa');
		$user->authorize('psa');
		$this->assertEquals(1, $user->id);
	}


	public function testCreate(){

		$user = new User('new');
		$user->username = 'TestUsername';
		$user->password = 'TestPass';
		$user->save();

		$user = new User(2);
		$user->restore();
		$this->assertEquals('TestUsername', $user->username);

		$user = new User('TestUsername');
		$user->authorize('TestPass');
		$this->assertEquals(2, $user->id);
	}


	public function testChangeUsername(){

		$user = new User(1);
		$user->username = 'testChangeUsername';
		$user->save();

		$user = new User(1);
		$user->restore();
		$this->assertEquals('testChangeUsername', $user->username);
	}


	public function testChangeId(){

		$user = new User('new');
		$user->username = 'TestUsername';
		$user->password = 'TestPass';
		$user->save();

		$user = new User('TestUsername');
		$user->id = '10';
		$user->save();

		$user = new User(10);
		$user->restore();
		$this->assertEquals('TestUsername', $user->username);
	}


	/**
	 * @expectedException UserException
	 */
	public function testDeleteByID(){

		deleteUser(1);

		$user = new User(1);
		$user->restore();
	}


	/**
	 * @expectedException UserException
	 */
	public function testDeleteByName(){

		deleteUser('psa');

		$user = new User(1);
		$user->restore();
	}


	public function testDeleteSeveral(){

		$user = new User('new');
		$user->username = 'testDeleteSeveralByID1';
		$user->password = 'testDeleteSeveralByID1';
		$user->save();

		$user = new User('new');
		$user->username = 'testDeleteSeveralByID2';
		$user->password = 'testDeleteSeveralByID2';
		$id = $user->save();

		deleteUser(array('psa', 'testDeleteSeveralByID1', $id));

		try{
			$user = new User(1);
			$user->restore();
		}catch(UserException $e){
		}
		if($user->username)
			$this->fail('Failed delete user psa');

		try{
			$user = new User('testDeleteSeveralByID1');
			$user->restore('username');
		}catch(UserException $e){
		}
		if($user->id)
			$this->fail('Failed delete group testDeleteSeveralByID1');

		try{
			$user = new User($id);
			$user->restore('id');
		}catch(UserException $e){
		}
		if($user->username)
			$this->fail('Failed delete group testDeleteSeveralByID2');
	}


	public function testDeleteUnexisting(){

		$this->assertEquals(-1, deleteUser('xxxxxxx'));
	}


	public function testDeleteSeveralUnexisting(){

		$this->assertEquals(-1, deleteUser(array('xxxxxxx', 123, 'psa')));
	}


	public function testAddGroup(){

		$group = new Group('new');
		$group->name = 'testAddGroup';
		$group->save();

		$user = new User(1);
		$user->addGroup(2);

		$this->assertEquals(1, isUserInGroup(1, 2));
	}


	public function testRemoveGroup(){

		$user = new User(1);
		$user->removeGroup(1);

		$this->assertEquals(0, isUserInGroup(1, 1));
	}


	public function testRemoveGroup1(){

		$user = new User('psa');
		$user->restore();
		$user->removeGroup(1);

		$this->assertEquals(0, isUserInGroup(1, 1));
	}


	public function testChangePassword(){

		$user = new User(1);
		$user->passwordChange('testChangePassword');
		$this->assertEquals(1, $user->passwordVerify('testChangePassword'));

		$user = new User('psa');
		$user->passwordChange('testChangePassword1');
		$this->assertEquals(1, $user->passwordVerify('testChangePassword1'));
	}


	public function testsaveLastLoginTime(){

		$user = new User(1);
		$user->saveLastLoginTime();
	}


	public function testGetGroups(){

		$user = new User(1);
		$groups = $user->getGroups();

		$this->assertEquals('psa', $groups[1]);
	}


	public function testExtend(){

		global $TCFG;

		run_sql_file('test1');

		$user = new TestUser('TestUser');
		$user->restore();
		$this->assertEquals('aaa', $user->custom_col1);

		$user->custom_col2 = 'eee';
		$user->save();
		$user->restore();

		$this->assertEquals('eee', $user->custom_col2);
		$this->assertEquals('ccc', $user->custom_col3);
	}
}


class TestUser extends User{


	public function __construct($user_id_or_username){

		parent::__construct($user_id_or_username, array('id', 'username', 'custom_col1', 'custom_col2', 'custom_col3'));
	}
}
