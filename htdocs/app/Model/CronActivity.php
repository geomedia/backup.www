<?php
class CronActivity extends AppModel {
	
	function criticalCronActivities($time = '1week') {
		$criticalCronActivities = $this->find('all', array(
			'conditions' => array(
				'start_time >= ' => date('Y-m-d H:i:s', strtotime('-'.$time)),
				'or' => array(
					'updated_feeds' => '',
					'updated_feeds_number' => 0,
					'new_feed_items' => 0
				),
				'verified' => '0'
			),
			'order' => 'CronActivity.id DESC'
		));
		return $criticalCronActivities;
	}
	
	public function emailReport() { 
		App::uses('FeedUpdate', 'Model');
		$this->FeedUpdate = new FeedUpdate();
		
		$criticalFeeds = $this->FeedUpdate->criticalFeeds('1day');
		
		$criticalCronActivities = $this->criticalCronActivities();
		
		if((count($criticalFeeds) >0) || (count($criticalCronActivities) >0)) {
		
			//send email
			App::uses('CakeEmail', 'Network/Email');
			$this->Email = new CakeEmail(); 
			$this->Email->emailFormat('html');
			$this->Email->transport('Mail');
			$this->Email->from(Configure::read('email_reports.from'));
			$this->Email->addTo(Configure::read('email_reports.to'));
			$this->Email->addCc(Configure::read('email_reports.cc'));
			$this->Email->addBcc(Configure::read('email_reports.bcc'));
			$this->Email->subject(Configure::read('email_reports.subject'));
			
			$message = '<strong>CIST cron alert</strong><br/><br/>';
			$message .= 'There are '.count($criticalFeeds).' critical feeds and '.count($criticalCronActivities).' critical cron activities. ';
			$message .= '<br/><a href="' . Configure::read('website_url') . '">Please check online.</a>';
			$message .= '<br/><ul>';
			foreach($criticalFeeds as $criticalFeed) {
				$message .= '<li>Feed <strong><a href="' . Configure::read('website_url') . 'admin/feed_updates/index/feed:' . $criticalFeed['Feed']['id'] . 
					'">' . $criticalFeed['Feed']['title'] . '</a></strong> is inactive from ' . 
					date('F d, Y - H:i:s', strtotime($criticalFeed['Feed']['inactive_from_label'])) . '</li>';
			}
			$message .= '</ul>';
			
			$this->Email->send($message);
		}
		return true;
	}
}