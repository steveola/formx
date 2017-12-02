<!DOCTYPE html>
<html>
<head>
<script src="jquery.js"></script>
</head>
<body>
<style>
.active_tab {background:grey;width:100px; text-align:center;	border-top-left-radius: 1em;border-top-right-radius: 1em;}
.active_tab input {width:100%;background:none;border:none;}

.inactive_tab {background:blue;width:100px; text-align:center;	border-top-left-radius: 1em;border-top-right-radius: 1em;}
.inactive_tab input {width:100%;background:none;border:none;}

.each_tab {background:grey;}

.inactive_tab:hover {background:grey;width:100px; text-align:center;cursor: pointer;}
#tabs {margin:0px; }
#tabs div {margin:0px; float:left;}
#tabs td {border:0px;padding:0px;}

.error_class {background:blue;}
.error_label_class {background:blue;}
</style>
<?php



function testalert($msg){

echo "<script>alert(\"$msg\");</script>";
	
}

class formgen {
//fileupload processor 
public $db_host = "";
var $db_type = "";
//public $db_type = "sqlite3";
//public $db_name = "sqlite-database.db";
public $db_name = "";
public $db_username = "";
public $db_password = "";
public $db_port = "";  ///define for port for the database


/////CONFIG

function con(){
switch ($this->db_type) {
    case "mysqli":
	{
		return mysqli_connect($this->db_host,$this->db_username,$this->db_password,$this->db_name,$this->db_port);
	}
	break;
	case "sqlite3": 
	{
		return new SQLite3($this->db_name);
	}
	break;
	case "postgresql": 
	{
	$host = $this->db_host;
	$dbport = $this->db_port;
	$dbname = $this->db_name;
	$dbuser = $this->db_username;
	$dbpass = $this->db_password;
	return pg_connect("host=$host port=$dbport dbname=$dbname user=$dbuser password=$dbpass");
	}
}	
}

function run_query($query){
switch ($this->db_type) {
    case "mysqli":
	{
			$con = $this->con();	
			mysqli_real_escape_string($con,$query);
			return mysqli_query($con,$query);
	}
	break;
	case "sqlite3":
    {
			$con = $this->con();
			$con->escapeString($query);
			return $con->query($query);
	}
		break;
	case "postgresql": 
	{
			$con = $this->con();
			pg_escape_string($con,$query);			
			return pg_query($con,$query);	
	}
}		
}


function escape_string($query){
switch ($this->db_type) {
    case "mysqli":
	{
			$con = $this->con();	
			return mysqli_real_escape_string($con,$query);
			
	}
	break;
	case "sqlite3":
    {
			$con = $this->con();
			return $con->escapeString($query);
	}
		break;
	case "postgresql": 
	{
			$con = $this->con();
			return pg_escape_string($con,$query);			
		
	}
}		
}
	
function fetch_assoc($results){
 switch ($this->db_type) {
 case "mysqli":
	{
			$con = $this->con();
			return mysqli_fetch_assoc($results);	
	}
	break;
	case "sqlite3":
    {
			return$results->fetchArray(SQLITE3_ASSOC);
	}
			break;
	case "postgresql": 
	{
			$con = $this->con();
			return pg_fetch_assoc($results);
	}
}
}

function multi_query($query){
switch ($this->db_type) {
 case "mysqli":
	{
		$con = $this->con();
		mysqli_real_escape_string($con, $query);
		return mysqli_multi_query($con, $query);
	}
	break;
	case "sqlite3":
    {
		$con = $this->con();
		$con->escapeString($query);
		return $con->exec($query);
	}
			break;
	case "postgresql": 
	{
		$con = $this->con();
		return pg_query($con, $query);	
	}
}
}

function describe_table($tablename) {
 switch ($this->db_type) {
 case "mysqli":
	{
			$table_des_sql = "SHOW COLUMNS FROM $tablename";
			return $table_des_sql;
	}
	break;
	case "sqlite3":
    {
			$table_des_sql = "pragma table_info($tablename)";
			return $table_des_sql;
	}
			break;
	case "postgresql": 
	{
			$table_des_sql = "select column_name, data_type, character_maximum_length from INFORMATION_SCHEMA.COLUMNS where table_name = '$tablename';";
			return $table_des_sql;	
	}
}
}

function describe_fname() { ///field name
 switch ($this->db_type) {
 case "mysqli":
	{
			$describe_fname = "Field";
			return $describe_fname;
	}
	break;
	case "sqlite3":
    {
			$describe_fname = "name";
			return $describe_fname;
	}
	break;
	case "postgresql": 
	{
			$describe_fname = "column_name";
			return $describe_fname;	
	}
}
}

function describe_type() { ///field type
 switch ($this->db_type) {
 case "mysqli":
	{
		$describe_type = "Type";
		return $describe_type;
	}
	break;
	case "sqlite3":
    {
		$describe_type = "type";
		return $describe_type;
	}
	break;
	case "postgresql": 
	{
		$describe_type = "data_type";
		return $describe_type;	
	}
}
}







function prt($string){		
	echo $string . "\n";	
	}
function alert($string){		
	echo "<script>alert('" .$string . "');</script>" . "\n";	
	}
function form_render($data){
	foreach($data as $writer)
	{
		echo $writer. "\n";
	}
}

public $validation_error = array();
public $allow_sql = 0;

////RENDER FORM ITEMS
	
public $while_sort_interset = array();
public $while_sorted = array();
public $render_data = array();
public $field_data = array();
public $input_label = array();
public $input_element = array();
public $pre_print = array("");
public $post_print = array("");
public $printer = array();
public $update_success = false;
public $update_fields = array();
public $update_fields_where = array();
public $update_fields_from = array();
public $update_fields_ajax = array();
public $update_current_field = array();
////END RENDER FORM ITEMS	
public $separator = array();

////TAB SEPARATOR PROPERTIES
public $tab_separator = array();

public $set_client_validator = array();
public $print_client_validator = "";




////END TAB PROPERTIES

function ValidateAsEmail($name,$value,$validate_error){
	if(preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-._]+..[a-zA-Z0-9-.]+$/",$value))////email
	{
		///nothing runs here
	}
	else
	{
		if($validate_error =="**not_set"){$validate_error ="$value is no a valid email format";}
		$this->validation_error["$name"][] = $validate_error;
		$this->allow_sql += 1;
	}	
}


function ValidateAsInteger($name,$value,$validate_error){
	if(preg_match("/^[0-9]+$/",$value))////email
	{
		///nothing runs here
	}
	else
	{
		if($validate_error =="**not_set"){$validate_error ="$value is not an Integer";}
		$this->validation_error["$name"][] = $validate_error;
		$this->allow_sql += 1;
	}	
}


function ValidateAsFloat($name,$value,$validate_error){
	if(preg_match("/^[0-9.]+[0-9.]+$/",$value))////email
	{
		///nothing runs here
	}
	else
	{
		//ValidateAsFloatErrorMessage
		if($validate_error =="**not_set"){$validate_error ="$value is not a Float";}
		$this->validation_error["$name"][] = $validate_error;
		$this->allow_sql += 1;
	}	
}

function ValidateAsDate($name,$value,$separator,$validate_error){
	$num_split = explode($separator, $value);
	$array_cnum = count($num_split);
	$regstring = "/^[0-9]+"."$separator"."[0-9]+"."$separator"."[0-9]+$/";
	if(preg_match($regstring, $value) && ($array_cnum == 3))
	{
		///nothing runs here
	}
	else
	{
		//ValidateAsFloatErrorMessage
		if($validate_error =="**not_set"){$validate_error ="$value is not a valid date format";}
		$this->validation_error["$name"][] = $validate_error;
		$this->allow_sql += 1;
	}	
}

function CustomValidation($name,$value,$label,$arr,$validation_callback,$validate_error){
	
	if(is_callable($validation_callback)){
		$custom_validator = call_user_func($validation_callback,$value,$arr);
	if($custom_validator == true)
	{
		///nothing runs here
	}
	else
	{
		//ValidateAsFloatErrorMessage
		if($validate_error =="**not_set"){$validate_error ="$value is not valid for $label";}
		$this->validation_error["$name"][] = $validate_error;
		$this->allow_sql += 1;
	}
}	
}


//////CALLBACK METHODS
function insert_callback($callback){
	if(is_callable($callback)){	
		$call_callback = call_user_func($callback);
	}
	else
	{
		echo $callback;
	}	
}

function insert_failure($callback){
	if(is_callable($callback)){	
		$call_callback = call_user_func($callback);
	}
	else
	{
		echo $callback;
	}	
}


function update_callback($callback){
	if(is_callable($callback)){	
		$call_callback = call_user_func($callback);
	}
	else
	{
		echo $callback;
	}	
}


function update_failure($callback){
	if(is_callable($callback)){	
		$call_callback = call_user_func($callback);
	}
	else
	{
		echo $callback;
	}	
}

//////END CALLBACK METHODS



///////Simple INSERTION Form Generator from database table////////
////// add form type later
function formx($tablename, $print_form, $exception,$sort_array, $display, $addtional_field,$add_free_field,$field_processor, $custom_to_db, $lang)
{

//include("connectdb.php");
//$dataclass = new databasecon;
//$this->run_db();

$reprint =  (object) array();
$this->post_print['form_printer'] = "";
$this->pre_print['call_client_validator'] = "";
$this->pre_print['print_client_validator'] = "";
$this->pre_print['begin_form'] = "";

////Define form callbacks
if(isset($display->insert_callback)){	
$insert_callback = $display->insert_callback;
}
else
{
$insert_callback = false;	
}

if(isset($display->insert_failure)){	
$insert_failure = $display->insert_failure;
}
else
{
$insert_failure = false;	
}		

if(isset($display->update_callback)){	
$update_callback = $display->update_callback;
}
else
{
$update_callback = false;	
}	

if(isset($display->update_failure)){	
$update_failure = $display->update_failure;
}
else
{
$update_failure = false;	
}
////End Define form callbacks



	/////In case $display parameter is not set
if(!isset($display))
	{
	$display = (object) array (0);
	}	

if(!isset($display->form_id))
	{
	$display->form_id = "";
	}	
///// DISPLAY Object//////	
//	if(isset($display)){echo "<pre>";	print_r($display);}	echo "</pre>";
///////END THE DISPLAY//////		
	
//$result = run_query("SHOW COLUMNS FROM $tablename"); /////for mysqli

////setting default for tablename to avoid Fatal error

if ($tablename==""){$tablename="no_table";}

$describe_table =  $this->describe_table($tablename);
//alert($describe_table);
$describe_db_table = $this->run_query("$describe_table");
//Declear array to remove colums from table
$cutarray= array("0");
$cutarray = array_merge($cutarray,$exception);


/////Check Post /////
//'form_method'
$form_idx = $display->form_id;
//$tablename
$sub = "$form_idx";
$allow_sql = 0;
$sql_array = array();
$delete_multirow_sql = "";
$arrayinsert_sql = ""; ///array type inputs
$arrayupdate_sql = ""; ///array type inputs
$method = "blank";

//////////////
if(isset($display->form_method))
{
$form_method = $display->form_method;
if($form_method == 'POST' && isset($_POST["$sub"]))
{
	$method = $_POST["$sub"];
	$method_type = $_POST;
//	alert($form_method);
}
if($form_method == 'GET' && isset($_GET["$sub"]))
{
//	alert($form_method);
	$method = $_GET["$sub"];
	$method_type = $_GET;
}

}



//////////////////////
if ($method != "blank"){

//$this->alert("$method");

	
	$type = $method_type;
	$a=$type;
	$value_name = array_keys($a);
	$posts = $value_name;
	$column_sql_value = "";
	$row_sql_value = ""; 

				
	
	/////foreach for setting values 
	$identifiers = (object) array();
	$arr = array();
	foreach ($posts as $value) 
				{///
				$name = $value;	
				$$value =$type[$name];
				$value = $type[$name];
				$arr[$name] = $value;
			$identifiers->$name = $value;					
				}

//////GET FILE DETAILS HERE TOO
	$ftype = $_FILES;
	$fa=$ftype;
	$fvalue_name = $fa;
	$fposts = $fvalue_name;
	//print_r($posts);		
	foreach ($fposts as $fvaluex=>$fvalue) 
	{	
	$fname = $fvaluex;
	//$$fvalue =$ftype[$fname];
	$fvalue = $ftype[$fname];
	$arr[$fname] = $fvalue;
	$identifiers->$fname = $fvalue;
	}
				
	
	if(isset($display->extra_data))
				{
				$extra_data = $display->extra_data;	
			//	eval($extra_data);	
			foreach($extra_data as $extra_data_variable => $extra_data_value){
				if(!is_callable($extra_data_value)){
					
				$$extra_data_variable  = $extra_data_value;
				$arr[$extra_data_variable] = $extra_data_value;
				echo "<h1>$extra_data_variable</h1>";
				} else{
					
				$extra_data_value = call_user_func($extra_data_value,$arg="");	
				$$extra_data_variable  = $extra_data_value;
				$arr[$extra_data_variable] = $extra_data_value;
					echo "<h1>$extra_data_variable</h1>";
				}
			}			
				}					
							
	//////foreach execution

/////DEVOLOPING	
//	echo "<pre>";
//	$gg = $posts[5];
//	print_r($posts);
//	echo "</pre>";
	$describe_table =  $this->describe_table($tablename);
//alert($describe_table);
$describe_db_tablex = $this->run_query("$describe_table");
//Declear array to remove colums from table
$cutarray= array("0");
$cutarray = array_merge($cutarray,$exception);


$db_field_array = array();
$all_db_field_array = array();
$emtpty_field_array_type = array();
while($rowxx = $this->fetch_assoc($describe_db_tablex))
	{////Check if field is in array that contains data no to return
/////setting identifiers for coulums

/////End setting identifiers for coulums

	$describe_fnamex = $this->describe_fname();
	$describe_typex = $this->describe_type();
	$all_db_field_array[] = $rowxx[$describe_fnamex];
		if (!in_array($rowxx[$describe_fnamex],$cutarray)){
		//	echo $rowxx[$describe_fnamex]. "--- yyy<br />";	
			if (!in_array($rowxx[$describe_fnamex],$posts)){
		//	echo $rowxx[$describe_fnamex]. "<br />";
			echo $rowxx[$describe_fnamex]. "--- xxx<br />";			
 				
			if(isset($display->fields->$rowxx[$describe_fnamex])){	
			$field_type_obj = $display->fields->$rowxx[$describe_fnamex]->type;
					if( ($field_type_obj == "multipleselect" ) || ($field_type_obj == "checkbox" ) || ($field_type_obj == "radio" ) || ($field_type_obj == "select" )){	
					
					if(!in_array($rowxx[$describe_fnamex],$posts)){
						$db_field_array[] = $rowxx[$describe_fnamex];
if(($field_type_obj == "multipleselect" ) || ($field_type_obj == "checkbox" )){
$emtpty_field_array_type[] = $rowxx[$describe_fnamex];
}						
						
					}
					
						}
			}
		}
		}
	//add addtional
	
	
	//$addtional_field,$add_free_field
	
	//newfield, type
	if(isset($addtional_field->fields->$rowxx[$describe_fnamex])){	
	
//	echo "<span style='color:red;'> isset here</span>";
	$add_field_type_obj = $addtional_field->fields->$rowxx[$describe_fnamex]->type;
				if( ($add_field_type_obj == "multipleselect" ) || ($add_field_type_obj == "checkbox" ) || ($add_field_type_obj == "radio" ) || ($add_field_type_obj == "select" )){	
					//to adviod distorted sort
					$add_fieldx = $addtional_field->fields->$rowxx[$describe_fnamex]->newfield;
					if(!in_array($add_fieldx,$posts)){
						$db_field_array[] = $add_fieldx;
						
						if(($add_field_type_obj == "multipleselect" ) || ($add_field_type_obj == "checkbox" )){
							$emtpty_field_array_type[] = $add_fieldx;
							}
							
					}						
				}	
			}
	//End add addtional
	
	
	
	
	}
	
//----//add free_field data
//$add_free_field
//incase not in database it can check add_free_field array 
	foreach($add_free_field as $free_rowxx){
	//	echo "<pre>";	print_r($free_row);	echo "</pre>";
		
//$this->alert($describe_type);	
		//$printaddtionals = "
$newfreefieldxx = $free_rowxx[0];
$newfreefieldtypexx = $free_rowxx[1];

if( ($newfreefieldtypexx == "multipleselect" ) || ($newfreefieldtypexx == "checkbox" ) || ($newfreefieldtypexx == "radio" ) || ($newfreefieldtypexx == "select" )){	
					//to adviod distorted sort
					if(!in_array($newfreefieldxx,$posts)){
						$db_field_array[] = $newfreefieldxx;
						
						if(($newfreefieldtypexx == "multipleselect" ) || ($newfreefieldtypexx == "checkbox" )){
							$emtpty_field_array_type[] = $newfreefieldxx;
							}
						
					}						
						}
						
		}
//----//End add free_field data	
	

///sorting
$field_sort2 = array();
$while_sorted2 = array();
if(isset($sort_array)){
$field_sort2 = $sort_array;
}
foreach($field_sort2 as $skey2)
{
	if (in_array($skey2,$db_field_array))
  {
  $while_sorted2[] = $skey2;
  }	
}

$while_sort_interset2 = array_diff($db_field_array,$while_sorted2);
$sorted_data2 = array_merge($while_sorted2,$while_sort_interset2);

//$sorted_data2 = $while_sorted2 + $db_field_array;
///sorting

$flip_test = array_flip($sorted_data2);	
$flip_post = array_flip($posts);	
$all_forms_array_flip = $flip_test + $flip_post;
$all_forms_array = array_keys($all_forms_array_flip);


$final_sorted = array();
$new_all_forms_array = $all_forms_array;
if(isset($sort_array)){
$field_sort3 = $sort_array;
}
foreach($field_sort3 as $sorting_field)
{
	if(in_array($sorting_field, $new_all_forms_array)){
		$final_sorted[] = $sorting_field;
		$new_all_forms_array = array_diff($new_all_forms_array,$final_sorted);
	}	
}

$last_sort = array_merge($final_sorted, $new_all_forms_array);


//echo "<pre>";*
//	print_r($exception);
//	print_r($db_field_array);
//	print_r($sorted_data2);
//	print_r($flip_test);
//	print_r($flip_post);
//	print_r($all_forms_array);*
//	print_r($last_sort);*
//	print_r($sort_array);*
//var_dump($posts);
//	echo "</pre>";*
	$posts = $last_sort;
/////END DEVOLOPING	


	//////STILL CHECK
				foreach ($posts as $value) 
				{///
				$name = $value;
if(!isset($type[$name])){
	
	if(in_array($name,$emtpty_field_array_type)){	
	$value = array();
	}
	else
	{
	$value = "";	
	}
	}
	else
	{
//echo $type[$name] . "----" . $value  . "<br />";	
				$$value =$type[$name];
				$value = $type[$name];
	}
	
	//////END STILL CHECK
			////////Check if $POSTED ITEM IS AN ARRAY INCASE OF A CHECK BOX
		
$reprint->$name = $value;		

					/////validation execution
					//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn
					//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn
					//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn
					//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn
////// MODIFICATION WITH DEFINED FUNCTION BEFORE VALIDATION
				if(isset($display->fields->$name->set_mod1))
				{
				$modifier_name = $display->fields->$name->set_mod1;
				
				$xvalue = $value;
				if(is_callable($modifier_name)){
				$value = call_user_func($modifier_name,$value,$arr);
				}
				if(!$value){$value = $xvalue;}				
				//	echo "<h1>$value</h1>";
				}
				

////
					
					if(isset($display->server_validate))
					{
						if($display->server_validate == true)
						{
							/////RUN VALIDATORS
				$validate_error = "";	
				if(isset($display->fields->$name->ValidateAsEmail))
				{
					if($display->fields->$name->ValidateAsEmail == true){
					$validate_error = "**not_set";					
				
					if(isset($display->fields->$name->ValidateAsEmailErrorMessage))
				{
					$validate_error = $display->fields->$name->ValidateAsEmailErrorMessage;
				}
								///set as callback	
				$xvalidate_error = $validate_error;
				if(is_callable($validate_error)){
				$validate_error = call_user_func($validate_error,$name,$value,$arr,$lang);
				}
				if(!$validate_error){$validate_error = $xvalidate_error;}	
				///set as callback
					$this->ValidateAsEmail($name,$value,$validate_error);
					}	
				}
				
				if(isset($display->fields->$name->ValidateAsInteger))
				{
					if($display->fields->$name->ValidateAsInteger == true){
					$validate_error = "**not_set";				
								if(isset($display->fields->$name->ValidateAsIntegerErrorMessage))
						{
							$validate_error = $display->fields->$name->ValidateAsIntegerErrorMessage;
						}
										///set as callback	
				$xvalidate_error = $validate_error;
				if(is_callable($validate_error)){
				$validate_error = call_user_func($validate_error,$name,$value,$arr,$lang);
				}
				if(!$validate_error){$validate_error = $xvalidate_error;}	
				///set as callback
					$this->ValidateAsInteger($name,$value,$validate_error);
				}
				}

				if(isset($display->fields->$name->ValidateAsFloat))
				{
					if($display->fields->$name->ValidateAsFloat == true){
					$validate_error = "**not_set";					
					if(isset($display->fields->$name->ValidateAsFloatErrorMessage))
						{
							$validate_error = $display->fields->$name->ValidateAsFloatErrorMessage;
						}
										///set as callback	
				$xvalidate_error = $validate_error;
				if(is_callable($validate_error)){
				$validate_error = call_user_func($validate_error,$name,$value,$arr,$lang);
				}
				if(!$validate_error){$validate_error = $xvalidate_error;}	
				///set as callback
				$this->ValidateAsFloat($name,$value,$validate_error);
				}
				}

								
				if(isset($display->fields->$name->ValidateAsDate))
				{
					if($display->fields->$name->ValidateAsDate == true){					
					$validate_error = "**not_set";				
					if(isset($display->fields->$name->ValidateAsDateErrorMessage))
						{
							$validate_error = $display->fields->$name->ValidateAsDateErrorMessage;
						}
										///set as callback	
				$xvalidate_error = $validate_error;
				if(is_callable($validate_error)){
				$validate_error = call_user_func($validate_error,$name,$value,$arr,$lang);
				}
				if(!$validate_error){$validate_error = $xvalidate_error;}	
				///set as callback
						
						
						$separator = "-";
					if(isset($display->fields->$name->DateSeparator))
						{
							$separator = $display->fields->$name->DateSeparator;
						}
					$this->ValidateAsDate($name,$value,$separator,$validate_error);
					}
				}
					
								
				if(isset($display->fields->$name->CustomValidate))
				{$validate_error = "**not_set";
					$label = $name;
					if(isset($lang->$name))
					{$label = $lang->$name;}
				
					$validation_callback = $display->fields->$name->CustomValidate;
					if(isset($display->fields->$name->CustomValidateErrorMessage))
						{
							$validate_error = $display->fields->$name->CustomValidateErrorMessage;
						}
								///set as callback	
				$xvalidate_error = $validate_error;
				if(is_callable($validate_error)){
				$validate_error = call_user_func($validate_error,$name,$value,$arr,$lang);
				}
				if(!$validate_error){$validate_error = $xvalidate_error;}	
				///set as callback		
				$this->CustomValidation($name,$value,$label,$arr,$validation_callback,$validate_error);
				}
				
							/////END RUN VALIDATORS
						
						}
						
						
						
			
					}
					/////END validation execution
					//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn
					//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn
					//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn
					//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn


			
			if (((!is_array($value))))
			{			
			//	$value = addslashes(mysqli_real_escape_string($con,$value));				
				$value = $this->escape_string($value);				
		//		echo "STRING $name -- $value <br />";
			}
			else 
			{
			//	echo " ARRAY $name --";					
			
				$addarrayvalue = "";	
				if(isset($display->fields->$name->field_separator))
				{
					$field_separator = $display->fields->$name->field_separator;
				}
				else
				{
					$field_separator = "+";
				}
									
					foreach ($value as $arrayvalue) 
				{
					
					$addarrayvalue .= $this->escape_string("$arrayvalue$field_separator");
				}	
					$addarrayvalue = substr($addarrayvalue, 0, -1);
				//	echo  "$addarrayvalue <br />";
			}
			//////END Check if posted
			
		/////data processing area////////

	
				
						
	///if statement below removes submit value from the string		
				/*		if ($name != $sub){							
						$column_sql_value .= "$name,";
						////where to replace the values we wish
						$row_sql_value .= "'$value',";
						}
				*/
////// MODIFICATION WITH DEFINED FUNCTION AFTER VALIDATION
				if(isset($display->fields->$name->set_mod2))
				{
				$modifier_name = $display->fields->$name->set_mod2;
				
				$xvalue = $value;
				if(is_callable($modifier_name)){
				$value = call_user_func($modifier_name,$value,$arr);
				}
				if(!$value){$value = $xvalue;}				
				//	echo "<h1>$value</h1>";
				}
				

////


				
if(isset($field_processor->$name)){
$processor = $field_processor->$name;	
if(isset($field_processor->$name->content)){
	eval($field_processor->$name->content);
}

if(is_string($processor)){
	//echo "<h1>STRING - $name</h1>";
	if(is_callable($processor)){
		$sql_arrayx = $sql_array;
		$sql_array = call_user_func($processor,$name,$value,$arr,$sql_array);
		
	//	$not_contained = 0;  ////to enable proccessor alter sql_array, uncomment the beginning of this line and comment below if statement 
		if(is_array($sql_array)){
			if((count(array_diff_assoc($sql_arrayx,$sql_array))!=0))			
				$not_contained = 1;
			else
				$not_contained = 0;
		}
		
		if((!$sql_array)||(!is_array($sql_array)) || ($not_contained == 1)){
			$sql_array = $sql_arrayx;
			}

	}
}

}
else
{////
if ($name != $sub){	
						
						
						////where to replace the values we wish
			
			////////Check if $value  IS ARRAY INCASE OF A CHECK BOX
			if (((!is_array($value))))
			{		
			//
						if(in_array($name,$all_db_field_array) || isset($display->fields->$name->to_other_tr)){
				if(!isset($display->fields->$name->to_other_tr)){	
					$column_sql_value .= "$name,";
					$row_sql_value .= "'$value',";
								$sql_array["$name"] = $value;
				}
				else{////to other tr for singles
				echo "<h1>$name</h1>";	
				$other_tobj = $display->fields->$name->to_other_tr; ///other table object	
				$othertablename = $other_tobj->tablename;
				$this_data_column = $other_tobj->this_column;
				$other_data_column_array = $other_tobj->other_column;
				$update_set = false;
								if (isset($display->update->set))
				{
				$update_set = $display->update->set;
				}
				
				
			if (isset($display->fields->$name->to_other_tr) && $update_set === true)
			{
				{//otyher tr values
				$other_tr_details = $display->fields->$name->to_other_tr;
				$other_tr_table = $other_tr_details->tablename;
				$other_tr_this_column = $other_tr_details->this_column;
				$other_tr_identifier = $other_tr_details->identifier;
				$other_tr_this_column_oc = $other_tr_details->other_column;
				$other_tr_key = $other_tr_this_column_oc["$other_tr_identifier"];
			//	alert($other_tr_key);
				///get from table
				$update_table_where = $display->update->where;

			//	include("connectdb.php");
			$con = $this->con();
			$this_result = $this->run_query( "SELECT * FROM $tablename WHERE $update_table_where");
				while($obj = $this->fetch_assoc($this_result)){	  
			$typex = $obj;
			$ax=$typex;
			$value_namex = array_keys($ax);
			$postsx = $value_namex;
			foreach ($postsx as $valuex) 
			{	
					$namex = $valuex;	
					$$valuex = "$typex[$namex]";
					$$namex = $obj[$valuex]; 
			}	
			$update_data_pre = $obj[$other_tr_identifier]; 
			//alert($update_data_pre);	  
															}
			//////use to remove values from tables		

			///removing from table

//check this letter reinsertion
			$delete_multirow_sql .= "DELETE FROM $other_tr_table WHERE $other_tr_key = '$update_data_pre';";

				
				
				}
				
			}	
			
			
				foreach ($other_data_column_array as $other_value =>$other_column) 
					{
					echo "***** $other_column >>>>>> $other_value<br />";
				//	$ocolumn_sql_value .= "$other_column,";
					$mod_other_value = "". $$other_value. "";
				//	$orow_sql_value .= "'".$mod_other_value."',"; ////remove $ sign
					///for update
					$update_othertr .= "$other_column =" . "'" .$$other_value . "',";
					
					}
					
			
				$oarrayvalue = $value; 
				//$update_othertr = "";	
				
				///SQL for updation
		//		$this_arrayupdate = "UPDATE $othertablename SET $update_othertr ". "$this_data_column = '$oarrayvalue' " ." WHERE $other_tr_key='$update_data_pre';";
		//		$arrayupdate_sql .= $this_arrayupdate;
				$arrayupdate_sql .= "";
				
				///SQL for insertion		
				$arrayinsert_sql .= "INSERT INTO $othertablename ($ocolumn_sql_value) VALUES ($orow_sql_value"."'$oarrayvalue');";
				
			//	$arrayinsert_sql .= $this_arrayupdate;
				

						
					
			///// Per row execution
						
			echo "<pre>$arrayinsert_sql</pre> <br />";
			echo "<pre>$$arrayupdate_sql</pre> <br />";
			
			
							
			}////END to other tr for singles		xyxy		
					
					}///ends check in db_array
			
			}
			else 
			{////if array
				if(isset($display->fields->$name->to_other_tr))
				{
	
	
$other_tobj = $display->fields->$name->to_other_tr; ///other table object	
$othertablename = $other_tobj->tablename;
$this_data_column = $other_tobj->this_column;
$other_data_column_array = $other_tobj->other_column;

				
				$ocolumn_sql_value = "";
				$orow_sql_value = "";
				$update_othertr = "";

//////call check from db
$update_set = false;
				if (isset($display->update->set))
{
$update_set = $display->update->set;
}


if (isset($display->fields->$name->to_other_tr) && $update_set === true)
{
	{//otyher tr values
	$other_tr_details = $display->fields->$name->to_other_tr;
	$other_tr_table = $other_tr_details->tablename;
	$other_tr_this_column = $other_tr_details->this_column;
	$other_tr_identifier = $other_tr_details->identifier;
	$other_tr_this_column_oc = $other_tr_details->other_column;
	$other_tr_key = $other_tr_this_column_oc["$other_tr_identifier"];
//	alert($other_tr_key);
	///get from table
	$update_table_where = $display->update->where;

//	include("connectdb.php");
$con = $this->con();
$this_result = $this->run_query( "SELECT * FROM $tablename WHERE $update_table_where");
	while($obj = $this->fetch_assoc($this_result)){	  
$typex = $obj;
$ax=$typex;
$value_namex = array_keys($ax);
$postsx = $value_namex;
foreach ($postsx as $valuex) 
{	
		$namex = $valuex;	
		$$valuex = "$typex[$namex]";
		$$namex = $obj[$valuex]; 
}	
$update_data_pre = $obj[$other_tr_identifier]; 
//alert($update_data_pre);	  
												}
//////use to remove values from tables		

///removing from table
									


$delete_multirow_sql .= "DELETE FROM $other_tr_table WHERE $other_tr_key = '$update_data_pre';";

	
	}
	
		}	
//////end call check from db 


				foreach ($other_data_column_array as $other_value =>$other_column) 
					{
					echo "***** $other_column >>>>>> $other_value<br />";
					$ocolumn_sql_value .= "$other_column,";
					$mod_other_value = "". $$other_value. "";
					$orow_sql_value .= "'".$mod_other_value."',"; ////remove $ sign
					///for update
					$update_othertr .= "$other_column =" . "'" .$$other_value . "',";
					
					}
					$ocolumn_sql_value .= "$this_data_column"; ////adding curremt data
					///update
					
					//$update_othertr .= "$this_data_column =" . "" .$$other_value . ""
					//$update_othertr = substr($update_othertr, 0, -1);
					///update 
			
					foreach ($value as $oarrayvalue) 
					{
						//$oarrayvalue = "$oarrayvalue";
				
				///SQL for updation
			//	$this_arrayupdate = "UPDATE $othertablename SET $update_othertr ". "$this_data_column = $oarrayvalue " ." WHERE $other_tr_key='$update_data_pre';";
			//	$arrayupdate_sql .= $this_arrayupdate;
				$arrayupdate_sql .= "";

				
				///SQL for insertion		
				$arrayinsert_sql .= "INSERT INTO $othertablename ($ocolumn_sql_value) VALUES ($orow_sql_value"."'$oarrayvalue');";
				
					}	
					
			///// Per row execution
						
			echo "<pre>$arrayinsert_sql</pre> <br />";
			echo "<pre>$$arrayupdate_sql</pre> <br />";
			
	//////DO not ececute a pack all quesries and run at once			
/*
		if (mysqlo_multiiiiiiii_query($con,"$arrayinsert_sql"))
			{	echo "Multidata capture captured <br />";	}
			else
			{	
				echo "<span style='color:red'>
				An error occurred  >>>
				</span>". mysqloo_error();	
			}
*/			
	///// End Per row execution	
	

				
				}
			else
				{
					$column_sql_value .= "$name,";  //// column for original table
					$addarrayvalue = "";	
if(isset($display->fields->$name->field_separator))
				{
					$field_separator = $display->fields->$name->field_separator;
				}
				else
				{
					$field_separator = "+";
				}
					
					foreach ($value as $arrayvalue) 
					{
						
					//	using seperators
						$addarrayvalue .= "$arrayvalue$field_separator";
					}	
						$addarrayvalue = substr($addarrayvalue, 0, -1);
						$sql_array["$name"] = $addarrayvalue;
						$row_sql_value .= "'$addarrayvalue',";
				}
					
					
					
			}
			//////END Check if $value	
					
						}
}////
	/////end data processing area/////
				
						///					
						
						
						
				}
					
	
////////////// FILEUPLOAD MANAGER /////////
$uploadOk = 1;
$array_of_uploaded_filename = array();  ////Declaration of array to carry list of uploaded file, Delete if SQL fails

	$type = $_FILES;
	$a=$type;
	$value_name = $a;
	$posts = $value_name;
	//print_r($posts);
		
		foreach ($posts as $valuex=>$value) 
		{
		
		$name = $valuex;
		$value = $value["name"];
		
	if(isset($display->fields->$name->file_type))	{
	$file_type = $display->fields->$name->file_type;
}
else{
$file_type ="";	
}

if(isset($field_processor->$name)){
$processor = $field_processor->$name;	
if(isset($field_processor->$name->content)){ ////remove
	eval($field_processor->$name->content);
}

if(is_string($processor)){
	//echo "<h1>STRING - $name</h1>";
	if(is_callable($processor)){
		$sql_arrayx = $sql_array;
		$sql_array = call_user_func($processor,$name,$value,$arr,$sql_array);
		
	//	$not_contained = 0;  ////to enable proccessor alter sql_array, uncomment the beginning of this line and comment below if statement 
		if(is_array($sql_array)){
			if((count(array_diff_assoc($sql_arrayx,$sql_array))!=0))			
				$not_contained = 1;
			else
				$not_contained = 0;
		}
		
		if(is_bool($sql_array)){
			if($sql_array == false){
				$uploadOk = 0;
				echo "<h1 style='color:red;'>file failed</h1>";
				//$display->fields->$name->folder
				$file_error = " ";
				if(isset($display->fields->$name->file_error)){
					$file_error = $display->fields->$name->file_error;
				}
				$this->validation_error["$name"][] = $file_error;
			}
		}
		
		
		if((!$sql_array)||(!is_array($sql_array)) || ($not_contained == 1)){
			$sql_array = $sql_arrayx;
			}

	}
}

}

else
	{
		if(!(is_array($value))) ///check if it is multiple file
		{
			echo "<br />";
		echo $name . " --**** " . $value . "<br />";	

	//	$column_sql_value .= "$name,";
		////where to replace the values we wish
	//	$row_sql_value .= "'$value',"; 
//$selection_dbt = $display->fields->$dfield->from_dbtable;

$reprint->$name = $value;	
	
		if(isset($display->fields->$name->type))
		{



///I file is not an image
$uploadOk = 1;
	
		////set folder
	if(isset($display->fields->$name->folder))
	{
		$target_dir = $display->fields->$name->folder . "/";
	}
	else
	{
		$target_dir ="./";
	}
	////end set folder
		
	
	
	$target_file = $target_dir . basename($_FILES["$name"]["name"]);
	// Check if file already exists
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
if (file_exists($target_file)) {
    

if(isset($display->fields->$name->overwrite)){
if(($display->fields->$name->overwrite == "1")){
echo "Sorry, file already exists.";
$uploadOk = 0;	
}	

}
}
// Check file size

if(isset($display->fields->$name->max_size)){
$max_size = $display->fields->$name->max_size;
}
else {
$max_size = ini_get('post_max_size');
}
	
if ($_FILES["$name"]["size"] > $max_size) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}



///////UPLOAD FILE DIR AFTER QUERY IS SUCCESFULL to in string arrays and eval

	
	
	if ( $uploadOk !=0 && move_uploaded_file($_FILES["$name"]["tmp_name"], $target_file)) {
//	if ( $uploadOk !=0 && copy($_FILES["$name"]["tmp_name"], $target_file)) {
			echo "The file ". basename( $_FILES["$name"]["name"]). " has been uploaded.";
		
		

		if(isset($display->fields->$name->rename_rule)){/////RENAME
		$newname = $display->fields->$name->rename_rule;
		$newnamex = $target_dir . $newname . "." . $imageFileType;
		rename($target_file, $newnamex);
		echo " and RENAMED as $newnamex";
		
		$array_of_uploaded_filename[] = $newnamex;
		
		///rrrrrr
		$newnamedb = $newname . "." . $imageFileType;
	
		////rrrrr
		}//// END RENAME
		else{ ///no renaming
		
			$array_of_uploaded_filename[] = $target_dir . basename( $_FILES["$name"]["name"]);
		$newnamedb = basename( $_FILES["$name"]["name"]);


		}

		///rrrrrr
		if(in_array($name,$all_db_field_array) || isset($display->fields->$name->to_other_tr)){
			if(!isset($display->fields->$name->to_other_tr)){
		$column_sql_value .= "$name,";
		$row_sql_value .= "'$newnamedb',"; 
		$sql_array["$name"] = $newnamedb;
			echo "<h1>$name null</h1>";
			}
				else{////to other tr for singles
				echo "<h1>$name null</h1>";	
				$other_tobj = $display->fields->$name->to_other_tr; ///other table object	
				$othertablename = $other_tobj->tablename;
				$this_data_column = $other_tobj->this_column;
				$other_data_column_array = $other_tobj->other_column;
				$update_set = false;
								if (isset($display->update->set))
				{
				$update_set = $display->update->set;
				}
				
				
			if (isset($display->fields->$name->to_other_tr) && $update_set === true)
			{
				{//otyher tr values
				$other_tr_details = $display->fields->$name->to_other_tr;
				$other_tr_table = $other_tr_details->tablename;
				$other_tr_this_column = $other_tr_details->this_column;
				$other_tr_identifier = $other_tr_details->identifier;
				$other_tr_this_column_oc = $other_tr_details->other_column;
				$other_tr_key = $other_tr_this_column_oc["$other_tr_identifier"];
			//	alert($other_tr_key);
				///get from table
				$update_table_where = $display->update->where;

			//	include("connectdb.php");
			$con = $this->con();
			$this_result = $this->run_query( "SELECT * FROM $tablename WHERE $update_table_where");
				while($obj = $this->fetch_assoc($this_result)){	  
			$typex = $obj;
			$ax=$typex;
			$value_namex = array_keys($ax);
			$postsx = $value_namex;
			foreach ($postsx as $valuex) 
			{	
					$namex = $valuex;	
					$$valuex = "$typex[$namex]";
					$$namex = $obj[$valuex]; 
			}	
			$update_data_pre = $obj[$other_tr_identifier]; 
			//alert($update_data_pre);	  
															}
			//////use to remove values from tables		

			///removing from table

//check this letter reinsertion
//			$delete_multirow_sql .= "DELETE FROM $other_tr_table WHERE $other_tr_key = '$update_data_pre';";

				
				
				}
				
			}	
				foreach ($other_data_column_array as $other_value =>$other_column) 
					{
					echo "***** $other_column >>>>>> $other_value<br />";
					$ocolumn_sql_value .= "$other_column,";
					$mod_other_value = "". $$other_value. "";
					$orow_sql_value .= "'".$mod_other_value."',"; ////remove $ sign
					///for update
					$update_othertr .= "$other_column =" . "'" .$$other_value . "',";
					
					}
			
				$oarrayvalue = $newnamedb; 
				//$update_othertr = "";	
				
				///SQL for updation
			//	$this_arrayupdate = "UPDATE $othertablename SET $update_othertr ". "$this_data_column = '$oarrayvalue' " ." WHERE $other_tr_key='$update_data_pre';";
			//	$arrayupdate_sql .= $this_arrayupdate;
				$arrayupdate_sql .= "";
				
				///SQL for insertion		
				$arrayinsert_sql .= "INSERT INTO $othertablename ($ocolumn_sql_value) VALUES ($orow_sql_value"."'$oarrayvalue');";
				
			//	$arrayinsert_sql .= $this_arrayupdate;
				

						
					
			///// Per row execution
						
			echo "<pre>$arrayinsert_sql</pre> <br />";
			echo "<pre>$$arrayupdate_sql</pre> <br />";
			
			
							
			}////END to other tr for singles		xyxy		
						
		
		
		
		
		}
			
	
		} else {
			echo "Sorry, there was an error uploading your file.";
			
			//phprequired
			if(isset($display->fields->$name->phprequired))
			{
			$uploadOk =0;
			}
		}



		} ///End file type check
//	*/	
		
			
			}		
			
	else{   ///execute multiple file codes here
	
$reprint->$name = "";
////set db column
		if(in_array($name,$all_db_field_array)){
				$column_sql_value .= "$name,";
				$row_sql_value .= "'";
				$newnamedb_files = "";				
		}
////END set db column		

		foreach ($value as $f => $name_files) {
		
  echo "<br /> >>>>>>>>>>$name -- ". $_FILES["$name"]["name"][$f] . " %%%%%%%%<br />";
		
//	$reprint->$name .= $name. ",";		

		if(isset($display->fields->$name->type)){
		
		///I file is not an image
	
		////set folder
	if(isset($display->fields->$name->folder))
	{
		$target_dir = $display->fields->$name->folder . "/";
	}
	else
	{
		$target_dir ="./";
	}
	////end set folder
	
		
	$target_file = $target_dir . basename($_FILES["$name"]["name"][$f]);
	// Check if file already exists
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
if (file_exists($target_file)) {
    

if(isset($display->fields->$name->overwrite)){
if(($display->fields->$name->overwrite == "1")){
echo "Sorry, file already exists.";
$uploadOk = 0;	
}	

}
}
// Check file size

if(isset($display->fields->$name->max_size)){
$max_size = $display->fields->$name->max_size;
}
else {
$max_size = ini_get('post_max_size');
}
	
if ($_FILES["$name"]["size"][$f] > $max_size) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

	
	if ( $uploadOk !=0 && move_uploaded_file($_FILES["$name"]["tmp_name"][$f], $target_file)) {
//	if ( $uploadOk !=0 && copy($_FILES["$name"]["tmp_name"][$f], $target_file)) {
			echo "The file ". basename( $_FILES["$name"]["name"][$f]). " has been uploaded.";
		


		if(isset($display->fields->$name->rename_rule)){/////RENAME
		$newname = $display->fields->$name->rename_rule;
		$files_sn =  $f + 1; ///files serial numbers
		$newnamex = $target_dir . $newname . "_$files_sn" . "." . $imageFileType;
		rename($target_file, $newnamex);
		echo " and RENAMED as $newnamex";
		
		$array_of_uploaded_filename[] = $newnamex;

		///rrrrrr
		$newnamedb = $newname . "_$files_sn" . "." . $imageFileType;   ////name with out folder

		$row_sql_value .= "$newnamedb,"; 
		$newnamedb_files .= $newnamedb . ",";
		////rrrrr
		}//// END RENAME
		else 
		{ ///no renaming
				
		$array_of_uploaded_filename[] = $target_dir . basename( $_FILES["$name"]["name"][$f]);

		///rrrrrr
		$newnamedb = basename( $_FILES["$name"]["name"][$f]);   ////name with out folder

		$row_sql_value .= "$newnamedb,"; 
		$newnamedb_files .= $newnamedb . ",";	
		}//END no renaming
		
		} else {
			echo "Sorry, there was an error uploading your file.";
			
			//phprequired
			if(isset($display->fields->$name->phprequired))
			{
			$uploadOk =0;
			}
		}			
			
		}///End type
		
		
		//$check_empty = $name_files;
		
		}
/*			
			if (!($newnamedb_files == "")){ // allow blank field replace //check for empty files in update
			if(in_array($name,$all_db_field_array)){	
			
			$row_sql_value = substr($row_sql_value, 0, -1);
			$newnamedb_files = substr($newnamedb_files, 0, -1);
			
				$row_sql_value .= "',";
				$sql_array["$name"] = $newnamedb_files;				
			}
			}
			
			*/
	/////newww	
	if (!($newnamedb_files == "")){ // allow blank field replace //check for empty files in update
	
		if(in_array($name,$all_db_field_array) || isset($display->fields->$name->to_other_tr)){

						if(isset($display->fields->$name->to_other_tr))
				{
	
	
$other_tobj = $display->fields->$name->to_other_tr; ///other table object	
$othertablename = $other_tobj->tablename;
$this_data_column = $other_tobj->this_column;
$other_data_column_array = $other_tobj->other_column;

				
				$ocolumn_sql_value = "";
				$orow_sql_value = "";
				$update_othertr = "";

//////call check from db
$update_set = false;
				if (isset($display->update->set))
{
$update_set = $display->update->set;
}


if (isset($display->fields->$name->to_other_tr) && $update_set === true)
{
	{//otyher tr values
	$other_tr_details = $display->fields->$name->to_other_tr;
	$other_tr_table = $other_tr_details->tablename;
	$other_tr_this_column = $other_tr_details->this_column;
	$other_tr_identifier = $other_tr_details->identifier;
	$other_tr_this_column_oc = $other_tr_details->other_column;
	$other_tr_key = $other_tr_this_column_oc["$other_tr_identifier"];
//	alert($other_tr_key);
	///get from table
	$update_table_where = $display->update->where;

//	include("connectdb.php");
$con = $this->con();
$this_result = $this->run_query( "SELECT * FROM $tablename WHERE $update_table_where");
	while($obj = $this->fetch_assoc($this_result)){	  
$typex = $obj;
$ax=$typex;
$value_namex = array_keys($ax);
$postsx = $value_namex;
foreach ($postsx as $valuex) 
{	
		$namex = $valuex;	
		$$valuex = "$typex[$namex]";
		$$namex = $obj[$valuex]; 
}	
$update_data_pre = $obj[$other_tr_identifier]; 
//alert($update_data_pre);	  
												}
//////use to remove values from tables		

///removing from table
									


$delete_multirow_sql .= "DELETE FROM $other_tr_table WHERE $other_tr_key = '$update_data_pre';";

	
	}
	
		}	
//////end call check from db 


				foreach ($other_data_column_array as $other_value =>$other_column) 
					{
					echo "***** $other_column >>>>>> $other_value<br />";
					$ocolumn_sql_value .= "$other_column,";
					$mod_other_value = "". $$other_value. "";
					$orow_sql_value .= "'".$mod_other_value."',"; ////remove $ sign
					///for update
					$update_othertr .= "$other_column =" . "'" .$$other_value . "',";
					
					}
					$ocolumn_sql_value .= "$this_data_column"; ////adding curremt data
					///update
					
					//$update_othertr .= "$this_data_column =" . "" .$$other_value . ""
					//$update_othertr = substr($update_othertr, 0, -1);
					///update 
			
					foreach ($value as $oarrayvalue) 
					{
						//$oarrayvalue = "$oarrayvalue";
				
				///SQL for updation
			//	$this_arrayupdate = "UPDATE $othertablename SET $update_othertr ". "$this_data_column = $oarrayvalue " ." WHERE $other_tr_key='$update_data_pre';";
			//	$arrayupdate_sql .= $this_arrayupdate;
				$arrayupdate_sql .= "";

				
				///SQL for insertion		
				$arrayinsert_sql .= "INSERT INTO $othertablename ($ocolumn_sql_value) VALUES ($orow_sql_value"."'$oarrayvalue');";
				
					}	
					
			///// Per row execution
						
			echo "<pre>$arrayinsert_sql</pre> <br />";
			echo "<pre>$$arrayupdate_sql</pre> <br />";
			
	//////DO not ececute a pack all quesries and run at once			
/*
		if (mysqlo_multiiiiiiii_query($con,"$arrayinsert_sql"))
			{	echo "Multidata capture captured <br />";	}
			else
			{	
				echo "<span style='color:red'>
				An error occurred  >>>
				</span>". mysqloo_error();	
			}
*/			
	///// End Per row execution	
	

				
				}
			else
				{
					$column_sql_value .= "$name,";  //// column for original table
					$addarrayvalue = "";	
if(isset($display->fields->$name->field_separator))
				{
					$field_separator = $display->fields->$name->field_separator;
				}
				else
				{
					$field_separator = "+";
				}
					
					foreach ($value as $arrayvalue) 
					{
						
					//	using seperators
						$addarrayvalue .= "$arrayvalue$field_separator";
					}	
			
			$row_sql_value = substr($row_sql_value, 0, -1);
			$newnamedb_files = substr($newnamedb_files, 0, -1);
			
				$row_sql_value .= "',";
				$sql_array["$name"] = $newnamedb_files;			
						
						
				}
				
//$reprint->$name = $newnamedb_files;		
		
		}
	}		
	
	//	echo "<hr /><pre>";	
	//	print_r($_FILES);
	//	echo "</pre><hr />";
		
		

	$reprint->$name .= implode(",",$value);	/// in view check this
	//	$this->alert("multiple file detected");*
	} ///END execute multiple file codes here
		}
}
////////////// END FILEUPLOAD MANAGER /////////




//	echo "<br />";
	echo "<br />";
///Trims out the comma(",") from the $column_sql_value and 	$row_sql_value
//$custom_to_db

//////////////////////////////////////
/////////////////////////////////////
//print_r($custom_to_db);
////////////////////////////////////
/////////////////////////////////////


/////ADDDING custom TO DB/////
foreach($custom_to_db as $custom_column => $custom_value)
{
	$column_sql_value .= "$custom_column,";
	////where to replace the values we wish
	$row_sql_value .= "'$custom_value',";
	$sql_array["$custom_column"] = $custom_value;
	//echo "$custom_column => $custom_value <br />";
}

/////END ADDDING custom TO DB/////

///check sql
$column_sql_value = "";
$row_sql_value = "";
foreach($sql_array as $sql_column => $sql_row)
{
	$column_sql_value .= "$sql_column,";
	$row_sql_value .= "'$sql_row',";
}
///End check


$column_sql_value = substr($column_sql_value, 0, -1);
$row_sql_value = substr($row_sql_value, 0, -1);

$insert_sql = "";

if(isset($display->file_must))
{	
	if($display->file_must == 0)
	{
		$uploadOk = "1";
	}
	else
	{
		$file_must_error = "All file(s) must be selected";
		if(isset($display->file_must_error)){
			$file_must_error = $display->file_must_error;
		}
		$this->validation_error["file_must"][] = $file_must_error;
		
		
	}

}



		
$allow_sql += $this->allow_sql;

$stop_sql = false;
if(isset($display->stop_sql)){
$stop_sql = $display->stop_sql;
}
/////set if to run the query	stop_sql

if($uploadOk !=0 && $allow_sql ==0 && $stop_sql == false && (empty($this->validation_error))){
	
////writing updating sql query
$set_update = "";
foreach($sql_array as $sql_column => $sql_row)
{
	echo  "<br />". $sql_column. "-----------". "$sql_row" . "";
	$set_update .= "$sql_column = '$sql_row',";
}
$set_update = substr($set_update, 0, -1);
$update_where = "";
if(isset($display->update->where)){
$update_where = $display->update->where;	
}


$update_sql = "UPDATE $tablename SET $set_update WHERE $update_where;";
////End writing updating query
	
	$this->prt("$update_sql");
	
$insert_sql = "INSERT INTO $tablename ($column_sql_value) VALUES ($row_sql_value);";
    $this->prt("$insert_sql");

if(isset($display->update))
{
	if($display->update->set == true)
	{
		$insert_sql = $update_sql;
	}
}






//echo "<pre>$insert_sql</pre> <br />";






		if(isset($arrayinsert_sql))
		{			
		//	$insert_sql = $insert_sql . $arrayinsert_sql;
			$insert_sql = $insert_sql;
			$arrayinsert_sql = $arrayinsert_sql;
		}
		else 
		{
			$arrayinsert_sql = "";
		}
		
		if(isset($delete_multirow_sql))
		{			
		//	$insert_sql = $insert_sql . $arrayinsert_sql;
			$insert_sql = $insert_sql;
			$delete_multirow_sql = $delete_multirow_sql;
		}
		else 
		{
			$delete_multirow_sql = "";
		}
		
echo "<pre>";
print_r($sql_array);
echo "</pre>";
		
		//echo "<h1>(((((((((((((((((((";	
//echo $delete_multirow_sql;
//echo "</h1>";	
	//$delete_multirow_sql     for multipleselect and checkboxes	

$other_tr_sql = $delete_multirow_sql.$arrayinsert_sql;







echo "<b>$insert_sql |||| $other_tr_sql</b>";	
if ($query = $this->multi_query($insert_sql)) ///break apart use loops to return errors in loop
			{	
		if ($query2 = $this->multi_query($other_tr_sql))
		{
		//echo "Data captured";		
		//echo $arrayinsert_sql;
		////callbacks

		if(isset($display->update)){
		if(($display->update->set == true)){	
		if(isset($display->update_callback)){	
		
		//eval($display->update_callback);
		//	$this->update_callback($update_callback);
			$this->update_callback($update_callback);
			$this->update_success =1;
		
		}
		}
		else {
		
		
		if(isset($display->insert_callback)){
			
			$this->insert_callback($insert_callback);
		}
									}
		}
									else {
		
		
		if(isset($display->insert_callback)){
			
			$this->insert_callback($insert_callback);
		}
									}
		////End callbacks	
		}
		
		///check sql
					if(isset($display->update) && ($display->update->set == true))
				{
					$reprint = $reprint;
				}
				else
				{
					$insert_reprint = false;
					if(isset($display->insert_reprint)){
						$insert_reprint = $display->insert_reprint;
					}
					
					if($insert_reprint == true)
					{
						$reprint = $reprint;
					}
					else
					{
						$reprint = (object) array();	
					}	
					///reprint for insert
				}

		
		}
			else
			{
					$sql_error = "";
		///declear erro type
	//	echo "<span style='color:red'>An error occurred check the details you entered >>></span>". mysqli_error($con);	
	//	echo "<span class='sql_error_css' style='color:;'>An error occurred check the details you entered >>></span>";	///sql error custom
		
			//	$array_of_uploaded_filename[] = $target_file;
		
			foreach($array_of_uploaded_filename as $fucount => $ufilename)		////Deletes filedetails is captured in database
			{	
			unlink($ufilename);	
			}
				
					////failures
		if(isset($display->update)){
		if(($display->update->set == true )){
		if(isset($display->update_failure)){			
			$this->update_failure($update_failure);
		}
									}
			else {
		
		
		if(isset($display->insert_failure)){
			
			$this->insert_failure($insert_failure);
		}
									}
	
		}
			else {
		
		if(isset($display->insert_failure)){
			
			$this->insert_failure($insert_failure);
		}
									}
								
		////End failures	
		}


		}
else
{
$sql_error = "";
//$insert_sql = "";	

///NOT OK FOR SQL
///set custome sql error


	
		foreach($array_of_uploaded_filename as $fucount => $ufilename)		////Deletes filedetails is captured in database
		{	
		unlink($ufilename);	
		}

		if($stop_sql == false){
		////failures
		if(isset($display->update)){
		if(($display->update->set == true )){
		if(isset($display->update_failure)){			
		$this->update_failure($update_failure);
		}
									}
			else {
		
		
		if(isset($display->insert_failure)){
			
			$this->insert_failure($insert_failure);
		}
									}
	
		}
			else {
		
		if(isset($display->insert_failure)){
			
			$this->insert_failure($insert_failure);
		}
				}
		}				
						
						
		////End failures	
	
}
	
	

//////set collection of value from valid re quest an proccess further
echo "<pre>";
print_r($arr);
print_r($sql_array);
echo "</pre>";

if(isset($display->form_data)){

	if(is_callable($display->form_data)){
$form_data = $display->form_data;		
		$form_data_func = call_user_func($form_data,$arr,$sql_array);
	}
	
}

/*				
if(isset($field_processor->$name)){
$processor = $field_processor->$name;	
if(isset($field_processor->$name->content)){
	eval($field_processor->$name->content);
}

if(is_string($processor)){
	//echo "<h1>STRING - $name</h1>";
	if(is_callable($processor)){
		$sql_arrayx = $sql_array;
		$sql_array = call_user_func($processor,$name,$value,$arr,$sql_array);
		
	//	$not_contained = 0;  ////to enable proccessor alter sql_array, uncomment the beginning of this line and comment below if statement 
		if(is_array($sql_array)){
			if((count(array_diff_assoc($sql_arrayx,$sql_array))!=0))			
				$not_contained = 1;
			else
				$not_contained = 0;
		}
		
		if((!$sql_array)||(!is_array($sql_array)) || ($not_contained == 1)){
			$sql_array = $sql_arrayx;
			}

	}
}

}
*/	
		
		}
else
{
	///////If post,get method of form IS NOT SET
	
	if($_REQUEST){
//	$this->alert(implode(",",$_REQUEST));
//	$this->alert(print_r($_REQUEST));
	
	$typex = $_REQUEST;
	$ax=$typex;
	$value_namex = array_keys($ax);
	$postsx = $value_namex;
	foreach ($postsx as $valuex) 
	{	
			$namex = $valuex;	
			$$valuex = $typex[$namex];
			$$namex = $typex[$valuex]; 
			$this_name = $namex;
			$this_value = $$namex;

//			$this->alert($this_name); 
//			$this->alert("value--" . $this_value);
			$reprint->$this_name = $this_value;
	}	

////put code to check for update
if(isset($_REQUEST['update_submit_button']) || isset($_REQUEST["active_tabnamex"]) || isset($_REQUEST["auto_complete"])){
//	$this->alert("update is set!!!");
	
	////update stuffs is here
	foreach ($postsx as $valuex) 
	{	
			$namex = $valuex;	
			$$valuex = $typex[$namex];
			$$namex = $typex[$valuex]; 
			$this_name = $namex;
			$this_value = $$namex;
	if(isset($display->fields->$this_name->to_update)){
	$to_update_array = explode(",",$display->fields->$this_name->to_update);	
	foreach($to_update_array as $to_update_key=>$to_update){	
//	$to_update = $display->fields->$this_name->to_update;

$additional_found = false;	
$additional_found_values = array();	
$additional_found_from = false;	
$additional_found_from_where = array();	

	////////	PARSE ADDTIONAL FIELDS
		foreach($addtional_field as $a_field=>$a_data){
			foreach($a_data as $a_fieldfname=>$a_sub_data){
				if(isset($a_sub_data->newfield)){
					if($a_sub_data->newfield == $to_update){
						if(isset($a_sub_data->update_values)){
						//	$this->alert("values set");
							$additional_found = true;	
							$additional_found_values = $a_sub_data->update_values;
						}
						if(isset($a_sub_data->update_from_dbtable)){
						//	$this->alert("values set");
							
							$additional_found = true;	
							$additional_found_from = true;
//							$additional_found_from_where = $a_sub_data->update_from_dbtable->where;
						} 										
					}			
				}				
			}		
		}
	////////	PARSE ADDTIONAL FIELDS		
		if(isset($display->fields->$to_update->update_values) || $additional_found==true){
		if($additional_found==true){
		$update_values = $additional_found_values;	
		}
		else{
		$update_values = $display->fields->$to_update->update_values;
		}
		
//		$this->alert("$to_update -- found");			
		//$this->alert("$this_value");
		//print_r($update_values);		
			if(array_key_exists($this_value,$update_values)){
			
		//	$this->alert("key exists");
if (isset($_REQUEST['update_ajax_request'])){			
$this->update_fields[$to_update] = $update_values[$this_value];	
$this->update_fields_ajax[$to_update] = $to_update;
//update_current_field
$this->update_current_field[$to_update] = $_REQUEST['update_current_field'];			
}
else{
	$this->update_fields[$to_update] = $update_values[$this_value];
}
			
			}
	
		} ////end from update_values

		
	if(isset($display->fields->$to_update->update_from_dbtable) || ($additional_found==true && $additional_found_from == true)){
	//	$this->alert("from table is set");
	if($additional_found_from==true){
	$this->update_fields_where[$to_update] = $this_value;
	$this->update_fields_from[$to_update] = $this_value;
//	$this->alert("from table is set");
	}
	else{	
	$this->update_fields_where[$to_update] = $this_value;
//	$this->alert("not from table is set");
	}
	
	}	



	}
	
	}
	
	}

	if(isset($_REQUEST["auto_complete"])){
		foreach ($postsx as $valuex) 
		{	
				$namex = $valuex;	
				$$valuex = $typex[$namex];
				$$namex = $typex[$valuex]; 
				$this_name = $namex;
				$this_value = $$namex;
		
		
		
		
		
		
		}
		
		
		
		
		
	}
	else
	{
//		$this->alert("auto_complete is not set");
	}	
}


///put code for auto_complete
//	if(isset($_REQUEST['update_submit_button']) && isset($_REQUEST["auto_complete"])){
		
//	}
	
}
	
}
/////Check Post /////

////////Declare form tag/////
//	if ($print_form == true){
		$form_attr = "";
if(isset($display->form_attr))
{
$form_attr = $display->form_attr;	
}



//LIST VALIDATION ERROR
{///set default values error
if(isset($display->form_validate_each_container)){
$form_validate_each_container = 	$display->form_validate_each_container;
}
else{
$form_validate_each_container = "p";
}

if(isset($display->form_validate_all_container)){
$form_validate_all_container = 	$display->form_validate_all_container;
}
else{
$form_validate_all_container = "div";
}

if(isset($display->form_validate_each_class)){
$form_validate_each_class = 	$display->form_validate_each_class;
}
else{
$form_validate_each_class = "";
}

if(isset($display->form_validate_all_class)){
$form_validate_all_class = 	$display->form_validate_all_class;
}
else{
$form_validate_all_class = "";
}

if(isset($display->form_validate_each_style)){
$form_validate_each_style = 	$display->form_validate_each_style;
}
else{
$form_validate_each_style = "";
}


if(isset($display->form_validate_all_style)){
$form_validate_all_style = 	$display->form_validate_all_style;
}
else{
$form_validate_all_style = "";
}


if(isset($display->form_validate_list_class)){
$form_validate_list_class = 	$display->form_validate_list_class;
}
else{
$form_validate_list_class = "";
}


if(isset($display->form_validate_list_style)){
$form_validate_list_style = 	$display->form_validate_list_style;
}
else{
$form_validate_list_style = "";
}

if(isset($display->form_error_label_container_class)){
$form_error_label_container_class = 	$display->form_error_label_container_class;
}
else{
$form_error_label_container_class = "";
}


if(isset($display->form_error_label_container_style)){
$form_error_label_container_style = 	$display->form_error_label_container_style;
}
else{
$form_error_label_container_style = "";
}


if(isset($display->form_error_label_class)){
$form_error_label_class = $display->form_error_label_class;
}
else{
$form_error_label_class = "";
}

if(isset($display->form_error_label_style)){
$form_error_label_style = $display->form_error_label_style;
}
else{
$form_error_label_style = "";
}

if(isset($display->form_error_element_container_class)){
$form_error_element_container_class = $display->form_error_element_container_class;
}
else{
$form_error_element_container_class = "";
}

if(isset($display->form_error_element_container_style)){
$form_error_element_container_style = $display->form_error_element_container_style;
}
else{
$form_error_element_container_style = "";
}

if(isset($display->form_error_element_class)){
$form_error_element_class = $display->form_error_element_class;
}
else{
$form_error_element_class = "";
}

if(isset($display->form_error_element_style)){
$form_error_element_style = $display->form_error_element_style;
}
else{
$form_error_element_style = "";
}

if(isset($display->form_error_separator)){
$form_error_separator = $display->form_error_separator;
}
else{
$form_error_separator = "<hr />";
}

}///END set default values
///check validation error
foreach($this->validation_error as $val_error=>$val_error_msg)
		{
			echo "<ul style=\"$form_validate_list_style\" class=\"$form_validate_list_class\">";
			
				foreach($val_error_msg as $val_error_print)
			{

//to add label value to error message
if(isset($lang->$val_error)) 	////// CHEKCING FOR RENAME LANG
		{ $label_lang = $lang->$val_error;} 
		else { $label_lang = $val_error; } 			
$label_lang = str_replace("@name", $val_error, $label_lang);
$label_lang = str_replace("@u-name", strtoupper($val_error), $label_lang);
$label_lang = str_replace("@l-name", strtolower($val_error), $label_lang);
$label_lang = str_replace("@lf-name", lcfirst($val_error), $label_lang);
$label_lang = str_replace("@uf-name", ucfirst($val_error), $label_lang);
$label_lang = str_replace("@wf-name", ucwords($val_error), $label_lang);
if(substr($label_lang,0,3) == "-u@")
{
$label_lang = str_replace("-u@","", $label_lang);	
$label_lang = str_replace("_"," ", $label_lang);	
}

$this_value = "";
if(isset($reprint->$val_error)){
$this_value = $reprint->$val_error; //input value
}

$val_error_print = str_replace("@name", $val_error, $val_error_print);
$val_error_print = str_replace("@label", $label_lang, $val_error_print);
$val_error_print = str_replace("@value", $this_value, $val_error_print);

				echo "<li><span>$val_error_print</span></li>";
			
			
			}
		echo "</ul>";
			
		}
///check validation error


	
///----//draw type check////////******@@@@@@@@@@@@@@@
/*custom */		$container_open = "";
					$container_close = "";
					$row_begin = "";
					$row_end = "";
					$column_textdisplay_open = "";
					$column_textdisplay_close = "";
					$column_formdisplay_open = "";
/*END custom*/		$column_formdisplay_close = "";

///// tab variables

$tab_start = "";
$tab_end = "";

$tab_menu_area_start = "";
$tab_menu_area_end = "";

$tab_button_start = "";
$tab_button_end = "";

$tab_body_start_element = ""; 
$tab_body_end_element = ""; 

/////end tab variables

////FORM ELEMENT SEPAROTOR		
if(isset($display->separator))
	{
		$separator = $display->separator;

	switch ($separator) {/////replace with suposed
		case "table":
		{ 
		$container_open = "<table  id='$tablename"."_table'  border='1'>";
		$container_close = "</table>";
		$row_begin = "<tr>";
		$row_end = "</tr>";
		$column_textdisplay_open = "<td>";
		$column_textdisplay_close = "</td>";
		$column_formdisplay_open = "<td>";
		$column_formdisplay_close = "</td>";

///for tabs


$tab_start = "<tr><td colspan='2'>";
$tab_end = "</td></tr>";

$tab_menu_area_start = "<table id='tabs'><tr>";
$tab_menu_area_end = "</tr></table>";

$tab_button_start = "<td>";
$tab_button_end = "</td>";

$tab_body_start_element = "table"; 
$tab_body_end_element = "table";		
		
		
		}
			break;
		case "table1c":
		{
		$container_open = "<table  id='$tablename"."_table'  border='1'>";
		$container_close = "</table>";
		$row_begin = "";
		$row_end = "";
		$column_textdisplay_open = "<tr><td>";
		$column_textdisplay_close = "</td></tr>";
		$column_formdisplay_open = "<tr><td>";
		$column_formdisplay_close = "</td></tr>";	



$tab_start = "<tr><td>";
$tab_end = "</td></tr>";

$tab_menu_area_start = "<table id='tabs'><tr>";
$tab_menu_area_end = "</tr></table>";

$tab_button_start = "<td>";
$tab_button_end = "</td>";

$tab_body_start_element = "table"; 
$tab_body_end_element = "table";		
		}
			break;
		case "div":
		{ 
$container_open = "<div  id='$tablename"."_table'>";
$container_close = "</div><div style='clear:both;'></div>";
$row_begin = "<div  class='column'>";
$row_end = "</div>";
$column_textdisplay_open = "<div class='right_column' style='clear:both;float:left;width:50%;'>";
$column_textdisplay_close = "</div>";
$column_formdisplay_open = "<div class='right_column' style='float:left;width:50%;'>";
$column_formdisplay_close = "</div>";




$tab_start = "<div style='width:100%;'>";
$tab_end = "</div>";

$tab_menu_area_start = "<div id='tabs'>";
$tab_menu_area_end = "</div>";

$tab_button_start = "";
$tab_button_end = "";

$tab_body_start_element = "div"; 
$tab_body_end_element = "div";

		}
			break;
		case "custom":
		{ 
		////custom print
if(!empty($this->separator)){		
$container_open	=	$this->separator['container_open'];
$container_close = $this->separator['container_close'];
$row_begin	=	$this->separator['row_begin'];
$row_end	=	$this->separator['row_end'];
$column_textdisplay_open	=	$this->separator['column_textdisplay_open'];
$column_textdisplay_close	=	$this->separator['column_textdisplay_close'];
$column_formdisplay_open	=	$this->separator['column_formdisplay_open'];
$column_formdisplay_close	=	$this->separator['column_formdisplay_close'];
}



if(!empty($this->tab_separator)){

$tab_start = $this->tab_separator['tab_start'];
$tab_end = $this->tab_separator['tab_end'];

$tab_menu_area_start = $this->tab_separator['tab_menu_area_start'];
$tab_menu_area_end = $this->tab_separator['tab_menu_area_end'];

$tab_button_start = $this->tab_separator['tab_button_start'];
$tab_button_end = $this->tab_separator['tab_button_end'];

$tab_body_start_element = $this->tab_separator['tab_body_start_element'];
$tab_body_end_element = $this->tab_separator['tab_body_end_element'];	
}
	
		////custom print
		}
			break;
		case "blank":
		{		
		$container_open = "";
		$container_close = "";
		$row_begin = "";
		$row_end = "";
		$column_textdisplay_open = "";
		$column_textdisplay_close = "";
		$column_formdisplay_open = "";
		$column_formdisplay_close = "";
		
						

		$tab_start = "";
		$tab_end = "";

		$tab_menu_area_start = "";
		$tab_menu_area_end = "";

		$tab_button_start = "";
		$tab_button_end = "";

		$tab_body_start_element = ""; 
		$tab_body_end_element = ""; }
			break;
		default:
		{ 
		
		$container_open = "";
		$container_close = "";
		$row_begin = "";
		$row_end = "";
		$column_textdisplay_open = "";
		$column_textdisplay_close = "";
		$column_formdisplay_open = "";
		$column_formdisplay_close = "";
		
						

		$tab_start = "";
		$tab_end = "";

		$tab_menu_area_start = "";
		$tab_menu_area_end = "";

		$tab_button_start = "";
		$tab_button_end = "";

		$tab_body_start_element = ""; 
		$tab_body_end_element = ""; 
		
		
		}
		
		
		
		///unlink files when query fails .................. set cases if isset FILES[]
						} ////end case

}
		

