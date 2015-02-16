<?php

$TCFG['use_db'] = 'mysql';
//$TCFG['use_db'] = 'pgsql'; // Uncoment to use pgsql database. Also change connection settings in psa/config_override.php

$TCFG['psa_dir'] = 'W:/WEBROOT/psa1/skeleton_application/app/psa';

// for mysql
$TCFG['mysql']['sql_file']['main'] = 'W:/WEBROOT/psa1/install/db_mysql.sql';
$TCFG['mysql']['sql_file']['test1'] = 'W:/WEBROOT/psa1/skeleton_application/app/psa/tests/test1_mysql.sql';
$TCFG['mysql']['sql_file']['test2'] = 'W:/WEBROOT/psa1/skeleton_application/app/psa/tests/test2_mysql.sql';
$TCFG['mysql']['cli_command'] = 'W:/APP/xampp-portable/mysql/bin/mysql -u <USERNAME> -p<PASSWORD> -h localhost <DATABASE_NAME> < <SQL_FILE>';

// for pgsql
$TCFG['pgsql']['sql_file']['main'] = 'W:/WEBROOT/psa1/install/db_pgsql.sql';
$TCFG['pgsql']['sql_file']['test1'] = 'W:/WEBROOT/psa1/tests/test1_pgsql.sql';
$TCFG['pgsql']['sql_file']['test2'] = 'W:/WEBROOT/psa1/tests/test2_pgsql.sql';
$TCFG['pgsql']['cli_command'] = '"C:/Program Files/PostgreSQL/9.3/bin/psql.exe" -U <USERNAME> -d <DATABASE_NAME> -h localhost -f <SQL_FILE>';

// NOTE: check also database connection settings in psa/config_override.php
// ------------------------------------------------------------------------


// PSA main dir
define('PSA_BASE_DIR', $TCFG['psa_dir']);


// include required files
include PSA_BASE_DIR . '/config.php';
include PSA_BASE_DIR . '/lib/Psa.php';
include PSA_BASE_DIR . '/lib/Logger.php';
include PSA_BASE_DIR . '/lib/Psa_Files.php';
include PSA_BASE_DIR . '/lib/Registry.php';
include PSA_BASE_DIR . '/lib/functions.php';
include PSA_BASE_DIR . '/lib/ActiveRecord.php';
include PSA_BASE_DIR . '/wri/asfunctions.php';


// register autoloader() function as __autoload() implementation
spl_autoload_register('autoloader');


// put PSA config array to registry
Reg()->PSA_CFG = $PSA_CFG;
// database connection wrapper object
Reg()->psa_database = Db();


// register and save files for autoloader
//$files_data = Files()->register();
//Files()->save($files_data);


// flag that this is test mode
if(!defined('PSA_TEST'))
	define('PSA_TEST', 1);


echo "\nPSA tests with {$TCFG['use_db']} database.\n\n";


/**
 * Executes SQL file.
 */
function run_sql_file($sql_file = null){

	global $PSA_CFG, $TCFG;

	if(!$sql_file)
		$sql_file = $TCFG[$TCFG['use_db']]['sql_file']['main'];
	else
		$sql_file = $TCFG[$TCFG['use_db']]['sql_file'][$sql_file];

	$command = $TCFG[$TCFG['use_db']]['cli_command'];

	$command = str_replace('<USERNAME>', $PSA_CFG['pdo']['username'], $command);
	$command = str_replace('<PASSWORD>', $PSA_CFG['pdo']['password'], $command);
	$command = str_replace('<DATABASE_NAME>', $PSA_CFG['pdo']['database'], $command);
	$command = str_replace('<SQL_FILE>', $sql_file, $command);

	shell_exec($command);
}
