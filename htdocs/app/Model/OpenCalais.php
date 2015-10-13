<?php
class OpenCalais extends AppModel {
	public $name = 'OpenCalais';
	var $useDbConfig = 'opencalais';
	var $useTable = false;
	
	/*
	 * Analyzes $string and format results for saving in FeedItemsTag model
	 */
	public function analyze($string) {
		try {
			$results = $this->query(strip_tags($string));
		} catch (Exception $e) {
			return false;
		}
		
		if(empty($results)) {
			return false;
		}
		
		$return = array();
		foreach($results as $result) {
			$return[] = $this->analyze_result($result);
		}
		return $return;
	}
	
	private function analyze_result($result) {
		return array(
			'name' => $result['name'],
			'normalized' => $result['normalized'],
			'class' => $result['entity'],
			'count' => $result['count'],
			'relevance' => $result['relevance'],
			'raw_data' => $result['raw_data'],
			'source' => 'OpenCalais'
		);
	}
}