		$this->separator['container_open'] = $container_open;
		$this->separator['container_close'] = $container_close;
		$this->separator['row_begin'] = $row_begin;
		$this->separator['row_end'] = $row_end;
		$this->separator['column_textdisplay_open'] = $column_textdisplay_open;
		$this->separator['column_textdisplay_close'] = $column_textdisplay_close;
		$this->separator['column_formdisplay_open'] = $column_formdisplay_open;
		$this->separator['column_formdisplay_close'] = $column_formdisplay_close;








$this->tab_separator['$tab_start']	=	$tab_start;
$this->tab_separator['tab_end']	=	$tab_end;

$this->tab_separator['tab_menu_area_start']	=	$tab_menu_area_start;
$this->tab_separator['tab_menu_area_end']	=	$tab_menu_area_end;

$this->tab_separator['tab_button_start']	=	$tab_button_start;
$this->tab_separator['tab_button_end']	=	$tab_button_end;

$this->tab_separator['tab_body_start_element']	=	$tab_body_start_element;
$this->tab_separator['tab_body_end_element']	=	$tab_body_end_element;			
////END FORM ELEMENT SEPAROTOR



////////////FORM OPENNING ELEMENT
$form_method = "GET";
if(isset($display->form_method))
{
$form_method = "GET";
if($display->form_method != "")
{	
$form_method = $display->form_method;	
}

}

$form_id = "$tablename";
if(isset($display->form_id))
{
$form_id = $display->form_id;
}

$form_action = "";
if(isset($display->form_action))
{
$form_action = $display->form_action;
}


$this->pre_print['call_client_validator'] .= "<script>
function validateForm_$form_id(this_form){\n
prevent_submit = 0;\n 
var validation = [];
";
//$this->pre_print['print_client_validator'] .= "<script>";

//$this->pre_print['begin_form'] .= "<form method='$form_method' onsubmit='return validateForm_$form_id(this);' action='$form_action'  id='$form_id' enctype='multipart/form-data' $form_attr>";  ///WITH INLINE EVENT SUBMISSION

$this->pre_print['begin_form'] .= "<form method='$form_method' action='$form_action'  id='$form_id' enctype='multipart/form-data' $form_attr>";

$this->pre_print['begin_form'] .= "
<script>
document.getElementById(\"$form_id\").addEventListener(\"submit\", function (event) {
var allow_submit = validateForm_$form_id(this);
if(allow_submit==false)
{event.preventDefault();}
else
{}	
}, false);
</script>
";



