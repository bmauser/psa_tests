<?php

include_once 'psa_init.php';


class Psa_Active_Record_Test extends PHPUnit_Framework_TestCase{


	protected function setUp(){

		run_sql_file();
	}


	public function testSave(){

		$ar = new ar_example();
		$ar->username = 'testtest';
		$ar->save_db();
	}


	public function testLoad(){

		$ar = new ar_example();
		$ar->restore_db();
		$this->assertEquals('psa', $ar->username);
	}


	public function testBeforeSaveToDatabaseModifier(){

		$ar = new ar_example();
		$ar->restore_db();

		$ar->set_before_save_to_database_modifier();
		$ar->save_db();
		$ar->restore_db();
		$this->assertEquals('psaMODIFIED_DBSAVE', $ar->username);
	}


	public function testAfterRestoreFromDatabaseModifier(){

		$ar = new ar_example();

		$ar->set_after_restore_from_database_modifier();
		$ar->restore_db();
		$this->assertEquals('psaMODIFIED_DBRESTORE', $ar->username);

		$ar->unset_modifiers();
		$ar->restore_db();
		$this->assertEquals('psa', $ar->username);
	}


	public function testSessionSave(){

		$_SESSION = array();

		$ar = new ar_example();
		$ar->restore_db();
		$ar->save_sess();

		$this->assertEquals('psa', $_SESSION['psa_active_record_data']['psa_user'][1]['username']);
	}


	public function testSessionRestore(){

		$_SESSION = array();

		$ar = new ar_example();
		$ar->restore_db();
		$ar->save_sess();

		$data = $ar->restore_sess();
		$this->assertEquals('psa', $data['username']);
	}


	public function testSelectColumnSql(){

		$ar = new ar_example();
		$ar->username = 'testtest';
		$ar->save_db();

		$ar->set_select_column_sql();
		$ar->restore_db();

		$this->assertEquals('testtest************', $ar->username);
	}


	public function testInsertUpdateColumnSql(){

		$ar = new ar_example();
		$ar->username = 'testtest';
		$ar->set_insert_update_column_sql();
		$ar->save_db();

		$ar->restore_db();
		$this->assertEquals('testtest************', $ar->username);
	}
}


class ar_example extends Psa_Active_Record{

	public $id = 1;
	public $username;
	public $last_login;

	public function __construct(){

		$table_columns = array('id', 'username', 'last_login');

		parent::__construct('psa_user', 'id', $this->id, $table_columns, 'psa_user_id_seq');
	}

	public function set_select_column_sql(){
		$this->psa_select_column_sql['username'] = "RPAD(username, 20, '*') AS username";
	}

	public function set_insert_update_column_sql(){
		$this->psa_insert_update_column_sql['username'] = "RPAD(?, 20, '*')";
	}

	public function save_db(){
		return $this->save_to_database();
	}

	public function save_sess(){
		return $this->save_to_session();
	}

	public function restore_db(){
		return $this->restore_from_database();
	}

	public function restore_sess(){
		return $this->restore_from_session();
	}

	public function set_before_save_to_database_modifier(){
		$this->register_data_modifier('before_save_to_database', 'username',  function($username){
    			return $username . 'MODIFIED_DBSAVE';
		});
	}

	public function set_after_restore_from_database_modifier(){
		$this->register_data_modifier('after_restore_from_database', 'username',  function($username){
			return $username . 'MODIFIED_DBRESTORE';
		});
	}

	public function unset_modifiers(){
		$this->psa_modifiers = array();
	}
}
