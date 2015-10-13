<?php
App::uses('AppModel', 'Model');
class FeedItemApi extends AppModel {
	public $name = 'FeedItemApi';
	public $useTable = false;
	
	public $APIsources = array('Alchemy', 'OpenCalais', 'Country');
	public $connectedModels = array('Feed', 'FeedItemsTag', 'FeedItem', 'FeedTag');
	
	public function __construct($id = false, $table = null, $ds = null) {
		
		//construct and instantiate API models
		foreach($this->APIsources as $APIsource) {
			App::import('Model', $APIsource);
			$this->{$APIsource} = new $APIsource();
		}
		
		//construct and instantiate connected models
		foreach($this->connectedModels as $connectedModel) {
			App::import('Model', $connectedModel);
			$this->{$connectedModel} = new $connectedModel();
		}
		
		return parent::__construct($id, $table, $ds);
	}
	
	/*
	 * 
	 */	
	public function analyze() {
		$items = $this->get_items_to_analyze();
		foreach($items as $item) { 
			$id = $item['FeedItem']['id'];
			$text = forceUTF8(strip_tags($item['FeedItem']['ItemTitle'] . ' ' . $item['FeedItem']['ItemDescription']));
			
			$tags = array();
			
			foreach($this->APIsources as $APIsource) {
				if(method_exists($this->$APIsource, 'analyze')) {
					$new_tags = $this->$APIsource->analyze($text);
					if($new_tags) {
						$tags = array_merge($tags, $new_tags);
					}
				} else {
					throw new CakeException('Error: ' . $APIsource . ' must implement the analyze() method.');
				}
			}
			
			$tags = array_filter($tags); 
			
			foreach($tags as $tag) {
				if(!empty($tag)) {
					$this->FeedItemsTag->create();
					$this->FeedItemsTag->set('feed_item_id',$id);
					$this->FeedItemsTag->set($tag);
					$this->FeedItemsTag->save();
				}
			}
			
			$this->FeedItem->id = $id;
			$this->FeedItem->saveField('analyzed', 1, false);
		}
	}
	
	
	/*
	 * find the feeds to analyze
	 */
	public function get_feeds_to_analyze() {
		$feeds = $this->Feed->find('all', array(
			'conditions' => array(
				'active' => 1,
				'analyze' => 1
			),
			'fields' => array('id'),
			'recursive' => -1
		));
		$feeds = Set::extract('/Feed/id', $feeds);
		return $feeds;
	}
	
	/*
	 * find items to analyze
	 */
	public function get_items_to_analyze($limit = 200) {
		$feeds = $this->get_feeds_to_analyze();
		$items = $this->FeedItem->find('all', array(
			'conditions' => array(
				'feed_id' => $feeds,
				'analyzed' => 0
			),
			'fields' => array(
				'id', 'ItemTitle', 'ItemDescription'
			),
			'order' => 'id ASC',
			'limit' => $limit,
			'recursive' => -1
		));
		return $items;
	}
}