	//}



$this->pre_print['begin_form'] .= $container_open;
///----//end draw type check//////*****@@@@@@@@@@@@@	


////[[[[[[[[[[[[[[[]]]]]]]]]]]]]]]
////[[[[[[[[[[[[MAIN PROCCESSOR DISPLAY[[[]]]]]]]]]]]]]]]
////[[[[[[[[[[[[[[[]]]]]]]]]]]]]]]
$input_type = ""; 
$print_lang = "";
$dfield = ""; 
$row = "";

$validation_error =  $this->validation_error;

/////////[[[[[[[[[[[[[[[[[]]]]]]]]]]]]]]]]] 
/////////[[[[[[[[[[[[[[[[[]]]]]]]]]]]]]]]]] 
/////////[[[[[[[[[[[[[[[[[]]]]]]]]]]]]]]]]]\


//@@@@@@@@@@@@@@---- While looping-----@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
///---------------------------------------------
////-----------------------------------------
////-----------------------------------------
////----------|------------------------------
////----------|---/\-------------------------
////----------|-/----\-|----------------------
////----------/-------\|----------------------
////-----------------------------------------
////-----------------------------------------
////-----------------------------------------
////-----------------------------------------///---------------------------------------------
////-----------------------------------------
////-----------------------------------------
////----------|--------|---------------------
////----------|---/\---|---------------------
////----------|-/----\-|----------------------
////----------/-------\|----------------------
////-----------------------------------------
////-----------------------------------------
////-----------------------------------------
////-----------------------------------------

////////printing adddtional rowa
//eval()
/*
$printaddtionals = "
\$newfield = \$addtional_field->fields->\$dfield->newfield;
\$newfieldtype = \$addtional_field->fields->\$dfield->type;
\$this->field_data[\"\$dfield\"] .=  \$row_begin;
fortypes(\$con,\$addtional_field,\$lang,\$addtional_field,\$input_type,\$column_textdisplay_open,
\$print_lang,\$column_textdisplay_close,
 \$column_formdisplay_open,\$column_formdisplay_close,\$newfield,\$newfieldtype, \$row, \$reprint, \$updatedata);	
 echo \$row_end;
";

*/

$updatedata = (object) array ();
$updatedata->setidentifier = (object) array ();

if(isset($display->update->set))
{
	if($display->update->set == true)
{ 

//alert('4899');
$update_array = array();
$sel_update_where = $display->update->where;
	$update_select_sql="SELECT * FROM $tablename WHERE $sel_update_where";
$obj ="";
		if ($result_update=$this->run_query($update_select_sql))
		{			
	  while ($obj=$this->fetch_assoc($result_update))
		{
			$update_array[] = $obj; //buffered update_data
		}
		
	foreach($update_array as $obj_key=>$obj)	
		{
			 //variable  mysql_real_escape_string(
 $type = $obj;
$a=$type;
$value_name = array_keys($a);
$posts = $value_name;
foreach ($posts as $value) 
{	
		$name = $value;	
		$$value = "$type[$name]";
		$$name = $obj[$value]; 
		$update_data = $obj[$value]; 
//echo "$$name = $row[$value]; <br />"; //database selection
$updatedata->$name = $update_data;
$updatedata->setidentifier->$name = $update_data;
//$updatedata->$name = $update_data;
		}
			
//$updatedata->$name;			
			
		
		}

//// Free result set
  

  
	}
	else
	{	
//alert("$sel_update_where");	


}

	
	
}

} ////END UPDATE


$while_sorted = array();
$buffer_db = array();
if ($describe_db_table)
{
while($row = $this->fetch_assoc($describe_db_table))
	{////Check if field is in array that contains data no to return
/////setting identifiers for coulums
$buffer_row_bd[] = $row; ///buffered database rows
/////End setting identifiers for coulums
	}
//	$buffer_bd = array_merge($buffer_bd,$add_free_field);
	foreach($buffer_row_bd as $row_key=>$row)
	{
$describe_fname = $this->describe_fname();
$describe_type = $this->describe_type();
	if (!in_array($row[$describe_fname],$cutarray)){
	//if (!in_array($row['Field'],$cutarray)){//for_mysqli

$dfield = $row[$describe_fname];
//$dfield = $row['Field'];//for_mysqli
$dfieldx = $row[$describe_fname];
//$dfieldx = $row['Field'];//for_mysqli
$dtype = $row[$describe_type];
//$dtype = $row['Type'];//for_mysqli
	 /*PRINT FORM   */   
	// if($print_form == true){     
	 ///check for addtionals
//$this->field_data["$dfield"] = "";	 	 
//$this->field_data["$dfield"] .=$row_begin;	 
	
	 /////////BEFORE ROW//////////
if(isset($addtional_field->fields->$dfield)){
	if (isset($addtional_field->fields->$dfield->position)){
	if ($addtional_field->fields->$dfield->position == "before"){		
//eval($printaddtionals); 

//$printaddtionals = "
$newfield = $addtional_field->fields->$dfield->newfield;
$newfieldtype = $addtional_field->fields->$dfield->type;
//alert($dfield);
//$this->field_data["$newfield"] = "";	 
//$this->field_data["$newfield"] .= $row_begin;
$this->fortypes($con,$addtional_field,$lang,$addtional_field,$add_free_field,$input_type,$column_textdisplay_open,
$print_lang,$column_textdisplay_close,
 $column_formdisplay_open,$column_formdisplay_close,$newfield,$newfieldtype, $row, $reprint, $updatedata, $validation_error);	
//$this->field_data["$newfield"] .= $row_end;
//";
	}
}
}
	 ///////// END BEFORE ROW//////////

///check for additionals
//$this->field_data["$dfield"] = "";	
//$this->field_data["$dfield"] .= $row_begin;
if(isset($display->fields->$dfield)){
//$newfield = $addtional_field->fields->$dfield->newfield;
if(isset($display->fields->$dfield->type))
{$newfieldtype = $display->fields->$dfield->type;}
else
{$newfieldtype = "text";}


}	

else {
	$newfieldtype = "text";
} 

	/////declared fprtypes
	{//////Print rows fro function
	$con = $this->con();
	$this->fortypes($con, $display,$lang,$addtional_field,$add_free_field,$input_type,$column_textdisplay_open,
	$print_lang,$column_textdisplay_close, $column_formdisplay_open,
	$column_formdisplay_close,$dfield, $newfieldtype, $row, $reprint, $updatedata, $validation_error);
							}  /////end of allow print form
$describe_fname = $this->describe_fname();
$newfields[] = $row[$describe_fname];
//$this->field_data["$dfield"] .= $row_end;	

	// }

	 
	 /////////AFTER ROW//////////
if(isset($addtional_field->fields->$dfield)){
	if (!isset($addtional_field->fields->$dfield->position)){
			
//eval($printaddtionals); 
	
$newfield = $addtional_field->fields->$dfield->newfield;
$newfieldtype = $addtional_field->fields->$dfield->type;
//alert($dfield);
//$this->field_data["$newfield"] = "";	 
//$this->field_data["$newfield"] .= $row_begin;
$this->fortypes($con,$addtional_field,$lang,$addtional_field,$add_free_field,$input_type,$column_textdisplay_open,
$print_lang,$column_textdisplay_close,
 $column_formdisplay_open,$column_formdisplay_close,$newfield,$newfieldtype, $row, $reprint, $updatedata, $validation_error);	
//$this->field_data["$newfield"] .= $row_end;
}
else 
{
	if (($addtional_field->fields->$dfield->position == "after") || 
	($addtional_field->fields->$dfield->position != "before")){
			
//eval($printaddtionals); 
 $newfield = $addtional_field->fields->$dfield->newfield;
$newfieldtype = $addtional_field->fields->$dfield->type;
//alert($dfield);
//$this->field_data["$newfield"] = "";	 
//$this->field_data["$newfield"] .= $row_begin;
$this->fortypes($con,$addtional_field,$lang,$addtional_field,$add_free_field,$input_type,$column_textdisplay_open,
$print_lang,$column_textdisplay_close,
 $column_formdisplay_open,$column_formdisplay_close,$newfield,$newfieldtype, $row, $reprint, $updatedata, $validation_error);	
//$this->field_data["$newfield"] .= $row_end;
	}

}
}

	 ///////// END AFTERS ROW//////////

	}		
	}		}
	
