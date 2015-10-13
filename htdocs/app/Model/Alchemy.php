<?php
class Alchemy extends AppModel {
	public $name = 'Alchemy';
	var $useDbConfig = 'alchemy';
	var $useTable = false;
	
	/*
	 * Analyzes $string and format results for saving in FeedItemsTag model
	 */
	public function analyze($string) {
		try {
			$resutls = $this->TextGetRankedNamedEntities($string);
			
		} catch (Exception $e) {
			$this->log('Impossible to access API', 'AlchemyAPI');
			return false;
		} debug($resutls);
		
		if(!$resutls || empty ($resutls['entities'])) {
			return false;
		}
		
		$entities = $resutls['entities']; 
		$return = array();
		foreach($entities as $entity) {
			$return[] = array(
				'name' => $entity['text'],
				'normalized_value' => isset($entity['disambiguated']['name']) ? $entity['disambiguated']['name'] : '',
				'class' => isset($entity['type']) ? $entity['type'] : NULL,
				'count' => $entity['count'],
				'relevance' => $entity['relevance'],
				'raw_data' => serialize($entity),
				'source' => 'Alchemy'
			);
		}
		debug($return);
		return $return;
	}
}