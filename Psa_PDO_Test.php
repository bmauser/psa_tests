<?php

include_once 'psa_init.php';


class Psa_PDO_Test extends PHPUnit_Framework_TestCase{


	protected function setUp(){

		run_sql_file();
	}


	//public function testContructor(){
	//
	//	$db = new Psa_Pdo("SELECT * FROM psa_user");
	//	$this->assertEquals(true, $db->result instanceof PDOStatement);
	//}


	public function testQuery(){

		$db = new Psa_Pdo();
		$q = $db->query("SELECT * FROM psa_user");
		$this->assertEquals(true, $q);
	}


	public function testAffectedRows(){

		$db = new Psa_Pdo();
		$db->query("UPDATE psa_user SET last_login = 123 WHERE id = 1");
		$this->assertEquals(1, $db->affected_rows());

		// this works only for mysql
		//$db->query("UPDATE psa_user SET last_login = 123 WHERE id = 1");
		//$this->assertEquals(0, $db->affected_rows());
	}


	public function testLastInsertID(){

		$db = new Psa_Pdo();
		$db->query("INSERT INTO psa_user (username, password) VALUES ('testLastInsertID','aa')");
		$this->assertEquals(2, $db->last_insert_id('psa_user_id_seq'));
	}


	public function testConnect(){

		global $PSA_CFG;
		$db = new Psa_Pdo();
		$a = $db->connect($PSA_CFG['pdo']['dsn'], $PSA_CFG['pdo']['username'], $PSA_CFG['pdo']['password'], $PSA_CFG['pdo']['driver_options']);
		$this->assertEquals(true, $a);

		$this->assertEquals(true, $db->pdo instanceof PDO);
	}


	public function testFetchAll(){

		$db = new Psa_Pdo();
		$db->query("SELECT * FROM psa_user");
		$all = $db->fetch_all();
		$this->assertEquals('psa', $all[0]['username']);
	}


	public function testFetchRow(){

		$db = new Psa_Pdo();
		$db->query("SELECT * FROM psa_user");
		while($row = $db->fetch_row())
			$this->assertEquals(1, $row['id']);
	}


	public function testPreparedStatements(){

		global $PSA_CFG;
		$db = new Psa_Pdo();

		$sql = "INSERT INTO psa_user (username, password) VALUES (?, ?)";
		$sql1 = "DELETE FROM psa_user WHERE id = ?";

		// insert row
		$db->execute(array('testPreparedStatements1', 'pass'), $db->prepare($sql));

		// insert row
		$db->execute(array('testPreparedStatements2', 'pass'), $sql);

		// delete row
		$db->execute(array($db->last_insert_id('psa_user_id_seq')), $db->prepare($sql1));

		// delete row
		$db->execute(array(2), $sql1);

		// check
		$db->query("SELECT * FROM psa_user");
		$users = $db->fetch_all();
		$this->assertEquals(1, count($users));
		$this->assertEquals('psa', $users[0]['username']);
	}
}