	else
	{
		//////Under dev
	//	echo mysqli_error($con); sql error here if allowed
		
	}
//@@@@@@@@@@@@@@---- END While looping-----@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
///---------------------------------------------
////-----------------------------------------
////-----------------------------------------
////----------|------------------------------
////----------|---/\-------------------------
////----------|-/----\-|----------------------
////----------/-------\|----------------------
////-----------------------------------------
////-----------------------------------------
////-----------------------------------------
////-----------------------------------------
	//////ADD FREE FIELDS
$describe_fname = $this->describe_fname();
$describe_type = $this->describe_type();

//$this->alert($describe_fname);
/*
$dfieldx = $row[$describe_fname];
//$dfieldx = $row['Field'];//for_mysqli
$dtype = $row[$describe_type];
*/
$row = array();

if(isset($add_free_field)) /////under development
{
	
	//echo "<pre>";	print_r($add_free_field);	echo "</pre>";
	foreach($add_free_field as $free_row){
	//	echo "<pre>";	print_r($free_row);	echo "</pre>";
		
//$this->alert($describe_type);	
		//$printaddtionals = "
$newfreefield = $free_row[0];
$newfreefieldtype = $free_row[1];
//$this->alert($newfreefield);	
$row[$describe_fname] = $newfreefield; ///find callname
$row[$describe_type] = $newfreefieldtype;
//alert($dfield);
//$this->field_data["$newfreefield"] = "";	 
//$this->field_data["$newfreefield"] .= $row_begin;
$con = $this->con();
$this->fortypes($con,$display,$lang,$addtional_field,$add_free_field,$input_type,$column_textdisplay_open,
$print_lang,$column_textdisplay_close,
 $column_formdisplay_open,$column_formdisplay_close,$newfreefield,$newfreefieldtype, $row, $reprint, $updatedata, $validation_error);	
//$this->field_data["$newfreefield"] .= $row_end;
//";
		
	}

	
}

//////END ADD FREE FIELDS
	

	////End of While
	
	


///// submission message
$submit_message = "";
if(isset($display->submit_message)){
$submit_message = $display->submit_message;	
}


/////End submission message


	///////Close form tag/////

$submit_attr = "";
if(isset($display->submit_attr))
{
$submit_attr = $display->submit_attr;	
}

$submit_button = "SUBMIT";
	if(isset($display->submit_button))
	{$submit_button = $display->submit_button;}

$submit_element = "<input type='submit' value='$submit_button' name='$form_id' id='$form_id' $submit_attr />";

if(isset($display->submit_wrapper))
{
$submit_wrapper = $display->submit_wrapper;

$submit_wrapper = str_replace("%submit_message%",$submit_message,$submit_wrapper);
$submit_wrapper = str_replace("%submit_element%",$submit_element,$submit_wrapper);

$this->post_print['form_printer'] .= $submit_wrapper;	
}
	else
{
$this->post_print['form_printer'] .= $row_begin;

$this->post_print['form_printer'] .= $column_textdisplay_open;

$this->post_print['form_printer'] .= $submit_message;

$this->post_print['form_printer'] .= $column_formdisplay_close;
$this->post_print['form_printer'] .= $column_formdisplay_open;

$this->post_print['form_printer'] .= $submit_element;	

$this->post_print['form_printer'] .= $column_formdisplay_close;
$this->post_print['form_printer'] .= $row_end;
}


$this->pre_print['call_client_validator'] .= "
for(f in validation){
//alert(validation[f][0]);	
validation_type = 	validation[f][0];
var get_input_value;
switch (validation_type) {//start witch
";	

$this->pre_print['call_client_validator'] .= $this->print_client_validator;

$this->pre_print['call_client_validator'] .= "	
}//end switch

}
//return false;
if(prevent_submit > 0){
	return false;
}
else{
	return true;
}
 }</script>";
//$this->pre_print['print_client_validator'] .= "</script>";

	
		$this->post_print['form_printer'] .= $container_close;
$this->post_print['form_printer'] .="</form>";	
//$newfields = $newfields;	


//>>>>>>>>>>>>>>>>>>>	Final array <<<<<<<<<<<//	/////#note = echo the following


/// can remove {///     THIS IS THE BUILD CUSTOM CODE FOR GENERATION FORM MANUAL




//////SPLIT to different files




///}end can remove ///


///>>>>>>>>>>>>>>>>> End Final array <<<<<<<<<<<<//

//	print_r($newarray['Feild']);

//////RENDERER
$field_sort = array();
if(isset($sort_array)){
$field_sort = $sort_array;
}
foreach($field_sort as $skey)
{
	if (array_key_exists($skey,$this->field_data))
  {
  $while_sorted["$skey"] = $this->field_data["$skey"];
  }	
}
$field_sort_flip = array_flip($field_sort);
$while_sort_interset = array_diff_key($this->field_data,$field_sort_flip);
$sorted_data = array_merge($while_sorted,$while_sort_interset);
////DEVELOP RENDERER
$this->renderer = array();
//'tabs' => array("PERSONAL"=>'faculty_day_added,faculty_year_added,faculty_campus',"OFFICE"=>'Faculty_text,faculty_files,location'),
/*
$custom_content = "
		
		\$container_open = \"<fieldset style='border:4px black double;'><legend>Testing the form</legend>\";
		\$container_close = \"</fieldset>\";
		\$row_begin = \"\";
		\$row_end = \"\";
		\$column_textdisplay_open = \"\";
		\$column_textdisplay_close = \": \";
		\$column_formdisplay_open = \"\";
		\$column_formdisplay_close = \"<hr width='50%' />\";
				
										";
										*/


//$this->post_print['form_printer']
/*
		$this->separator[$container_open] = $container_open;
		$this->separator[$container_close] = $container_close;
		$this->separator[$row_begin] = $row_begin;
		$this->separator[$row_end] = $row_end;
		$this->separator[$column_textdisplay_open] = $column_textdisplay_open;
		$this->separator[$column_textdisplay_close] = $column_textdisplay_close;
		$this->separator[$column_formdisplay_open] = $column_formdisplay_open;
		$this->separator[$column_formdisplay_close] = $column_formdisplay_close;

*/




$tab_start = $this->tab_separator['$tab_start'];
$tab_end = $this->tab_separator['tab_end'];

$tab_menu_area_start = $this->tab_separator['tab_menu_area_start'];
$tab_menu_area_end = $this->tab_separator['tab_menu_area_end'];

$tab_button_start = $this->tab_separator['tab_button_start'];
$tab_button_end = $this->tab_separator['tab_button_end'];

$tab_body_start_element = $this->tab_separator['tab_body_start_element'];
$tab_body_end_element = $this->tab_separator['tab_body_end_element'];	


echo "<pre>";
//print_r($this->separator);
echo "</pre>";

$tabed_array = array();
$tabed_button = array();
$tabed_style = array();
$excute_current_tab = "";
$tabed_style[] = "<script>";
$tabed_style_key = 0;
$error_tab = "";
$error_active_tab = "";
$tab_layout_current = "";
$request_tab = "";
//active_tab_namex
if(isset($_REQUEST["active_tabnamex"])){
//	if($REQUEST['active_tabnamex'] != ""){
		$request_tab = $_REQUEST['active_tabnamex'];
//$this->alert("active tab xxx");		
//	}	
}	
//$this->alert($request_tab);

if(isset($display->tabs)){
		$tabs = $display->tabs;
		$tabed_array[] = $tab_start;
		$tabed_array[] = $tab_menu_area_start;		
$tab_count_button = 0;
$error_key = key($this->validation_error);

//$this->alert($error_key);
	
foreach($tabs as $tabname_index=>$tabarray_index){
	//$this->alert($tabname_index);
	$element_array = explode(",",$tabarray_index);
	foreach($element_array as $element_array_key => $element_array_value){
		if($element_array_value == $error_key){
		$error_tab = $tabname_index;	
		}	
	}
}

//$this->alert($error_tab);
$tabscript = "";
$tabname_botton = "";
$active_tab_button = 	"$tab_button_start<div onclick=\"$tabscript document.getElementById('active_tabnamex').value='$tabname_botton';return false;\" id=\"tab_button_id_$tabname_botton\" class='active_tab'><input type='submit' name='$tabname_botton"."_tab_button' value='$tabname_botton' /></div>$tab_button_end";
$inactive_tab_button = 	"$tab_button_start<div onclick=\"$tabscript document.getElementById('active_tabnamex').value='$tabname_botton';return false;\" id=\"tab_button_id_$tabname_botton\" class='inactive_tab'><input type='submit' name='$tabname_botton"."_tab_button' value='$tabname_botton' /></div>$tab_button_end";

foreach($tabs as $tabname_botton=>$tabarray_botton){
	
	///script writer
	$tabscript = "";
	$tabscript .= "document.getElementById('tab_$tabname_botton').style='display:;';";
	$tabscript .= "document.getElementById('tab_$tabname_botton').style.display='';";
	$tabscript .= "this.className='active_tab';";
	foreach($tabs as $tabname_each=>$tabarray_each){
		if($tabname_each!=$tabname_botton){
			$tabscript .= "document.getElementById('tab_$tabname_each').style='display:none;';";
			$tabscript .= "document.getElementById('tab_$tabname_each').style.display='none';";
			$tabscript .= "document.getElementById('tab_button_id_$tabname_each').className='inactive_tab';";
			if($tabed_style_key ==0){
				$tabed_style[] = "document.getElementById('tab_$tabname_each').style='display:none;';";  ///key to set default display of tabs
				$tabed_style[] = "document.getElementById('tab_$tabname_each').style.display='none';";
				$tabed_style[] = "document.getElementById('tab_button_id_$tabname_each').className='inactive_tab';";				
				$tabed_style[] = "document.getElementById('tab_button_id_$tabname_botton').className='active_tab';";				
			}			
		}
	}
$tabed_style_key =1;	
	///End script writer
	$tab_value = $tabname_botton . "_tab_button";
	$tab_id = "tab_button_id_" . $tabname_botton;		
if(!$_REQUEST){
$tab_count_button += 1;	
	if($tab_count_button ==1){
	$tabed_array[] = "$tab_button_start<div onclick=\"$tabscript document.getElementById('active_tabnamex').value='$tabname_botton';return false;\" id=\"tab_button_id_$tabname_botton\" class='active_tab'><input type='submit' name='$tabname_botton"."_tab_button' value='$tabname_botton' /></div>$tab_button_end";	
	//	$this->alert("$tabname_botton");
	$excute_current_tab .= "<script>$tabscript</script>";
	$excute_current_tab .= "<script>document.getElementById('tab_button_id_$tabname_botton').className='active_tab';</script>";
		
		}
	else
	{		
	$tabed_array[] = "$tab_button_start<div onclick=\"$tabscript document.getElementById('active_tabnamex').value='$tabname_botton';return false;\" id=\"tab_button_id_$tabname_botton\" class='inactive_tab'><input type='submit' name='$tabname_botton"."_tab_button' value='$tabname_botton' /></div>$tab_button_end";		
	}
}
else{
	if(isset($_REQUEST["$tab_value"])){
	$tabed_array[] = "$tab_button_start<div onclick=\"$tabscript document.getElementById('active_tabnamex').value='$tabname_botton';return false;\" id=\"tab_button_id_$tabname_botton\" class='active_tab'><input type='submit' name='$tabname_botton"."_tab_button' value='$tabname_botton' /></div>$tab_button_end";	
	//	$this->alert("$tabname_botton");
	$excute_current_tab .= "<script>$tabscript</script>";
	$excute_current_tab .= "<script>document.getElementById('tab_button_id_$tabname_botton').className='active_tab';</script>";
		
		}
	else
	{
///but if method of post is set here    ///check for current tab info

	if(!empty($this->validation_error)){
			if($error_tab == $tabname_botton){
		$tabed_array[] = "$tab_button_start<div onclick=\"$tabscript document.getElementById('active_tabnamex').value='$tabname_botton';return false;\" id=\"tab_button_id_$tabname_botton\" class='active_tab'><input type='submit' name='$tabname_botton"."_tab_button' value='$tabname_botton' /></div>$tab_button_end";				
		$error_active_tab .= "<script>document.getElementById('tab_button_id_$tabname_botton').className='active_tab';</script>";
			}
			else{
		$tabed_array[] = "$tab_button_start<div onclick=\"$tabscript document.getElementById('active_tabnamex').value='$tabname_botton';return false;\" id=\"tab_button_id_$tabname_botton\" class='inactive_tab'><input type='submit' name='$tabname_botton"."_tab_button' value='$tabname_botton' /></div>$tab_button_end";		
		$error_active_tab .= "<script>document.getElementById('tab_button_id_$tabname_botton').className='inactive_tab';</script>";	
			}
			
		}
		else{
		///code for current tab	//taberror
				if(($request_tab == $tabname_botton) && (  (isset($_REQUEST["$tabname_botton"."_tab_button"]) || isset($_REQUEST[$display->form_id])  || isset($_REQUEST['update_submit_button'])))){
		$tabed_array[] = "$tab_button_start<div onclick=\"$tabscript document.getElementById('active_tabnamex').value='$tabname_botton';return false;\" id=\"tab_button_id_$tabname_botton\" class='active_tab'><input type='submit' name='$tabname_botton"."_tab_button' value='$tabname_botton' /></div>$tab_button_end";				
		$error_active_tab .= "<script>document.getElementById('tab_button_id_$tabname_botton').className='active_tab';</script>";			
				}
		else
		{
		$tabed_array[] = "$tab_button_start<div onclick=\"$tabscript document.getElementById('active_tabnamex').value='$tabname_botton';return false;\" id=\"tab_button_id_$tabname_botton\" class='inactive_tab'><input type='submit' name='$tabname_botton"."_tab_button' value='$tabname_botton' /></div>$tab_button_end";		
		$error_active_tab .= "<script>document.getElementById('tab_button_id_$tabname_botton').className='inactive_tab';</script>";	
		}
		}
		
	}	

}	
					}
$tabed_array[] = $tab_menu_area_end;	
$tab_count = 0;				
		foreach($tabs as $tabname=>$tabarray){
$tab_value = $tabname . "_tab_button";			
if(!$_REQUEST){	
$tab_count += 1;
	if($tab_count == 1){
			$tabed_array[] = "<$tab_body_start_element class='each_tab' id='tab_$tabname' width='100%'>";
			$tab_layout_current = 	$tabname;
	}
	else
	{
			$tabed_array[] = "<$tab_body_end_element class='each_tab' style='display:none;' id='tab_$tabname' width='100%'>";		
	}
}
else
{
	if(isset($_REQUEST["$tab_value"]) || isset($_REQUEST['update_ajax_request'])){
			$tabed_array[] = "<$tab_body_start_element class='each_tab' id='tab_$tabname' width='100%'>";
			$tab_layout_current = 	$tabname;	
	}
	else
	{
		if(!empty($this->validation_error)){	
		if($error_tab == $tabname){
//		$this->alert($tabname);	
			$tabed_array[] = "<$tab_body_start_element class='each_tab' id='tab_$tabname' width='100%'>";
			$tab_layout_current = 	$tabname;
			$error_active_tab .= "<script>document.getElementById('tab_$tabname').style.display='';</script>";
		}
		else{
			$tabed_array[] = "<$tab_body_end_element class='each_tab' style='display:none;' id='tab_$tabname' width='100%'>";		
			}
	}
	else{
		///code for current tab
		if(($request_tab == $tabname) && (isset($_REQUEST["$tab_value"]) || isset($_REQUEST[$display->form_id]) || isset($_REQUEST['update_submit_button']))){
			$tabed_array[] = "<$tab_body_start_element class='each_tab' id='tab_$tabname' width='100%'>";
			$tab_layout_current = 	$tabname;
			$error_active_tab .= "<script>document.getElementById('tab_$tabname').style.display='';</script>";
			
		}
		else{
			$tabed_array[] = "<$tab_body_end_element class='each_tab' style='display:none;' id='tab_$tabname' width='100%'>";
			$error_active_tab .= "<script>document.getElementById('tab_$tabname').style.display='none';</script>";				
		}
	}
	}

	
}	
			
			$tabfields= explode(",",$tabarray);
			foreach($tabfields as $fieldindex=>$fieldname){
				if(array_key_exists($fieldname,$sorted_data)){
					//$this->alert("$tabname -- $fieldname");
					if(!in_array($sorted_data[$fieldname],$tabed_array)){
					$tabed_array[]  = $sorted_data[$fieldname];   ///may refine renderer
					}
				}
			}
			
			
			$tabed_array[] = "</$tab_body_end_element>";
			
			
		}
		$tabed_array[] = $tab_end; //////where tabbing end

$current_tab = "<input type='text' name='active_tabnamex'  id='active_tabnamex' style='display:none;' value='$tab_layout_current' />";		
$tabed_style[] = "</script>$excute_current_tab $error_active_tab $current_tab";
		
	$this->renderer = array_merge($this->pre_print,$tabed_button,$tabed_array,$tabed_style,$this->post_print);

}

