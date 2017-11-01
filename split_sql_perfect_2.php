
<?php


$sql = "UPDATE faculty SET faculty_description = '0\'\';009\r\n',institution_id = '2',
faculty_day_added = '8',faculty_year_added = '3',faculty_id = '12334',
faculty_fullname = '1507884831',faculty_shortname = '1',faculty_month_added = '2',
faculty_text = '4-87-3920',faculty_time_todb = '1507890233' WHERE faculty_id = 12334; 
DELETE FROM othertr2 WHERE ffn = '12338';
DELETE FROM othertr3 WHERE ffn = '12338';
DELETE FROM othertr3 WHERE ffn = '12338';
DELETE FROM othertr3 WHERE ffn = '12338';
DELETE FROM othertr3 WHERE ffn = '12338';
DELETE FROM othertr3 WHERE ffn = '12338';
DELETE FROM othertr3 WHERE ffn = '12338';
DELETE FROM othertr3 WHERE ffn = '12338';
DELETE FROM othertr3 WHERE ffn = '12338';
DELETE FROM othertr3 WHERE ffn = '12338';
DELETE FROM othertr3 WHERE ffn = '1233;;;;;;;;;8';
DELETE FROM othertr3 WHERE ffn = '12338';
DELETE FROM othertr3 WHERE ffn = '12338\'\'\'\'';
DELETE FROM othertr3daadada WHERE ffn = '12338';
DELETE FROM otherthghjghjghgjhghghghghjghjg jh jghghghghghjghj gjghr3 WHERE ffn = '12338';
DELETE FROM othertr3 WHERE ffn = '12338';
DELETE FROM othertr3 WHERE ffn = '12338';
DELETE FROM othertrxxxxx WHERE ffn = '123389';";


$sql_string  = preg_split('//', $sql, -1, PREG_SPLIT_NO_EMPTY);
$positions = array();
$close_query = array();
$sub_query_array = array();
$sql2 = $sql;


$num_query = count($sql_string);

for($i=0;$i<$num_query;$i++){
	if($sql_string[$i] == "'"){
		if($i!=0){
		$ic = $i-1;
		$ix = $i+1;
			if($sql_string[$ic ] != "\\"){	
			array_push($positions,$i);
			}	
		}
	}

	
	if($sql_string[$i] == ";"){
		$max_length = count($positions);
		$this_sql_string = $sql_string[$i];
		if(($max_length%2)==0){
		$close_query[] = $i;
		}
		
	}
}

$count_query = count($close_query);


////
for($j=0;$j<$count_query;$j++){
	$cut_length = $close_query[$j];
	$sj = $j-1;
	
	if($j!=0){
		$cut_length = 	$cut_length - $close_query[$sj];
	}
	
	$start = 1;
	$end = 0;
	if($j==0){
		$start = 0;
		$end = 1;
	}
	
	$fill_up = 0;
	$all_length= strlen($sql2);
	$reserve = $cut_length - $all_length;
	$sub_query = substr($sql2,$start,$cut_length+$end);
	$sql2 = substr($sql2,strlen($sub_query),strlen($sql2));
	$sub_query_array[] = $sub_query;	
}

echo "<pre>";
echo $sql;
echo "</pre>";
echo "<br />";


echo "<pre>";
print_r($sub_query_array);
echo "</pre>";