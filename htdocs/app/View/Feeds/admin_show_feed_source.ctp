<?php 
echo $this->Html->tag('h2', $title);
if($source) {
	echo $this->Html->div('rss-block', htmlentities($source));
} else {
	'Rss source not available';
}

?>