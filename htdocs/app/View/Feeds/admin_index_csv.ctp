<?php

$filename = 'feeds';
$separator = ',';
$encloser = '"';

//headers
header("Content-type:text/csv"); 
header("Content-disposition:attachment;filename=".$filename."_".date('YmdHis').'.csv'); 

//csv headers
//$fields = array_keys($fields);
foreach($fields as $n => $field) {
	echo $encloser . $field . $encloser . $separator;
}
echo "\n";

foreach($feeds as $item) {
	foreach($fields as $n => $field) {		
		if(isset($item['Feed'][$field])) {
			echo $encloser . utf8_encode(str_replace(array($encloser), array("\\".$encloser), $item['Feed'][$field])) . $encloser;
		}
		echo $separator;		
	}
	echo "\n";
}