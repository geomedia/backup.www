<?php
App::uses('AppModel', 'Model');
/**
 * Feed Model
 *
 * @property FeedItem $FeedItem
 * @property FeedUpdate $FeedUpdate
 */
class Feed extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'url' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public function afterSave($created) {
		if($created) {
			$this->saveField('first_update', date('Y:m:d H:i:s'));
		}
		return parent::afterSave($created);
	}
/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'FeedItem' => array(
			'className' => 'FeedItem',
			'foreignKey' => 'feed_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'FeedUpdate' => array(
			'className' => 'FeedUpdate',
			'foreignKey' => 'feed_id',
			'dependent' => true,
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
	
	public $actsAs = array('Containable');
	
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		App::uses('Rss', 'Lib');
	}
	
	public function beforeSave($options = array()) {
		if($this->id) {
			$calculatedFields = $this->calculate_fields($this->id);
			$this->set($calculatedFields);
		}
		return parent::beforeSave($options);
	}
	
	/*
	 * main cron action
	 */
	public function cronUpdate() {
		$cronMode = Configure::read('cronMode');
		switch($cronMode) {
			case 'all':
				return $this->cronUpdateAll();
				break;
			case 'everyone':
				return $this->cronUpdateEveryOne();
				break;
		}
	}
	
	/*
	 * updates all feeds, each one in its own process
	 */
	public function cronUpdateEveryOne() {
		App::uses('CronActivity', 'Model');
		$this->CronActivity = new CronActivity();
		
		//sends email to admin if there are cron errors
		$this->CronActivity->emailReport();
		
		$this->CronActivity->create(array(
			'start_time' => date('Y-m-d H:i:s', strtotime('now')),
			'end_time' => null
		));
		$this->CronActivity->save();
		$updatedFeeds = array();
		$updatedFeedsNumber = 0;
		$newFeedItems = 0;
		
		$now = strtotime("now");
		
		$feeds = $this->find('all', array(
			'conditions' => array('Feed.active' => 1),
			'fields' => array('id', 'update_interval', 'last_update', 'title'),
			'order' => 'Feed.id',
			'recursive' => -1
		));
		
		foreach($feeds as $n =>  $feed) {
			$lastUpdateTime = strtotime($feed['Feed']['last_update']);
			$nextUpdateTime = ((int)$lastUpdateTime + (int)$feed['Feed']['update_interval']);
			
			if(empty($feed['Feed']['last_update']) || ($nextUpdateTime <= $now)) {
				try {
					//feed needs to be updated
//					$this->updateFeed($feed['Feed']['id'], $this->CronActivity->id);
					if (Configure::read('useShell')) {
						//run the shell script
						shell_exec(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'cake -app ' . ROOT . DS . APP_DIR . DS . ' feedcron runSingleFeed ' . $feed['Feed']['id'] . ' ' . $this->CronActivity->id . ' > /dev/null &');

					} else {
						shell_exec(ROOT . DS . 'lib' . DS . 'Cake' . DS . 'Console' . DS . 'cake -app ' . ROOT . DS . APP_DIR . DS . ' feedcron runSingleFeed ' . $feed['Feed']['id'] . ' ' . $this->CronActivity->id . ' > /dev/null &');
					}
				} catch (Exception $e) {
					continue;
				}
			}
			
//			debug($feed);
//			debug($lastUpdateTime);
//			debug($nextUpdateTime);
//			debug(($nextUpdateTime <= $now) ? 'needs to be updated' : 'doesn\'t neeed');
		}
		
		$this->CronActivity->set('end_time', date('Y-m-d H:i:s', strtotime('now')));
		$this->CronActivity->set('verified', 0);
		$this->CronActivity->save();
	}
	
	/*
	 * updates all feeds in a single process
	 */
	public function cronUpdateAll() {
		App::uses('CronActivity', 'Model');
		$this->CronActivity = new CronActivity();
		
		//sends email to admin if there are cron errors
		$this->CronActivity->emailReport();
		
		$this->CronActivity->create(array(
			'start_time' => date('Y-m-d H:i:s', strtotime('now')),
			'end_time' => null
		));
		$this->CronActivity->save();
		$updatedFeeds = array();
		$updatedFeedsNumber = 0;
		$newFeedItems = 0;
		
		$now = strtotime("now");
		
		$feeds = $this->find('all', array(
			'conditions' => array('Feed.active' => 1),
			'fields' => array('id', 'update_interval', 'last_update', 'title'),
			'order' => 'Feed.id',
			'recursive' => -1
		));
		
		foreach($feeds as $n =>  $feed) {
			$lastUpdateTime = strtotime($feed['Feed']['last_update']);
			$nextUpdateTime = ((int)$lastUpdateTime + (int)$feed['Feed']['update_interval']);
			
			if(empty($feed['Feed']['last_update']) || ($nextUpdateTime <= $now)) {
				try {
					//feed needs to be updated
					$new_items = $this->updateFeed($feed['Feed']['id']);

					//transform false to string
					if($new_items === false) {
						$new_items = 'INVALID RSS';
					}

					$updatedFeeds[] = $feed['Feed']['id'] . '-' . $feed['Feed']['title'] . '(' . $new_items . ')';
					$updatedFeedsNumber ++;
					$newFeedItems += $new_items;
				} catch (Exception $e) {
					continue;
				}
			}
			
//			debug($feed);
//			debug($lastUpdateTime);
//			debug($nextUpdateTime);
//			debug(($nextUpdateTime <= $now) ? 'needs to be updated' : 'doesn\'t neeed');
		}
		
		$this->CronActivity->set('updated_feeds', implode(', ', $updatedFeeds));
		$this->CronActivity->set('updated_feeds_number', $updatedFeedsNumber);
		$this->CronActivity->set('new_feed_items', $newFeedItems);
		$this->CronActivity->set('end_time', date('Y-m-d H:i:s', strtotime('now')));
		$this->CronActivity->set('verified', 1);
		$this->CronActivity->save();
	}
	
	public function updateFeed($id, $cronActivityId = false) {
		$this->FeedUpdate->create();
		
		$feed = $this->find('first', array(
			'conditions' => array('id' => $id),
			'recursive' => -1
		));
		
		$this->FeedUpdate->set('feed_id', $feed['Feed']['id']);
		
		$rss = new Rss($feed['Feed']['url']);
		
		//if RSS is valid, set result = 1, else set result = 0 and terminate
		if($rss->valid) {
			$this->FeedUpdate->set('result', 1);
		} else {
			$this->FeedUpdate->set('result', 0);
			$this->FeedUpdate->save();
			return false;
		}
		
		$new_items = 0;
		foreach($rss->items as $item) {
			$this->FeedItem->create();
			$this->FeedItem->set('feed_id', $feed['Feed']['id']);
			if($this->FeedItem->save($item)) {
				$new_items ++;
			}
		}
		
		//update the model FeedUpdate
		$this->FeedUpdate->set('new_items', $new_items);
		if($cronActivityId) {
			$this->FeedUpdate->set('cron_activity_id', $cronActivityId);
		}
		$this->FeedUpdate->save();
		
		$this->set('id', $feed['Feed']['id']);
		$this->set('last_update', date('Y-m-d H:i:s'));
		$this->save();
		
		//if called in "cronUpdateEveryOne" mode, update current cron activity
		if($cronActivityId) {
			$this->updateCronActivity($cronActivityId, $feed, $new_items);
		}
		
		return $new_items;
	}
	
	/*
	 * update an existing cron activity, adding the new feed data
	 * (used in the cron, in the "cronUpdateEveryOne" mode)
	 */
	private function updateCronActivity($cronActivityId, $feed, $new_items) {
		App::uses('CronActivity', 'Model');
		$this->CronActivity = new CronActivity($cronActivityId);		
		
		//start transaction
		$CronActivityDataSource = $this->CronActivity->getDataSource();
		$CronActivityDataSource->begin();
		
		$cronData = $this->CronActivity->find('first', array(
			'conditions' => array('id' => $cronActivityId),
			'recursive' => -1
		));
		
		$cronData['CronActivity']['updated_feeds_number'] = $cronData['CronActivity']['updated_feeds_number'] +1;
		$cronData['CronActivity']['updated_feeds'] = $cronData['CronActivity']['updated_feeds'] . 
			' || ' . $feed['Feed']['id'] . 
			'-' . $feed['Feed']['title'] . 
			'(' . $new_items . ')';
		$cronData['CronActivity']['new_feed_items'] = $cronData['CronActivity']['new_feed_items'] + $new_items;
		
		//save and end transaction
		if($this->CronActivity->save($cronData)) {
			$CronActivityDataSource->commit();
		} else {
			$CronActivityDataSource->rollback();
		}
		
	}
	
	public function updateAllFeeds() {
		$feeds = $this->find('all', array(
			'conditions' => array('Feed.active' => 1),
			'fields' => array('id'),
			'recursive' => -1
		));
		$return = true;
		foreach($feeds as $feed) {
			if(!$this->updateFeed($feed['Feed']['id'])) {
				$return = false;
			}
		}
		return $return;
	}
	
	public function hasItems($rssArray) {
		if (isset($rssArray['rss'])
			&& isset($rssArray['rss']['channel'])
			&& isset($rssArray['rss']['channel']['item'])) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * @param int $id Feed id
	 * @return array 
	 */
	public function calculate_fields($id) {
		$last_week_feeds = $this->FeedItem->find('count', array(
			'conditions' => array(
				'FeedItem.feed_id' => $id,
				'FeedItem.ItemAddedTime >= ' => date('Y-m-d H:I:s', strtotime('-1week'))
			)
		));
		
		$average_weekly_feeds = $this->FeedItem->query('
			SELECT AVG(feed_number) as average_weekly_feeds FROM(
				SELECT COUNT(  `FeedItem`.`id` ) feed_number
					FROM  `feed_items` AS  `FeedItem` 
					WHERE  `FeedItem`.`feed_id` = '.$id.'
					GROUP BY YEARWEEK(  `FeedItem`.`ItemAddedTime` )) AS WeeklyUpdates');
		$average_weekly_feeds = $average_weekly_feeds['0']['0']['average_weekly_feeds'];
		return array(
			'last_week_feeds' => $last_week_feeds,
			'average_weekly_feeds' => $average_weekly_feeds
		);
	}
}