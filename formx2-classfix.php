<html>
<head>
<script src="jquery.js"></script>
</head>
<body>
<style>
.active_tab {background:grey;width:100px; text-align:center;	border-top-left-radius: 1em;border-top-right-radius: 1em;}

.inactive_tab {background:blue;width:100px; text-align:center;	border-top-left-radius: 1em;border-top-right-radius: 1em;}

.each_tab {background:grey;}

.inactive_tab:hover {background:grey;width:100px; text-align:center;}
#tabs {margin:0px;}
#tabs td {border:0px;padding:0px;}
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
////END RENDER FORM ITEMS	
public $separator = array();
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



///////Simple INSERTION Form Generator from database table////////
////// add form type later
function formx($tablename, $print_form, $exception,$sort_array, $display, $addtional_field,$add_free_field,$field_processor, $costume_to_db, $lang)
{

//include("connectdb.php");
//$dataclass = new databasecon;
//$this->run_db();

$reprint =  (object) array();


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
				if(!$validate_error){$value = $xvalidate_error;}	
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
				if(!$validate_error){$value = $xvalidate_error;}	
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
				if(!$validate_error){$value = $xvalidate_error;}	
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
				if(!$validate_error){$value = $xvalidate_error;}	
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
				if(!$validate_error){$value = $xvalidate_error;}	
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
			
				$oarrayvalue = $value; 
				//$update_othertr = "";	
				
				///SQL for updation
				$this_arrayupdate = "UPDATE $othertablename SET $update_othertr ". "$this_data_column = '$oarrayvalue' " ." WHERE $other_tr_key='$update_data_pre';";
				$arrayupdate_sql .= $this_arrayupdate;
				
				///SQL for insertion		
				//$arrayinsert_sql .= "INSERT INTO $othertablename ($ocolumn_sql_value) VALUES ($orow_sql_value"."'$oarrayvalue');";
				
				$arrayinsert_sql .= $this_arrayupdate;
				

						
					
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
				$this_arrayupdate = "UPDATE $othertablename SET $update_othertr ". "$this_data_column = $oarrayvalue " ." WHERE $other_tr_key='$update_data_pre';";
				$arrayupdate_sql .= $this_arrayupdate;

				
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
$max_size = 5000000;
}
	
if ($_FILES["$name"]["size"] > $max_size) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}



///////UPLOAD FILE DIR AFTER QUERY IS SUCCESFULL to in string arrays and eval

	
	
	if ( $uploadOk !=0 && move_uploaded_file($_FILES["$name"]["tmp_name"], $target_file)) {
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
				$this_arrayupdate = "UPDATE $othertablename SET $update_othertr ". "$this_data_column = '$oarrayvalue' " ." WHERE $other_tr_key='$update_data_pre';";
				$arrayupdate_sql .= $this_arrayupdate;
				
				///SQL for insertion		
				//$arrayinsert_sql .= "INSERT INTO $othertablename ($ocolumn_sql_value) VALUES ($orow_sql_value"."'$oarrayvalue');";
				
				$arrayinsert_sql .= $this_arrayupdate;
				

						
					
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
		
	$reprint->$name .= $name. ",";		

		if(isset($display->fields->$name->type)){
		
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
$max_size = 5000000;
}
	
if ($_FILES["$name"]["size"][$f] > $max_size) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

	
	if ( $uploadOk !=0 && move_uploaded_file($_FILES["$name"]["tmp_name"][$f], $target_file)) {
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
			
			if (!($newnamedb_files == "")){ // allow blank field replace //check for empty files in update
			if(in_array($name,$all_db_field_array)){	
			
			$row_sql_value = substr($row_sql_value, 0, -1);
			$newnamedb_files = substr($newnamedb_files, 0, -1);
			
				$row_sql_value .= "',";
				$sql_array["$name"] = $newnamedb_files;				
			}
			}
	
	//	echo "<hr /><pre>";	
	//	print_r($_FILES);
	//	echo "</pre><hr />";
		
		

		
	//	$this->alert("multiple file detected");*
	} ///END execute multiple file codes here
		}
}
////////////// END FILEUPLOAD MANAGER /////////




//	echo "<br />";
	echo "<br />";
///Trims out the comma(",") from the $column_sql_value and 	$row_sql_value
//$costume_to_db

//////////////////////////////////////
/////////////////////////////////////
//print_r($costume_to_db);
////////////////////////////////////
/////////////////////////////////////
if(!(isset($_FILES))){
$uploadOk = "1";

}

/////ADDDING COSTUME TO DB/////
foreach($costume_to_db as $costume_column => $costume_value)
{
	$column_sql_value .= "$costume_column,";
	////where to replace the values we wish
	$row_sql_value .= "'$costume_value',";
	$sql_array["$costume_column"] = $costume_value;
	//echo "$costume_column => $costume_value <br />";
}

