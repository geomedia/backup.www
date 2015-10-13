<?php
class FeedcronShell extends AppShell {
	
	public $uses = array('Feed');
	
	public function main() {
		$this->log('Cron started on '.date('Y-m-d H:i:s'), 'feed_cron');
		set_time_limit(0);
		$this->Feed->cronUpdate();
		$this->log('Cron executed on '.date('Y-m-d H:i:s'), 'feed_cron');
	}
	
	public function runSingleFeed() {
		$id = $this->args[0];
		$cronActivityId = $this->args[1];
		$this->log('Cron ' . $cronActivityId . ' on feed ' . $id . ' started at ' . date('Y-m-d H:i:s'), 'single_feed_cron');
		set_time_limit(0);
		$this->Feed->updateFeed($id, $cronActivityId);
		$this->log('Cron ' . $cronActivityId . ' on feed ' . $id . ' ended at ' . date('Y-m-d H:i:s'), 'single_feed_cron');
	}
	
	public function analyze() {
		$this->log('Api cron launched', 'api_cron');
		App::uses('FeedItemApi', 'Model');
		$this->FeedItemApi = new FeedItemApi();
		$this->FeedItemApi->analyze();
		$this->log('Api cron finished', 'api_cron');
	}
}