else
{
	$this->renderer = array_merge($this->pre_print,$sorted_data,$this->post_print);
}	


////END DEVELOP RENDERER

//$this->renderer = array_merge($this->pre_print,$sorted_data,$this->post_print);


////END RENDERER
}

function fortypes ($con, $display,$lang,$addtional_field,$add_free_field,$input_type,
$column_textdisplay_open,$print_lang,$column_textdisplay_close, 
$column_formdisplay_open,$column_formdisplay_close,$dfield,$newfieldtype, $row, $reprint, $updatedata, $validation_error)
{//////fortypes
$describe_fname = $this->describe_fname();

//////test external function

//testalert('all is fine');


$dfieldx = $row[$describe_fname]; ///debug ///find callname

$reprint_value = ""; ////default


//$dfieldx = $dfield;
	if(isset($lang->$dfield)) 	////// CHEKCING FOR RENAME LANG
		{ $print_lang = $lang->$dfield;} 
		else { $print_lang = $dfield; } 						////// END CHEKCING FOR RENAME LANG
	/////before	addtional_field /////
	//if (isset($display->fields)	
	/////end before	addtional_field /////



		if (isset($display->fields) && isset($display->fields->$dfieldx->type)) { ///Check if fields display is set
			//$dc = $display->fields;
			//$dct = $dc->$dfield->type;
			//$input_type = $dct;
			$input_type = $newfieldtype;
		} else { if(isset($display->fields) && isset($display->fields->$dfield->type))
			{
				$input_type =  $display->fields->$dfield->type;
			}
		}
	

///update reprint
if(isset($updatedata->$dfield) || isset($display->fields->$dfield->to_other_tr))
{
	if(isset($updatedata->$dfield)){	
	$reprint_value = $updatedata->$dfield;
	}	
		/////CHECK if data is from another table 
		if(isset($display->fields->$dfield->to_other_tr))
{	
if($this->update_success ==1)
{sleep(0.5);} /// to allow finishing update queries before redrawing the forms again

//	echo "found $dfield <br />";
if(isset($display->update)){	
if(isset($display->fields->$dfield->type)){

$field_type = $display->fields->$dfield->type;
if(($field_type != "multipleselect") || ($field_type != "checkbox"))
{
	{//otyher tr values
	$other_tr_details = $display->fields->$dfield->to_other_tr;
	$other_tr_table = $other_tr_details->tablename;
	$other_tr_this_column = $other_tr_details->this_column;
	$other_tr_identifier = $other_tr_details->identifier;
	$identifier_value = $updatedata->$other_tr_identifier; ///Able to access the value of any data of update column
	$other_tr_other_column = $other_tr_details->other_column; ///array carrying other colunmnan to select data from
	$with_select_identifier =  $other_tr_other_column["$other_tr_identifier"];///// column with select value
	}

$multipleselect_result = $this->run_query( "SELECT * FROM $other_tr_table WHERE $with_select_identifier = '$identifier_value'");

if(($field_type != "multiplefile"))
{
$select_row = $this->fetch_assoc($multipleselect_result);
$reprint_value = $select_row["$other_tr_this_column"];
//$reprint->$dfield = $reprint_value;
}
else{
//	sleep(1);
		$reprint_value = "";
			while($select_row = $this->fetch_assoc($multipleselect_result)){
			$reprint_value .= $select_row["$other_tr_this_column"] . ",";					
			}
		$reprint_value = substr($reprint_value, 0, -1);
//echo "<script>alert('$reprint_value');</script>";	
}


}	

	
}
}
	
}	
	
	/////end CHECK if data is from another table 	
	//add preprocessor 
		if(isset($display->fields->$dfield->preprocessor))
		{
		$preprocessor_name = $display->fields->$dfield->preprocessor;
		
		$xreprint_value = $reprint_value;
		if(is_callable($preprocessor_name)){
		$reprint_value = call_user_func($preprocessor_name,$reprint_value,$dfield,$updatedata);///refresh may pass other parameters
		}
		if(!$reprint_value){$reprint_value = $xreprint_value;}				
		//	echo "<h1>$value</h1>";
		}
	//end preprocessor 		
	
}
//echo $updatedata->$dfield;



////set reprint values

if(isset($reprint->$dfield))
{
if($this->update_success == 0){
	$reprint_value = $reprint->$dfield; //////refresh from db add functionality to that...
	
}
}	


////setting attributes		
if(isset($display->fields->$dfield->attr))
{
$attr = $display->fields->$dfield->attr;	
} else { $attr = "";}





	$id = $dfield;
		if(isset($display->fields->$dfield->id)){$id= $display->fields->$dfield->id;}
	

	
///labels custom	
$print_lang = str_replace("@name", "$dfield", $print_lang);
$print_lang = str_replace("@u-name", strtoupper($dfield), $print_lang);
$print_lang = str_replace("@l-name", strtolower($dfield), $print_lang);
$print_lang = str_replace("@lf-name", lcfirst($dfield), $print_lang);
$print_lang = str_replace("@uf-name", ucfirst($dfield), $print_lang);
$print_lang = str_replace("@wf-name", ucwords($dfield), $print_lang);

if(substr($print_lang,0,3) == "-u@")
{
$print_lang = str_replace("-u@","", $print_lang);	
$print_lang = str_replace("_"," ", $print_lang);	
}
//end labels custom



$error_before_element = "";
$error_after_element = "";

{///set default values error
if(isset($display->form_validate_each_container)){
$form_validate_each_container = 	$display->form_validate_each_container;
}
else{
$form_validate_each_container = "p";
}

if(isset($display->form_validate_all_container)){
$form_validate_all_container = 	$display->form_validate_all_container;
}
else{
$form_validate_all_container = "div";
}

if(isset($display->form_validate_each_class)){
$form_validate_each_class = 	$display->form_validate_each_class;
}
else{
$form_validate_each_class = "";
}

if(isset($display->form_validate_all_class)){
$form_validate_all_class = 	$display->form_validate_all_class;
}
else{
$form_validate_all_class = "";
}

if(isset($display->form_validate_each_style)){
$form_validate_each_style = 	$display->form_validate_each_style;
}
else{
$form_validate_each_style = "";
}


if(isset($display->form_validate_all_style)){
$form_validate_all_style = 	$display->form_validate_all_style;
}
else{
$form_validate_all_style = "";
}


if(isset($display->form_validate_list_class)){
$form_validate_list_class = 	$display->form_validate_list_class;
}
else{
$form_validate_list_class = "";
}


if(isset($display->form_validate_list_style)){
$form_validate_list_style = 	$display->form_validate_list_style;
}
else{
$form_validate_list_style = "";
}

if(isset($display->form_error_label_container_class)){
$form_error_label_container_class = 	$display->form_error_label_container_class;
}
else{
$form_error_label_container_class = "";
}


if(isset($display->form_error_label_container_style)){
$form_error_label_container_style = 	$display->form_error_label_container_style;
}
else{
$form_error_label_container_style = "";
}


if(isset($display->form_error_label_class)){
$form_error_label_class = $display->form_error_label_class;
}
else{
$form_error_label_class = "";
}

if(isset($display->form_error_label_style)){
$form_error_label_style = $display->form_error_label_style;
}
else{
$form_error_label_style = "";
}

if(isset($display->form_error_element_container_class)){
$form_error_element_container_class = $display->form_error_element_container_class;
}
else{
$form_error_element_container_class = "";
}

if(isset($display->form_error_element_container_style)){
$form_error_element_container_style = $display->form_error_element_container_style;
}
else{
$form_error_element_container_style = "";
}

if(isset($display->form_error_element_class)){
$form_error_element_class = $display->form_error_element_class;
}
else{
$form_error_element_class = "";
}

if(isset($display->form_error_element_style)){
$form_error_element_style = $display->form_error_element_style;
}
else{
$form_error_element_style = "";
}

if(isset($display->form_error_separator)){
$form_error_separator = $display->form_error_separator;
}
else{
$form_error_separator = "";
}

}///END set default values


$invalid_error = "";

if(isset($display->form_validate_inline)){ ////setting inline error details
	if(($display->form_validate_inline == true)){
if(isset($validation_error["$dfield"])){
$invalid_error .= "<$form_validate_all_container class=\"$form_validate_all_class\" style=\"$form_validate_all_style\">";

				foreach($validation_error["$dfield"] as $val_error_print)
			{
//error custome
$this_value = $reprint_value; //input value
$val_error_print = str_replace("@name", $dfield, $val_error_print);
$val_error_print = str_replace("@label", $print_lang, $val_error_print);
$val_error_print = str_replace("@value", $this_value, $val_error_print);
//End erro custome
			$invalid_error .= "<$form_validate_each_container style=\"$form_validate_each_style\" class=\"$form_validate_each_class\" >" . $val_error_print . "</$form_validate_each_container>";
			}
$invalid_error .= "</$form_validate_all_container>$form_error_separator";		
			
		}
		else{
			
			$form_error_label_class = "";
			$form_error_label_style = "";			
			$form_error_element_class = "";
			$form_error_element_style = "";
			
		}

///inline error position


if(isset($display->form_validate_inline_position)){
	if($display->form_validate_inline_position=="before"){
		$error_before_element = "$invalid_error";		
	}
	else{
		$error_after_element = "$invalid_error";	
	}
	
}
else{
	
}
///END inline error position

}
}

$this->input_label["$dfield"] = "";
$this->input_element["$dfield"] = "";
$this->field_data["$dfield"] = "";

		
$this->input_label["$dfield"] = "<label for=\"$dfield\" accesskey=\"\" id='$dfield"."_label' class=\"$form_error_label_class\" style=\"$form_error_label_style\">".  $print_lang . "</label> ";//LABEL
/////////////////////////////////////
////////////CLIENT SIDE VALIDATION//
/////////////////////////////////////
/////////////////////////////////////
{///set default values error


if(isset($display->form_validate_inline_position)){
$validate_inline_position = 	$display->form_validate_inline_position;
}
else{
$validate_inline_position = "after";
}

if(isset($display->form_validate_each_container)){
$validate_each_container = 	strtoupper($display->form_validate_each_container);
}
else{
$validate_each_container = strtoupper("p");
}

if(isset($display->form_validate_all_container)){
$validate_all_container = 	strtoupper($display->form_validate_all_container);
}
else{
$validate_all_container = strtoupper("div");
}

if(isset($display->form_validate_each_class)){
$validate_each_class = 	$display->form_validate_each_class;
}
else{
$validate_each_class = "";
}

if(isset($display->form_validate_all_class)){
$validate_all_class = 	$display->form_validate_all_class;
}
else{
$validate_all_class = "";
}

if(isset($display->form_validate_each_style)){
$validate_each_style = 	$display->form_validate_each_style;
}
else{
$validate_each_style = "";
}


if(isset($display->form_validate_all_style)){
$validate_all_style = 	$display->form_validate_all_style;
}
else{
$validate_all_style = "";
}


if(isset($display->form_validate_list_class)){
$validate_list_class = 	$display->form_validate_list_class;
}
else{
$validate_list_class = "";
}


if(isset($display->form_validate_list_style)){
$validate_list_style = 	$display->form_validate_list_style;
}
else{
$validate_list_style = "";
}

if(isset($display->form_error_label_container_class)){
$error_label_container_class = 	$display->form_error_label_container_class;
}
else{
$error_label_container_class = "";
}


if(isset($display->form_error_label_container_style)){
$error_label_container_style = 	$display->form_error_label_container_style;
}
else{
$error_label_container_style = "";
}


if(isset($display->form_error_label_class)){
$error_label_class = $display->form_error_label_class;
}
else{
$error_label_class = "";
}

if(isset($display->form_error_label_style)){
$error_label_style = $display->form_error_label_style;
}
else{
$error_label_style = "";
}

if(isset($display->form_error_element_container_class)){
$error_element_container_class = $display->form_error_element_container_class;
}
else{
$error_element_container_class = "";
}

if(isset($display->form_error_element_container_style)){
$error_element_container_style = $display->form_error_element_container_style;
}
else{
$error_element_container_style = "";
}

if(isset($display->form_error_element_class)){
$error_element_class = $display->form_error_element_class;
}
else{
$error_element_class = "";
}

if(isset($display->form_error_element_style)){
$error_element_style = $display->form_error_element_style;
}
else{
$error_element_style = "";
}

if(isset($display->form_error_separator)){
$error_separator = $display->form_error_separator;
}
else{
$error_separator = "";
}

}///END set default values
	

//////CLEINT VALIDATE AS INTERGER				
if(isset($display->fields->$dfieldx->ValidateAsInteger)){
		if($display->fields->$dfieldx->ValidateAsInteger == true){
		$validate_error = "**not_set";				
					if(isset($display->fields->$dfieldx->ValidateAsIntegerErrorMessage))
			{
				$validate_error = $display->fields->$dfieldx->ValidateAsIntegerErrorMessage;
			}
							///set as callback	
	$xvalidate_error = $validate_error;
	if(is_callable($validate_error)){
	$validate_error = call_user_func($validate_error,$dfieldx,$value,$arr,$lang);
	}
	if(!$validate_error){$validate_error = $xvalidate_error;}	

	$this->pre_print['call_client_validator'] .= "validation.push(['ValidateAsInteger',this_form,'$dfield','$validate_error']); \n ";

	if(!isset($this->set_client_validator['ValidateAsInteger'])){
	$this->print_client_validator .= "
case 'ValidateAsInteger':
	{	
if(validation[f][1][validation[f][2]].type){
	get_input_value = 	validation[f][1][validation[f][2]].value;
}
else{
	//get_input_value = 	document.querySelector('input[name=faculty_shortname][checked]').value;	
	var radios = 	validation[f][1][validation[f][2]];
	
    // loop through list of radio buttons
    for (var i=0, len=radios.length; i<len; i++) {
        if ( radios[i].checked || radios[i].selected) { // radio checked?
            get_input_value = radios[i].value; // if so, hold its value in val
            break; // and break out of for loop
        }
    }	
}

/*
if(!document.getElementById(validation[f][2]  + '_inline_error')){var newItemAll = document.createElement('$validate_all_container'); newItemAll.className = '$validate_all_class';  newItemAll.id = validation[f][2]  + '_inline_error'; var typAll = document.createAttribute('style'); typAll.value = '$validate_all_style';	newItemAll.attributes.setNamedItem(typAll); this_err_parent.appendChild(newItemAll);} else {newItemAll = document.getElementById(validation[f][2]  + '_inline_error')} */

if(get_input_value != undefined){
eval('var field_value' + \"='\" + get_input_value + \"';var n = field_value.search(/^[0-9]+$/);alert(field_value);if(n == -1){prevent_submit += 1;alert(validation[f][3]);validation[f][1][validation[f][2]].className='$error_element_class';document.getElementById(validation[f][2] + '_label').className='error_label_class'; var this_err_parent = document.getElementById(validation[f][2]).parentElement; /* */if(!document.getElementById(validation[f][2]  + '_inline_error')){var newItemAll = document.createElement('$validate_all_container'); newItemAll.className = '$validate_all_class';  newItemAll.id = validation[f][2]  + '_inline_error'; var typAll = document.createAttribute('style'); typAll.value = '$validate_all_style';	newItemAll.attributes.setNamedItem(typAll);  if('after' == '$validate_inline_position'){this_err_parent.appendChild(newItemAll); alert('xxxxx not set yet');}   if('before' == '$validate_inline_position'){ this_err_parent.insertBefore(newItemAll, document.getElementById(validation[f][2])); alert('xxxxx not set yet');}	} else { var newItemAll = document.getElementById(validation[f][2]  + '_inline_error'); if(document.getElementById(validation[f][2]  + '_inline_error_' + validation[f][0])){	newItemAll.removeChild(document.getElementById(validation[f][2]  + '_inline_error_' + validation[f][0]));	} }  /* */ var newItem = document.createElement('$validate_each_container'); newItem.className = '$validate_each_class';  newItem.id = validation[f][2]  + '_inline_error_' + validation[f][0]; var typ = document.createAttribute('style');typ.value = '$validate_each_style';	newItem.attributes.setNamedItem(typ);	var textnode = document.createTextNode(validation[f][3]);newItem.appendChild(textnode);newItemAll.appendChild(newItem); } else{validation[f][1][validation[f][2]].className='';document.getElementById(validation[f][2] + '_label').className=''; if(document.getElementById(validation[f][2]  + '_inline_error')){ var newItemAll = document.getElementById(validation[f][2]  + '_inline_error');   if(document.getElementById(validation[f][2]  + '_inline_error_' + validation[f][0])){	newItemAll.removeChild(document.getElementById(validation[f][2]  + '_inline_error_' + validation[f][0]));	} } }\");
	
	}	
	}
	break;
";
	$this->set_client_validator['ValidateAsInteger'] = true;
		}
		
		
		
		
		
	}

}

//////END CLEINT VALIDATE AS INTERGER	

	

//////CLEINT VALIDATE AS EMAIL		
if(isset($display->fields->$dfieldx->ValidateAsEmail)){
		if($display->fields->$dfieldx->ValidateAsEmail == true){
		$validate_error = "**not_set";				
					if(isset($display->fields->$dfieldx->ValidateAsEmailErrorMessage))
			{
				$validate_error = $display->fields->$dfieldx->ValidateAsEmailErrorMessage;
			}
							///set as callback	
	$xvalidate_error = $validate_error;
	if(is_callable($validate_error)){
	$validate_error = call_user_func($validate_error,$dfieldx,$value,$arr,$lang);
	}
	if(!$validate_error){$validate_error = $xvalidate_error;}	

	$this->pre_print['call_client_validator'] .= "validation.push(['ValidateAsEmail',this_form,'$dfield','$validate_error']); \n ";

	if(!isset($this->set_client_validator['ValidateAsEmail'])){
	$this->print_client_validator .= "
case 'ValidateAsEmail':
	{	
if(validation[f][1][validation[f][2]].type){
	get_input_value = 	validation[f][1][validation[f][2]].value;
}
else{
	//get_input_value = 	document.querySelector('input[name=faculty_shortname][checked]').value;	
	var radios = 	validation[f][1][validation[f][2]];
	
    // loop through list of radio buttons
    for (var i=0, len=radios.length; i<len; i++) {
        if ( radios[i].checked || radios[i].selected) { // radio checked?
            get_input_value = radios[i].value; // if so, hold its value in val
            break; // and break out of for loop
        }
    }	
}

eval('var field_value' + \"='\" + get_input_value + \"';var n = field_value.search(/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-._]+..[a-zA-Z0-9-.]+$/);alert(field_value);if(n == -1){prevent_submit += 1;alert(validation[f][3]);validation[f][1][validation[f][2]].className='$error_element_class';}else{validation[f][1][validation[f][2]].className='';}\");
	}
	break;
";
	$this->set_client_validator['ValidateAsEmail'] = true;
		}
		
		
		
		
		
	}

}

//////END CLEINT VALIDATE AS EMAIL

////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
//////////END CLIENT VALIDATION////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////






////NOTE
/*
use $dfield for field name if not accessing $display use $dfieldx

*/

/////SETTING UPDATE BUTTON	
$update_button = "";	
		if(isset($display->fields->$dfieldx->to_update)){  ////come set		fields
//$this->alert('found');		
		$to_update_button = "&gt;";	
		if(isset($display->fields->$dfieldx->to_update_button)){
			$to_update_button = $display->fields->$dfieldx->to_update_button;
		}	
			
		$update_button = "<input type='submit' name='update_submit_button'  value='$to_update_button' />";	
		}
/////END SETTING UPDATE BUTTON


//////SET UPDATE FUNCTIONALITY
	$ajax_update = "";
	if(isset($display->fields->$dfieldx->to_update)){
	
	$to_update = $display->fields->$dfieldx->to_update;
			if(isset($display->fields->$dfieldx->to_update_event)){
				$to_update_event = $display->fields->$dfieldx->to_update_event;
			}
			else{
			$to_update_event = "onchange";	
			}
//not_found_message			
			if(isset($display->fields->$dfieldx->not_found_message)){
				$not_found_message = $display->fields->$dfieldx->not_found_message;
			}
			else{
			$not_found_message = "";	
			}			
	//onchange="loadFieldUpdate(this.name,this.value,'myDiv')"		
	$ajax_update = "$to_update_event=\"loadFieldUpdate(this.name,this.value,'$to_update','$not_found_message')\"";
/*
$this->input_element["$dfield"] .= "
<script>
document.getElementById(\"$dfield\").addEventListener(\"change\", function (event) {
//loadFieldUpdate(document.getElementById(\"$dfield\").name,document.getElementById(\"$dfield\").value,'$to_update','$not_found_message');
alert('hrloe');
	}, true);
</script>
";
*/
	
	}	
//////END SET UPDATE FUNCTIONALITY

//////SET AUTO COMPLETE FUNCTIONALITY
$auto_complete_display = "";
$auto_complete_button = "";
$auto_complete_attr = "";


	if(isset($display->fields->$dfieldx->auto_complete)){
		if(($display->fields->$dfieldx->auto_complete) == true){
			$button_text = "...";
				if(isset($display->fields->$dfieldx->button_text)){
					$button_text = $display->fields->$dfieldx->button_text;
				}
						
			if(isset($display->fields->$dfieldx->not_found_message)){
				$not_found_message = $display->fields->$dfieldx->not_found_message;
			}
			else{
			$not_found_message = "";	
			}

			
			
			if(isset($display->fields->$dfieldx->suggestion_box_id)){
				$suggestion_box_id = $display->fields->$dfieldx->suggestion_box_id;
			}
			else{
				$suggestion_box_id = $dfieldx . "_autocomplete";	
			}
				
			$auto_complete_button = "<input type='submit' name='update_submit_button' value='$button_text' />";
			$auto_complete_button = ""; ////overwrite above
	
			$auto_complete_display = "<div id='$suggestion_box_id'  onclick=\"this.innerHTML ='';int_key_$suggestion_box_id = -1;\" style='position:absolute;background:#eee;'></div> $auto_complete_button";
			/////END SET AUTO COMPLETE FUNCTIONALITY
		$auto_complete_display .= "
<script>
//alert('found');
function auto_fill_$dfield(fill_value){
document.getElementById(\"$dfield\").value = fill_value;	
}

function activeAutoFill_$dfield(fill_value_id){
	
var x =document.getElementById(\"$suggestion_box_id\").children;
var i;
for (i = 0; i < x.length; i++) {
x[i].style.backgroundColor = '';
x[i].style.color = 'black';
//return true;	
};


document.getElementById(\"$dfield\").value = fill_value_id.title;

int_key_$suggestion_box_id = parseInt(fill_value_id.id);

	
fill_value_id.style.backgroundColor = '#67f';
fill_value_id.style.color = 'white';
}

function inactiveAutoFill_$dfield(fill_value_id){
//fill_value_id.style.backgroundColor = '';	
}

</script>
";

$auto_complete_display .= "<script>

int_key_$suggestion_box_id = -1;

document.getElementById(\"$dfield\").addEventListener(\"keyup\", function (event) {
	if(document.activeElement.id == this.id){
		if(event.keyCode == 40 || event.keyCode == 38)
		{
			return false;
		}
		else
		{
			int_key_$suggestion_box_id = -1;
			autoCompleteField(this.name,this.value,'$suggestion_box_id','$not_found_message');
		}
	}	
}, true);

document.getElementById(\"$dfield\").addEventListener(\"change\", function (event) {
	if(document.activeElement.id == this.id){	
		autoCompleteField(this.name,this.value,'$suggestion_box_id','$not_found_message');
	if(this.value==\"\"){
	int_key_$suggestion_box_id = -1;
	}
	int_key_$suggestion_box_id = -1;	
	}
}, true);

document.getElementById(\"$dfield\").addEventListener(\"dblclick\", function (event) {
		if(document.activeElement.id == this.id){
		autoCompleteField(this.name,this.value,'$suggestion_box_id','$not_found_message');
		}
}, true);


window.addEventListener(\"keydown\", function (event) {

if(document.activeElement.id == document.getElementById(\"$dfield\").id){
if(document.getElementById(\"$suggestion_box_id\").children.length > 0){


if(event.keyCode == 38){
	if(int_key_$suggestion_box_id >= -1){
	if(int_key_$suggestion_box_id >	0){	
	int_key_$suggestion_box_id -= 1;
	}
	if(int_key_$suggestion_box_id <0){
	int_key_$suggestion_box_id = document.getElementById(\"$suggestion_box_id\").children.length;	
	}		
	current_suggestion = document.getElementById(\"$suggestion_box_id\").children[int_key_$suggestion_box_id];
	current_suggestion_id = document.getElementById(\"$suggestion_box_id\").children[int_key_$suggestion_box_id].id;
	activeAutoFill_$dfield(current_suggestion,current_suggestion_id);
	}	
}

if(event.keyCode == 40){
	
	if(int_key_$suggestion_box_id <= document.getElementById(\"$suggestion_box_id\").children.length - 1){
	if(int_key_$suggestion_box_id < document.getElementById(\"$suggestion_box_id\").children.length - 1){	
	int_key_$suggestion_box_id += 1;
	}
//alert(int_key_$suggestion_box_id);	
	current_suggestion = document.getElementById(\"$suggestion_box_id\").children[int_key_$suggestion_box_id];
	current_suggestion_id = document.getElementById(\"$suggestion_box_id\").children[int_key_$suggestion_box_id].id;
	activeAutoFill_$dfield(current_suggestion,current_suggestion_id);
	}	
}

if(event.keyCode == 13 || event.keyCode == 39 || event.keyCode == 37){
	
	if(int_key_$suggestion_box_id > -1){	
document.getElementById(\"$dfield\").value = document.getElementById(\"$suggestion_box_id\").children[int_key_$suggestion_box_id].title;
document.getElementById(\"$suggestion_box_id\").innerHTML = \"\";
int_key_$suggestion_box_id = -1;
	}

	if(event.keyCode == 39){
		if(int_key_$suggestion_box_id > -1){	
			document.getElementById(\"$dfield\").value = document.getElementById(\"$suggestion_box_id\").children[int_key_$suggestion_box_id].title;;
			document.getElementById(\"$suggestion_box_id\").innerHTML = \"\";
			int_key_$suggestion_box_id = -1;
			var fieldelement = document.getElementById(\"$dfield\");	
			autoCompleteField(fieldelement.name,fieldelement.value,'$suggestion_box_id','$not_found_message');	
		}	
	}	

    event.preventDefault();	

}


	
}

else{
	
if(event.keyCode == 40){	
autoCompleteField(document.getElementById(\"$dfield\").name,document.getElementById(\"$dfield\").value,'$suggestion_box_id','$not_found_message');	
}
	
}
  if (event.defaultPrevented) {
    return; // Should do nothing if the default action has been cancelled
  }

  var handled = false;
  if (event.key !== undefined) {
    // Handle the event with KeyboardEvent.key and set handled true.
  } else if (event.keyIdentifier !== undefined) {
    // Handle the event with KeyboardEvent.keyIdentifier and set handled true.
  } else if (event.keyCode !== undefined) {
    // Handle the event with KeyboardEvent.keyCode and set handled true.
  }

  if (handled) {
    // Suppress \"double action\" if event handled
    event.preventDefault();
  }
  
} 
}, true);

window.addEventListener(\"click\", function (event) {

document.getElementById(\"$suggestion_box_id\").innerHTML = \"\";
int_key_$suggestion_box_id = -1;
  if (event.defaultPrevented) {
    return; // Should do nothing if the default action has been cancelled
  }

  var handled = false;
  if (event.key !== undefined) {
    // Handle the event with KeyboardEvent.key and set handled true.
  } else if (event.keyIdentifier !== undefined) {
    // Handle the event with KeyboardEvent.keyIdentifier and set handled true.
  } else if (event.keyCode !== undefined) {
    // Handle the event with KeyboardEvent.keyCode and set handled true.
  }

  if (handled) {
    // Suppress \"double action\" if event handled
    event.preventDefault();
  }
}, true);
</script>";		
		
		}
	}
	
$auto_complete_response = "";
$suggestions = "";
//	req = "auto_complete_button=" + sname + "&auto_complete_request=" + sname + "&auto_complete_value=" + svalue;

if(isset($_REQUEST['auto_complete_request'])){
	if($_REQUEST['auto_complete_request'] == "$dfield"){
$auto_complete_value = $_REQUEST['auto_complete_value'];



if(isset($display->fields->$dfieldx->suggestions)){
if(is_array($display->fields->$dfieldx->suggestions)){
$sug_set = 1;	
foreach($display->fields->$dfieldx->suggestions as $sug_key=>$sug_value){
$suggestions .= 	$sug_key . "=>" .  $sug_value . ",";
}

$suggestions = substr($suggestions, 0, -1);	
}
else{
	$suggestions .= $display->fields->$dfieldx->suggestions;
}

}

/////check if sugesstion is array then put like car{%%%%}CAR strip earlier of tags



if(isset($display->fields->$dfieldx->db_suggestion)){
$tablename = "";
$value_column = "";	
	if(isset($display->fields->$dfieldx->db_suggestion['tablename'])){
		$tablename = $display->fields->$dfieldx->db_suggestion['tablename'];
	}
	if(isset($display->fields->$dfieldx->db_suggestion['value_column'])){
		$value_column = $display->fields->$dfieldx->db_suggestion['value_column'];
	}

	$suggestion_sql = "SELECT $value_column FROM $tablename where $value_column like '%$auto_complete_value%';";
$suggest_list = ",";
$suggestion_query = $this->run_query("$suggestion_sql");
	if($suggestion_query)
	{		
		while($suggestion_row = $this->fetch_assoc($suggestion_query))
		  {
	$suggest_list .= $suggestion_row[$value_column] . ",";	
		  }	
	}
	

$suggest_list = substr($suggest_list, 0, -1);	


$suggestions .= $suggest_list;	
}

$suggestion_array = explode(",",$suggestions);

sort($suggestion_array, SORT_NATURAL | SORT_FLAG_CASE);

$matched_suggestion = array();

$sug_id = -1;

foreach($suggestion_array as $suggestion_key => $suggestion){

$match_val = strtolower($auto_complete_value);
$sug_val = strtolower($suggestion);

if(preg_match("/^$match_val/",$sug_val)){

$sug_id += 1;

if(isset($sug_set) && strpbrk($suggestion,"=>") != false){
$new_sug_arr = explode("=>",$suggestion);
$sug_title = $new_sug_arr[0];
$suggestion = $new_sug_arr[1];
	
}
else{
$sug_title = strip_tags($suggestion);
}
/////suggestion if array here,

	
$matched_suggestion[] = "<div 
onclick=\"auto_fill_$dfield(this.title);\" title='$sug_title' 
onmouseenter=\"activeAutoFill_$dfield(this,this.id);\" 
onmouseout=\"inactiveAutoFill_$dfield(this);\" 
id=\"$sug_id\" style='padding-left:3px;';
>$suggestion</div>";

}
	
}

$print_suggestion = implode("",$matched_suggestion);
	
$auto_complete_response = "<auto_complete-response>6 $print_suggestion<auto_complete-response>6";

echo "$auto_complete_response";	
echo die();	
	
	}
	
}


	
switch ($input_type)  ////////@@@@@@@@    SWITCH FOR FORM INPUT TYPES
{
	case "select": //selection type data
	{
	
	
	
	$this->input_element["$dfield"] .= "<select name='$dfield' $attr style=\"$form_error_element_style\" class=\"$form_error_element_class\"  id='$id' $ajax_update >";
	
	$options_array = array();
	//$this->update_fields_where[$to_update]
	if(isset($display->fields->$dfieldx->from_dbtable)|| (isset($this->update_fields_where[$dfield])))///checking for selection from db else selection from values
	{
			////{{{ COUNTINUE FROM HERE SELECT FROM DATABASE FROM DATABASE  }}}/////////
			
									
			if(isset($this->update_fields_where[$dfield])){
			$selection_dbt =$display->fields->$dfieldx->update_from_dbtable;
		
			}
			else
			{
			$selection_dbt = $display->fields->$dfieldx->from_dbtable;	
			}	
			

			$select_tablename = $selection_dbt->tablename;

			if(isset($selection_dbt->where))
			{
				if(isset($this->update_fields_where[$dfield])){
				$selection_dbt->where = str_replace("%v",$this->update_fields_where[$dfield],$selection_dbt->where);
				}	
				
				$select_where = "WHERE " . $selection_dbt->where;
			} else 
			{
				$select_where = "";
				
			}

//include("connectdb.php");
//$con = $GLOBALS["con"];
if(isset($selection_dbt->custom_query)){
	$sqlxx = $selection_dbt->custom_query;
	$sqlxx = str_replace("%v",$this->update_fields_where[$dfield],$sqlxx);
}
else{
	$sqlxx = "SELECT * FROM $select_tablename $select_where;";
}

$select_result22 = $this->run_query("$sqlxx");
if($select_result22)
{
//alert($sqlxx);
//alert(fetch_assoc($select_result));
while($select_row = $this->fetch_assoc($select_result22))
  {
	
$option_display = $select_row["$selection_dbt->option_display"];
$option_value = $select_row["$selection_dbt->option_value"];
	
$options_array[$option_display] = $option_value;
  }
  
  
				}
				else{ 
//				alert('erro occured');   //////check and remove return sql error
				//$this->alert($sqlxx);
				}
		
	}
else{ /// execution if database selection is not set
			if(isset($display->fields->$dfieldx->values_for_select)){
				$options_array =	$display->fields->$dfieldx->values_for_select; ///array of select values	
			}
	}
	
/* */	
	//	print_r($options_array);
		////Display selection option
			$optgroup = array();
			if(isset($display->fields->$dfieldx->optgroup)){
				
			$optgroup = $display->fields->$dfieldx->optgroup;
			}
				
				$ingroup_option = array();
				$ingroup_print = "";
				
				foreach($optgroup as $each_optgroup => $optgroup_value){
				
					if(!empty($each_optgroup))
					{							
						$ingroup_print .=	"<optgroup label=\"$each_optgroup\">";
						$optvalue_array = explode(",", $optgroup_value);
						
						foreach($optvalue_array as $optiongroup_key => $optiongroup_value){
								if(in_array($optiongroup_value,$options_array)){
								//array_search("red",$a);
								$ingroup_option[] = $optiongroup_value;
								$option_label = array_search($optiongroup_value,$options_array);
					$selected = "";
					if($reprint_value==$optiongroup_value)
					{$selected = "selected=\"selected\"";}
					if(isset($display->fields->$dfieldx->selected))
					{
						if(in_array($optiongroup_value, $display->fields->$dfieldx->selected))
						{$selected = "selected=\"selected\"";}	
					}
					
								$ingroup_print .= "<option value='$optiongroup_value' $selected>$option_label</option>";							
								}	
						}
						$ingroup_print .=	"</optgroup>";				
					}
				
				}

if(!isset($this->update_fields_where[$dfield])){				
	if(isset($this->update_fields[$dfield])){
		$options_array = $this->update_fields[$dfield];
//			$this->alert("$dfield". "--xgood");	
	}	
}

$not_ingroup  = "";	
				foreach ($options_array as $option => $value ) { 
					if(!in_array($value,$ingroup_option)){
					$selected = "";
					if($reprint_value==$value)
					{$selected = "selected=\"selected\"";}
					if(isset($display->fields->$dfieldx->selected))
					{
						if(in_array($value, $display->fields->$dfieldx->selected))
						{$selected = "selected=\"selected\"";}	
					}
						
						//$this->input_element["$dfield"] .= "<option value='$value'  $selected>$option</option>";
						$not_ingroup .= "<option value='$value'  $selected>$option</option>";
						
					}
				}
				
				$this->input_element["$dfield"] .=	$not_ingroup;
				$this->input_element["$dfield"] .=	$ingroup_print;
if(isset($this->update_fields_ajax["$dfield"]))
{
	//update_current_field
	if($this->update_current_field["$dfield"] == $dfield){
	  $return_ajax	=  "<xx--xx--xx>9" . $not_ingroup . "<xx--xx--xx>9"; /// 9 (nine) for print 9 function in javascript without repitation
	echo "$return_ajax";
	die;
	}
}	
				

				



		$this->input_element["$dfield"] .= "</select>";
		
		$this->input_element["$dfield"] .= $update_button;

		
		////End selection each option
	} 	///END SELECTION TYPE
        break;
	case "multipleselect": //selection type data
	{
	$this->input_element["$dfield"] .= "<select name='$dfield"."[]' $attr multiple='multiple'  style=\"$form_error_element_style\" class=\"$form_error_element_class\"   id='$id' selected='$dfield'>";
	
	$options_array = array();
	
	if(isset($display->fields->$dfieldx->from_dbtable)|| (isset($this->update_fields_where[$dfield])))///checking for selection from db else selection from values
	{
//		$this->alert("$dfield");
////{{{ COUNTINUE FROM HERE SELECT FROM DATABASE FROM DATABASE  }}}/////////
			if(isset($this->update_fields_where[$dfield])){
//			print_r($display->fields->$dfieldx);	
			$selection_dbt =$display->fields->$dfieldx->update_from_dbtable;
		
			}
			else
			{
			$selection_dbt = $display->fields->$dfieldx->from_dbtable;	
			}

			$select_tablename = $selection_dbt->tablename;
			

			if(isset($selection_dbt->where))
			{
				if(isset($this->update_fields_where[$dfield])){
				$selection_dbt->where = str_replace("%v",$this->update_fields_where[$dfield],$selection_dbt->where);
				}	
				
				$select_where = "WHERE " . $selection_dbt->where;
			} else 
			{
				$select_where = "";
				
			}

//include("connectdb.php");
//$con = $GLOBALS["con"];
if(isset($selection_dbt->custom_query)){
	$sqlx2 = $selection_dbt->custom_query;
	$sqlx2 = str_replace("%v",$this->update_fields_where[$dfield],$sqlx2);
}
else{
	$sqlx2 = "SELECT * FROM $select_tablename $select_where;";
}

$select_result = $this->run_query( "$sqlx2");

if($select_result)
{
while($select_row = $this->fetch_assoc($select_result))
  {
$option_display = $select_row["$selection_dbt->option_display"];
$option_value = $select_row["$selection_dbt->option_value"];

	//////%%%%%%%%%%%%%%%%%%%
////End setting for other table											 
								 
												 
$options_array[$option_display] = $option_value;
  
  }
}
else
{
	////error message here, add to database  errorarray
	
}
		
	}
	
	
else{ /// execution if database selection is not set
			if(isset($display->fields->$dfieldx->values_for_select)){
	$options_array =	$display->fields->$dfieldx->values_for_select; ///array of select values		
			}
	}
	//	print_r($options_array);
		////Display selection option
		//if(isset())
			$options_toprint = array();
		
if(!isset($this->update_fields_where[$dfield])){				
	if(isset($this->update_fields[$dfield])){
		$options_array = $this->update_fields[$dfield];
//			$this->alert("$dfield". "--xgood");
//@@@@@@@@@@@@	
	}	
}

		
		foreach ($options_array as $option => $value ) 
				{
					
				$selected = "";
				 if(isset($reprint->$dfield)){
				if(in_array($value, $reprint->$dfield))
				{
					$selected = "selected=\"selected\"";
				}
				}
				
	
	////setting for other table	
	else{			
if (isset($display->fields->$dfieldx->to_other_tr))
{
	{//otyher tr values
	$other_tr_details = $display->fields->$dfieldx->to_other_tr;
	$other_tr_table = $other_tr_details->tablename;
	$other_tr_this_column = $other_tr_details->this_column;
	$other_tr_identifier = $other_tr_details->identifier;
	$identifier_value = $updatedata->$other_tr_identifier; ///Able to access the value of any data of update column
	$other_tr_other_column = $other_tr_details->other_column; ///array carrying other colunmnan to select data from
	$with_select_identifier =  $other_tr_other_column["$other_tr_identifier"];///// column with select value
	}

$multipleselect_result = $this->run_query( "SELECT * FROM $other_tr_table WHERE $with_select_identifier = '$identifier_value'");

while($select_row = $this->fetch_assoc($multipleselect_result))
  {
$this_row = $select_row["$other_tr_this_column"];
$identifier_row = $select_row["$with_select_identifier"];

				if(($value == "$this_row"))
				{
					$selected = "selected=\"selected\"";
				}
		
  }
	
	
}	
	
	else{/////just from same table
	$raw_select = "";
	if(isset($updatedata->$dfield))
	{
	$raw_select =  $updatedata->$dfield;
	}
	
if(isset($display->fields->$dfieldx->field_separator))
				{
					$field_separator = $display->fields->$dfieldx->field_separator;
					
				}
				else
				{
					$field_separator = "+";
				//	alert("$field_separator");
				}
			
	
$array_raw_select = explode("$field_separator",$raw_select); /// set separator latter


foreach($array_raw_select as $skey => $sdata)
{

	if($sdata == $value)
		{
		//	alert("$sdata");
			$selected = "selected=\"selected\"";	
		}
}	
		
	
	
	}
		
			}
	////End setting for other table		
				if(isset($display->fields->$dfieldx->selected))
			{
				if(in_array($value, $display->fields->$dfieldx->selected))
				{
					$selected = "selected=\"selected\"";
				}
				
				
			}	
				$options_toprint[$value] = "<option value='$value' $selected >$option</option>";
				}
				
				
			$optgroup = array();
			if(isset($display->fields->$dfieldx->optgroup)){
				
			$optgroup = $display->fields->$dfieldx->optgroup;
			}			
				$ingroup_option = array();
				$ingroup_print = "";

/////>>>>>>>>
				foreach($optgroup as $each_optgroup => $optgroup_value){
				
					if(!empty($each_optgroup))
					{							
						$ingroup_print .=	"<optgroup label=\"$each_optgroup\">";
						$optvalue_array = explode(",", $optgroup_value);
						
						foreach($optvalue_array as $optiongroup_key => $optiongroup_value){
								if(array_key_exists($optiongroup_value,$options_toprint)){
								//array_search("red",$a);
								$ingroup_option[] = $optiongroup_value;
								$ingroup_print .= $options_toprint[$optiongroup_value];							
								}	
						}
						$ingroup_print .=	"</optgroup>";				
					}
				
				}


if(!isset($this->update_fields_where[$dfield])){				
	if(isset($this->update_fields[$dfield])){
		$options_array = $this->update_fields[$dfield];
//			$this->alert("$dfield". "--xgood");
//@@@@@@@@@@@@	
	}	
}

$not_ingroup  = "";	
				
				foreach ($options_array as $option => $value ) { 
					if(!in_array($value,$ingroup_option)){

						
						$not_ingroup .= $options_toprint[$value];
					}
				}
				
		$this->input_element["$dfield"] .=	$not_ingroup;		
		
		$this->input_element["$dfield"] .=	$ingroup_print;

if(isset($this->update_fields_ajax["$dfield"]))
{
	//update_current_field
	if($this->update_current_field["$dfield"] == $dfield){
	  $return_ajax	=  "<xx--xx--xx>9" . $not_ingroup . "<xx--xx--xx>9"; /// 9 (nine) for print 9 function in javascript without repitation
	echo "$return_ajax";
	die;
	}
}
		
		
		$this->input_element["$dfield"] .= "</select>";
		
		////End selection each option
	} 	///END SELECTION TYPE
        break;
    case "radio":
	{ 
	
		$element_separator = "";
		if(isset($display->fields->$dfieldx->element_separator)){
		$element_separator = $display->fields->$dfieldx->element_separator;
		}
		
        $options_array =	array(); ///array of select values
			
	if(isset($display->fields->$dfieldx->from_dbtable)|| (isset($this->update_fields_where[$dfield])))///checking for selection from db else selection from values
{
///////db options
						if(isset($this->update_fields_where[$dfield])){
			$selection_dbt =$display->fields->$dfieldx->update_from_dbtable;
		
			}
			else
			{
			$selection_dbt = $display->fields->$dfieldx->from_dbtable;	
			}

			$select_tablename = $selection_dbt->tablename;

			if(isset($selection_dbt->where))
			{
				if(isset($this->update_fields_where[$dfield])){
				$selection_dbt->where = str_replace("%v",$this->update_fields_where[$dfield],$selection_dbt->where);
				}	
				
				$select_where = "WHERE " . $selection_dbt->where;
			} else 
			{
				$select_where = "";
				
			}

//	include("connectdb.php");
//$con = $GLOBALS["con"];

if(isset($selection_dbt->custom_query)){
	$sqlvv = $selection_dbt->custom_query;
	$sqlvv = str_replace("%v",$this->update_fields_where[$dfield],$sqlvv);
}
else{
	$sqlvv = "SELECT * FROM $select_tablename $select_where;";
}
	//alert($sqlvv);
	$resultvv = $this->run_query($sqlvv);
	if($resultvv){
	while($select_row = $this->fetch_assoc($resultvv))
	  {
		  
	$option_display = $select_row["$selection_dbt->option_display"];
	$option_value = $select_row["$selection_dbt->option_value"];
	$options_array[$option_display] = $option_value;
	}}
	else{
	//	alert('An error occured in selection');
//	$this->alert("error is true");
	}

///////db options
		
		
	}
			
		else	{
	if(isset($display->fields->$dfieldx->values_for_select)){		
		 $options_array =	$display->fields->$dfieldx->values_for_select;
	}
		}


if(!isset($this->update_fields_where[$dfield])){				
	if(isset($this->update_fields[$dfield])){
		$options_array = $this->update_fields[$dfield];
//			$this->alert("$dfield". "--xgood");	
	}	
}	
$not_ingroup  = "";		
		foreach ($options_array as $option_display => $option_value ) 
				{
					
					$selected = "";
				if($reprint_value==$option_value)
				{
					$selected = "checked=\"checked\"";
				}
			if(isset($display->fields->$dfieldx->checked))
			{
				if(in_array($option_value, $display->fields->$dfieldx->checked))
				{
						$selected = "checked=\"checked\"";
				}
				
				
			}
				$not_ingroup .= "<input type='radio' name='$dfield' $ajax_update $attr  style=\"$form_error_element_style\" class=\"$form_error_element_class\"   $selected id='$id" . "_" . "$option_value' value='$option_value'> $option_display $element_separator";
			//	$this->input_element["$dfield"] .= "<input type='radio' name='$dfield' $attr  style=\"$form_error_element_style\" class=\"$form_error_element_class\"   $selected id='$id" . "_" . "$option_value' value='$option_value'> $option_display $element_separator";
				
				
				}
	$this->input_element["$dfield"] .= "<span id='$dfield' name='$dfield'>" . $not_ingroup . "</span>";

if(isset($this->update_fields_ajax["$dfield"]))
{
	//update_current_field
	if($this->update_current_field["$dfield"] == $dfield){
	  $return_ajax	=  "<xx--xx--xx>9" . $not_ingroup . "<xx--xx--xx>9"; /// 9 (nine) for print 9 function in javascript without repitation
	echo "$return_ajax";
	die;
	}
}


		$this->input_element["$dfield"] .= $update_button;
	
	}
        break;
    case "password":
	{	
	$reprint_value = htmlspecialchars($reprint_value);	
		
	$this->input_element["$dfield"] .= "	<input type='password' name='$dfield' $attr  style=\"$form_error_element_style\" class=\"$form_error_element_class\"   id='$id' value='$reprint_value' />";

	}
        break;

	case "text":
	{
	
	$reprint_value = htmlspecialchars($reprint_value);			
		$this->input_element["$dfield"] .= "<input type='text' name='$dfield' $ajax_update $auto_complete_attr $attr  style=\"$form_error_element_style\" class=\"$form_error_element_class\"   id='$id' value=\"$reprint_value\" />";
		$this->input_element["$dfield"] .= $auto_complete_display;		
		$this->input_element["$dfield"] .= $update_button;
		
	}
        break;
		
	case "number":
	{

	
		$this->input_element["$dfield"] .= "<input type='number' name='$dfield' $ajax_update $attr  style=\"$form_error_element_style\" class=\"$form_error_element_class\"   id='$id' value=\"$reprint_value\" />";
		$this->input_element["$dfield"] .= $auto_complete_display;		
		$this->input_element["$dfield"] .= $update_button;		

	}
        break;	
		
	case "textarea":
	{
		
	$reprint_value = htmlspecialchars($reprint_value);		
	$this->input_element["$dfield"] .= "	<textarea type='text'  id='$id' $ajax_update $attr  style=\"$form_error_element_style\" class=\"$form_error_element_class\"   name='$dfield'>$reprint_value</textarea>";
	$this->input_element["$dfield"] .= $auto_complete_display;		
	$this->input_element["$dfield"] .= $update_button;
	}
        break;
	case "customHTML":  	////// Replacing with customized html content 
	{	

	//echo "Your favourite HTML CODE";
	///////////check set content////////////
	if (isset($display->fields->$dfieldx->content))
	{
	$htm_content = $display->fields->$dfieldx->content;
	}
	else
	{
	$htm_content = "";
	}
	///////////End check set content////////////
	
	
	$this->input_element["$dfield"] .= $htm_content;

	}
        break;
	case "customPHP":		////// Replacing with customized php content eval()
	{	

	//echo "Your favourite PHP CODE";
	///////////check set////////////
	if (isset($display->fields->$dfieldx->content))
	{
	$php_content =$display->fields->$dfieldx->content;
	}
	else
	{
	//$this->input_element["$dfield"] . = "echo \"\";";
	}
	///////////End check set////////////
	
	eval($php_content); /// use "" in object  
	

		
	}
        break;

	case "color":
	{	

		
		$this->input_element["$dfield"] .= "<input type='color'  name='$dfield' $ajax_update $attr  style=\"$form_error_element_style\" class=\"$form_error_element_class\"   id='$id' value='$reprint_value' />";
		$this->input_element["$dfield"] .= $auto_complete_display;		
		$this->input_element["$dfield"] .= $update_button;

	}
        break;		
	case "range":
	{
	

	$this->input_element["$dfield"] .= "<input type='range' name='$dfield'  $ajax_update $attr  style=\"$form_error_element_style\" class=\"$form_error_element_class\"   id='$id' value='$reprint_value' />";
	$this->input_element["$dfield"] .= $auto_complete_display;		
	$this->input_element["$dfield"] .= $update_button;

	}
        break;
	
	case "file":
	{	
/*
	if(isset($reprint->$dfield))
{
	$reprint_value = $reprint->$dfield;
	$reprint_value = "<span id='$dfield'  style='color:red;'><i>file:</i><b>$reprint_value</b> was not uploaded! choose file again </span><br />";
	
if(isset($display->update))
{
	if($display->update->set == true)
	{
		$reprint_value = "";
	}
}
}
*/

//$this->input_element["$dfield"] .= "$reprint_value";   ///comment out to prevent showing
	
	$this->input_element["$dfield"] .= "<input type='file' name='$dfield' $attr  style=\"$form_error_element_style\" class=\"$form_error_element_class\"   id='$id' value='' />";
	

	}
        break;	
	case "multiplefile":
	{	

/*	
	if(isset($reprint->$dfield))
{

	$reprint_value = $reprint->$dfield;
	$reprint_value = "<span id='$dfield'  style='color:red;'><i>file:</i><b>$reprint_value</b> was not uploaded! choose file again </span><br />";

if(isset($display->update))
{
	if($display->update->set == true)
	{
		$reprint_value = "";
	}
}

}

*/

//$this->input_element["$dfield"] .= "$reprint_value";   ///comment out to prevent showing
	
	$this->input_element["$dfield"] .= "<input type='file'  multiple=\"multiple\" name='$dfield" . "[]' $attr  style=\"$form_error_element_style\" class=\"$form_error_element_class\"   id='$id' value='' />";
	

	}
        break;

	case "checkbox":
	{
		$element_separator = "";
//		$options_array = array();
		if(isset($display->fields->$dfieldx->element_separator)){
		$element_separator = $display->fields->$dfieldx->element_separator;
		}
	$options_array = array();	
	if(isset($display->fields->$dfieldx->from_dbtable)|| (isset($this->update_fields_where[$dfield])))///checking for selection from db else selection from values
	{
////{{{ COUNTINUE FROM HERE SELECT FROM DATABASE FROM DATABASE  }}}/////////
			if(isset($this->update_fields_where[$dfield])){
//			print_r($display->fields->$dfieldx);	
			$selection_dbt =$display->fields->$dfieldx->update_from_dbtable;
		
			}
			else
			{
			$selection_dbt = $display->fields->$dfieldx->from_dbtable;	
			}

			$select_tablename = $selection_dbt->tablename;

	if(isset($selection_dbt->where))
	{
		if(isset($this->update_fields_where[$dfield])){
		$selection_dbt->where = str_replace("%v",$this->update_fields_where[$dfield],$selection_dbt->where);
		}	
		
		$select_where = "WHERE " . $selection_dbt->where;
	} else 
	{
		$select_where = "";
		
	}

	
if(isset($selection_dbt->custom_query)){
	$sqlx21 = $selection_dbt->custom_query;
	$sqlx21 = str_replace("%v",$this->update_fields_where[$dfield],$sqlx21);
}
else{
	$sqlx21 = "SELECT * FROM $select_tablename $select_where;";
}	

$select_result = $this->run_query("$sqlx21");


while($select_row = $this->fetch_assoc($select_result))
  {
$option_display = $select_row["$selection_dbt->option_display"];
$option_value = $select_row["$selection_dbt->option_value"];

$options_array[$option_display] = $option_value;

  }

		
	}
else{ /// execution if database selection is not set
if((isset($display->fields->$dfieldx->values_for_select))){
	$options_array =	$display->fields->$dfieldx->values_for_select; ///array of select values	
}

}

if(!isset($this->update_fields_where[$dfield])){				
	if(isset($this->update_fields[$dfield])){
		$options_array = $this->update_fields[$dfield];
//			$this->alert("$dfield". "--xgood");
//@@@@@@@@@@@@	
	}	
}	

$not_ingroup  = "";	
	//	print_r($options_array);
		////Display selection option
		foreach ($options_array as $option => $value ) 
				{
					
				$selected = "";
				if(isset($reprint->$dfield)){
				if(in_array($value, $reprint->$dfield))
				{
					$selected = "checked=\"checked\"";
				}
				}	
					////setting for other table	
	else{			
if (isset($display->fields->$dfieldx->to_other_tr))
{
	{//otyher tr values
	$other_tr_details = $display->fields->$dfieldx->to_other_tr;
	$other_tr_table = $other_tr_details->tablename;
	$other_tr_this_column = $other_tr_details->this_column;
	$other_tr_identifier = $other_tr_details->identifier;
	$identifier_value = "";
	if(isset($updatedata->$other_tr_identifier))
	{
	$identifier_value = $updatedata->$other_tr_identifier; ///Able to access the value of any data of update column
	}
	
	$other_tr_other_column = $other_tr_details->other_column; ///array carrying other colunmnan to select data from
	$with_select_identifier =  $other_tr_other_column["$other_tr_identifier"];///// column with select value
	}

$multipleselect_result = $this->run_query( "SELECT * FROM $other_tr_table WHERE $with_select_identifier = '$identifier_value'");

if($multipleselect_result)
{
while($select_row = $this->fetch_assoc($multipleselect_result))
  {
$this_row = $select_row["$other_tr_this_column"];
$identifier_row = $select_row["$with_select_identifier"];

				if(($value == "$this_row"))
				{
					$selected = "checked=\"checked\"";
				}
		
  }
}
	
	
}	
	
	else{/////just from same table
	$raw_select = "";
	if(isset($updatedata->$dfieldx)){
	$raw_select =  $updatedata->$dfieldx;
	}
if(isset($display->fields->$dfieldx->field_separator))
				{
					$field_separator = $display->fields->$dfieldx->field_separator;
					
				}
				else
				{
					$field_separator = "+";
				//	alert("$field_separator");
				}
			
	
$array_raw_select = explode("$field_separator",$raw_select); /// set separator latter


foreach($array_raw_select as $skey => $sdata)
{

	if($sdata == $value)
		{
		//	alert("$sdata");
			$selected = "checked=\"checked\"";	
		}
}	
		
	
	
	}
		
			}
	////End setting for other table	
	
							if(isset($display->fields->$dfieldx->checked))
			{
				if(in_array($value, $display->fields->$dfieldx->checked))
				{
						$selected = "checked=\"checked\"";
				}
				
				
			}	
				$not_ingroup .= "<input type='checkbox' $selected value='$value'  $attr  style=\"$form_error_element_style\" class=\"$form_error_element_class\"   id='$id"."_value' name='$dfield"."[]' /> $option $element_separator";
				}
//}
	
	$this->input_element["$dfield"] .= "<span id='$dfield' name='$dfield'>" . $not_ingroup . "</span>";

if(isset($this->update_fields_ajax["$dfield"]))
{
	//update_current_field
	if($this->update_current_field["$dfield"] == $dfield){
	  $return_ajax	=  "<xx--xx--xx>9" . $not_ingroup . "<xx--xx--xx>9"; /// 9 (nine) for print 9 function in javascript without repitation
	echo "$return_ajax";
	die;
	}
}

		////End selection each option
		
		}
        break;
			
		case "addtypecase":
	{	

	$this->input_element["$dfield"] .= "add other contents";

	}
        break;
				
	default:
	
	{ /////Default types
	$describe_type = $this->describe_type();
if ($row[$describe_type] == "text")
{

	$reprint_value = htmlspecialchars($reprint_value);		
	$this->input_element["$dfield"] .= "	<textarea type='text'  id='$id' $ajax_update $attr  style=\"$form_error_element_style\" class=\"$form_error_element_class\"   name='$dfield'>$reprint_value</textarea>";
	$this->input_element["$dfield"] .= $auto_complete_display;		
	$this->input_element["$dfield"] .= $update_button;

}

else{
	$reprint_value = htmlspecialchars($reprint_value);			
		$this->input_element["$dfield"] .= "<input type='text' name='$dfield' $ajax_update $auto_complete_attr $attr  style=\"$form_error_element_style\" class=\"$form_error_element_class\"   id='$id' value=\"$reprint_value\" />";
		$this->input_element["$dfield"] .= $auto_complete_display;		
		$this->input_element["$dfield"] .= $update_button;
	
}

}

////End cases
}


////start wrappers
$wrapper_array = array("before_label","after_label","before_element","after_element");
foreach($wrapper_array as $wrapper_key=>$wrapper){
	if(isset($display->fields->$dfield->$wrapper)){
		$this_wrapper= $display->fields->$dfield->$wrapper;
		if(!is_callable($this_wrapper)){
		$$wrapper = $this_wrapper;
		}
		else {		
		$$wrapper = "";	
		
		$$wrapper = call_user_func($this_wrapper,$reprint_value,$dfield,$updatedata,$lang);///refresh may pass other parameters
		if(!$$wrapper){$$wrapper = "";}				
		}
	}
	else
	{
	$$wrapper = "";	
	}	
}
////end wrappers

	
//FORM ELEMENT WRITER
	if(isset($display->fields->$dfieldx->wrapper))
	{
		$custom_wrapper = $display->fields->$dfieldx->wrapper;
		
		$custom_wrapper = str_replace("%element%",$this->input_element["$dfield"],$custom_wrapper);
		$custom_wrapper = str_replace("%label%",$this->input_label["$dfield"],$custom_wrapper);
		
		$custom_wrapper = str_replace("%before_label%",$before_label,$custom_wrapper);
		$custom_wrapper = str_replace("%after_label%",$after_label,$custom_wrapper);
		
		$custom_wrapper = str_replace("%before_element%",$before_element,$custom_wrapper);
		$custom_wrapper = str_replace("%after_element%",$after_element,$custom_wrapper);
		
		$custom_wrapper = str_replace("%error_before_element%",$error_before_element,$custom_wrapper);
		$custom_wrapper = str_replace("%error_after_element%",$error_after_element,$custom_wrapper);
		
		$this->field_data["$dfield"] .= $custom_wrapper;
		
	}
		else
	{
	$this->field_data["$dfield"] .= $this->separator['row_begin'];
	
	$this->field_data["$dfield"] .= $column_textdisplay_open;
	
	$this->field_data["$dfield"] .= $before_label;


	$this->field_data["$dfield"] .= $this->input_label["$dfield"];//LABEL

	$this->field_data["$dfield"] .= $after_label;
	$this->field_data["$dfield"] .= $column_textdisplay_close;

	
	$this->field_data["$dfield"] .= $column_formdisplay_open;
	$this->field_data["$dfield"] .= $before_element;
	$this->field_data["$dfield"] .= $error_before_element;
	
	
	$this->field_data["$dfield"] .= $this->input_element["$dfield"]; ///INPUTS
		
		
		
	$this->field_data["$dfield"] .= $error_after_element;
	$this->field_data["$dfield"] .= $after_element;	
	
	$this->field_data["$dfield"] .= $column_formdisplay_close;
	
	$this->field_data["$dfield"] .= $this->separator['row_end'];
}
	
		
//END FORM ELEMENT WRITER		
	
}////End fortypes



//////////// CLASS DATA BELOW





//call_user_func_array($function_name, ///function to fire
/*
function formx ($table_name, //// database table name
		$print_form,       //wheher to print fom
		$exception, //omitted fields                                                                                          
		$sort_array, //omitted fields                                                                                          
		$display,   //// handles the display of form elemnt type and values object 
		$addtional_field,   //// extra fields not from db 
		$add_free_field,   //// extra fields not from db free
		$field_processor,   //// handles the customizes proccessors for field, verification checking to validate
		$custom_to_db, /// add addional data to database 
		$lang          /////name to display of form item HTML compatible 
	 
	 );   
*/
 //);
 

}

//// END CLASS

//$cutarray= array("faculty_shortname", "deleted", "faculty_month_added");
/////////////////////////////////////////////
////////////FIRE FUNCTIONS////////////
///////////////////////////////
//table("faculty", array("faculty_id", "deleted", "faculty_month_added"));

// PARAMETERS: call_user_func_array([PARA 1 String $fuction_name], [PARA 2 array ([PARA_2_1 String $tablename], [PARA_2_2 array_of_fields_to_omit([$column1],$colum2,....) ))
//call_user_func_array('table', array("faculty", array("faculty_id", "deleted")));
///DEFINATIONS////
$custom_to_db = array();
$display = (object) array();
$lang = (object) array();
$print_form = true;
$table_name = "";
$field_processor = (object) array();		
$sort_array = (object) array();		
$addtional_field = (object) array();		
///Goes up with the function
$faculty_shortname = "";





//////////////////////////////CLASS INPUT///////////////////






///extra values 
////custom print
$custom_content = array(	"container_open" => "<table  id='form_table'  border='1'>",
								"container_close" => "</table>",
								"row_begin" => "<tr>",
								"row_end" => "</tr>",
								"column_textdisplay_open" => "<td>",
								"column_textdisplay_close" => "</td>",
								"column_formdisplay_open" => "<td>",
								"column_formdisplay_close" => "</td>"
								);	




$ctab = array(
				"tab_start"=>"<tr><td colspan='2'>",
				"tab_end"=>"</td></tr>",
				"tab_menu_area_start"=>"<table id='tabs'><tr>",
				"tab_menu_area_end"=>"</tr></table>",
				"tab_button_start"=>"<td>",
				"tab_button_end"=>"</td>",
				"tab_body_start_element"=>"table",
				"tab_body_end_element"=>"table"
			);

////update database table
$update = (object) array	(
'set' => true,
'where' => 'faculty_id = 12334', //	222, 1233
						);


///external data
$time = time();
//'fields' => (object) array ( 
{///add field under dev $add_field
}
$add_free_field  = array( //must add proccessors   ////when $add_free_field is declared, parameter 2 must be same as that set in $display->fields->type
					array("house","customHTML"),
					array("location","color"),  /////must still be set in display to choose type
					array("place","select"),
					array("joint","textarea"),
					array("free_no_display","text"),
					array("free_no_display2","text"),
					//array("","");
						);



{$display = (object) //must be declared
array(	'foo' => 'bar',
		'property' => 'value',
		'file_must' => 0, //// check if file must be upload to allow form submission
		'file_must_error' => "All files must be uploaded", //// check if file must be upload to allow form submission
		'submit_button' => 'APPLY',
		'submit_wrapper' => "<tr><td colspan='2'><center> %submit_message% --- %submit_element% </center></td></tr>",
		//'insert_callback' => "\$this->alert(\"Data inserted Successfully\");",
		'insert_callback' => "<script>alert('insert is good');</script>",
		//'insert_failure' => "\$this->alert(\"Data insertion failed\");",
		'insert_failure' => "<script>alert('insert is bad');</script>",
		//'update_callback' => "\$this->alert(\"Update Successfully\");",
		//'update_callback' => "<script>alert('update is good');</script>",
		'update_callback' => "up_good",
		//'update_failure' => "\$this->alert(\"Update failed\" .  \$sql_error);",
		'update_failure' => "<script>alert('update is bad');</script>",
		//'update_callback' => "alert(\"vvvvvvvvvvvvv\");",
		///@@@@@@@@@@@@@@@@@@@@ Start Printing type @@@@@@@@@@@@@@@@///
		//'separator' => 'div', //div, p, floated div add class values id's
		//'separator' => 'table', //div, p,table1c floated div add class values id's
		'separator' => 'div', //div, p,custom, floated div add class values id's
		'custom_content' => $custom_content, //div, p, floated div add class values id's
		///@@@@@@@@@@@@@@@@@@@@ End Printing type @@@@@@@@@@@@@@@@///
		'insert_reprint' => true,
		'form_data' => 'alldata',
		'submit_attr' => "style=''",
		'server_validate' => true,
		'form_validate_inline' => true, //inline //list
		'form_validate_list' => true, //inline //list
		'form_validate_inline_position' => "after", //before,after      //before or after elemen
		'form_validate_each_container' => "span", //span, p, div 
		'form_validate_each_class' => "", //span, p, div 
		'form_validate_each_style' => "color:red;", //span, p, div 
		'form_validate_all_container' => "span", //span, p, div 
		'form_validate_all_class' => "", //span, p, div 
		'form_validate_all_style' => "", //span, p, div 
		'form_validate_list_class' => "", 
		'form_validate_list_style' => "color:red;margin:0px;", 
		'form_error_label_container_class' => "",
		'form_error_label_container_style' => "",
		'form_error_label_class' => "error_label_class",
		'form_error_label_style' => "color:red;",
		'form_error_element_container_class' => "",
		'form_error_element_container_style' => "",
		'form_error_element_class' => "error_class",
		'form_error_element_style' => "",
		'form_error_separator' => "",
		'custom_tab' => $ctab,
		'tabsX' => array("PERSONAL"=>'faculty_auto,faculty_auto2,faculty_sel,faculty_sel2,faculty_day_added,faculty_year_added,faculty_campus,faculty_shortname,faculty_id,faculty_note',
						"OFFICE"=>'faculty_text,faculty_files,location,faculty_logo,faculty_fullname,institution_id',
						"OTHERS"=>'house,free_no_display,free_no_display2,joint,cvupload,2ndpasswprd,faculty_code,faculty_month_added'),
		
		'update' => $update, /////UPDATE
		'stop_sql' => false, /////UPDATE
		'submit_message' => "<i>Read instructions clearfully</i>",
		'form_attr' => "",  ////// What happens on submit of form
		'form_method' => "POST",  ////// method //required /// set as defalut property
		'form_id' => "formxxx",  ////// What happens on submit of form
		'form_action' => "",  ////// What happens on submit of form
		'extra_data' => array("time"=>"time","xxtime"=>"time","extra"=>"404"),  ///string or callablles as "time" is a callable
		'fields' => (object) array ( 'column1'=> 'money', //checkiing
								'column2' => 'column4',	//checkiing
						
		'faculty_month_added' => (object) array ( 	'type'=> 'multipleselect', //checkbox, multipleselect			
													'attr' => "",
													'before_label' => 'before label',
													'after_label' => 'after label',
													'before_element' => 'before element',
													'after_element' => 'after element',
													'element_separator' => '<br />', ///HTML TAG
													'values_for_select' => array(	"January" => "1", ////The key is displayed & value = value
																					"February" => "2",
																					"March" => "3",
																					"April" => "4",
																					"May" => "5",
																					"June" => "6",
																					"July" => "7",
																				),/*
													'to_other_tr' => (object) array(	"tablename" => "othertr", ////The key is displayed & value = value
																						"this_column" => "insid",
																						"identifier"  => "faculty_shortname", ///because of updating
																						"other_column" => array(	'faculty_shortname' => "ffn", ////The key is displayed & value = value
																													'xxtime' 	=> "time" ////set in extra data if vairable is not a name of form item sent
																												)
																					),*/
													'field_separator'	=> "@",

												//	'selected'	=> array('2','3'),
												'optgroup' => array('Early' => '3,4', 'Middle' => '6,7', 'Late'=> '5')
												),	
		'faculty_code' => (object) array ( 	'type'=> 'checkbox', //checkbox, multipleselect							
													'values_for_select' => array(	"January" => "1", ////The key is displayed & value = value
																					"February" => "2",
																					"March" => "3"
																				),
													'to_other_tr' => (object) array(	"tablename" => "othertr", ////The key is displayed & value = value
																						"this_column" => "insid",
																						"identifier" => "faculty_id", ///unchange variable
																						"other_column" => array(	'faculty_id' => "ffn", ////The key is displayed & value = value
																													'xxtime' 	=> "time" ////set in extra data if vairable is not a name of form item sent
																												)
																					),
													'element_separator' => '<br />', ///HTML TAG
												///	'checked' => array('2','3')
												),
		'faculty_note' => (object) array ( 	'type'=> 'checkbox', //checkbox, multipleselect							
													'values_for_select' => array(	"January" => "1", ////The key is displayed & value = value
																					"February" => "2",
																					"March" => "3"
																				),
													'to_other_tr' => (object) array(	"tablename" => "othertr2", ////The key is displayed & value = value
																						"this_column" => "insid",
																						"identifier" => "faculty_id", ///unchange variable
																						"other_column" => array(	'faculty_id' => "ffn", ////The key is displayed & value = value
																													'xxtime' 	=> "time" ////set in extra data if vairable is not a name of form item sent
																												)
																					),
													'element_separator' => '<br />', ///HTML TAG

												///	'checked' => array('2','3')
												),	
		'free_no_display' => (object) array ( 	'type'=> 'text', //checkbox, multipleselect		
												'ValidateAsInteger' => true,
											//	'ValidateAsIntegerErrorMessage' => "@name @label @value is not an integer",
												'ValidateAsIntegerErrorMessage' => "not a whole number",		
													'values_for_select' => array(	"January" => "1", ////The key is displayed & value = value
																					"February" => "2",
																					"March" => "3"
																				),
													'to_other_tr' => (object) array(	"tablename" => "othertr3", ////The key is displayed & value = value
																						"this_column" => "insid",
																						"identifier" => "faculty_id", ///unchange variable
																						"other_column" => array(	'faculty_id' => "ffn", ////The key is displayed & value = value must be same as identifier
																													'xxtime' 	=> "time" ////set in extra data if vairable is not a name of form item sent
																												)
																					),
													'element_separator' => '<br />', ///HTML TAG
												///	'checked' => array('2','3')
												),
		'free_no_display2' => (object) array ( 	'type'=> 'text', //checkbox, multipleselect		
												'ValidateAsEmail' => true,
											//	'ValidateAsIntegerErrorMessage' => "@name @label @value is not an integer",
												'ValidateAsEmailErrorMessage' => "not a valid email",		
													'values_for_select' => array(	"January" => "1", ////The key is displayed & value = value
																					"February" => "2",
																					"March" => "3"
																				),
													'element_separator' => '<br />', ///HTML TAG
												///	'checked' => array('2','3')
												),													
		'institution_id' => (object) array ( 	'type'=> 'select',
												'id' => 'insssss',		
								/*
													'values_for_select' => array(	"1" => "1",
																					"2" => "2",
																					"3" => "3",
																					"4" => "4"
																				), */
													'from_dbtable' => (object) array('tablename'=>'institution',//to select from database
																					//'column'=>'department_id',//fom
																					//'where'=>"",//selection to return add double slashes
																					'where'=>"institution_id>1",//selection to return add double slashes
																					'option_display'=>'institution_fullname',
																					'option_value'=>'institution_id',
																					
																					
																					) 
												),
		'faculty_year_added' => (object) array ( 	'type'=> 'radio', 
								
													'values_for_select' => array(	"1" => "1",
																					"2" => "2",
																					"3" => "3",
																					"4" => "4"
																				),
													'from_dbtable' => (object) array('tablename'=>'institution',//to select from database
																					//'column'=>'department_id',//fom
																					'where'=>"institution_id>1",//selection to return add double slashes
																					'option_display'=>'institution_fullname',
																					'option_value'=>'institution_id',
																					
																					
																					) 
												),
		
		'faculty_shortname' => (object) array ( 	'type'=> 'radio', 
								
													'values_for_select' => array(	"100" => "1",
																					"200" => "2",
																					"300" => "3",
																					"587" => "379",
																					"586" => "372",
																					"526" => "172",
																					"402" => "124",
																					"480" => "453",
																					"490" => "194",
																					"40f" => "4de",
																					"40c" => "984",
																					"400" => "4d",
																			),
												//	'checked' => array('1'),
													'ValidateAsInteger' => true,
												//	'ValidateAsIntegerErrorMessage' => "@name @label @value is not an integer",
													'ValidateAsIntegerErrorMessage' => "not an integer",
													'element_separator' => '<br />', ///HTML TAG
												),
		'faculty_fullname' => (object) array ( 	
												'type'=> 'text', 									
											//	'type'=> 'customPHP', 
												'content'=> " echo \" <h1>\$print_lang YEYOOO PHP</h1>\";",  ///use "" for string   data field: $dfield or $print_lang
												//'custom_validation' => array()
												'set_mod2' => "modify",////name of function
												'preprocessor' => "preprocessor",////name of a function
												'CustomValidate' => "cval",
												'CustomValidateErrorMessage' => "(CUSTOM)input was not valid",
												'before_label' => 'blabel',
												'after_label' => 'alabel',
												'before_element' => 'belement',
												'after_element' => 'aelement',	
												
												),	
'faculty_logo' => (object) array ( 	
												'type'=> 'multiplefile',
												'max_size'=> '500000000', ///file size ib bytes
												'folder'=>'downloads', ///folder to move file too
												'file_type'=>'', //// comment if not an image or remove rule
												'proccessor'=>'filep', //// comment if not an image or remove rule
										 
										//		'type'=> 'customPHP', //'customHTML',customisizing
										//		'rename_rule'=> $faculty_shortname, 
										//		'rename_rule'=> time(). "falculty", 
													'before_element' => 'preview_image',
													'after_element' => 'after element',
												
												'phprequired'=> "",  /////if decleared block insert
									//			'overwrite'=> "1", ////set one not to overwrite
												'content'=> " echo \" <h1>\$print_lang YEYOOO PHP</h1>\";",  ///use "" for string   data field: $dfield or $print_lang
												'to_other_tr' => (object) array(	"tablename" => "othertr4", ////The key is displayed & value = value
													"this_column" => "insid",
													"identifier" => "faculty_id", ///unchange variable
													"other_column" => array(	'faculty_id' => "ffn", ////The key is displayed & value = value must be same as identifier
																				'xxtime' 	=> "time" ////set in extra data if vairable is not a name of form item sent
																			)
												),		
												),	
'faculty_files' => (object) array ( 	
												'type'=> 'multiplefile',
												'max_size'=> '500000000', ///file size ib bytes
												'folder'=>'downloads', ///folder to move file too
												'file_type'=>'', //// comment if not an image or remove rule
										 
										//		'type'=> 'customPHP', //'customHTML',customisizing
										//		'rename_rule'=> $faculty_shortname, 
												'rename_rule'=> time(). "falculty", 
												
												'phprequired'=> "",  /////if decleared block insert
									//			'overwrite'=> "1", ////set one not to overwrite
												'content'=> " echo \" <h1>\$print_lang YEYOOO PHP</h1>\";",  ///use "" for string   data field: $dfield or $print_lang

												),												
'faculty_text' => (object) array ( 	
													'type'=> 'text',
													'ValidateAsDate' => true, //ValidateAsDate, ValidateAsEmail, ValidateAsFloat, ValidateAsInteger
													'ValidateAsDateErrorMessage' => "not_date",
												//	'ValidateAsFloat' => 'true',
													'DateSeparator' => "-"	//no slash (/)fix letter
													),	
	'faculty_campus' => (object) array ( 	
													'type'=> 'file',
													'max_size'=> '500000000', ///file size ib bytes
													'folder'=>'downloads', ///folder to move file too
													'file_type'=>'', //// comment if not an image or remove rule
											 
												//	'type'=> 'customPHP', 'customHTML',customisizing
													'rename_rule'=> $faculty_shortname, 
													'rename_rule'=> time(). "falculty" . "campus", 
													'file_error'=> "THIS FILE MUST  BE UPLOADED!", 
													
													'phprequired'=> "",  /////if decleared block insert
										//			'overwrite'=> "1", ////set one not to overwrite
													'content'=> " echo \" <h1>\$print_lang YEYOOO PHP</h1>\";"  ///use "" for string   data field: $dfield or $print_lang
														
													),	

'house' => (object) array ( 	
											//	'type'=> 'number', 									
												'type'=> 'customHTML', 
												'content'=> "HELLO",  ///use "" for string   data field: $dfield or $print_lang
												//'ValidateAsDate' => 1,	
												),	
'location' => (object) array ( 	
												'type'=> 'color', 									
											//	'type'=> 'customPHP', 
												'content'=> " echo \" <h1>\$print_lang YEYOOO PHP</h1>\";"  ///use "" for string   data field: $dfield or $print_lang
													
												),													
'place' => (object) array ( 	
													'type'=> 'select', 
								
													'values_for_select' => array(	"1" => "1",
																					"2" => "2",
																					"3" => "3",
																					"4" => "4"
																				),
												),
'faculty_sel' => (object) array ( 	
													'type'=> 'select', 
								
													'values_for_select' => array(	"one" => "1",
																					"two" => "2",
																					"three" => "3",
																					"four" => "4"
																				),
													'to_update' => 'faculty_sel2,2ndpasswprd',
													'to_update_event' => 'onchange',
													'to_update_button' => '&gt;&gt;'
												),
'faculty_sel2' => (object) array ( 	
												//	'type'=> 'multipleselect', 
													'type'=> 'radio', 
											//		'to_update' => 'faculty_sel2,2ndpasswprd',
													'to_update' => '',
													'to_update_event' => 'onclick',
													'to_update_button' => '&gt;&gt;',	
													'values_for_select' => array("Select"=>"none"),
													'update_values' => 	array(	"1" => array(	"1one" => "11",
																									"1two" => "21",
																									"1three" => "31",
																									"1four" => "41"
																								),
																					"2" => array(	"2one" => "12",
																									"2two" => "22",
																									"2three" => "32",
																									"2four" => "42"
																								),
																					"3" => array(	"3one" => "13",
																									"3two" => "23",
																									"3three" => "33",
																									"3four" => "43"
																								),
																					"4" => array(	"4one" => "14",
																									"4two" => "24",
																									"4three" => "34",
																									"4four" => "44"
																								)																				
																			),
													'update_from_dbtable' => (object) array('tablename'=>'institution',//to select from database
																					//'column'=>'department_id',//fom
																					'where'=>"institution_id>=%v",//selection to return add double slashes
																					'option_display'=>'institution_fullname',
																					'option_value'=>'institution_id',																					
																					'custom_query'=>'select * from institution where institution_id>=%v',																					
														
																					) 													
													
												),													
'faculty_auto' => (object) array ( 	
												'type'=> 'text',
												'attr' => "autocomplete='off'",
												'auto_complete' => true,
												'search_type'=> 'start', /// values = start,within
											//	'suggestions' => "<b>car</b>,alarm<input type='submit' value='null' style='color:red; background:none;border:none;'>,trigger,fire,ban,ear,fan",
												'suggestions' => array("car"=>"Car","horse"=>"Horse"),
												//'suggestion_box_id' => "jhjhkjh",
												'button_text' => "..>..",
												'db_suggestion' => array(
																	'tablename'=>'institution',
																	'value_column'=>'institution_fullname',
																	),
												'wrapper' => "<tr><td colspan='2'> %label% -- %element% </td></tr>",					
/*												'to_update' => 'faculty_sel2,2ndpasswprd',
												'to_update_event' => 'onkeyup',
												'to_update_button' => '&gt;&gt;',
												'not_found_message' => 'No records found'				
*/												
												),
'faculty_auto2' => (object) array ( 	
												'type'=> 'text',
												'attr' => "autocomplete='off'",
												'auto_complete' => true,
												'search_type'=> 'start', /// values = start,within
												'suggestions' => "apple,ball,cat,dog,ram,fan,xamp,php",
												//'suggestion_box_id' => "jhjhkjh",
												'button_text' => "..>..",
												'db_suggestion' => array(
																	'tablename'=>'institution',
																	'value_column'=>'institution_fullname',
																	),
/*												'to_update' => 'faculty_sel2,2ndpasswprd',
												'to_update_event' => 'onkeyup',
												'to_update_button' => '&gt;&gt;',
												'not_found_message' => 'No records found'				
*/												
												),												
'joint' => (object) array ( 	
												'type'=> 'textarea', 									
											//	'type'=> 'customPHP', 
												'content'=> " echo \" <h1>\$print_lang YEYOOO PHP</h1>\";",  ///use "" for string   data field: $dfield or $print_lang
												'set_mod2' => "modify"	
												),
													
'faculty_day_added' => (object) array ( 	'type'=> 'select',
												//'code'=> '' ///Code to be executed could unset other objects
													'values_for_select' => array(	"SELECT" => "",
																					"ONE" => "1",
																					"TWO" => "2",
																					"THREE" => "3",
																					"FOUR" => "4",
																					"FIVE" => "5",
																					"SIX" => "6",
																					"SEVEN" => "7",
																					"EIGHT" => "8",
																					"NINE" => "9",
																					"TEN" => "10",
																				),
												//	'selected' => array('4'),
													'optgroup' => array('Early' => '3,4,5', 'Middle' => '6,7', 'Late'=> '9,10')
												)
	)
	);
}


{$addtional_field = (object) array(/////addition form item not from f\databases tables  ///Adds to existion form items 
	'fields' => (object) array (
'faculty_shortname' => (object) array ( 	
						'newfield'=> '2ndpasswprd',				
						'type'=> 'checkbox', 
						'position'=> 'before', ////position before, after
						'values_for_select' => array(	"Jatttt" => "1", ////The key is displayed & value = value
														"February" => "2",
														"March" => "3"
													),
					//	'to_update' => 'faculty_sel2,2ndpasswprd',
						'to_update' => '',
						'to_update_event' => 'onclick',
						'to_update_button' => '&gt;&gt;',							
						'update_values' => 	array(	"1" => array(	"x1one" => "11",
																		"x1two" => "21",
																		"x1three" => "31",
																		"x1four" => "41"
																	),
														"2" => array(	"x2one" => "12",
																		"x2two" => "22",
																		"x2three" => "32",
																		"x2four" => "42"
																	),
														"3" => array(	"x3one" => "13",
																		"x3two" => "23",
																		"x3three" => "33",
																		"x3four" => "43"
																	),
														"4" => array(	"x4one" => "14",
																		"x4two" => "24",
																		"x4three" => "34",
																		"x4four" => "44"
																	)
																	
												),
						'Xupdate_from_dbtable' => (object) array('tablename'=>'institution',//to select from database
														//'column'=>'department_id',//fom
														'where'=>"institution_id>=%v",//selection to return add double slashes
														'option_display'=>'institution_fullname',
														'option_value'=>'institution_id',																					
														'custom_query'=>'select * from institution where institution_id>=%v',																												
														) 												
					//	'selected' => array('3')
													
							),
'faculty_logo' => (object) array ( 	
												'type'=> 'textarea',
												'newfield'=> 'cvupload',
												'max_size'=> '500000000', ///file size ib bytes
												'folder'=>'downloads', ///folder to move file too
												'file_type'=>'', //// comment if not an image or remove rule										 
											//	'type'=> 'customPHP', 'customHTML',customisizing
										//		'rename_rule'=> $faculty_shortname, 
												'rename_rule'=> time(). "falculty", 
												'phprequired'=> "",  /////if decleared block insert
									//			'overwrite'=> "1", ////set one not to overwrite
												'content'=> " echo \" <h1>\$print_lang YEYOOO PHP</h1>\";"  ///use "" for string   data field: $dfield or $print_lang
													
												),							
							
							
	)
	
	
	
	);
}
	
	////form data
	function alldata($arr,$sql_array){
	//	echo "<h1>" . $arr['faculty_campus']['tmp_name'] . "</h1>";
	if(file_exists($arr['faculty_campus']['tmp_name'])){	
	$imgString = file_get_contents($arr['faculty_campus']['tmp_name']);
	$image = imagecreatefromstring($imgString);
	imagejpeg($image, "newpic.jpg", 100);
	}
	else{
		return false;
	}

		echo "<hr />";
		print_r($arr);
		
	}
	
	
	
	
	///for set modifier
	function modify($value,$arr){
		//$value = $name;
		echo "<h1>all is well </h1> " . $arr["faculty_shortname"];
		//return strtoupper($value);
		return $value / 2;
		//return $arr['time']; can modify with the extra_data
		//return false;
		
	}

	///for set preprocessor
	function preprocessor($value,$name,$udata){
		//$value = $name;
	
		return $value * 2 . "---" . $udata->faculty_id;
		//return false;
		
	}
	///callback for date validation error
	function not_date($name,$value,$udata,$lang){
		//$value = $name;
	
		return $value . " is not a date";
		//return false;
		
	}	
	
	///element wrapper
	function belement($value,$name,$udata,$lang){
		$cons = "";
		if(isset($udata->faculty_id)){
			$cons  = $udata->faculty_id;
		}
			$lang  = $lang->$name;
		$before = "B4[$value]($name)--$cons --$lang";
	
		return $before;
		//return false;
		
	}		
	///element wrapper
	function preview_image($value,$name,$udata,$lang){
//			$cons  = $udata->faculty_id;
//			$lang  = $lang->$name;
$img_name_array = explode(",",$value);
$before = "";
			foreach($img_name_array as $key=>$val){
			$before .= "<img src='downloads/$val' width='100px' border='1' /> ";	
			}

//		$before = "<b>$value</b>";
	
		return $before;
		//return false;
		
	}	
	
	/////for custom validation
	function cval($value,$arr){
			//$value = $name;
		if($value>3)
			return true;
		else
			return false;
			//return false;
		
	}
	////for field_processor
	function fpro($name,$value,$arr,$sql_array){ /////pass all 4 parameter variables
		echo "!!!!!!0000!!!!!!!$name -- $value";
		$sql_array["$name"] = $value;
	//	$sql_array = array(4,0,5);
	//	return true;
	//	return $sql_array;		//return to perform sql function
	}

		function filep($name,$value,$arr,$sql_array){ /////pass all 4 parameter variables FILE PROCCESSOR
		echo "<h1>XX FILE $name -- $value </h1>";
		$sql_array["$name"] = $value;
		echo $_FILES[$name]['tmp_name'];
		echo $_FILES[$name]['size'];
	//	$sql_array = array(4,0,5);
	//	return true;
	//	return $sql_array;		//return to perform sql function
	if($_FILES[$name]['error'] == 4){
	//		return false;
	}
	
	}
	
		////for field_processor
	//udate callback	
	function up_good(){
		echo "<script>alert('update is working well');</script>";
	}	
		
	///custom data to add to db
//"" => "",

//$print_form = true;

//$exception = array("faculty_id", "deleted", "faculty_time_todb");

//// additional data to db
$faculty_id = rand(1000,9000);
$time = time();
///

{///custom sending the data to database


$custom_to_db = array("faculty_time_todb" => "$time");
//$custom_to_db = array("faculty_id" => "$faculty_id", "faculty_time_todb" => "$time");

}


{// lang : display label of form items
	$lang =  (object) array( 

'faculty_fullname' => 'FACULTY FULL NAME',
'faculty_shortname' => 'FACULTY SHORT NAME',
'faculty_text' => '@wf-name',
'institution_id' => '-u@@u-name',  //-u@ starts a string to replace underscore with space
'cvupload' => '<b>CV UPLOADER</b>',
'2ndpasswprd' => '<b>Verify password</b>',
'faculty_logo' => '<b>FACULTY LOGO</b>',
'joint' => "<b>@name--joint@u-name@l-name@uf-name@lf-name</b>"  ////test string replace @name with name 
						);
}


{////processor of input items


	$field_processor = (object) array(
	/*'faculty_fullname' => (object) array(
					'content' => "
					echo \" <h1>\$name - \$value </h1> \";
					if (\$value != \"#000000\")
					{
						echo \" <h1>incorrect</h1> \";	
					//	\$allow_sql +=1;
					}
					else{
						echo \" <h1>correct</h1> \";	
					}
					///////addiing proccessor to database
					\$column_sql_value .= \"\$name,\";
					\$row_sql_value .= \"'\$value',\";									
					"
										), */
'house' => (object) array('content' => "
					echo \" <marquee>\$name - \$value \$faculty_description</marquee> \";
				
					///////addiing proccessor to database
													
					"
										),
'location' => (object) array(), //////use undesrscore for spaced values
'2ndpasswprd' => (object) array(),
'place' => (object) array(),	
'faculty_campus' => 'filep',	
'joint' => "fpro",				///string as callable and object array as eval() function if source code is known						
'cvupload' => (object) array(),  //for all additional field is recommened to add at least a blank proccessor
//'free_no_display' => (object) array(),															
										);		
}



$tablename = "faculty";
$exception = array("deleted", "faculty_time_todb");
$sort_array = array(/*'location',*/'faculty_auto','faculty_auto2','faculty_sel','faculty_sel2','faculty_logo','faculty_description','faculty_campus','institution_id','faculty_code','faculty_day_added','faculty_year_added');


/*
$form = new formgen();
$form->formx($tablename, $print_form, $exception,$sort_array, $display, $addtional_field,$add_free_field,$field_processor, $custom_to_db, $lang);
$renderer = $form->renderer;
//form_render($caster);
$form->form_render($renderer);
*/



//echo "<hr />";
 //rename_function('run_query', 'new_name');

class formgen2 extends formgen {}
 $display->form_id = "zzzz";
$form2 = new formgen2();

$form2->separator = $custom_content;
$form2->tab_separator = $ctab;
$display->separator = "custom";

$form2->db_type = "mysqli";
$form2->db_host = "localhost";
$form2->db_port = "3306";
$form2->db_name = "sms";
//$form2->db_name = "sqlite-database.db";
$form2->db_username = "root";
$form2->db_password = "";
$form2->formx($tablename, $print_form, $exception,$sort_array, $display, $addtional_field,$add_free_field,$field_processor, $custom_to_db, $lang);
$renderer2 = $form2->renderer;
//form_render($caster);
$form2->form_render($renderer2);

?>
<script>
//,stag
function loadFieldUpdate(sname,svalue,stag_data,not_found_message)
{

	stag_array = stag_data.split(",");
//	alert("working ajax");

	for(xvalueKey in stag_array){

stag = stag_array[xvalueKey];
//	alert(stag);
	
	if(svalue != ""){
	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();

	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

	  }
	  

	  
	  
	xmlhttp.onreadystatechange=function(){
		  	 	  	
		 if (xmlhttp.readyState==1)
	  {
	// document.getElementById("myDiv").innerHTML="Loading.....";
	 // alert(7777);

	  } 
	  

	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
	  deli = 4+5;  
		response_text_array = xmlhttp.responseText.split("<xx--xx--xx>" + deli);
	//	alert(response_text_array.length);
		if(response_text_array[1] == undefined){
		document.getElementById(stag).innerHTML = not_found_message;	
		}
		else
		{
		document.getElementById(stag).innerHTML=response_text_array[1];
		}
		
		document.getElementById('seeresult').value=response_text_array[1];
		
		//	sleep(1000);
//	alert(stag);
		}
	  }
url = window.location.href;
	//change this url to same script
xmlhttp.open("POST",url,false);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded;charset=UTF-8");

	req = "update_submit_button=" + sname + "&update_ajax_request=" + sname + "&update_current_field=" + stag + "&" + sname + "=" + svalue;

//alert(stag);
	xmlhttp.setRequestHeader("Content-length", req.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(req);

	}

}
}

</script>

<script>
//,stag
function autoCompleteField(sname,svalue,stag,not_found_message)
{
//	stag_array = stag_data.split(",");
//	alert("working ajax");
//	for(xvalueKey in stag_array){
	
//stag = stag_array[xvalueKey];
//	alert(stag);
	
	if((svalue != undefined) || (svalue != null)){
	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  

	  
	  
	xmlhttp.onreadystatechange=function()
	  {
		 if (xmlhttp.readyState==1)
	  {
	 document.getElementById(stag).innerHTML="Loading...";
	 // alert(7777);
	  } 
	  
	  
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
	  deli = 4+2; 
//<auto_complete-response>6	  
		response_text_array = xmlhttp.responseText.split("<auto_complete-response>" + deli);
	//	alert(response_text_array.length);
		if(response_text_array[1] == undefined){
		document.getElementById(stag).innerHTML = not_found_message;	
		}
		else
		{
		document.getElementById(stag).innerHTML=response_text_array[1];
		}
		
		document.getElementById('seeresult').value=response_text_array[1];
		
		//	sleep(1000);
//	alert(stag);
		}
	  }

	url = window.location.href;
	//change this url to same script
	xmlhttp.open("POST",url,true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded;charset=UTF-8");
	req = "auto_complete_button=" + sname + "&auto_complete_request=" + sname + "&auto_complete_value=" + svalue;

//alert(stag);
	xmlhttp.setRequestHeader("Content-length", req.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(req);

	}

//}
}

</script>
<script>
	function sleep(milliSeconds){
		var startTime = new Date().getTime(); // get the current time
		while (new Date().getTime() < startTime + milliSeconds); // hog cpu
	}
</script>
<input id='seeresult' name='see' />
<?


echo "</body></html>";


/*
////EXTENDED sql EXAMPLE
{
class formgen3 extends formgen{
	
function con(){
		return mysqli_connect($this->db_host,$this->db_username,$this->db_password,$this->db_name);	
}

function run_query($query){
			$con = $this->con();	
			mysqli_real_escape_string($con,$query);
			return mysqli_query($con,$query);			
}
	
function fetch_assoc($results){
			$con = $this->con();
			return mysqli_fetch_assoc($results);	
	
}

function multi_query($query){
		$con = $this->con();
		mysqli_real_escape_string($con, $query);
		return mysqli_multi_query($con, $query);
	
}

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


	
}
$form3 = new formgen3();
$form3->db_type = "mysqli";
$form3->db_name = "sms";
$form3->formx($tablename, $print_form, $exception,$sort_array, $display, $addtional_field,$add_free_field,$field_processor, $custom_to_db, $lang);
$renderer3 = $form3->renderer;
//form_render($caster);
$form3->form_render($renderer3);


}
//////END sql EXTENDED EXAMPLE
*/

/*
////EXTENDED POSTGRES sql EXAMPLE
{
class formgen3 extends formgen{
	
function con(){
		$dbconn3 = pg_connect("host=localhost port=5432 dbname=sms user=postgres password=root");
		return $dbconn3;
}

function run_query($query){
			$con = $this->con();			
			return pg_query($con,$query);			
}
	
function fetch_assoc($results){
			$con = $this->con();
			return pg_fetch_assoc($results);	
	
}

function multi_query($query){
		$con = $this->con();
		return pg_query($con, $query);
	
}

function describe_table($tablename) {
			$table_des_sql = "select column_name, data_type, character_maximum_length from INFORMATION_SCHEMA.COLUMNS where table_name = '$tablename';";
			return $table_des_sql;

}

function describe_fname() {
			$describe_fname = "column_name";
			return $describe_fname;
}

function describe_type() {
		$describe_type = "data_type";
		return $describe_type;

}


	
}
$form3 = new formgen3();
$form3->db_type = "mysqli";
$form3->db_name = "sms";
$display->update->where = "faculty_id = 1";

$form3->formx($tablename, $print_form, $exception,$sort_array, $display, $addtional_field,$add_free_field,$field_processor, $custom_to_db, $lang);
$renderer3 = $form3->renderer;
//form_render($caster);
$form3->form_render($renderer3);


}
//////END sql EXTENDED EXAMPLE
*/

/*
//fileupload processor 
public $db_host = "localhost";
var $db_type = "mysqli";
//public $db_type = "sqlite3";
//public $db_name = "sqlite-database.db";
public $db_name = "sms";
public $db_username = "root";
public $db_password = "";
*/


 {//NOTES FOR DEVELOPMENT
 /////check data type verification
 
 
 ///// may call data be post processing to check variousdata types
 ////allow html or not strip tag mysql_real_escape_string
 ////max length to inputs
 /////selected to select
 
 
 ////multiple select

///////////////////////////////////////// SUBMIT DISPLAYS
 
 ////convert function to object arrays 
 ///dev alorithm for multiple check boxes ---- Optional all check in one field in bd or table
 ///implement all input types color, range, file
 ///add addtional form input after while
 ///CAPTCHA
 
 
 //// addtional execution after SUBMIT
 ////form type POST GET MULTIPART
 ///image resizer 
 ////MAX INPUT LIMIT
 ///EDIT IMAGE UPLOADER
 ////add custume execution before insert sql exec()
 ///add calender date time
 ///post input, pre input post, form pre, form form
 ///Display or not to display entire form 
 /////SET SEPERATOR TYPE hr , br , table, div
 /////check custom_to_db check in array ***** remove if invalid 
 //////erro massage per input
 ////redisplay form
 
 ///////FULL DIRECTORY PARSING fileupload.php
 
 ///onclick js in forms
 
 ////add image previewer to type if file
 
 ///>>>>>>File upload management and processing 
 /*
 perform certain file operations and
 such as move_uploaded_file
 remane file add other content 
 for image add different image proccessors
 use eval() for each input value
 */
 
/* Replacement object array 

Will check if(isset($raname->display))

replace all display with 

$dfield

$dfieldr else = $dbfield

*/
 
 }//// END NOTE


///////////////////////////////////////
///////////END FIRE FUNCTIONS/////////////
///////////////////////////
//print_r($field_data);
/////RENDERING


////xxxx
/////xxx
/////END RENDERING
//echo "lions";
 //$caster = $renderer;
//form_render($caster);
//form_render($renderer);
//echo $renderer['location'];
//echo $renderer['house'];

?>

<?php die; ?>