/////END ADDDING COSTUME TO DB/////

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

if(isset($display->filemust))
{	if($display->filemust == 0)
{
	$uploadOk = "1";
}}



		
$allow_sql += $this->allow_sql;

if($uploadOk !=0 && $allow_sql ==0){
	
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
		
			eval($display->update_callback);
			$this->update_success =1;
		
		}
		}
		else {
		
		
		if(isset($display->insert_callback)){
			
			eval($display->insert_callback);
		}
									}
		}
									else {
		
		
		if(isset($display->insert_callback)){
			
			eval($display->insert_callback);
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
					$reprint = (object) array();
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
			eval($display->update_failure);
		}
									}
			else {
		
		
		if(isset($display->insert_failure)){
			
			eval($display->insert_failure);
		}
									}
	
		}
			else {
		
		if(isset($display->insert_failure)){
			
			eval($display->insert_failure);
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
		
		////failures
		if(isset($display->update)){
		if(($display->update->set == true )){
		if(isset($display->update_failure)){			
			eval($display->update_failure);
		}
									}
			else {
		
		
		if(isset($display->insert_failure)){
			
			eval($display->insert_failure);
		}
									}
	
		}
			else {
		
		if(isset($display->insert_failure)){
			
			eval($display->insert_failure);
		}
									}
								
		////End failures	
	
}
	
	





	
		
		}
else
{
	///////If post IS NOT SET
	
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
if(isset($display->server_validate_each_container)){
$server_validate_each_container = 	$display->server_validate_each_container;
}
else{
$server_validate_each_container = "p";
}

if(isset($display->server_validate_all_container)){
$server_validate_all_container = 	$display->server_validate_all_container;
}
else{
$server_validate_all_container = "div";
}

if(isset($display->server_validate_each_class)){
$server_validate_each_class = 	$display->server_validate_each_class;
}
else{
$server_validate_each_class = "";
}

if(isset($display->server_validate_all_class)){
$server_validate_all_class = 	$display->server_validate_all_class;
}
else{
$server_validate_all_class = "";
}

if(isset($display->server_validate_each_style)){
$server_validate_each_style = 	$display->server_validate_each_style;
}
else{
$server_validate_each_style = "";
}


if(isset($display->server_validate_all_style)){
$server_validate_all_style = 	$display->server_validate_all_style;
}
else{
$server_validate_all_style = "";
}


if(isset($display->server_validate_list_class)){
$server_validate_list_class = 	$display->server_validate_list_class;
}
else{
$server_validate_list_class = "";
}


if(isset($display->server_validate_list_style)){
$server_validate_list_style = 	$display->server_validate_list_style;
}
else{
$server_validate_list_style = "";
}

if(isset($display->server_error_label_container_class)){
$server_error_label_container_class = 	$display->server_error_label_container_class;
}
else{
$server_error_label_container_class = "";
}


if(isset($display->server_error_label_container_style)){
$server_error_label_container_style = 	$display->server_error_label_container_style;
}
else{
$server_error_label_container_style = "";
}


if(isset($display->server_error_label_class)){
$server_error_label_class = $display->server_error_label_class;
}
else{
$server_error_label_class = "";
}

if(isset($display->server_error_label_style)){
$server_error_label_style = $display->server_error_label_style;
}
else{
$server_error_label_style = "";
}

if(isset($display->server_error_element_container_class)){
$server_error_element_container_class = $display->server_error_element_container_class;
}
else{
$server_error_element_container_class = "";
}

if(isset($display->server_error_element_container_style)){
$server_error_element_container_style = $display->server_error_element_container_style;
}
else{
$server_error_element_container_style = "";
}

if(isset($display->server_error_element_class)){
$server_error_element_class = $display->server_error_element_class;
}
else{
$server_error_element_class = "";
}

if(isset($display->server_error_element_style)){
$server_error_element_style = $display->server_error_element_style;
}
else{
$server_error_element_style = "";
}

if(isset($display->server_error_separator)){
$server_error_separator = $display->server_error_separator;
}
else{
$server_error_separator = "<hr />";
}

}///END set default values
///check validation error
foreach($this->validation_error as $val_error=>$val_error_msg)
		{
			echo "<ul style=\"$server_validate_list_style\" class=\"$server_validate_list_class\">";
			
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


$this_value = $reprint->$val_error; //input value
$val_error_print = str_replace("@name", $val_error, $val_error_print);
$val_error_print = str_replace("@label", $label_lang, $val_error_print);
$val_error_print = str_replace("@value", $this_value, $val_error_print);

				echo "<li><span>$val_error_print</span></li>";
			
			
			}
		echo "</ul>";
			
		}
