<?php

$filename = 'feed_items';
$separator = ',';
$encloser = '"';

//headers
header("Content-type:text/csv"); 
header("Content-disposition:attachment;filename=".$filename."_".date('YmdHis').'.csv');

//csv headers
//$fields = array_keys($fields);

//foreach($fields as $n => $field) {
//	echo $encloser . $field . $encloser . $separator;
//}
//echo PHP_EOL;
//
//foreach($feedItems as $item) {
//	
//	foreach($fields as $n => $field) {		
//		if(isset($item['FeedItem'][$field])) {
//			echo $encloser . str_replace(array($encloser, PHP_EOL), array("\\".$encloser, ''), $item['FeedItem'][$field]) . $encloser;
//		}
//		echo $separator;
//	}
//	echo PHP_EOL;
//}


$fp = fopen('php://temp', 'r+');

fputcsv($fp, $fields, $separator, $encloser);
foreach($feedItems as $item) { 
	fputcsv($fp, $item['FeedItem'], $separator, $encloser);
}

rewind($fp);
echo stream_get_contents($fp);
fclose($fp);