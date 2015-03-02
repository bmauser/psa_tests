<?php

include_once __DIR__ . '/init/init.php';


class DbTest extends PHPUnit_Framework_TestCase{


	protected function setUp(){

		run_sql_file();
	}


	//public function testContructor(){
	//
	//	$db = new Db("SELECT * FROM psa_user");
	//	$this->assertEquals(true, $db->result instanceof PDOStatement);
	//}


	public function testQuery(){

		$db = new Db();
		$q = $db->query("SELECT * FROM psa_user");
		$this->assertEquals(true, $q);
	}


	public function testAffectedRows(){

		$db = new Db();
		$db->query("UPDATE psa_user SET last_login = 123 WHERE id = 1");
		$this->assertEquals(1, $db->affectedRows());
	}


	public function testLastInsertID(){

		$db = new Db();
		$db->query("INSERT INTO psa_user (username, password) VALUES ('testLastInsertID','aa')");
		$this->assertEquals(2, $db->lastInsertId('psa_user_id_seq'));
	}


	public function testConnect(){

		global $PSA_CFG;
		$db = new Db();
		$a = $db->connect($PSA_CFG['db']['dsn'], $PSA_CFG['db']['username'], $PSA_CFG['db']['password'], $PSA_CFG['db']['driver_options']);
		$this->assertEquals(true, $a);

		$this->assertEquals(true, $db->pdo instanceof PDO);
	}


	public function testFetchAll(){

		$db = new Db();
		$db->query("SELECT * FROM psa_user");
		$all = $db->fetchAll();
		$this->assertEquals('psa', $all[0]['username']);
	}


	public function testFetchRow(){

		$db = new Db();
		$db->query("SELECT * FROM psa_user");
		while($row = $db->fetchRow())
			$this->assertEquals(1, $row['id']);
	}


	public function testPreparedStatements(){

		global $PSA_CFG;
		$db = new Db();

		$sql = "INSERT INTO psa_user (username, password) VALUES (?, ?)";
		$sql1 = "DELETE FROM psa_user WHERE id = ?";

		// insert row
		$db->execute(array('testPreparedStatements1', 'pass'), $db->prepare($sql));

		// insert row
		$db->execute(array('testPreparedStatements2', 'pass'), $sql);

		// delete row
		$db->execute(array($db->lastInsertId('psa_user_id_seq')), $db->prepare($sql1));

		// delete row
		$db->execute(array(2), $sql1);

		// check
		$db->query("SELECT * FROM psa_user");
		$users = $db->fetchAll();
		$this->assertEquals(1, count($users));
		$this->assertEquals('psa', $users[0]['username']);
	}
}