///check validation error


	
///----//draw type check////////******@@@@@@@@@@@@@@@
/*costume */		$container_open = "";
					$container_close = "";
					$row_begin = "";
					$row_end = "";
					$column_textdisplay_open = "";
					$column_textdisplay_close = "";
					$column_formdisplay_open = "";
/*END costume*/		$column_formdisplay_close = "";


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
		}
			break;
		case "costume_print":
		{ 
		////costume print
		if(isset($display->costume_print_content))
		{
		$costume_print_content = $display->costume_print_content;
		}
		else 
		{
		$costume_print_content = "echo \'\';";	
			
		}
		
		eval($costume_print_content);
		
		////costume print
		}
			break;
		case "hr":
		{ echo "<hr />";}
			break;
		case "p":
		{echo "<br />";}
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
		
		
		
		
		}
		
		
		
		///unlink files when query fails .................. set cases if isset FILES[]
						} ////end case

}
		
		$this->separator['container_open'] = $container_open;	
		$this->separator['container_open'] = $container_open;
		$this->separator['container_close'] = $container_close;
		$this->separator['row_begin'] = $row_begin;
		$this->separator['row_end'] = $row_end;
		$this->separator['column_textdisplay_open'] = $column_textdisplay_open;
		$this->separator['column_textdisplay_close'] = $column_textdisplay_close;
		$this->separator['column_formdisplay_open'] = $column_formdisplay_open;
		$this->separator['column_formdisplay_close'] = $column_formdisplay_close;
		
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




$this->pre_print[0] .= "<form method='$form_method' action='$form_action'  id='$form_id' enctype='multipart/form-data' $form_attr>";


	//}



$this->pre_print[0] .= $container_open;
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
$this->post_print[0] .= $row_begin;

$this->post_print[0] .= $column_textdisplay_open;


///// submission message
$submit_message = "";
if(isset($display->submit_message)){
$submit_message = $display->submit_message;	
}
$this->post_print[0] .= "$submit_message";
/////End submission message

$this->post_print[0] .= $column_formdisplay_close;

$this->post_print[0] .= $column_formdisplay_open;
	///////Close form tag/////
/////	if ($print_form == true){
$submit_attr = "";
if(isset($display->submit_attr))
{
$submit_attr = $display->submit_attr;	
}

$submit_button = "SUBMIT";
	if(isset($display->submit_button))
	{$submit_button = $display->submit_button;}

$this->post_print[0] .= "<input type='submit' value='$submit_button' name='$form_id' id='$form_id' $submit_attr />";	

	
	/////}
	$this->post_print[0] .= $column_formdisplay_close;
	$this->post_print[0] .= $row_end;
	
		$this->post_print[0] .= $container_close;
$this->post_print[0] .="</form>";	
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
$costume_print_content = "
		
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


//$this->post_print[0]
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
echo "<pre>";
//print_r($this->separator);
echo "</pre>";

$tabed_array = array();
$tabed_button = array();
$tabed_style = array();

$tabed_style[] = "<script>";
$tabed_style_key = 0;	

if(isset($display->tabs)){
		$tabs = $display->tabs;
		$tabed_array[] = "<tr>";
		$tabed_array[] = "<td colspan='2'>";
		$tabed_array[] = "<table id='tabs' cellspacing='0px' cellpadding='2px'><tr>";		
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
					$tabed_array[] = "<td ><div onclick=\"$tabscript\" id=\"tab_button_id_$tabname_botton\">$tabname_botton</div></td>";
}
$tabed_array[] = "</tr></table>";	
				
		foreach($tabs as $tabname=>$tabarray){
	
			$tabed_array[] = "<table cellspacing='0px' cellpadding='2px' class='each_tab' id='tab_$tabname' width='100%'>";
			
			
			$tabfields= explode(",",$tabarray);
			foreach($tabfields as $fieldindex=>$fieldname){
				if(array_key_exists($fieldname,$sorted_data)){
					//$this->alert("$tabname -- $fieldname");
					if(!in_array($sorted_data[$fieldname],$tabed_array)){
					$tabed_array[]  = $sorted_data[$fieldname];   ///may refine renderer
					}
				}
			}
			
			
			$tabed_array[] = "</table>";
		}
		$tabed_array[] = "</td>";
		$tabed_array[] = "</tr>";

$tabed_style[] = "</script>";
		
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

$select_row = $this->fetch_assoc($multipleselect_result);

$reprint_value = $select_row["$other_tr_this_column"];
//$reprint->$dfield = $reprint_value;
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
if(isset($display->server_validate_each_container)){
$server_validate_each_container = 	$display->server_validate_each_container;
}
else{
$server_validate_each_container = "p";
}

if(isset($display->server_validate_all_container)){
$server_validate_all_container = 	$display->server_validate_all_container;
}
else{
$server_validate_all_container = "div";
}

