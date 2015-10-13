<?php
class ToolsShell extends AppShell {
	
	public $uses = array();
	
//	public function main() {
//		set_time_limit(0);
//	}
	
	public function forceUtf8() {
//		App::uses('FeedItem', 'Model');
		App::import('Model', 'FeedItem');
		$this->FeedItem = new FeedItem();
		set_time_limit(0);
		
		$feed_count = $this->FeedItem->find('count');
		$feed_loaded = 0;
		
		while($feed_loaded < $feed_count) {
			$feeds = $this->FeedItem->find('all', array('limit' => 200, 'offset' => $feed_loaded));
			foreach($feeds as $feed) {
				$feed['FeedItem']['ItemTitle'] = forceUTF8($feed['FeedItem']['ItemTitle']);
				$feed['FeedItem']['ItemDescription'] = forceUTF8($feed['FeedItem']['ItemDescription']);

//				debug($feed);

				$this->FeedItem->create();
				$this->FeedItem->save($feed);
				
				$feed_loaded ++;

				$this->out('Item saved: ' . $feed['FeedItem']['id']);
			}
		}
	}
	
	public function analyzecountries() {
		App::import('Model', 'FeedItem');
		App::import('Model', 'Country');
		App::import('Model', 'FeedItemsTag');
		
		$this->FeedItem = new FeedItem();
		$this->Country = new Country();
		$this->FeedItemsTag = new FeedItemsTag();
		
		set_time_limit(0);
		
		$feed_count = $this->FeedItem->find('count');
		$feed_loaded = 0;
	
		while($feed_loaded < $feed_count) {
			$feeds = $this->FeedItem->find('all', array('limit' => 200, 'offset' => $feed_loaded, 'recursive' => -1));
			foreach($feeds as $feed) {
				
				$text = forceUTF8(strip_tags($feed['FeedItem']['ItemTitle'] . ' ' . $feed['FeedItem']['ItemDescription']));
				$tags = $this->Country->analyze($text);

				foreach($tags as $tag) {
					if(!empty($tag)) {
						$this->FeedItemsTag->create();
						$this->FeedItemsTag->set('feed_item_id', $feed['FeedItem']['id']);
						$this->FeedItemsTag->set($tag);
						$this->FeedItemsTag->save();
					}
				}
				
				
				$feed_loaded ++;

				$this->out('Item processed: ' . $feed['FeedItem']['id']);
			}
		}
	}
}