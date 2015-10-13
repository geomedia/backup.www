<?php
App::uses('AppModel', 'Model');
/**
 * FeedItemsTag Model
 *
 * @property FeedItem $FeedItem
 * @property FeedTag $FeedTag
 */
class FeedItemsTag extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'full_name';
	public $virtualFields = array(
		'full_name' => 'CONCAT(FeedItemsTag.normalized_value, " - ", FeedItemsTag.name)'
	);
	public $actsAs = array('Containable');
	

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'FeedItem' => array(
			'className' => 'FeedItem',
			'foreignKey' => 'feed_item_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true
		),
		'FeedTag' => array(
			'className' => 'FeedTag',
			'foreignKey' => 'feed_tag_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true
		),
		'Country' => array(
			'className' => 'Country',
			'foreignKey' => 'country_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true
		)
	);
	
	public function beforeSave($options = array()) {
		if((!isset($this->data['FeedItemsTag']['normalized_value'])
			|| empty($this->data['FeedItemsTag']['normalized_value']))
				&& !isset($this->data['FeedItemsTag']['id'])) {
			$this->data['FeedItemsTag']['normalized_value'] = $this->normalize($this->data['FeedItemsTag']['name']);
		}
		
		//find the related FeedTag and saves its id
		$relatedFeedTag = $this->FeedTag->find('first', array(
			'conditions' => array(
				'normalized_value' => $this->data['FeedItemsTag']['normalized_value']
			),
			'fields' =>array('id'),
			'recursive' => -1
		));
		
		if(!empty($relatedFeedTag)) {
			//associate FeedTag if tag exists
			$this->set('feed_tag_id', $relatedFeedTag['FeedTag']['id']);
		} else {
			//create a new FeedTag if the tag doesn't exists
			$this->FeedTag->create();
			$this->FeedTag->save(array(
				'FeedTag' => array(
					'normalized_value' => $this->data['FeedItemsTag']['normalized_value']
				)
			));
			$this->set('feed_tag_id', $this->FeedTag->id);
		}
		
		return parent::beforeSave($options);
	}
	
	//try to get the best normalized value for a given name
	public function normalize($name) {
		//find other normalized fields
		$normalized = $this->find('first', array(
			'conditions' => array(
				'name' => $name,
				'normalized_value IS NOT NULL'
			),
			'fields' => array('normalized_value', 'count(normalized_value) as number'),
			'group' => 'normalized_value',
			'order' => 'number DESC',
			'recursive' => -1
		));
		//debug($normalized);//TODO verificare questa funzione!
		if(empty($normalized)) {
			return $name;
		} else {
			return($normalized['FeedItemsTag']['normalized_value']);
		}
		return false;
	}
	
	
}