if(isset($display->server_validate_each_class)){
$server_validate_each_class = 	$display->server_validate_each_class;
}
else{
$server_validate_each_class = "";
}

if(isset($display->server_validate_all_class)){
$server_validate_all_class = 	$display->server_validate_all_class;
}
else{
$server_validate_all_class = "";
}

if(isset($display->server_validate_each_style)){
$server_validate_each_style = 	$display->server_validate_each_style;
}
else{
$server_validate_each_style = "";
}


if(isset($display->server_validate_all_style)){
$server_validate_all_style = 	$display->server_validate_all_style;
}
else{
$server_validate_all_style = "";
}


if(isset($display->server_validate_list_class)){
$server_validate_list_class = 	$display->server_validate_list_class;
}
else{
$server_validate_list_class = "";
}


if(isset($display->server_validate_list_style)){
$server_validate_list_style = 	$display->server_validate_list_style;
}
else{
$server_validate_list_style = "";
}

if(isset($display->server_error_label_container_class)){
$server_error_label_container_class = 	$display->server_error_label_container_class;
}
else{
$server_error_label_container_class = "";
}


if(isset($display->server_error_label_container_style)){
$server_error_label_container_style = 	$display->server_error_label_container_style;
}
else{
$server_error_label_container_style = "";
}


if(isset($display->server_error_label_class)){
$server_error_label_class = $display->server_error_label_class;
}
else{
$server_error_label_class = "";
}

if(isset($display->server_error_label_style)){
$server_error_label_style = $display->server_error_label_style;
}
else{
$server_error_label_style = "";
}

if(isset($display->server_error_element_container_class)){
$server_error_element_container_class = $display->server_error_element_container_class;
}
else{
$server_error_element_container_class = "";
}

if(isset($display->server_error_element_container_style)){
$server_error_element_container_style = $display->server_error_element_container_style;
}
else{
$server_error_element_container_style = "";
}

if(isset($display->server_error_element_class)){
$server_error_element_class = $display->server_error_element_class;
}
else{
$server_error_element_class = "";
}

if(isset($display->server_error_element_style)){
$server_error_element_style = $display->server_error_element_style;
}
else{
$server_error_element_style = "";
}

if(isset($display->server_error_separator)){
$server_error_separator = $display->server_error_separator;
}
else{
$server_error_separator = "";
}

}///END set default values


$invalid_error = "";

