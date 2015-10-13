<?php
App::uses('AppModel', 'Model');
/**
 * FeedUpdate Model
 *
 * @property Feed $Feed
 */
class FeedUpdate extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	public $virtualFields = array(
		'name' => 'CONCAT(FeedUpdate.feed_id, " - ", FeedUpdate.created)'
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
	
	public function mostActiveFeeds($time = '1week') {
		$updates = $this->find('all', array(
			'conditions' => array(
				'FeedUpdate.result' => 1,
				'FeedUpdate.created >= ' => date('Y-m-d H:i', strtotime('-'.$time))
				),
			'fields' => array(
				'FeedUpdate.id',
				'FeedUpdate.feed_id',
				'SUM(new_items) as sum_items'
			),
			'group' => 'FeedUpdate.feed_id',
			'order' => 'sum_items DESC',
			'limit' => 10
		));
		$feeds_id = array_filter(Set::extract('/FeedUpdate/feed_id', $updates));
		$feeds = $this->Feed->find('all', array(
			'conditions' => array('Feed.id' => $feeds_id),
			'fields' => array('id', 'title'),
			'recursive' => -1
		));
		$feeds = Set::combine($feeds, '{n}.Feed.id', '{n}.Feed.title');
		
		foreach($updates as $n => $update) {
			$updates[$n]['Feed']['title'] = $feeds[$update['FeedUpdate']['feed_id']];
		}
		return $updates;
	}
	
	public function criticalFeeds($time = '3hours') {
//		App::uses('View', 'View');
//		App::uses('TimeHelper', 'View/Helper');
//		$TimeHelper = new TimeHelper(new View(new Controller()));
		
//		$lastInsertedRecords = $this->find('all', array(
//			'fields' => array('result', 'created', 'feed_id'),
//			'order' => 'FeedUpdate.created DESC',
//			'group' => 'FeedUpdate.feed_id',
//			'recursive' => -1
//		));
		
		//find all feeds with last inserted result = 0
//		$inactiveFeeds = $this->query('
//			SELECT LastUpdates.feed_id FROM
//				(SELECT `FeedUpdate`.`result`, `FeedUpdate`.`created`, `FeedUpdate`.`feed_id`
//					FROM `feed_updates` AS `FeedUpdate`
//					GROUP BY `FeedUpdate`.`feed_id`
//					ORDER BY `FeedUpdate`.`created` DESC)
//				as LastUpdates
//			WHERE LastUpdates.result = 0');
//		$inactiveFeeds = Set::extract('/LastUpdates/feed_id', $inactiveFeeds);
		
//		$inactiveFeeds = $this->query('
//			SELECT `LastUpdates`.`feed_id`, `LastUpdates`.`result` FROM 
//				(SELECT `FeedUpdate`.`feed_id`, MAX(CAST(created) AS CHAR) as created, `FeedUpdate`.`result`
//				FROM `feed_updates` AS `FeedUpdate`
//				GROUP BY feed_id
//				ORDER BY `FeedUpdate`.`created` DESC) as `LastUpdates`
//			WHERE `LastUpdates`.`result` != 1');
//		$inactiveFeeds = Set::extract('/LastUpdates/feed_id', $inactiveFeeds);
		
		$inactiveFeeds = $this->query('
			SELECT LastUpdate.feed_id, LastUpdate.created, LastUpdate.result 
			FROM (SELECT feed_id, MAX(created) as created FROM feed_updates GROUP BY feed_id) as Updates
			INNER JOIN feed_updates as LastUpdate
				ON LastUpdate.feed_id = Updates.feed_id AND LastUpdate.created = Updates.created
			WHERE LastUpdate.result = 0'); //debug($inactiveFeeds);
		$inactiveFeeds = Set::extract('/LastUpdate/feed_id', $inactiveFeeds);
		
		//very slow
//		$inactiveFeeds = $this->query('
//			SELECT `FeedUpdate`.`feed_id`, `FeedUpdate`.`result`, `FeedUpdate`.`created` FROM 
//				`feed_updates` AS `FeedUpdate`
//			WHERE (
//				SELECT count(F.id) FROM feed_updates as F
//				WHERE F.feed_id = FeedUpdate.feed_id 
//					AND F.created > FeedUpdate.created) <= 1
//				AND `FeedUpdate`.`result` != 1'); //debug($inactiveFeeds);
//		$inactiveFeeds = Set::extract('/FeedUpdate/feed_id', $inactiveFeeds);
		
//		$inactiveFeeds = $this->find('all', array(
//			'fields' => array(
//				'feed_id',
//				'MAX(created) as created',
//				'result'
//			),
//			'group' => 'feed_id',
//			'order' => 'created DESC',
//			'recursive' => -1
//		)); debug($inactiveFeeds); exit;
//		$inactiveFeeds = Set::extract('/FeedUpdate/feed_id', $inactiveFeeds);
		
		//find feeds titles
		$feeds = $this->Feed->find('all', array(
			'conditions' => array(
				'id' => $inactiveFeeds,
				'active' => 1),
			'fields' => array('id', 'title'),
			'recursive' => -1
		));
		$feeds = Set::combine($feeds, '{n}.Feed.id', '{n}.Feed.title');
		
		//find last inserted result = 1 for each inactive feed
		//mixes data from $inactiveFeeds and $feeds
		$result = array();
		foreach($inactiveFeeds as $n => $feed_id) {
			if(!isset($feeds[$feed_id])) {
				continue;
			}
			$inactiveFrom = $this->find('first', array(
				'conditions' => array(
					'FeedUpdate.feed_id' => $feed_id,
					'FeedUpdate.result' => 1),
				'order' => 'FeedUpdate.created DESC',
				'fields' => 'FeedUpdate.created'
			));
			
			$lastActiveDate = date('YmdHis', strtotime($inactiveFrom['FeedUpdate']['created']));
			$timer = date('YmdHis', strtotime('-'.$time));
			
			if($lastActiveDate < $timer) {				
				$result[] = array('Feed' => array(
					'id' => $feed_id,
					'title' => $feeds[$feed_id],
					'inactive_from' => $inactiveFrom['FeedUpdate']['created'],
					'inactive_from_label' => $inactiveFrom['FeedUpdate']['created']
//					'inactive_from_label' => $TimeHelper->timeAgoInWords(
//						$inactiveFrom['FeedUpdate']['created'])
				));
			}
		}
		
		return $result;
	}
}