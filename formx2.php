
<?php

//fileupload processor 

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

$validation_error = array();
$GLOBALS["validation_error"] = $validation_error;
$GLOBALS["allow_sql"] = 0;

function ValidateAsEmail($name,$value,$validate_error){
	if(preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-._]+..[a-zA-Z0-9-.]+$/",$value))////email
	{
		///nothing runs here
	}
	else
	{
		if($validate_error ==""){$validate_error ="$value is no a valid email format";}
		$GLOBALS["validation_error"]["$name"] = $validate_error;
		$GLOBALS["allow_sql"] += 1;
	}	
}



function ValidateAsInteger($name,$value,$validate_error){
	if(preg_match("/^[0-9]+$/",$value))////email
	{
		///nothing runs here
	}
	else
	{
		if($validate_error ==""){$validate_error ="$value is not an Integer";}
		$GLOBALS["validation_error"]["$name"] = $validate_error;
		$GLOBALS["allow_sql"] += 1;
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
		if($validate_error ==""){$validate_error ="$value is not a Float";}
		$GLOBALS["validation_error"]["$name"] = $validate_error;
		$GLOBALS["allow_sql"] += 1;
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
		if($validate_error ==""){$validate_error ="$value is not a valid date format";}
		$GLOBALS["validation_error"]["$name"] = $validate_error;
		$GLOBALS["allow_sql"] += 1;
	}	
}



////RENDER FORM ITEMS	
$while_sort_interset = array();
$while_sorted = array();
$render_data = array();
$while_data = array();
$pre_print = array("");
$post_print = array("");
////END RENDER FORM ITEMS	

///////Simple INSERTION Form Generator from database table////////
////// add form type later
function table($tablename, $print_form, $exception,$sort_array, $display, $addtional_field,$add_free_field,$field_processor, $costume_to_db, $lang)

