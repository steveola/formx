<?php

if(" " == "")
{
	echo "true";
}


?>

<hr />


<?php
////////	EMAIL
if(preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-._]+..[a-zA-Z0-9-.]+$/",	"hhh@jbg.hhh_ggg-hhy.com")) ////email
	{
		echo "true";
	}
	else
	{
		echo "false";
	}

	echo "<br />";
////// INTEGER
	
	if(preg_match("/^[0-9]+$/",	"676767"))/////interger
	{
		echo "true";
	}
	else
	{
		echo "false";
	}
	
		echo "<br />";
	
////FLOAT	
	$number = "1.1";
	$num_split = explode(".", $number);
	$array_cnum = count($num_split);
	if(preg_match("/^[0-9.]+[0-9.]+$/",	$number) && ($array_cnum < 3))
	{
		echo " float true";
	}
	else
	{
		echo "float false";
	}
	
	echo "<br />";
	
	
////DATE DATE TIME   /////set date separator ///may be in config /// define format for date and time too
	$number = "1-8-1000";
	$separator = "-";
	$num_split = explode($separator, $number);
	$array_cnum = count($num_split);
	if(preg_match("/^[0-9]+-[0-9]+-[0-9]+$/",	$number) && ($array_cnum == 3))
	{
		echo "date true";
	}
	else
	{
		echo "date false";
	}
		
	
	
	
	
	



?>
<pre>





ValidateAsNotEmpty

ValidateAsDifferentFromText

ValidateMinimumLength

ValidateRegularExpression

ValidateAsNotRegularExpression

ValidateAsInteger

ValidateAsFloat

ValidateAsEmail

ValidateAsCreditCard

ValidateAsEqualTo

ValidateAsDifferentFrom

ValidateAsSet

ValidationServerFunction

</pre>