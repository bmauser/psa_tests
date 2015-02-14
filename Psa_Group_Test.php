<?php

include_once 'psa_init.php';


class Psa_Group_Test extends PHPUnit_Framework_TestCase{


	protected function setUp(){

		run_sql_file();
	}

	// tearDown()
	public function testRestoreByID(){

		$group = new Psa_Group('psa');
		$group->restore();
		$this->assertEquals(1, $group->id);
	}


	public function testRestoreByName(){

		$group = new Psa_Group(1);
		$group->restore();
		$this->assertEquals('psa', $group->name);
	}


	public function testCreate(){

		$group = new Psa_Group('new');
		$group->name = 'TestName';
		$group->save();

		$group = new Psa_Group('TestName');
		$group->restore();
		$this->assertEquals(2, $group->id);

		$group = new Psa_Group(2);
		$group->restore();
		$this->assertEquals('TestName', $group->name);
	}


	public function testRename(){

		$group = new Psa_Group(1);
		$group->name = 'TestRename';
		$group->save();

		$group = new Psa_Group(1);
		$group->restore();
		$this->assertEquals('TestRename', $group->name);
	}


	public function testChangeID(){

		$group = new Psa_Group('new');
		$group->name = 'testChangeID';
		$group->save();

		$group = new Psa_Group('testChangeID');
		$group->id = 20;
		$group->save();

		$group = new Psa_Group(20);
		$group->restore();
		$this->assertEquals('testChangeID', $group->name);
	}


	/**
	 * @expectedException Psa_Group_Exception
	 */
	public function testDeleteByID(){

		deleteGroup(1);

		$group = new Psa_Group(1);
		$group->restore();
	}


	/**
	 * @expectedException Psa_Group_Exception
	 */
	public function testDeleteByName(){

		deleteGroup('psa');

		$group = new Psa_Group(1);
		$group->restore();
	}


	public function testDeleteSeveral(){

		$group = new Psa_Group('new');
		$group->name = 'testDeleteSeveralByID1';
		$group->save();

		$group = new Psa_Group('new');
		$group->name = 'testDeleteSeveralByID2';
		$id = $group->save();

		deleteGroup(array('psa', 'testDeleteSeveralByID1', $id));

		try{
			$group = new Psa_Group(1);
			$group->restore();
		}catch(Psa_Group_Exception $e){
		}
		if($group->name)
			$this->fail('Failed delete group psa');

		try{
			$group = new Psa_Group('testDeleteSeveralByID1');
			$group->restore();
		}catch(Psa_Group_Exception $e){
		}
		if($group->id)
			$this->fail('Failed delete group testDeleteSeveralByID1');

		try{
			$group = new Psa_Group($id);
			$group->restore();
		}catch(Psa_Group_Exception $e){
		}
		if($group->name)
			$this->fail('Failed delete group testDeleteSeveralByID2');
	}


	public function testDeleteUnexisting(){

		$this->assertEquals(-1, deleteGroup('xxxxxxx'));
	}


	public function testDeleteSeveralUnexisting(){

		$this->assertEquals(-1, deleteGroup(array('xxxxxxx', 123, 'psa')));
	}


	/**
	 * @expectedException Psa_Group_Exception
	 */
	public function testRestoreUnexisting(){

		$group = new Psa_Group(123);
		$group->restore();
	}


	public function testAddRemoveUser(){

		$group = new Psa_Group(1);
		$this->assertEquals(1, $group->remove_user(1));
		$this->assertEquals(0, isUserInGroup(1, 1));

		$group = new Psa_Group(1);
		$this->assertEquals(1, $group->add_user(1));
		$this->assertEquals(1, isUserInGroup(1, 1));
	}


	public function testAddUsers(){

		$group = new Psa_Group(1);
		$this->assertEquals(-1, $group->add_user(array(3, 2, 1)));
	}


	public function testAddUnexistingUser(){

		$group = new Psa_Group(1);
		$this->assertEquals(-1, $group->add_user(123));
	}


	public function testRemoveUsers(){

		$group = new Psa_Group(1);
		$this->assertEquals(-1, $group->remove_user(array(3, 2, 1)));
		$this->assertEquals(0, isUserInGroup(1, 1));
	}


	public function testRemoveUnexistingUser(){

		$group = new Psa_Group(1);
		$this->assertEquals(-1, $group->remove_user(123));
	}


	public function testExtend(){

		global $TCFG;

		run_sql_file('test2');

		$group = new TestGroup('TestGroup');
		$group->restore();
		$this->assertEquals('aaa', $group->custom_col1);

		$group->custom_col2 = 'eee';
		$group->save();
		$group->restore();

		$this->assertEquals('eee', $group->custom_col2);
		$this->assertEquals('ccc', $group->custom_col3);
	}

}


class TestGroup extends Psa_Group{


	public function __construct($group_id_or_groupname){

		parent::__construct($group_id_or_groupname, array('id', 'name', 'custom_col1', 'custom_col2', 'custom_col3'));
	}
}
