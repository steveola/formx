<?php

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

//echo $target_dir;
//$display->columns->$name->folder
$target_file = $target_dir . basename($_FILES["$name"]["name"]);

//$target_file = $target_dir . basename($_FILES["$name"]["name"]);


$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["$name"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
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
// Allow certain file formats
if(
$imageFileType != "jpg" && 
$imageFileType != "png" && 
$imageFileType != "jpeg" && 
$imageFileType != "gif" && 
$imageFileType != "GIF" && 
$imageFileType != "JPG" && 
$imageFileType != "PNG" && 
$imageFileType != "JPEG"
	) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
	
	
	
    if (move_uploaded_file($_FILES["$name"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["$name"]["name"]). " has been uploaded.";
		
		if($display->columns->$name->rename_rule){/////RENAME
		$newname = $display->columns->$name->rename_rule;
		$newnamex = $target_dir . $newname . "." . $imageFileType;
		rename($target_file, $newnamex);
		echo " and RENAMED as $newnamex";
		
		$array_of_uploaded_filename[] = $newnamex; 
		
		///rrrrrr
		$newnamedb = $newname . "." . $imageFileType;
		$column_sql_value .= "`$name`,";
		$row_sql_value .= "'$newnamedb',"; 
		///rrrrrrr
		}//// END RENAME
    } else {
        echo "Sorry, there was an error uploading your file.";
		if(isset($display->columns->$name->phprequired))
			{
			$uploadOk =0;
			}
    }
}
?> 