{

include("connectdb.php");

$reprint =  (object) array();


	/////In case $display parameter is not set
if(!isset($display))
	{
	$display = (object) array (0);
	}	
	
///// DISPLAY Object//////	
//	if(isset($display)){echo "<pre>";	print_r($display);}	echo "</pre>";
///////END THE DISPLAY//////		
	
//$result = run_query("SHOW COLUMNS FROM $tablename"); /////for mysqli

////setting default for tablename to avoid Fatal error

if ($tablename==""){$tablename="no_table";}

$describe_table =  describe_table($tablename);
//alert($describe_table);
$describe_db_table = run_query("$describe_table");
//Declear array to remove colums from table
$cutarray= array("0");
$cutarray = array_merge($cutarray,$exception);


/////Check Post /////
//'form_method'
$sub = "submit_$tablename";
$allow_sql = 0;
$sql_array = array();
$delete_multirow_sql = "";
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
	foreach ($posts as $value) 
				{///
				$name = $value;	
				$$value =$type[$name];
				$value = $type[$name];
			$identifiers->$name = $value;		
				}
	//////foreach execution
				foreach ($posts as $value) 
				{///
				$name = $value;	
				$$value =$type[$name];
				$value = $type[$name];
			////////Check if $POSTED ITEM IS AN ARRAY INCASE OF A CHECK BOX
	if(isset($display->columns->$name->extra_data))
				{
				$extra_data = $display->columns->$name->extra_data;	
				eval($extra_data);	
					
				}			
$reprint->$name = $value;		
			
			if (((!is_array($value))))
			{			
			//	$value = addslashes(mysqli_real_escape_string($con,$value));				
				$value = addslashes($value);				
		//		echo "STRING $name -- $value <br />";
			}
			else 
			{
			//	echo " ARRAY $name --";					
			
				$addarrayvalue = "";	
				if(isset($display->columns->$name->field_separator))
				{
					$field_separator = $display->columns->$name->field_separator;
				}
				else
				{
					$field_separator = "+";
				}
									
					foreach ($value as $arrayvalue) 
				{
					
					$addarrayvalue .= "$arrayvalue$field_separator";
				}	
					$addarrayvalue = substr($addarrayvalue, 0, -1);
				//	echo  "$addarrayvalue <br />";
			}
			//////END Check if posted
			
		/////data processing area////////

	
				
						
	///if statement below removes submit value from the string		
				/*		if ($name != $sub){							
						$column_sql_value .= "`$name`,";
						////where to replace the values we wish
						$row_sql_value .= "'$value',";
						}
				*/
if(isset($field_processor->$name)){
	
if(isset($field_processor->$name->content)){
	eval($field_processor->$name->content);
}

}
	

else
{////
if ($name != $sub){	
						
						
						////where to replace the values we wish
			
			////////Check if $value  IS ARRAY INCASE OF A CHECK BOX
			if (((!is_array($value))))
			{		$column_sql_value .= "`$name`,";
					$row_sql_value .= "'$value',";
					$sql_array["$name"] = $value;
					/////validation execution
					//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn
					//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn
					//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn
					//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn
					if(isset($display->columns->$name->server_validate))
					{
						if($display->columns->$name->server_validate == true)
						{
							/////RUN VALIDATORS
				$validate_error = "";	
				if(isset($display->columns->$name->ValidateAsEmail))
				{$validate_error = "";	
					if(isset($display->columns->$name->ValidateAsEmailErrorMessage))
				{
					$validate_error = $display->columns->$name->ValidateAsEmailErrorMessage;
				}
					ValidateAsEmail($name,$value,$validate_error);
					
				}
				
				if(isset($display->columns->$name->ValidateAsInteger))
				{$validate_error = "";	
								if(isset($display->columns->$name->ValidateAsIntegerErrorMessage))
						{
							$validate_error = $display->columns->$name->ValidateAsIntegerErrorMessage;
						}
					ValidateAsInteger($name,$value,$validate_error);
				}

				if(isset($display->columns->$name->ValidateAsFloat))
				{$validate_error = "";	
					if(isset($display->columns->$name->ValidateAsFloatErrorMessage))
						{
							$validate_error = $display->columns->$name->ValidateAsFloatErrorMessage;
						}
					ValidateAsFloat($name,$value,$validate_error);
				}
				
				if(isset($display->columns->$name->ValidateAsDate))
				{$validate_error = "";	
					if(isset($display->columns->$name->ValidateAsDateErrorMessage))
						{
							$validate_error = $display->columns->$name->ValidateAsDateErrorMessage;
						}
						$separator = "-";
					if(isset($display->columns->$name->DateSeparator))
						{
							$separator = $display->columns->$name->DateSeparator;
						}
					ValidateAsDate($name,$value,$separator,$validate_error);
				}
					

				
							/////END RUN VALIDATORS
						
						}
						
						
						
			
					}
					/////END validation execution
					//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn
					//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn
					//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn
					//nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn
			
			}
			else 
			{////if array
				if(isset($display->columns->$name->to_other_tr))
				{
	
	
$other_tobj = $display->columns->$name->to_other_tr; ///other table object	
$othertablename = $other_tobj->tablename;
$this_data_column = $other_tobj->this_column;
$other_data_column_array = $other_tobj->other_column;

				
				$ocolumn_sql_value = "";
				$orow_sql_value = "";
				$update_othertr = "";

//////call check from db
if (isset($display->columns->$name->to_other_tr) && $display->update->set === true)
{
	{//otyher tr values
	$other_tr_details = $display->columns->$name->to_other_tr;
	$other_tr_table = $other_tr_details->tablename;
	$other_tr_this_column = $other_tr_details->this_column;
	$other_tr_identifier = $other_tr_details->identifier;
	$other_tr_this_column_oc = $other_tr_details->other_column;
	$other_tr_key = $other_tr_this_column_oc["$other_tr_identifier"];
//	alert($other_tr_key);
	///get from table
	$update_table_where = $display->update->where;

//	include("connectdb.php");
$con = $GLOBALS["con"];
$this_result = run_query( "SELECT * FROM $tablename WHERE $update_table_where");
	while($obj = fetch_assoc($this_result)){	  
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
									
//$delete_row_sql = run_query( "DELETE FROM $other_tr_table WHERE $other_tr_key = '$update_data_pre' ");

$delete_multirow_sql .= "DELETE FROM $other_tr_table WHERE $other_tr_key = '$update_data_pre';";


/*									
$this_result_otr = run_query( "SELECT * FROM $other_tr_table WHERE $other_tr_key = '$update_data_pre' ");

while($objx = fetch_assoc($this_result_otr)){

	}
	*/
//////use to remove values from tables	
	
	
	}
	
		}	
//////end call check from db 


				foreach ($other_data_column_array as $other_value =>$other_column) 
					{
					echo "***** $other_column >>>>>> $other_value<br />";
					$ocolumn_sql_value .= "`$other_column`,";
					$orow_sql_value .= "'".$$other_value."',"; ////remove $ sign
					///for update
					$update_othertr .= "`$other_column` =" . "`" .$$other_value . "`,";
					
					}
					$ocolumn_sql_value .= "`$this_data_column`"; ////adding curremt data
					///update
					
					//$update_othertr .= "`$this_data_column` =" . "`" .$$other_value . "`"
					//$update_othertr = substr($update_othertr, 0, -1);
					///update 
			$arrayinsert_sql = "";
					foreach ($value as $oarrayvalue) 
					{
						//$oarrayvalue = "$oarrayvalue";
				///SQL for insertion		
				$arrayinsert_sql .= "INSERT INTO `$othertablename` ($ocolumn_sql_value) VALUES ($orow_sql_value"."'$oarrayvalue');\n";
				
				///SQL for updation
				$update_sql = "UPDATE `$othertablename` SET $update_othertr ". "`$this_data_column` = `$oarrayvalue` " ." WHERE id=2;";
				//alert("$update_sql");
					}	
					
			///// Per row execution
						
			echo "<pre>$arrayinsert_sql</pre> <br />";
			
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
					$column_sql_value .= "`$name`,";  //// column for original table
					$addarrayvalue = "";	
if(isset($display->columns->$name->field_separator))
				{
					$field_separator = $display->columns->$name->field_separator;
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
		echo $name . " -- " . $value . "<br />";							
	//	$column_sql_value .= "`$name`,";
		////where to replace the values we wish
	//	$row_sql_value .= "'$value',"; 
//$selection_dbt = $display->columns->$dfield->from_dbtable;

$reprint->$name = $value;	
	
		if(isset($display->columns->$name->type))
		{

if(isset($display->columns->$name->file_type))	{
$file_type = $display->columns->$name->file_type;
}
else{
$file_type ="";	
}

if($file_type=='image')
{
include('imageprocessor.php');

}
else
{
///I file is not an image
$uploadOk = 1;
	
	////set folder
	if(isset($display->columns->$name->folder))
	{
		$target_dir = $display->columns->$name->folder . "/";
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
    

if(isset($display->columns->$name->overwrite)){
if(($display->columns->$name->overwrite == "1")){
echo "Sorry, file already exists.";
$uploadOk = 0;	
}	

}
}
// Check file size

if(isset($display->columns->$name->max_size)){
$max_size = $display->columns->$name->max_size;
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
		


		if(isset($display->columns->$name->rename_rule)){/////RENAME
		$newname = $display->columns->$name->rename_rule;
		$newnamex = $target_dir . $newname . "." . $imageFileType;
		rename($target_file, $newnamex);
		echo " and RENAMED as $newnamex";
		
		$array_of_uploaded_filename[] = $newnamex;
		
		///rrrrrr
		$newnamedb = $newname . "." . $imageFileType;
		$column_sql_value .= "`$name`,";
		$row_sql_value .= "'$newnamedb',"; 
		$sql_array["$name"] = $newnamedb;
		////rrrrr
		}//// END RENAME
		} else {
			echo "Sorry, there was an error uploading your file.";
			//phprequired
			if(isset($display->columns->$name->phprequired))
			{
			$uploadOk =0;
			}
		}


}
		} ///End file type check
//	*/	
		
						
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
	$column_sql_value .= "`$costume_column`,";
	////where to replace the values we wish
	$row_sql_value .= "'$costume_value',";
	$sql_array["$costume_column"] = $costume_value;
	//echo "$costume_column => $costume_value <br />";
}

/////END ADDDING COSTUME TO DB/////


$column_sql_value = substr($column_sql_value, 0, -1);
$row_sql_value = substr($row_sql_value, 0, -1);

$insert_sql = "";

if(isset($display->filemust))
{	if($display->filemust == 0)
{
	$uploadOk = "1";
}}


foreach($GLOBALS["validation_error"] as $val_error)
		{
			echo "<span class='error_messages' style='color:;'>$val_error</span><br />";
			
		}
$allow_sql += $GLOBALS["allow_sql"];

if($uploadOk !=0 && $allow_sql ==0){
	
////writing updating sql query
$set_update = "";
foreach($sql_array as $sql_column => $sql_row)
{
	echo  "<br />". $sql_column. "-----------". "$sql_row" . "<br />";
	$set_update .= "$sql_column = '$sql_row',";
}
$set_update = substr($set_update, 0, -1);
$update_where = $display->update->where;

$update_sql = "UPDATE `$tablename` SET $set_update WHERE $update_where;";
////End writing updating query
	
	prt("$update_sql");
	
$insert_sql = "INSERT INTO `$tablename` ($column_sql_value) VALUES ($row_sql_value);";


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
		
//echo "<h1>(((((((((((((((((((";	
//echo $delete_multirow_sql;
//echo "</h1>";	
	//$delete_multirow_sql     for multipleselect and checkboxes	
if ($query = multi_query($insert_sql))
			{	
		if ($query2 = multi_query($delete_multirow_sql.$arrayinsert_sql))
		{
		//echo "Data captured";		
		//echo $arrayinsert_sql;
		////callbacks
		if(isset($display->update)){
		if(isset($display->update_callback)){			
			eval($display->update_callback);
		}
									}
									else {
		
		
		if(isset($display->insert_callback)){
			
			eval($display->insert_callback);
		}
									}
		////End callbacks	
		}
		
		
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
		///declear erro type
	//	echo "<span style='color:red'>An error occurred check the details you entered >>></span>". mysqli_error($con);	
		echo "<span class='sql_error_css' style='color:;'>An error occurred check the details you entered >>></span>";	///sql error custom
		
			//	$array_of_uploaded_filename[] = $target_file;
		
	foreach($array_of_uploaded_filename as $fucount => $ufilename)		////Deletes filedetails is captured in database
	{	
	unlink($ufilename);	
	}
		
		
		}


		}
else
{
//$insert_sql = "";	

///NOT OK FOR SQL
///set custome sql error

echo "<span class='sql_error_css' style='color:;'> NOT SUCCESSFUL </span><br />";

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




$GLOBALS['pre_print'][0] .= "<form method='$form_method' action='$form_action'  id='$form_id' enctype='multipart/form-data' $form_attr> \n";


	//}
	
///----//draw type check////////******@@@@@@@@@@@@@@@
/*costume */		$container_open = "";
					$container_close = "";
					$row_begin = "";
					$row_end = "";
					$column_textdisplay_open = "";
					$column_textdisplay_close = "";
					$column_formdisplay_open = "";
/*END costume*/		$column_formdisplay_close = "";
		
if(isset($display->separator))
	{
		$favcolor = $display->separator;

	switch ($favcolor) {/////replace with suposed
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



$GLOBALS['pre_print'][0] .= $container_open;
///----//end draw type check//////*****@@@@@@@@@@@@@	


////[[[[[[[[[[[[[[[]]]]]]]]]]]]]]]
////[[[[[[[[[[[[MAIN PROCCESSOR DISPLAY[[[]]]]]]]]]]]]]]]
////[[[[[[[[[[[[[[[]]]]]]]]]]]]]]]
$input_type = ""; 
$print_lang = "";
$dfield = ""; 
$row = "";

function fortypes ($con, $display,$lang,$addtional_field,$add_free_field,$input_type,
$column_textdisplay_open,$print_lang,$column_textdisplay_close, 
$column_formdisplay_open,$column_formdisplay_close,$dfield,$newfieldtype, $row, $reprint, $updatedata)
{//////fortypes
$describe_fname = describe_fname();

$dfieldx = $row[$describe_fname]; ///debug ///find callname
//$dfieldx = $dfield;
	if(isset($lang->$dfield)) 	////// CHEKCING FOR RENAME LANG
		{ $print_lang = $lang->$dfield;} 
		else { $print_lang = $dfield; } 						////// END CHEKCING FOR RENAME LANG
	/////before	addtional_field /////
	//if (isset($display->columns)	
	/////end before	addtional_field /////



		if (isset($display->columns) && isset($display->columns->$dfieldx->type)) { ///Check if columns display is set
			//$dc = $display->columns;
			//$dct = $dc->$dfield->type;
			//$input_type = $dct;
			$input_type = $newfieldtype;
		} else { $input_type = null; }

		
	$reprint_value = ""; ////default
///update reprint
if(isset($updatedata->$dfield))
{
	$reprint_value = $updatedata->$dfield;
}
//echo $updatedata->$dfield;

////set reprint values

if(isset($reprint->$dfield))
{
	$reprint_value = $reprint->$dfield;
}

////setting attributes		
if(isset($display->columns->$dfield->attr))
{
$attr = $display->columns->$dfield->attr;	
} else { $attr = "";}

///setting before label
if(isset($display->columns->$dfield->before_label))
{
$before_label = $display->columns->$dfield->before_label;	
} else { $before_label = "";}

///setting after label
if(isset($display->columns->$dfield->after_label))
{
$after_label = $display->columns->$dfield->after_label;	
} else { $after_label = "";}

///setting before element
if(isset($display->columns->$dfield->before_element))
{
$before_element = $display->columns->$dfield->before_element;	
} else { $before_element = "";}

///setting before element
if(isset($display->columns->$dfield->after_element))
{
$after_element = $display->columns->$dfield->after_element;	
} else { $after_element = "";}

	$id = $dfield;
		if(isset($display->columns->$dfield->id)){$id= $display->columns->$dfield->id;}
	
	


	$GLOBALS['while_data']["$dfield"] .= $column_textdisplay_open;
	$GLOBALS['while_data']["$dfield"] .= $before_label;
	$GLOBALS['while_data']["$dfield"] .= $print_lang . " ";
	$GLOBALS['while_data']["$dfield"] .= $after_label;
$GLOBALS['while_data']["$dfield"] .= $column_textdisplay_close;



switch ($input_type)  ////////@@@@@@@@    SWITCH FOR FORM INPUT TYPES
{
	case "select": //selection type data
	{
$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_open;
$GLOBALS['while_data']["$dfield"] .= $before_element;

	$GLOBALS['while_data']["$dfield"] .= "<select name='$dfield' $attr  id='$id' >";
	
				if(isset($display->columns->$dfieldx->from_dbtable))///checking for selection from db else selection from values
				{
			////{{{ COUNTINUE FROM HERE SELECT FROM DATABASE FROM DATABASE  }}}/////////
			$selection_dbt = $display->columns->$dfieldx->from_dbtable;

			$select_tablename = $display->columns->$dfieldx->from_dbtable->tablename;

			if(isset($display->columns->$dfieldx->from_dbtable->where))
			{
				$select_where = "WHERE " . $display->columns->$dfieldx->from_dbtable->where;
			} else 
			{
				$select_where = "";
				
			}

//include("connectdb.php");
//$con = $GLOBALS["con"];

$sqlxx = "SELECT * FROM $select_tablename $select_where;";

$select_result22 = run_query("SELECT * FROM `$select_tablename` $select_where");
if($select_result22)
{
//alert($sqlxx);
//alert(fetch_assoc($select_result));
while($select_row = fetch_assoc($select_result22))
  {
	
$option_display = $select_row["$selection_dbt->option_display"];
$option_value = $select_row["$selection_dbt->option_value"];
  $selected = "";
				if($reprint_value==$option_value)
				{
					$selected = "selected=\"selected\"";
				}
$GLOBALS['while_data']["$dfield"] .= "<option value='$option_value' $selected >$option_display</option>";
  }
				}
				else{ 
				alert('erro occured');
				alert($sqlxx);
				}
		
	}
else{ /// execution if database selection is not set
	$options_array =	$display->columns->$dfieldx->values_for_select; ///array of select values		
	//	print_r($options_array);
		////Display selection option
		foreach ($options_array as $option => $value ) 
				{ 
				$selected = "";
				if($reprint_value==$value)
				{
					$selected = "selected=\"selected\"";
				}
			//selected='$dfield'	
				$GLOBALS['while_data']["$dfield"] .= "<option value='$value' $selected >$option</option>";
				}
	}
	

		$GLOBALS['while_data']["$dfield"] .= "</select>";
		$GLOBALS['while_data']["$dfield"] .= $after_element;
		$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_close;
		////End selection each option
	} 	///END SELECTION TYPE
        break;
	case "multipleselect": //selection type data
	{
$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_open;
$GLOBALS['while_data']["$dfield"] .= $before_element;

	$GLOBALS['while_data']["$dfield"] .= "<select name='$dfield"."[]' $attr multiple='multiple'  id='$id' selected='$dfield'>";
	
	if(isset($display->columns->$dfieldx->from_dbtable))///checking for selection from db else selection from values
	{
////{{{ COUNTINUE FROM HERE SELECT FROM DATABASE FROM DATABASE  }}}/////////
$selection_dbt = $display->columns->$dfieldx->from_dbtable;

$select_tablename = $display->columns->$dfieldx->from_dbtable->tablename;

if(isset($display->columns->$dfieldx->from_dbtable->where))
{
	$select_where = "WHERE " . $display->columns->$dfieldx->from_dbtable->where;
} else 
{
	$select_where = "";
	
}

//include("connectdb.php");
$con = $GLOBALS["con"];

$select_result = run_query( "SELECT * FROM $select_tablename $select_where");


while($select_row = fetch_assoc($select_result))
  {
$option_display = $select_row["$selection_dbt->option_display"];
$option_value = $select_row["$selection_dbt->option_value"];

	//////%%%%%%%%%%%%%%%%%%%
$selected = "";
				 if(isset($reprint->$dfield))	{
				if(in_array($value, $reprint_value))
				{
					$selected = "selected=\"selected\"";
				}
											     }
												 
												 
												 
	////setting for other table	
	else{			
if (isset($display->columns->$dfield->to_other_tr) && isset($display->update))
{
	{//otyher tr values
	$other_tr_details = $display->columns->$dfield->to_other_tr;
	$other_tr_table = $other_tr_details->tablename;
	$other_tr_this_column = $other_tr_details->this_column;
	$other_tr_identifier = $other_tr_details->identifier;
	$identifier_value = $updatedata->$other_tr_identifier; ///Able to access the value of any data of update column
	$other_tr_other_column = $other_tr_details->other_column; ///array carrying other colunmnan to select data from
	$with_select_identifier =  $other_tr_other_column["$other_tr_identifier"];///// column with select value
	}

$multipleselect_result = run_query( "SELECT * FROM $other_tr_table WHERE $with_select_identifier = '$identifier_value'");


while($select_row = fetch_assoc($multipleselect_result))
  {
	  
$this_row = $select_row["$other_tr_this_column"];
$identifier_row = $select_row["$with_select_identifier"];

				if(($value == "$this_row"))
				{
					$selected = "selected=\"selected\"";
				}
		
  }
	
	
		}	
	
		//////if not blank break array in to via separator ////Define separator
	else{/////just from same table
	$raw_select =  $updatedata->$dfield;
	
if(isset($display->columns->$dfield->field_separator))
				{
					$field_separator = $display->columns->$dfield->field_separator;
					
				}
				else
				{
					$field_separator = "+";
					
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
												 
												 
												 
$GLOBALS['while_data']["$dfield"] .= "<option value='$option_value' $selected >$option_display</option>";
  }

		
	}
	
	
else{ /// execution if database selection is not set
	$options_array =	$display->columns->$dfieldx->values_for_select; ///array of select values		
	//	print_r($options_array);
		////Display selection option
		//if(isset())
		foreach ($options_array as $option => $value ) 
				{
					
				$selected = "";
				 if(isset($reprint->$dfield)){
				if(in_array($value, $reprint_value))
				{
					$selected = "selected=\"selected\"";
				}
				}
				
	
	////setting for other table	
	else{			
if (isset($display->columns->$dfield->to_other_tr))
{
	{//otyher tr values
	$other_tr_details = $display->columns->$dfield->to_other_tr;
	$other_tr_table = $other_tr_details->tablename;
	$other_tr_this_column = $other_tr_details->this_column;
	$other_tr_identifier = $other_tr_details->identifier;
	$identifier_value = $updatedata->$other_tr_identifier; ///Able to access the value of any data of update column
	$other_tr_other_column = $other_tr_details->other_column; ///array carrying other colunmnan to select data from
	$with_select_identifier =  $other_tr_other_column["$other_tr_identifier"];///// column with select value
	}

$multipleselect_result = run_query( "SELECT * FROM $other_tr_table WHERE $with_select_identifier = '$identifier_value'");

while($select_row = fetch_assoc($multipleselect_result))
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
	
if(isset($display->columns->$dfield->field_separator))
				{
					$field_separator = $display->columns->$dfield->field_separator;
					
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

				$GLOBALS['while_data']["$dfield"] .= "<option value='$value' $selected >$option</option>";
				}
}
		$GLOBALS['while_data']["$dfield"] .= "</select>";
		$GLOBALS['while_data']["$dfield"] .= $after_element;
		$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_close;
		////End selection each option
	} 	///END SELECTION TYPE
        break;
    case "radio":
	{  
	
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_open;
	$GLOBALS['while_data']["$dfield"] .= $before_element;
		
        $options_array =	$display->columns->$dfieldx->values_for_select; ///array of select values
			
	if(isset($display->columns->$dfieldx->from_dbtable)){
///////db options
			$selection_dbt = $display->columns->$dfieldx->from_dbtable;

	$select_tablename = $display->columns->$dfieldx->from_dbtable->tablename;

	if(isset($display->columns->$dfieldx->from_dbtable->where))
	{
		$select_where = "WHERE " . $display->columns->$dfieldx->from_dbtable->where;
	} else 
	{
		$select_where = "";
		
	}

//	include("connectdb.php");
//$con = $GLOBALS["con"];

	$sqlvv = "SELECT * FROM $select_tablename $select_where";
	//alert($sqlvv);
	$resultvv = run_query($sqlvv);
	if($resultvv){
	while($select_row = fetch_assoc($resultvv))
	  {
		  
	$option_display = $select_row["$selection_dbt->option_display"];
	$option_value = $select_row["$selection_dbt->option_value"];
	$selected = "";
				if($reprint_value==$option_value)
				{
					$selected = "checked=\"checked\"";
				}
	$GLOBALS['while_data']["$dfield"] .= "<input  type='radio' name='$dfield' $attr $selected  id='$id" . "_" . "$option_value' value='$option_value'> $option_display";
	}}
	else{
	//	alert('An error occured in selection');
	}

///////db options
		
		
	}
			
		else	{
		foreach ($options_array as $option_display => $option_value ) 
				{
					
					$selected = "";
				if($reprint_value==$option_value)
				{
					$selected = "checked=\"checked\"";
				}
				$GLOBALS['while_data']["$dfield"] .= "<input type='radio' name='$dfield' $attr $selected id='$id" . "_" . "$option_value' value='$option_value'> $option_display";
				}
	}
	$GLOBALS['while_data']["$dfield"] .= $after_element;
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_close;
	}
        break;
    case "password":
	{	
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_open;
	$GLOBALS['while_data']["$dfield"] .= $before_element;
		
	$GLOBALS['while_data']["$dfield"] .= "	<input type='password' name='$dfield' $attr  id='$id' value='$reprint_value' />\n";
	
	$GLOBALS['while_data']["$dfield"] .= $after_element;
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_close;
	}
        break;

	case "text":
	{
		$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_open;
		$GLOBALS['while_data']["$dfield"] .= $before_element;
	
		$GLOBALS['while_data']["$dfield"] .= "<input type='text' name='$dfield' $attr  id='$id' value='$reprint_value' />\n";
	$GLOBALS['while_data']["$dfield"] .= $after_element;
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_close;	
	}
        break;
		
	case "number":
	{
		$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_open;
		$GLOBALS['while_data']["$dfield"] .= $before_element;
	
		$GLOBALS['while_data']["$dfield"] .= "<input type='number' name='$dfield' $attr  id='$id' value='$reprint_value' />\n";
	$GLOBALS['while_data']["$dfield"] .= $after_element;
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_close;	
	}
        break;	
		
	case "textarea":
	{
$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_open;
$GLOBALS['while_data']["$dfield"] .= $before_element;		
		
	$GLOBALS['while_data']["$dfield"] .= "	<textarea type='text'  id='$id' $attr name='$dfield'>$reprint_value</textarea>\n";
	$GLOBALS['while_data']["$dfield"] .= $after_element;
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_close;
	}
        break;
	case "costumeHTML":  	////// Replacing with customized html content 
	{	
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_open;
	$GLOBALS['while_data']["$dfield"] .= $before_element;
	//echo "Your favourite HTML CODE";
	///////////check set content////////////
	if (isset($display->columns->$dfield->content))
	{
	$htm_content = $display->columns->$dfield->content;
	}
	else
	{
	$htm_content = "";
	}
	///////////End check set content////////////
	
	
	$GLOBALS['while_data']["$dfield"] .= $htm_content;
	$GLOBALS['while_data']["$dfield"] .= $after_element;
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_close;
	}
        break;
	case "costumePHP":		////// Replacing with customized php content eval()
	{	
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_open;
	$GLOBALS['while_data']["$dfield"] .= $before_element;
	//echo "Your favourite PHP CODE";
	///////////check set////////////
	if (isset($display->columns->$dfield->content))
	{
	$php_content =$display->columns->$dfield->content;
	}
	else
	{
	//$GLOBALS['while_data']["$dfield"] . = "echo \"\";";
	}
	///////////End check set////////////
	
	eval($php_content); /// use "" in object  
	
	$GLOBALS['while_data']["$dfield"] .= $after_element;
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_close;
		
	}
        break;

	case "color":
	{	
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_open;
	$GLOBALS['while_data']["$dfield"] .= $before_element;
		
		$GLOBALS['while_data']["$dfield"] .= "<input type='color' name='$dfield' $attr id='$id' value='$reprint_value' />\n";

		$GLOBALS['while_data']["$dfield"] .= $after_element;
		$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_close;
	}
        break;		
	case "range":
	{
$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_open;
$GLOBALS['while_data']["$dfield"] .= $before_element;		

	$GLOBALS['while_data']["$dfield"] .= "<input type='range' name='$dfield' $attr  id='$id' value='$reprint_value' />\n";
	
	$GLOBALS['while_data']["$dfield"] .= $after_element;
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_close;	
	}
        break;
	
	case "file":
	{	
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_open;
	$GLOBALS['while_data']["$dfield"] .= $before_element;
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

$GLOBALS['while_data']["$dfield"] .= "$reprint_value";   ///comment out to prevent showing
	
	$GLOBALS['while_data']["$dfield"] .= "<input type='file' name='$dfield' $attr id='$id' value='' />\n";
	
	$GLOBALS['while_data']["$dfield"] .= $after_element;
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_close;
	}
        break;

	case "checkbox":
	{
		$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_open;
		$GLOBALS['while_data']["$dfield"] .= $before_element;
	
	if(isset($display->columns->$dfieldx->from_dbtable))///checking for selection from db else selection from values
	{
////{{{ COUNTINUE FROM HERE SELECT FROM DATABASE FROM DATABASE  }}}/////////
$selection_dbt = $display->columns->$dfieldx->from_dbtable;

$select_tablename = $display->columns->$dfieldx->from_dbtable->tablename;

if(isset($display->columns->$dfieldx->from_dbtable->where))
{
	$select_where = "WHERE " . $display->columns->$dfieldx->from_dbtable->where;
} else 
{
	$select_where = "";
	
}


$select_result = run_query("SELECT * FROM $select_tablename $select_where");


while($select_row = fetch_assoc($select_result))
  {
$option_display = $select_row["$selection_dbt->option_display"];
$option_value = $select_row["$selection_dbt->option_value"];

	
				$selected = "";
				if(isset($reprint->$dfield)){
				if(in_array($value, $reprint_value))
				{
					$selected = "checked=\"checked\"";
				}
				}
				////setting for other table	
	else{			
if (isset($display->columns->$dfield->to_other_tr) && isset($display->update))
{
	{//otyher tr values
		$other_tr_details = $display->columns->$dfield->to_other_tr;
		$other_tr_table = $other_tr_details->tablename;
		$other_tr_this_column = $other_tr_details->this_column;
		$other_tr_identifier = $other_tr_details->identifier;
		$identifier_value = $updatedata->$other_tr_identifier; ///Able to access the value of any data of update column
		$other_tr_other_column = $other_tr_details->other_column; ///array carrying other colunmnan to select data from
		$with_select_identifier =  $other_tr_other_column["$other_tr_identifier"];///// column with select value
	}

$multipleselect_result = run_query( "SELECT * FROM $other_tr_table WHERE $with_select_identifier = '$identifier_value'");
if($multipleselect_result)
{
while($select_row = fetch_assoc($multipleselect_result))
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
	
		//////if not blank break array in to via separator ////Define separator
	else{/////just from same table
	$raw_select =  $updatedata->$dfield;
	
if(isset($display->columns->$dfield->field_separator))
				{
					$field_separator = $display->columns->$dfield->field_separator;
					
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
				
$GLOBALS['while_data']["$dfield"] .= "<input type='checkbox' $selected value='$value'  $attr id='$id"."_value' name='$dfield"."[]' /> $option";
//echo "<option value='$option_value'>$option_display</option>";
  }

		
	}
else{ /// execution if database selection is not set
	$options_array =	$display->columns->$dfieldx->values_for_select; ///array of select values		
	//	print_r($options_array);
		////Display selection option
		foreach ($options_array as $option => $value ) 
				{
					
				$selected = "";
				if(isset($reprint->$dfield)){
				if(in_array($value, $reprint_value))
				{
					$selected = "checked=\"checked\"";
				}
				}	
					////setting for other table	
	else{			
if (isset($display->columns->$dfield->to_other_tr))
{
	{//otyher tr values
	$other_tr_details = $display->columns->$dfield->to_other_tr;
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

$multipleselect_result = run_query( "SELECT * FROM $other_tr_table WHERE $with_select_identifier = '$identifier_value'");

if($multipleselect_result)
{
while($select_row = fetch_assoc($multipleselect_result))
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
	
	
if(isset($display->columns->$dfield->field_separator))
				{
					$field_separator = $display->columns->$dfield->field_separator;
					
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
										
				$GLOBALS['while_data']["$dfield"] .= "<input type='checkbox' $selected value='$value'  $attr id='$id"."_value' name='$dfield"."[]' /> $option";
				}
}
	

		$GLOBALS['while_data']["$dfield"] .= $after_element;
		$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_close;
		////End selection each option
		
		}
        break;
			
		case "addtypecase":
	{	
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_open;
	$GLOBALS['while_data']["$dfield"] .= $before_element;
	$GLOBALS['while_data']["$dfield"] .= "add other contents";
	$GLOBALS['while_data']["$dfield"] .= $after_element;
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_close;
	}
        break;
				
	default:
	
	{ /////Default types
	$describe_type = describe_type();
if ($row[$describe_type] == "text")
{
	
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_open;
	$GLOBALS['while_data']["$dfield"] .= $before_element;
	$GLOBALS['while_data']["$dfield"] .= "	<textarea type='text' name='$dfield' $attr id='$dfield'>$reprint_value</textarea>\n";
	$GLOBALS['while_data']["$dfield"] .= $after_element;
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_close;
}

else{
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_open;
	$GLOBALS['while_data']["$dfield"] .= $before_element;
$GLOBALS['while_data']["$dfield"] .= "	<input type='text' name='$dfield' $attr  id='$dfield' value='$reprint_value' />\n";
	$GLOBALS['while_data']["$dfield"] .= $after_element;
	$GLOBALS['while_data']["$dfield"] .= $column_formdisplay_close;
	
}

}

////End cases
}
	
	
	
}////End fortypes



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
\$newfield = \$addtional_field->columns->\$dfield->newfield;
\$newfieldtype = \$addtional_field->columns->\$dfield->type;
\$GLOBALS['while_data'][\"\$dfield\"] .=  \$row_begin;
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
$sel_update_where = $display->update->where;
	$update_select_sql="SELECT * FROM $tablename WHERE $sel_update_where";
$obj ="";
		if ($result_update=run_query($update_select_sql))
		{			
	  while ($obj=fetch_assoc($result_update))
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
if ($describe_db_table)
{
while($row = fetch_assoc($describe_db_table))
	{////Check if field is in array that contains data no to return
/////setting identifiers for coulums

/////End setting identifiers for coulums

$describe_fname = describe_fname();
$describe_type = describe_type();
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
$GLOBALS['while_data']["$dfield"] = "";	 	 
$GLOBALS['while_data']["$dfield"] .=$row_begin;	 
	
	 /////////BEFORE ROW//////////
if(isset($addtional_field->columns->$dfield)){
	if (isset($addtional_field->columns->$dfield->position)){
	if ($addtional_field->columns->$dfield->position == "before"){		
//eval($printaddtionals); 

//$printaddtionals = "
$newfield = $addtional_field->columns->$dfield->newfield;
$newfieldtype = $addtional_field->columns->$dfield->type;
//alert($dfield);
$GLOBALS['while_data']["$newfield"] = "";	 
$GLOBALS['while_data']["$newfield"] .= $row_begin;
fortypes($con,$addtional_field,$lang,$addtional_field,$add_free_field,$input_type,$column_textdisplay_open,
$print_lang,$column_textdisplay_close,
 $column_formdisplay_open,$column_formdisplay_close,$newfield,$newfieldtype, $row, $reprint, $updatedata);	
$GLOBALS['while_data']["$newfield"] .= $row_end;
//";
	}
}
}
	 ///////// END BEFORE ROW//////////

///check for additionals
$GLOBALS['while_data']["$dfield"] = "";	
$GLOBALS['while_data']["$dfield"] .= $row_begin;
if(isset($display->columns->$dfield)){
//$newfield = $addtional_field->columns->$dfield->newfield;
if(isset($display->columns->$dfield->type))
{$newfieldtype = $display->columns->$dfield->type;}
else
{$newfieldtype = "text";}


}	

else {
	$newfieldtype = "text";
} 

	/////declared fprtypes
	{//////Print rows fro function
	fortypes($con, $display,$lang,$addtional_field,$add_free_field,$input_type,$column_textdisplay_open,
	$print_lang,$column_textdisplay_close, $column_formdisplay_open,
	$column_formdisplay_close,$dfield, $newfieldtype, $row, $reprint, $updatedata);
							}  /////end of allow print form
$describe_fname = describe_fname();
$newfields[] = $row[$describe_fname];
$GLOBALS['while_data']["$dfield"] .= $row_end;	

	// }

	 
	 /////////AFTER ROW//////////
if(isset($addtional_field->columns->$dfield)){
	if (!isset($addtional_field->columns->$dfield->position)){
			
//eval($printaddtionals); 
	
$newfield = $addtional_field->columns->$dfield->newfield;
$newfieldtype = $addtional_field->columns->$dfield->type;
//alert($dfield);
$GLOBALS['while_data']["$newfield"] = "";	 
$GLOBALS['while_data']["$newfield"] .= $row_begin;
fortypes($con,$addtional_field,$lang,$addtional_field,$add_free_field,$input_type,$column_textdisplay_open,
$print_lang,$column_textdisplay_close,
 $column_formdisplay_open,$column_formdisplay_close,$newfield,$newfieldtype, $row, $reprint, $updatedata);	
$GLOBALS['while_data']["$newfield"] .= $row_end;
}
else 
{
	if (($addtional_field->columns->$dfield->position == "after") || 
	($addtional_field->columns->$dfield->position != "before")){
			
//eval($printaddtionals); 
 $newfield = $addtional_field->columns->$dfield->newfield;
$newfieldtype = $addtional_field->columns->$dfield->type;
//alert($dfield);
$GLOBALS['while_data']["$newfield"] = "";	 
$GLOBALS['while_data']["$newfield"] .= $row_begin;
fortypes($con,$addtional_field,$lang,$addtional_field,$add_free_field,$input_type,$column_textdisplay_open,
$print_lang,$column_textdisplay_close,
 $column_formdisplay_open,$column_formdisplay_close,$newfield,$newfieldtype, $row, $reprint, $updatedata);	
$GLOBALS['while_data']["$newfield"] .= $row_end;
	}

}
}

	 ///////// END AFTERS ROW//////////

	}		
	}		}
	
	else
	{
		//////Under dev
	//	echo mysqli_error($con);
		
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
$describe_fname = describe_fname();
$describe_type = describe_type();

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
		
	
		//$printaddtionals = "
$newfreefield = $free_row[0];
$newfreefieldtype = $free_row[1];
$row[$describe_fname] = $newfreefield; ///find callname
$row[$describe_type] = $newfreefieldtype;
//alert($dfield);
$GLOBALS['while_data']["$newfreefield"] = "";	 
$GLOBALS['while_data']["$newfreefield"] .= $row_begin;
fortypes($con,$display,$lang,$addtional_field,$add_free_field,$input_type,$column_textdisplay_open,
$print_lang,$column_textdisplay_close,
 $column_formdisplay_open,$column_formdisplay_close,$newfreefield,$newfreefieldtype, $row, $reprint, $updatedata);	
$GLOBALS['while_data']["$newfreefield"] .= $row_end;
//";
		
	}

	
}

//////END ADD FREE FIELDS
	

	////End of While
$GLOBALS['post_print'][0] .= $row_begin;

$GLOBALS['post_print'][0] .= $column_textdisplay_open;


///// submission message
$submit_message = "";
if(isset($display->submit_message)){
$submit_message = $display->submit_message;	
}
$GLOBALS['post_print'][0] .= "$submit_message";
/////End submission message

$GLOBALS['post_print'][0] .= $column_formdisplay_close;

$GLOBALS['post_print'][0] .= $column_formdisplay_open;
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

$GLOBALS['post_print'][0] .= "<input type='submit' value='$submit_button' name='submit_$tablename' id='submit_$tablename' $submit_attr /> \n ";	

	
	/////}
	$GLOBALS['post_print'][0] .= $column_formdisplay_close;
	$GLOBALS['post_print'][0] .= $row_end;
	
		$GLOBALS['post_print'][0] .= $container_close;
$GLOBALS['post_print'][0] .="</form><br />";	
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
	if (array_key_exists($skey,$GLOBALS['while_data']))
  {
  $while_sorted["$skey"] = $GLOBALS['while_data']["$skey"];
  }	
}
$field_sort_flip = array_flip($field_sort);
$while_sort_interset = array_diff_key($GLOBALS['while_data'],$field_sort_flip);
$sorted_data = array_merge($while_sorted,$while_sort_interset);

$GLOBALS['renderer'] = array_merge($GLOBALS['pre_print'],$sorted_data,$GLOBALS['post_print']);

////END RENDERER
}





///DEFINATIONS////
$costume_to_db = array();
$display = (object) array();
$lang = (object) array();
$print_form = true;
$function_name = "table";
$table_name = "";
$field_processor = (object) array();		
$sort_array = (object) array();		
$addtional_field = (object) array();		
///Goes up with the function

//$cutarray= array("faculty_shortname", "deleted", "faculty_month_added");
/////////////////////////////////////////////
////////////FIRE FUNCTIONS////////////
///////////////////////////////
//table("faculty", array("faculty_id", "deleted", "faculty_month_added"));

// PARAMETERS: call_user_func_array([PARA 1 String $fuction_name], [PARA 2 array ([PARA_2_1 String $tablename], [PARA_2_2 array_of_columns_to_omit([$column1],$colum2,....) ))
//call_user_func_array('table', array("faculty", array("faculty_id", "deleted")));

$faculty_shortname = "";

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
'where' => 'faculty_id = 1233', //	2345, 1233

						);


///external data
$time = time();
//'columns' => (object) array ( 
{///add field under dev $add_field
}
$add_free_field  = array( //must add proccessors   ////when $add_free_field is declared, parameter 2 must be same as that set in $display->columns->type
					array("house","number"),
					array("location","color"),  /////must still be set in display to choose type
					array("place","select"),
					array("joint","textarea"),
					array("free no display","textarea"),
					//array("","");
						);


{$display = (object) //must be declared
array(	'foo' => 'bar',
		'property' => 'value',
		'filemust' => 0, //// check if file must be upload to allow form submission
		'submit_button' => 'APPLY',
		'insert_callback' => "alert(\"Data inserted Successfully\");",
		'update_callback' => "alert(\"Update Successfully\");",
		//'update_callback' => "alert(\"vvvvvvvvvvvvv\");",
		///@@@@@@@@@@@@@@@@@@@@ Start Printing type @@@@@@@@@@@@@@@@///
		//'separator' => 'div', //div, p, floated div add class values id's
		//'separator' => 'table', //div, p, floated div add class values id's
		'separator' => 'table', //div, p,costume_print, floated div add class values id's
		'costume_print_content' => $costume_print_content, //div, p, floated div add class values id's
		///@@@@@@@@@@@@@@@@@@@@ End Printing type @@@@@@@@@@@@@@@@///
		'submit_attr' => "style=''",
		'update' => $update, /////UPDATE
		'submit_message' => "<i>Read instructions clearfully</i>",
		'form_attr' => "onsubmit='alert(57689'",  ////// What happens on submit of form
		'form_method' => "POST",  ////// method //required /// set as defalut property
		'form_id' => "formxxx",  ////// What happens on submit of form
		'form_action' => "",  ////// What happens on submit of form
		'columns' => (object) array ( 'column1'=> 'money', //checkiing
								'column2' => 'column4',	//checkiing
						
		'faculty_month_added' => (object) array ( 	'type'=> 'multipleselect', //checkbox, multipleselect			
													'attr' => "style=\"background:green;\"",
													'before_label' => 'before label',
													'after_label' => 'after label',
													'before_element' => 'before element',
													'after_element' => 'after element',
													'values_for_select' => array(	"January" => "1", ////The key is displayed & value = value
																					"February" => "2",
																					"March" => "3"
																				),/*
													'to_other_tr' => (object) array(	"tablename" => "othertr", ////The key is displayed & value = value
																						"this_column" => "insid",
																						"identifier"  => "faculty_shortname", ///because of updating
																						"other_column" => array(	'faculty_shortname' => "ffn", ////The key is displayed & value = value
																													'xxtime' 	=> "time" ////set in extra data if vairable is not a name of form item sent
																												)
																					),*/
													'field_separator'	=> "@",
													'extra_data' => "\$time = time(); \$xxtime = time();",    ////e.g 'extra_data' => "\$time = time();",
													
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
		
													'extra_data' => "\$time = time(); \$xxtime = time();",    ////e.g 'extra_data' => "\$time = time();",
													
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
																			)
												),
		'faculty_fullname' => (object) array ( 	
												'type'=> 'color', 									
											//	'type'=> 'costumePHP', 
												'content'=> " echo \" <h1>\$print_lang YEYOOO PHP</h1>\";"  ///use "" for string   data field: $dfield or $print_lang
													
												),	
'faculty_logo' => (object) array ( 	
												'type'=> 'file',
												'max_size'=> '500000000', ///file size ib bytes
												'folder'=>'downloads', ///folder to move file too
												'file_type'=>'', //// comment if not an image or remove rule
										 
										//		'type'=> 'costumePHP', //'costumeHTML',customisizing
										//		'rename_rule'=> $faculty_shortname, 
												'rename_rule'=> time(). "falculty", 
												
												'phprequired'=> "",  /////if decleared block insert
									//			'overwrite'=> "1", ////set one not to overwrite
												'content'=> " echo \" <h1>\$print_lang YEYOOO PHP</h1>\";"  ///use "" for string   data field: $dfield or $print_lang
													
												),	
		'faculty_text' => (object) array ( 	
													'type'=> 'text',
													'server_validate' => true,
													'ValidateAsDate' => '1', //ValidateAsDate, ValidateAsEmail, ValidateAsFloat, ValidateAsInteger
													'ValidateAsDateErrorMessage' => 'This not a is Date',
													'DateSeparator' => "-"	//no slash (/)fix letter
													),	
	'faculty_campus' => (object) array ( 	
													'type'=> 'file',
													'max_size'=> '500000000', ///file size ib bytes
													'folder'=>'downloads', ///folder to move file too
													'file_type'=>'', //// comment if not an image or remove rule
											 
												//	'type'=> 'costumePHP', 'costumeHTML',customisizing
											//		'rename_rule'=> $faculty_shortname, 
													'rename_rule'=> time(). "falculty" . "campus", 
													
													'phprequired'=> "",  /////if decleared block insert
										//			'overwrite'=> "1", ////set one not to overwrite
													'content'=> " echo \" <h1>\$print_lang YEYOOO PHP</h1>\";"  ///use "" for string   data field: $dfield or $print_lang
														
													),	

'house' => (object) array ( 	
												'type'=> 'number', 									
											//	'type'=> 'costumePHP', 
												'content'=> " echo \" <h1>\$print_lang YEYOOO PHP</h1>\";"  ///use "" for string   data field: $dfield or $print_lang
													
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
												'content'=> " echo \" <h1>\$print_lang YEYOOO PHP</h1>\";"  ///use "" for string   data field: $dfield or $print_lang
													
												),													
		'faculty_day_added' => (object) array ( 	'type'=> 'select',
												//'code'=> '' ///Code to be executed could unset other objects
													'values_for_select' => array(	"1" => "1",
																					"2" => "2",
																					"3" => "3",
																					"4" => "4"
																				)
												)
	)
	);
}
	

{$addtional_field = (object) array(/////addition form item not from f\databases tables  ///Adds to existion form items 
	'columns' => (object) array (
'faculty_shortname' => (object) array ( 	
						'newfield'=> '2ndpasswprd',				
						'type'=> 'select', 
						'position'=> 'before', ////position before, after
						'values_for_select' => array(	"Jatttt" => "1", ////The key is displayed & value = value
														"February" => "2",
														"March" => "3"
													)
													
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
	

	
	
	
	///costume data to add to db
//"" => "",

//$print_form = true;
$function_name = "table";
$table_name = "faculty";
$exception = array("deleted", "faculty_time_todb");
$sort_array = array(/*'location',*/'faculty_description','faculty_campus','institution_id','faculty_code','faculty_day_added','faculty_year_added');
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
'institution_id' => 'INSTITUTION ID',
'cvupload' => '<b>CV UPLOADER</b>',
'2ndpasswprd' => '<b>Verify password</b>',
'faculty_logo' => '<b>FACULTY LOGO</b>',
'joint' => '<b>JOINT</b>'
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
					\$column_sql_value .= \"`\$name`,\";
					\$row_sql_value .= \"'\$value',\";									
					"
										), */
'house' => (object) array(
					'content' => "
					echo \" <h4>\$name - \$value </h4> \";
				
					///////addiing proccessor to database
													
					"
										),
'location' => (object) array(), //////use undesrscore for spaced values
'2ndpasswprd' => (object) array(),
'place' => (object) array(),	
'joint' => (object) array(),										
'cvupload' => (object) array(),
'free_no_display' => (object) array(),
										
					
										);		
}


call_user_func_array($function_name, ///function to fire
 array($table_name, //// database table name
		$print_form,       //wheher to print fom
		$exception, //omitted columns                                                                                          
		$sort_array, //omitted columns                                                                                          
		$display,   //// handles the display of form elemnt type and values object 
		$addtional_field,   //// extra fields not from db 
		$add_free_field,   //// extra fields not from db free
		$field_processor,   //// handles the customizes proccessors for field, verification checking to validate
		$costume_to_db, /// add addional data to database 
		$lang          /////name to display of form item HTML compatible 
	 
	 )   

 );
 
 
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
//print_r($while_data);
/////RENDERING


////xxxx
/////xxx
/////END RENDERING
//echo "lions";
 $caster = $renderer;
//form_render($caster);
form_render($renderer);
//echo $renderer['location'];
//echo $renderer['house'];

?>

<?php die; ?>

<html>
<head><title>Add Institution</title>
<link rel="stylesheet" type="text/css" href="nbootstrap.css" />
<script type="text/javascript" src="nbootstrap.js"></script>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
	/**
 *
 */
$(document).ready(function() {
	var p = $("#uploadPreview");

	// prepare instant preview
	$("#uploadImage").change(function(){
		// fadeOut or hide preview
		p.fadeOut();
		// prepare HTML5 FileReader
		var oFReader = new FileReader();
		oFReader.readAsDataURL(document.getElementById("uploadImage").files[0]);
		oFReader.onload = function (oFREvent) {
	   		p.attr('src', oFREvent.target.result).fadeIn();
		};
	});
});
$("input").attr("required","required");
	</script>
	</head>
	<body>
<style>
tr:hover{background:#bbbbbb;}
input[type=text] {width:100%;}
</style>

<form action='' method='post'
enctype='multipart/form-data'>

<h1>Add institution FORM</h1>
<table style="width:70%;" border='1'>
<tr><td style="width:20%;">

institution_shortname: 	

</td><td style="width:80%;">
<input type='text' name='institution_shortname' value='' />
</tr>
<tr><td>
<label for='file'>


PASSPORT:</label>


</td><td>
<input name='room' type='file'  title='Choose picture' id="uploadImage" /><br />
<img id="uploadPreview" style="display:;" width='200px' height='200px' />
</td></tr>

</table>
</form>


<script>

$("input").attr("required","required");
$("textarea").attr("required","required");
$("select").attr("required","required");
	</script>
</body>
</html>



<script>
function validateForm() {
    var x = document.forms["myForm"]["fname"].value;
    if (x == null || x == "") {
        alert("Name must be filled out");
        return false;
    }
}
</script>

 <form name="myForm" action="demo_form.asp" onsubmit="return validateForm()" method="post">
Name: <input type="text" name="fname">
<input type="submit" value="Submit">
</form>

////// email validation

<!DOCTYPE html>
<html>
<head>
<script>
function validateForm() {
    var x = document.forms["myForm"]["email"].value;
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
        alert("Not a valid e-mail address");
        return false;
    }
}
</script>
</head>

<body>
<form name="myForm" action="demo_form.asp" onsubmit="return validateForm();" method="post">
Email: <input type="text" name="email">
<input type="submit" value="Submit">
</form>
</body>

</html>
