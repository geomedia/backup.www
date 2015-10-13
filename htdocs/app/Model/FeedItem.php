<?php
App::uses('AppModel', 'Model');
/**
 * FeedItem Model
 *
 * @property Feed $Feed
 * @property FeedTag $FeedTag
 */
class FeedItem extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	public $virtualFields = array(
		'name' => 'CONCAT(FeedItem.ItemTitle, " - ", FeedItem.ItemPubDate)'
	);
	public $actsAs = array('Containable');

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Feed' => array(
			'className' => 'Feed',
			'foreignKey' => 'feed_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public $hasMany = array(
		'FeedItemsTag'
	);
	
	public function beforeSave($options = array()) {
		parent::beforeSave($options);
		
//		debug($this->data);
		
		$this->mapFields();
		$this->setInternalValues();
		
		if(isset($this->data['FeedItem']['CreatedUniqueID'])) {
			if($this->hasAny(array('CreatedUniqueID' => $this->data['FeedItem']['CreatedUniqueID']))) {
				//don't save existing records
				return false;
			}
		}
		return true;
	}
	
	public function mapFields() {
		foreach($this->data['FeedItem'] as $field => $value) {
			switch($field) {				
				case 'guid':
					if(isset($this->data['FeedItem']['guid'])) {
						$this->data['FeedItem']['CreatedUniqueID'] = $this->data['FeedItem']['guid'];
					} else {
						$this->data['FeedItem']['CreatedUniqueID'] = md5($item['title'].$item['description']);
					}
					$this->data['FeedItem']['ItemGuid'] = $value;
					unset($this->data['FeedItem'][$field]);
					break;
					
				case 'title':
					$this->data['FeedItem']['ItemTitle'] = $value;
					unset($this->data['FeedItem'][$field]);
					break;
				
				case 'link':
					$this->data['FeedItem']['ItemLink'] = $value;
					unset($this->data['FeedItem'][$field]);
					break;
				
				case 'description':
					$this->data['FeedItem']['ItemDescription'] = $value;
					unset($this->data['FeedItem'][$field]);
					break;
				
				case 'pubDate':
					$this->data['FeedItem']['ItemPubDate'] = $value;
					$this->data['FeedItem']['ItemPubDate_t'] = date('Y-m-d H:i:s', strtotime($value));
					unset($this->data['FeedItem'][$field]);
					break;
				
				case 'author':
					if(is_array($value) && isset($value['@'])) {
						$value = $value['@'];
					}
					$this->data['FeedItem']['ItemAuthor'] = $value;
					unset($this->data['FeedItem'][$field]);
					break;
					
				case 'category':
					if(is_array($value)) {
						if(isset($value['@domain'])) {
							$this->data['FeedItem']['ItemCategoryDomain'] = $value['@domain'];
						}
						if(isset($value['@'])) {
							$value = $value['@'];
						} else {
							$value = '';
						}
					}
					$this->data['FeedItem']['ItemCategory'] = $value;
					unset($this->data['FeedItem'][$field]);
					break;
					
//				case '';
//					$this->data['FeedItem'][''] = $value;
//					unset($this->data['FeedItem'][$field]);
//					break;
			}
		}
	}
	
	public function setInternalValues() {
		$this->data['FeedItem']['ItemAddedTime'] = date('Y-m-d H:i:s');
		
		$other_fields = array_diff(array_keys($this->data['FeedItem']), array_keys($this->schema()));
		
		$other_fields_values = array();
		foreach($other_fields as $field) {
			$other_fields_values[$field] = $this->data['FeedItem'][$field];
		}
		
		$this->data['FeedItem']['other_fields'] = serialize($other_fields_values);
	}

}
