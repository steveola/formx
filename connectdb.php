<?php


/////CONFIG

$db_host = "localhost";
//$db_type = "mysqli";
$db_type = "sqlite3";
$db_name = "sqlite-database.db";
//$db_name = "sms";
$db_username = "root";
$db_password = "";


switch ($db_type) {
    case "mysqli":
	{//////////	MYSQLi///////
	$con = mysqli_connect($db_host,$db_username,$db_password,$db_name);
	$GLOBALS["con"] = $con;
function run_query($query){
$con = $GLOBALS["con"];	
mysqli_real_escape_string($con,$query);
	return mysqli_query($con,$query);
}		
	
function fetch_assoc($resulti){

	$con = $GLOBALS["con"];
	return mysqli_fetch_assoc($resulti);
}

function multi_query($query){
	$con = $GLOBALS["con"];
	 mysqli_real_escape_string($con, $query);
	return mysqli_multi_query($con, $query);
}

///reuturn table details
function describe_table($tablename) {
	$table_des_sql = "SHOW COLUMNS FROM $tablename";
	return $table_des_sql;
}


function describe_fname() { ///field name
	$describe_fname = "Field";
	return $describe_fname;
}


function describe_type() { ///field name
	$describe_type = "Type";
	return $describe_type;
}

///type and names returned

	}//////////	END MYSQLi///////
        break;
    case "sqlite3":
    {
$con = new SQLite3($db_name);
$GLOBALS["con"] = $con;
function run_query($query){
$con = $GLOBALS["con"];
$con->escapeString($query);
	return $con->query($query);
}


function multi_query($query){
//mysqli_fetch_assoc($this_result_otr)
$con = $GLOBALS["con"];
$con->escapeString($query);
	return $con->exec($query);
}

function fetch_assoc($results){
	return$results->fetchArray(SQLITE3_ASSOC);
	}

///reuturn table details
function describe_table($tablename) {
	$table_des_sql = "pragma table_info($tablename)";
	return $table_des_sql;
}

function describe_fname() { ///field name dblabel
	$describe_fname = "name";
	return $describe_fname;
}


function describe_type() { ///field type dblabel
	$describe_type = "type";
	return $describe_type;
}

$con = $GLOBALS["con"];

	}
        break;
    case "green":
    {

	}
          break;
    default:
	{
		//default type
	}
}


?>