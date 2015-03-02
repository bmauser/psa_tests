<?php

include_once __DIR__ . '/init/init.php';


class ActiveRecordTest extends PHPUnit_Framework_TestCase{


	protected function setUp(){

		run_sql_file();
	}


	public function testSave(){

		$ar = new ar_example(1);
		$ar->username = 'testtest';
		$ar->save_db();

		$ar->restore_db();
		$this->assertEquals('testtest', $ar->username);
	}


	public function testNew(){

		$ar = new ar_example();
		$ar->username = 'new';
		$id = $ar->save_db();
		$this->assertEquals(2, $id);

		$ar->restore_db($id);
		$this->assertEquals('new', $ar->username);
	}


	public function testLoad(){

		$ar = new ar_example(1);
		$ar->restore_db();
		$this->assertEquals('psa', $ar->username);
	}


	public function testBeforeSaveToDatabaseModifier(){

		$ar = new ar_example(1);
		$ar->restore_db();

		$ar->set_before_save_to_database_modifier();
		$ar->save_db();
		$ar->restore_db();
		$this->assertEquals('psaMODIFIED_DBSAVE', $ar->username);
	}


	public function testAfterRestoreFromDatabaseModifier(){

		$ar = new ar_example(1);

		$ar->set_after_restore_from_database_modifier();
		$ar->restore_db();
		$this->assertEquals('psaMODIFIED_DBRESTORE', $ar->username);

		$ar->unset_modifiers();
		$ar->restore_db();
		$this->assertEquals('psa', $ar->username);
	}


	public function testSessionSave(){

		$_SESSION = array();

		$ar = new ar_example(1);
		$ar->restore_db();
		$ar->save_sess();

		$this->assertEquals('psa', $_SESSION['psa_active_record_data']['psa_user'][1]['username']);
	}


	public function testSessionRestore(){

		$_SESSION = array();

		$ar = new ar_example(1);
		$ar->restore_db();
		$ar->save_sess();

		$data = $ar->restore_sess();
		$this->assertEquals('psa', $data['username']);
	}


	public function testSelectColumnSql(){

		$ar = new ar_example(1);
		$ar->username = 'testtest';
		$ar->save_db();

		$settings['select_sql'] = "RPAD(username, 20, '*') AS username";
		$ar->set_col_settings('username', $settings);

		$ar->restore_db();

		$this->assertEquals('testtest************', $ar->username);
	}


	public function testInsertUpdateColumnSql(){

		$ar = new ar_example(1);
		$ar->username = 'testtest';

		$settings['insert_update_sql'] = "RPAD(?, 20, '*')";
		$ar->set_col_settings('username', $settings);

		$ar->save_db();

		$ar->restore_db();
		$this->assertEquals('testtest************', $ar->username);
	}


	public function testInsertUpdateColumnNoParam1(){

		$ar = new ar_example(); // new row
		$settings['insert_update_sql'] = "CONCAT('Test','Test')";
		$settings['insert_no_params'] = true;
		$settings['update_no_params'] = true;
		$ar->set_col_settings('username', $settings);
		$ar->save_db();
		$ar->restore_db();
		$this->assertEquals('TestTest', $ar->username);


		$ar = new ar_example(1);
		$settings['insert_update_sql'] = "CONCAT('Test33','Test33')";
		$settings['insert_no_params'] = true;
		$settings['update_no_params'] = true;
		$ar->set_col_settings('username', $settings);
		$ar->save_db();
		$ar->restore_db();
		$this->assertEquals('Test33Test33', $ar->username);


	}


	public function testInsertUpdateColumnNoParam2(){

		$ar = new ar_example();
		$settings['insert_update_sql'] = "CONCAT('Test8','Test8')";
		$settings['insert_no_params'] = true;
		$settings['update_no_params'] = true;
		$ar->set_col_settings('username', $settings);

		$settings['insert_update_sql'] = "22+22";
		$settings['insert_no_params'] = true;
		$settings['update_no_params'] = true;
		$ar->set_col_settings('last_login', $settings);
		$ar->save_db();

		$ar->restore_db();
		$this->assertEquals('Test8Test8', $ar->username);
		$this->assertEquals('44', $ar->last_login);

		// try with different values
		$ar->username = '1122';
		$ar->last_login = '5555';
		$ar->save_db();
		$ar->restore_db();
		$this->assertEquals('Test8Test8', $ar->username);
		$this->assertEquals('44', $ar->last_login);
	}


	public function testUpdateSql(){

		$ar = new ar_example();
		$ar->username = 'newusername';
		$settings['update_sql'] = "CONCAT('Test88','Test88')";
		$settings['update_no_params'] = true;
		$ar->set_col_settings('username', $settings);

		$settings['update_sql'] = "55";
		$settings['insert_sql'] = "66";
		$settings['insert_no_params'] = true;
		$settings['update_no_params'] = true;
		$ar->set_col_settings('last_login', $settings);

		$ar->save_db(); // insert

		$ar->restore_db();
		$this->assertEquals('newusername', $ar->username);
		$this->assertEquals('66', $ar->last_login);

		$ar->username = 'edit';
		$ar->save_db(); // update
		$ar->restore_db();
		$this->assertEquals('Test88Test88', $ar->username);
		$this->assertEquals('55', $ar->last_login);
	}


}


class ar_example extends ActiveRecord{

	public $id;
	public $username;
	public $last_login;

	public function __construct($row_id = null){

		$table_columns = array('id', 'username', 'last_login');

		parent::__construct('psa_user', 'id', $row_id, $table_columns, 'psa_user_id_seq');
	}

	public function set_col_settings($col_name, $options){
		$this->setColumnSettings($col_name, $options);
	}

	public function save_db(){
		return $this->saveToDatabase();
	}

	public function save_sess(){
		return $this->saveToSession();
	}

	public function restore_db(){
		return $this->restoreFromDatabase();
	}

	public function restore_sess(){
		return $this->restoreFromSession();
	}

	public function set_before_save_to_database_modifier(){
		$this->registerDataModifier('before_save_to_database', 'username',  function($username){
    			return $username . 'MODIFIED_DBSAVE';
		});
	}

	public function set_after_restore_from_database_modifier(){
		$this->registerDataModifier('after_restore_from_database', 'username',  function($username){
			return $username . 'MODIFIED_DBRESTORE';
		});
	}

	public function unset_modifiers(){
		$this->psa_modifiers = array();
	}
}
