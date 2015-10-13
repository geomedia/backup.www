<?php

App::uses('AppController', 'Controller');

/**
 * Feeds Controller
 *
 * @property Feed $Feed
 */
class FeedsController extends AppController {

	public $components = array('GChartDataTable');
	public $paginateConditions = array();
	public $export = false;

	public function beforeFilter() {

		//Search functions
		if ($this->request->data('Search')) {
			//if search is post, redirect to search url
			$this->redirect(array_merge(
					$this->request->params, array_filter($this->request->data('Search'))));
		}

		//apply filters
		if (isset($this->request->params['named']['key'])) {
			$this->paginateConditions['Feed.title LIKE'] = '%' . $this->request->params['named']['key'] . '%';
		}
		if (isset($this->request->params['named']['type'])) {
			$this->paginateConditions['Feed.type'] = $this->request->params['named']['type'];
		}
		if (isset($this->request->params['named']['country'])) {
			$this->paginateConditions['Feed.country'] = $this->request->params['named']['country'];
		}
		if (isset($this->request->params['named']['language'])) {
			$this->paginateConditions['Feed.language'] = $this->request->params['named']['language'];
		}
		if (isset($this->request->params['named']['irregular'])) {
			switch($this->request->params['named']['irregular']) {
				case 'include':
					$this->paginateConditions['Feed.irregular'] = 1;
					break;
				case 'exclude':
					$this->paginateConditions['Feed.irregular'] = 0;
					break;
			}
		}

		//export filter
		if (isset($this->request->params['named']['export'])) {
			$this->export = $this->request->params['named']['export'];
		}

		return parent::beforeFilter();
	}

	/**
	 * admin_index method
	 *
	 * @return void
	 */
	public function admin_index() {
		$this->Feed->recursive = 0;
		$this->paginate = array(
			'conditions' => $this->paginateConditions,
			'limit' => 30,
			'order' => 'Feed.id ASC',
			'contain' => array(
				'FeedUpdate' => array(
					'conditions' => array('FeedUpdate.created >= ' => date('Y-m-d H:I:s', strtotime('-1week'))),
					'fields' => array('created', 'new_items'),
					'order' => 'FeedUpdate.id ASC'
				)
			));

		switch ($this->export) {
			case 'csv':
				$this->response->type('csv');
				$feeds = $this->Feed->find('all', array(
					'conditions' => $this->paginateConditions,
					'order' => 'Feed.id ASC',
					'recursive' => -1
//					'contain' => array(
//						'FeedUpdate' => array(
//							'fields' => array('created', 'new_items'),
//							'order' => 'FeedUpdate.id DESC',
//							'limit' => 20
//						))
					));

				$fields = array_keys($this->Feed->schema());
				$this->set(compact('fields', 'feeds'));
				$this->render('admin_index_csv', false);
				break;
			default:
				$feeds = $this->paginate();
		
				foreach ($feeds as $n => $feed) {
					$feeds[$n]['JsonUpdates'] = $this->GChartDataTable->toJson($feed['FeedUpdate'], array(
						'/created' => array(
							'label' => 'Date',
							'type' => 'string'
						),
						'/new_items' => array(
							'label' => 'New items',
							'type' => 'number'
						)
						));
					$this->set(compact('feeds'));
				}
		}
	}

	/**
	 * admin_view method
	 *
	 * @param string $id
	 * @return void
	 */
	public function admin_view($id = null) {
		$this->Feed->id = $id;
		if (!$this->Feed->exists()) {
			throw new NotFoundException(__('Invalid feed'));
		}
		$this->set('feed', $this->Feed->read(null, $id));
	}

	/**
	 * admin_add method
	 *
	 * @return void
	 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Feed->create();
			if ($this->Feed->save($this->request->data)) {
				$this->Session->setFlash(__('The feed has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The feed could not be saved. Please, try again.'));
			}
		}
	}

	/**
	 * admin_edit method
	 *
	 * @param string $id
	 * @return void
	 */
	public function admin_edit($id = null) {
		$this->Feed->id = $id;
		if (!$this->Feed->exists()) {
			throw new NotFoundException(__('Invalid feed'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Feed->save($this->request->data)) {
				$this->Session->setFlash(__('The feed has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The feed could not be saved. Please, try again.'));
			}
		} else {
			$this->Feed->recursive = -1;
			$this->request->data = $this->Feed->read(null, $id);
		}
	}

	/**
	 * admin_delete method
	 *
	 * @param string $id
	 * @return void
	 */
	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Feed->id = $id;
		if (!$this->Feed->exists()) {
			throw new NotFoundException(__('Invalid feed'));
		}
		if ($this->Feed->delete()) {
			$this->Session->setFlash(__('Feed deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Feed was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

	public function admin_update() {
		if (isset($this->request->params['named']['feed'])) {
			$this->Feed->updateFeed($this->request->params['named']['feed']);
		} else {
			$this->Feed->updateAllFeeds();
		}
		$this->redirect($this->referer());
	}

	public function admin_cron_update() {

		if (Configure::read('useShell')) {
			$this->log('Cron started from controller', 'feed_cron');
			
			//run the shell script
			shell_exec(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'cake -app ' . ROOT . DS . APP_DIR . DS . ' feedcron > /dev/null &');
			
		} else {
			$this->Feed->cronUpdate();
		}

		$this->Session->setFlash('Cron update started');
		$this->redirect($this->referer());
	}

	public function admin_show_feed_source($id) {
		$this->Feed->recursive = -1;
		$feed = $this->Feed->findById($id);

		$title = $feed['Feed']['title'];

		$file = @fopen($feed['Feed']['url'], "r");
		if ($file) {
			$source = '';
			while (($line = fgets($file)) !== false) {
				$source .= $line;
			}
			fclose($file);
		} else {
			$source = false;
		}
		$this->set(compact('source', 'title'));
		if ($this->request->is('ajax')) {
			$this->layout = false;
		}
	}

}
