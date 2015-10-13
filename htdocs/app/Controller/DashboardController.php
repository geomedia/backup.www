<?php
App::uses('AppController', 'Controller');

class DashboardController extends AppController {
	
	public $uses = array('Feed', 'FeedUpdate', 'CronActivity');
	public $components = array('GChartDataTable');
	
	public function index() {
		
	}
	
	public function admin_index() {
		
		$criticalFeeds = $this->FeedUpdate->criticalFeeds();
		
		$criticalCronActivities = $this->CronActivity->criticalCronActivities();
		
		$cronActivityData = $this->CronActivity->find('all', array(
			'conditions' => array(
				'start_time >= ' => date('Y-m-d H:i:s', strtotime('-1day'))
			),
			'fields' => array(
				'TIME_FORMAT(start_time, "%H:%I") as start_time', 'new_feed_items'
			),
			'order' => 'id ASC'
		));
		$cronActivityData = $this->GChartDataTable->toJson($cronActivityData, array(
			'/0/start_time' => array('label' => false, 'type' => 'string'),
			'/CronActivity/new_feed_items' => array('label' => 'Total feeds', 'type' => 'number')
		));
		
		$mostActiveFeeds = $this->FeedUpdate->mostActiveFeeds('1week');
		$mostActiveFeeds = $this->GChartDataTable->toJson($mostActiveFeeds, array(
			'/Feed/title' => array('label' => false, 'type' => 'string'),
			'/0/sum_items' => array('label' => 'Total feeds', 'type' => 'number')
		));
		
		$this->set(compact('mostActiveFeeds', 'criticalFeeds', 'criticalCronActivities', 'cronActivityData'));
	}
}