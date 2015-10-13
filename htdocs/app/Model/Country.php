<?php
App::uses('AppModel', 'Model');
/**
 * Country Model
 *
 * @property CountryTag $CountryTag
 * @property FeedItemsTag $FeedItemsTag
 */
class Country extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	var $useDbConfig = 'default';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'CountryTag' => array(
			'className' => 'CountryTag',
			'foreignKey' => 'country_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'FeedItemsTag' => array(
			'className' => 'FeedItemsTag',
			'foreignKey' => 'country_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	public function analyze($string) {
		$tags = $this->CountryTag->find('all');
		$return = array();
		
		foreach ($tags as $tag){
			
			$name = $tag['CountryTag']['name'];
			$country_id = $tag['CountryTag']['country_id'];
			$normalized_value = $tag['Country']['name'];
			
			$count = substr_count($string, $name);
			
			if($count > 0) {
				$return[] = array(
					'name' => $name,
					'normalized_value' => $normalized_value,
					'class' => 'Country',
					'count' => $count,
					'relevance' => 0,
					'raw_data' => '',
					'source' => 'Country'
				);
			}
		}
		
		return $return;
	}

}