if(isset($display->server_validate_inline)){ ////setting inline error details
	if(($display->server_validate_inline == true)){
if(isset($validation_error["$dfield"])){
$invalid_error .= "<$server_validate_all_container class=\"$server_validate_all_class\" style=\"$server_validate_all_style\">";

				foreach($validation_error["$dfield"] as $val_error_print)
			{
//error custome
$this_value = $reprint_value; //input value
$val_error_print = str_replace("@name", $dfield, $val_error_print);
$val_error_print = str_replace("@label", $print_lang, $val_error_print);
$val_error_print = str_replace("@value", $this_value, $val_error_print);
//End erro custome
			$invalid_error .= "<$server_validate_each_container style=\"$server_validate_each_style\" class=\"$server_validate_each_class\" >" . $val_error_print . "</$server_validate_each_container>";
			}
$invalid_error .= "</$server_validate_all_container>$server_error_separator";		
			
		}
		else{
			
			$server_error_label_class = "";
			$server_error_label_style = "";			
			$server_error_element_class = "";
			$server_error_element_style = "";
			
		}

///inline error position


if(isset($display->server_validate_inline_position)){
	if($display->server_validate_inline_position=="before"){
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

		
$this->input_label["$dfield"] = "<label for=\"$dfield\" accesskey=\"\" class=\"$server_error_label_class\" style=\"$server_error_label_style\">".  $print_lang . "</label> ";//LABEL




switch ($input_type)  ////////@@@@@@@@    SWITCH FOR FORM INPUT TYPES
{
	case "select": //selection type data
	{

	$this->input_element["$dfield"] .= "<select name='$dfield' $attr style=\"$server_error_element_style\" class=\"$server_error_element_class\"  id='$id' >";
	
	$options_array = array();
	
				if(isset($display->fields->$dfieldx->from_dbtable))///checking for selection from db else selection from values
	{
			////{{{ COUNTINUE FROM HERE SELECT FROM DATABASE FROM DATABASE  }}}/////////
			$selection_dbt = $display->fields->$dfieldx->from_dbtable;

			$select_tablename = $display->fields->$dfieldx->from_dbtable->tablename;

			if(isset($display->fields->$dfieldx->from_dbtable->where))
			{
				$select_where = "WHERE " . $display->fields->$dfieldx->from_dbtable->where;
			} else 
			{
				$select_where = "";
				
			}

//include("connectdb.php");
//$con = $GLOBALS["con"];

$sqlxx = "SELECT * FROM $select_tablename $select_where;";

$select_result22 = $this->run_query("SELECT * FROM $select_tablename $select_where");
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
				alert('erro occured');   //////check and remove return sql error
				$this->alert($sqlxx);
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
						
						$this->input_element["$dfield"] .= "<option value='$value'  $selected>$option</option>";
					}
				}
				
				$this->input_element["$dfield"] .=	$ingroup_print;
				
				

				
//	}
	

		$this->input_element["$dfield"] .= "</select>";
	
		////End selection each option
	} 	///END SELECTION TYPE
        break;
	case "multipleselect": //selection type data
	{
	$this->input_element["$dfield"] .= "<select name='$dfield"."[]' $attr multiple='multiple'  style=\"$server_error_element_style\" class=\"$server_error_element_class\"   id='$id' selected='$dfield'>";
	
	$options_array = array();
	
	if(isset($display->fields->$dfieldx->from_dbtable))///checking for selection from db else selection from values
	{
////{{{ COUNTINUE FROM HERE SELECT FROM DATABASE FROM DATABASE  }}}/////////
$selection_dbt = $display->fields->$dfieldx->from_dbtable;

$select_tablename = $display->fields->$dfieldx->from_dbtable->tablename;

if(isset($display->fields->$dfieldx->from_dbtable->where))
{
	$select_where = "WHERE " . $display->fields->$dfieldx->from_dbtable->where;
} else 
{
	$select_where = "";
	
}

//include("connectdb.php");
//$con = $GLOBALS["con"];

$select_result = $this->run_query( "SELECT * FROM $select_tablename $select_where");


while($select_row = $this->fetch_assoc($select_result))
  {
$option_display = $select_row["$selection_dbt->option_display"];
$option_value = $select_row["$selection_dbt->option_value"];

	//////%%%%%%%%%%%%%%%%%%%
////End setting for other table											 
								 
												 
$options_array[$option_display] = $option_value;
  
  }

		
	}
	
	
else{ /// execution if database selection is not set
	$options_array =	$display->fields->$dfieldx->values_for_select; ///array of select values		
}
	//	print_r($options_array);
		////Display selection option
		//if(isset())
			$options_toprint = array();
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
if (isset($display->fields->$dfield->to_other_tr))
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
	
if(isset($display->fields->$dfield->field_separator))
				{
					$field_separator = $display->fields->$dfield->field_separator;
					
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
				
				foreach ($options_array as $option => $value ) { 
					if(!in_array($value,$ingroup_option)){

						
						$this->input_element["$dfield"] .= $options_toprint[$value];
					}
				}
				$this->input_element["$dfield"] .=	$ingroup_print;
/////>>>>>>>>
				
//}
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
			
	if(isset($display->fields->$dfieldx->from_dbtable)){
///////db options
			$selection_dbt = $display->fields->$dfieldx->from_dbtable;

	$select_tablename = $display->fields->$dfieldx->from_dbtable->tablename;

	if(isset($display->fields->$dfieldx->from_dbtable->where))
	{
		$select_where = "WHERE " . $display->fields->$dfieldx->from_dbtable->where;
	} else 
	{
		$select_where = "";
		
	}

//	include("connectdb.php");
//$con = $GLOBALS["con"];

	$sqlvv = "SELECT * FROM $select_tablename $select_where";
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
	}

///////db options
		
		
	}
			
		else	{
	if(isset($display->fields->$dfieldx->values_for_select)){		
		 $options_array =	$display->fields->$dfieldx->values_for_select;
	}
		}	 
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
				$this->input_element["$dfield"] .= "<input type='radio' name='$dfield' $attr  style=\"$server_error_element_style\" class=\"$server_error_element_class\"   $selected id='$id" . "_" . "$option_value' value='$option_value'> $option_display $element_separator";
				}
	//}

	
	}
        break;
    case "password":
	{	
	$reprint_value = htmlspecialchars($reprint_value);	
		
	$this->input_element["$dfield"] .= "	<input type='password' name='$dfield' $attr  style=\"$server_error_element_style\" class=\"$server_error_element_class\"   id='$id' value='$reprint_value' />";

	}
        break;

	case "text":
	{
	
	$reprint_value = htmlspecialchars($reprint_value);			
		$this->input_element["$dfield"] .= "<input type='text' name='$dfield' $attr  style=\"$server_error_element_style\" class=\"$server_error_element_class\"   id='$id' value=\"$reprint_value\" />";
		
	}
        break;
		
	case "number":
	{

	
		$this->input_element["$dfield"] .= "<input type='number' name='$dfield' $attr  style=\"$server_error_element_style\" class=\"$server_error_element_class\"   id='$id' value=\"$reprint_value\" />";
		

	}
        break;	
		
	case "textarea":
	{
		
	$reprint_value = htmlspecialchars($reprint_value);		
	$this->input_element["$dfield"] .= "	<textarea type='text'  id='$id' $attr  style=\"$server_error_element_style\" class=\"$server_error_element_class\"   name='$dfield'>$reprint_value</textarea>";

	}
        break;
	case "costumeHTML":  	////// Replacing with customized html content 
	{	

	//echo "Your favourite HTML CODE";
	///////////check set content////////////
	if (isset($display->fields->$dfield->content))
	{
	$htm_content = $display->fields->$dfield->content;
	}
	else
	{
	$htm_content = "";
	}
	///////////End check set content////////////
	
	
	$this->input_element["$dfield"] .= $htm_content;

	}
        break;
	case "costumePHP":		////// Replacing with customized php content eval()
	{	

	//echo "Your favourite PHP CODE";
	///////////check set////////////
	if (isset($display->fields->$dfield->content))
	{
	$php_content =$display->fields->$dfield->content;
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

		
		$this->input_element["$dfield"] .= "<input type='color'  name='$dfield' $attr  style=\"$server_error_element_style\" class=\"$server_error_element_class\"   id='$id' value='$reprint_value' />";


	}
        break;		
	case "range":
	{
	

	$this->input_element["$dfield"] .= "<input type='range' name='$dfield' $attr  style=\"$server_error_element_style\" class=\"$server_error_element_class\"   id='$id' value='$reprint_value' />";
	

	}
        break;
	
	case "file":
	{	

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

$this->input_element["$dfield"] .= "$reprint_value";   ///comment out to prevent showing
	
	$this->input_element["$dfield"] .= "<input type='file' name='$dfield' $attr  style=\"$server_error_element_style\" class=\"$server_error_element_class\"   id='$id' value='' />";
	

	}
        break;	
	case "multiplefile":
	{	

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

$this->input_element["$dfield"] .= "$reprint_value";   ///comment out to prevent showing
	
	$this->input_element["$dfield"] .= "<input type='file'  multiple=\"multiple\" name='$dfield" . "[]' $attr  style=\"$server_error_element_style\" class=\"$server_error_element_class\"   id='$id' value='' />";
	

	}
        break;

	case "checkbox":
	{
		$element_separator = "";
		$options_array = array();
		if(isset($display->fields->$dfieldx->element_separator)){
		$element_separator = $display->fields->$dfieldx->element_separator;
		}
	
	if(isset($display->fields->$dfieldx->from_dbtable))///checking for selection from db else selection from values
	{
////{{{ COUNTINUE FROM HERE SELECT FROM DATABASE FROM DATABASE  }}}/////////
$selection_dbt = $display->fields->$dfieldx->from_dbtable;

$select_tablename = $display->fields->$dfieldx->from_dbtable->tablename;

if(isset($display->fields->$dfieldx->from_dbtable->where))
{
	$select_where = "WHERE " . $display->fields->$dfieldx->from_dbtable->where;
} else 
{
	$select_where = "";
	
}


$select_result = $this->run_query("SELECT * FROM $select_tablename $select_where");


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
if (isset($display->fields->$dfield->to_other_tr))
{
	{//otyher tr values
	$other_tr_details = $display->fields->$dfield->to_other_tr;
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
	$raw_select =  $updatedata->$dfield;
	
	
if(isset($display->fields->$dfield->field_separator))
				{
					$field_separator = $display->fields->$dfield->field_separator;
					
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
				$this->input_element["$dfield"] .= "<input type='checkbox' $selected value='$value'  $attr  style=\"$server_error_element_style\" class=\"$server_error_element_class\"   id='$id"."_value' name='$dfield"."[]' /> $option $element_separator";
				}
//}
	


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

	$this->input_element["$dfield"] .= "	<textarea type='text' name='$dfield' $attr  style=\"$server_error_element_style\" class=\"$server_error_element_class\"  id='$dfield'>$reprint_value</textarea>";

}

else{
$reprint_value = htmlspecialchars($reprint_value);	
$this->input_element["$dfield"] .= "	<input type='text' name='$dfield' $attr  id='$dfield' value='$reprint_value' />";

	
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
		$costume_to_db, /// add addional data to database 
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
$costume_to_db = array();
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
{////costume print
$costume_print_content = "
		
		\$container_open = \"<fieldset style='border:4px black double;'><legend>Testing the form</legend>\";
		\$container_close = \"</fieldset>\";
		\$row_begin = \"\";
		\$row_end = \"\";
		\$column_textdisplay_open = \"\";
		\$column_textdisplay_close = \": \";
		\$column_formdisplay_open = \"\";
		\$column_formdisplay_close = \"<hr width='50%' />\";
				
										";

}

////update database table
$update = (object) array	(
'set' => true,
'where' => 'faculty_id = 12334', //	2345, 1233
						);


///external data
$time = time();
//'fields' => (object) array ( 
{///add field under dev $add_field
}
$add_free_field  = array( //must add proccessors   ////when $add_free_field is declared, parameter 2 must be same as that set in $display->fields->type
					array("house","number"),
					array("location","color"),  /////must still be set in display to choose type
					array("place","select"),
					array("joint","textarea"),
					array("free_no_display","text"),
					//array("","");
						);



{$display = (object) //must be declared
array(	'foo' => 'bar',
		'property' => 'value',
		'filemust' => 0, //// check if file must be upload to allow form submission
		'submit_button' => 'APPLY',
		'insert_callback' => "\$this->alert(\"Data inserted Successfully\");",
		'insert_failure' => "\$this->alert(\"Data insertion failed\");",
		'update_callback' => "\$this->alert(\"Update Successfully\");",
		'update_failure' => "\$this->alert(\"Update failed\" .  \$sql_error);",
		//'update_callback' => "alert(\"vvvvvvvvvvvvv\");",
		///@@@@@@@@@@@@@@@@@@@@ Start Printing type @@@@@@@@@@@@@@@@///
		//'separator' => 'div', //div, p, floated div add class values id's
		//'separator' => 'table', //div, p, floated div add class values id's
		'separator' => 'table', //div, p,costume_print, floated div add class values id's
		'costume_print_content' => $costume_print_content, //div, p, floated div add class values id's
		///@@@@@@@@@@@@@@@@@@@@ End Printing type @@@@@@@@@@@@@@@@///
		'submit_attr' => "style=''",
		'server_validate' => true,
		'server_validate_inline' => true, //inline //list
		'server_validate_list' => true, //inline //list
		'server_validate_inline_position' => "after", //before,after      //before or after elemen
		'server_validate_each_container' => "span", //span, p, div 
		'server_validate_each_class' => "", //span, p, div 
		'server_validate_each_style' => "color:red;", //span, p, div 
		'server_validate_all_container' => "span", //span, p, div 
		'server_validate_all_class' => "", //span, p, div 
		'server_validate_all_style' => "", //span, p, div 
		'server_validate_list_class' => "", 
		'server_validate_list_style' => "color:red;margin:0px;", 
		'server_error_label_container_class' => "",
		'server_error_label_container_style' => "",
		'server_error_label_class' => "",
		'server_error_label_style' => "color:red;",
		'server_error_element_container_class' => "",
		'server_error_element_container_style' => "",
		'server_error_element_class' => "error_class",
		'server_error_element_style' => "background:red;",
		'server_error_separator' => "",
		
		'tabsx' => array("PERSONAL"=>'faculty_day_added,faculty_year_added,faculty_campus,faculty_shortname,faculty_id',
						"OFFICE"=>'faculty_text,faculty_files,location,faculty_logo,faculty_fullname,institution_id',
						"OTHERS"=>'house,free_no_display,joint,cvupload,2ndpasswprd,faculty_code,faculty_month_added'),
		
		'update' => $update, /////UPDATE
		'submit_message' => "<i>Read instructions clearfully</i>",
		'form_attr' => "onsubmit='\$this->alert(57689'",  ////// What happens on submit of form
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
													'ValidateAsIntegerErrorMessage' => "@name @label @value is not an integer",
													'element_separator' => '<br />', ///HTML TAG
												),
		'faculty_fullname' => (object) array ( 	
												'type'=> 'text', 									
											//	'type'=> 'costumePHP', 
												'content'=> " echo \" <h1>\$print_lang YEYOOO PHP</h1>\";",  ///use "" for string   data field: $dfield or $print_lang
												//'custom_validation' => array()
												'set_mod2' => "modify",////name of function
												'preprocessor' => "preprocessor",////name of a function
												'CustomValidate' => "cval",
												'CustomValidateErrorMessage' => "input was not valid",
												'before_label' => 'blabel',
												'after_label' => 'alabel',
												'before_element' => 'belement',
												'after_element' => 'aelement',	
												
												),	
'faculty_logo' => (object) array ( 	
												'type'=> 'file',
												'max_size'=> '500000000', ///file size ib bytes
												'folder'=>'downloads', ///folder to move file too
												'file_type'=>'', //// comment if not an image or remove rule
										 
										//		'type'=> 'costumePHP', //'costumeHTML',customisizing
										//		'rename_rule'=> $faculty_shortname, 
										//		'rename_rule'=> time(). "falculty", 
												
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
										 
										//		'type'=> 'costumePHP', //'costumeHTML',customisizing
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
													'DateSeparator' => "-"	//no slash (/)fix letter
													),	
	'faculty_campus' => (object) array ( 	
													'type'=> 'file',
													'max_size'=> '500000000', ///file size ib bytes
													'folder'=>'downloads', ///folder to move file too
													'file_type'=>'', //// comment if not an image or remove rule
											 
												//	'type'=> 'costumePHP', 'costumeHTML',customisizing
													'rename_rule'=> $faculty_shortname, 
													'rename_rule'=> time(). "falculty" . "campus", 
													
													'phprequired'=> "",  /////if decleared block insert
										//			'overwrite'=> "1", ////set one not to overwrite
													'content'=> " echo \" <h1>\$print_lang YEYOOO PHP</h1>\";"  ///use "" for string   data field: $dfield or $print_lang
														
													),	

'house' => (object) array ( 	
												'type'=> 'number', 									
											//	'type'=> 'costumePHP', 
												'content'=> " echo \" <h1>\$print_lang YEYOOO PHP</h1>\";",  ///use "" for string   data field: $dfield or $print_lang
												//'ValidateAsDate' => 1,	
												),	
'location' => (object) array ( 	
												'type'=> 'color', 									
											//	'type'=> 'costumePHP', 
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
'joint' => (object) array ( 	
												'type'=> 'textarea', 									
											//	'type'=> 'costumePHP', 
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
						'type'=> 'select', 
						'position'=> 'before', ////position before, after
						'values_for_select' => array(	"Jatttt" => "1", ////The key is displayed & value = value
														"February" => "2",
														"March" => "3"
													),
					//	'selected' => array('3')
													
							),
'faculty_logo' => (object) array ( 	
												'type'=> 'textarea',
												'newfield'=> 'cvupload',
												'max_size'=> '500000000', ///file size ib bytes
												'folder'=>'downloads', ///folder to move file too
												'file_type'=>'', //// comment if not an image or remove rule										 
											//	'type'=> 'costumePHP', 'costumeHTML',customisizing
										//		'rename_rule'=> $faculty_shortname, 
												'rename_rule'=> time(). "falculty", 
												'phprequired'=> "",  /////if decleared block insert
									//			'overwrite'=> "1", ////set one not to overwrite
												'content'=> " echo \" <h1>\$print_lang YEYOOO PHP</h1>\";"  ///use "" for string   data field: $dfield or $print_lang
													
												),							
							
							
	)
	
	
	
	);
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
			$cons  = $udata->faculty_id;
			$lang  = $lang->$name;
		$before = "B4[$value]($name)--$cons --$lang";
	
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
		////for field_processor
		
		
		
	///costume data to add to db
//"" => "",

//$print_form = true;

//$exception = array("faculty_id", "deleted", "faculty_time_todb");

//// additional data to db
$faculty_id = rand(1000,9000);
$time = time();
///

{///costume sending the data to database


$costume_to_db = array("faculty_time_todb" => "$time");
//$costume_to_db = array("faculty_id" => "$faculty_id", "faculty_time_todb" => "$time");

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
'joint' => "fpro",				///string as callable and object array as eval() function if source code is known						
'cvupload' => (object) array(),  //for all additional field is recommened to add at least a blank proccessor
//'free_no_display' => (object) array(),															
										);		
}



$tablename = "faculty";
$exception = array("deleted", "faculty_time_todb");
$sort_array = array(/*'location',*/'faculty_description','faculty_campus','institution_id','faculty_code','faculty_day_added','faculty_year_added');


/*
$form = new formgen();
$form->formx($tablename, $print_form, $exception,$sort_array, $display, $addtional_field,$add_free_field,$field_processor, $costume_to_db, $lang);
$renderer = $form->renderer;
//form_render($caster);
$form->form_render($renderer);
*/



echo "<hr />";
 //rename_function('run_query', 'new_name');

class formgen2 extends formgen {}
 $display->form_id = "zzzz";
 $display->separator = "table";
$form2 = new formgen2();
$form2->db_type = "mysqli";
$form2->db_host = "localhost";
$form2->db_port = "3306";
$form2->db_name = "sms";
//$form2->db_name = "sqlite-database.db";
$form2->db_username = "root";
$form2->db_password = "";
$form2->formx($tablename, $print_form, $exception,$sort_array, $display, $addtional_field,$add_free_field,$field_processor, $costume_to_db, $lang);
$renderer2 = $form2->renderer;
//form_render($caster);



$form2->form_render($renderer2);

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
$form3->formx($tablename, $print_form, $exception,$sort_array, $display, $addtional_field,$add_free_field,$field_processor, $costume_to_db, $lang);
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

$form3->formx($tablename, $print_form, $exception,$sort_array, $display, $addtional_field,$add_free_field,$field_processor, $costume_to_db, $lang);
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
 /////check costume_to_db check in array ***** remove if invalid 
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
