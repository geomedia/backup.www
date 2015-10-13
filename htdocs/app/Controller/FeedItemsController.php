<?php

App::uses('AppController', 'Controller');

/**
 * FeedItems Controller
 *
 * @property FeedItem $FeedItem
 */
class FeedItemsController extends AppController {

	public $paginateConditions = array();
	public $export = false;

	public function beforeFilter() {

		//Search functions
		if ($this->request->data('Search')) {
			//if search is post, redirect to search url
			$this->redirect(array_merge(
					array(
					'controller' => $this->request->params['controller'],
					'action' => $this->request->params['action'],
					'search' => '1'
					), $this->request->params['named'], array_filter($this->request->data('Search'))));
		}

		//apply filters
		if (isset($this->request->params['named']['key'])) {
			$this->paginateConditions[] = array('or' => array(
					'FeedItem.ItemTitle LIKE' => '%' . $this->request->params['named']['key'] . '%',
					'FeedItem.ItemDescription LIKE' => '%' . $this->request->params['named']['key'] . '%',
					'FeedItem.ItemLink LIKE' => '%' . $this->request->params['named']['key'] . '%',
					'FeedItem.ItemAuthor LIKE' => '%' . $this->request->params['named']['key'] . '%'
				));
		}
		if (isset($this->request->params['named']['date_start'])) {
			$this->paginateConditions['FeedItem.ItemPubDate_t >= '] = $this->request->params['named']['date_start'] . ' 00.00.00';
		}
		if (isset($this->request->params['named']['date_end'])) {
			$this->paginateConditions['FeedItem.ItemPubDate_t <='] = $this->request->params['named']['date_end'] . ' 23.59.59';
		}
		
		//feed filters
		$feedFilters = array('country', 'type', 'language', 'irregular');
		foreach($feedFilters as $feedFilter) {
			if (isset($this->request->params['named'][$feedFilter])) {
				$feeds = $this->FeedItem->Feed->find('all', array(
					'conditions' => array($feedFilter => $this->request->params['named'][$feedFilter]),
					'fields' => 'id',
					'recursive' => -1
				));
				$feedsId = Set::extract('/Feed/id', $feeds);
				if(isset($this->paginateConditions['FeedItem.feed_id'])) {
					$this->paginateConditions['FeedItem.feed_id'] = array_intersect($this->paginateConditions['FeedItem.feed_id'], $feedsId);
				} else {
					$this->paginateConditions['FeedItem.feed_id'] = $feedsId;
				}
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
		$this->FeedItem->recursive = 0;
		$this->paginate = array(
			'conditions' => $this->paginateConditions,
			'order' => 'FeedItem.ItemAddedTime DESC',
			'limit' => 100
		);

		if (isset($this->request->params['named']['feed'])) {
			$this->paginate = array(
				'conditions' => array_merge(
					$this->paginateConditions, array('feed_id' => $this->request->params['named']['feed'])
				),
				'order' => 'FeedItem.ItemAddedTime DESC',
				'limit' => 100
			);
			$feed = $this->FeedItem->Feed->find('first', array(
				'conditions' => array('id' => $this->request->params['named']['feed']),
				'recursive' => -1
				));
			$this->set('feed', $feed);
		}

		switch ($this->export) {
			case 'csv':
				$this->response->type('csv');
				$fields = array_keys($this->FeedItem->schema());
				$feedItems = $this->FeedItem->find('all', array(
					'conditions' => $this->paginate['conditions'],
					'order' => 'FeedItem.ItemAddedTime DESC',
					'fields' => $fields,
					'recursive' => -1
					));

				$this->set(compact('fields', 'feedItems'));
				$this->render('admin_index_csv', false);
				break;
			default:
				$this->set('feedItems', $this->paginate());
		}
	}

	public function admin_tag_edit($id) {
		if ($this->request->is('ajax')) {
			$this->layout = 'ajax';
		}
		if($this->request->data) {
			$this->FeedItem->FeedItemsTag->save($this->request->data);
		}
		$this->request->data = $this->FeedItem->FeedItemsTag->find('first', array(
			'conditions' => array('FeedItemsTag.id' => $id),
			'contain' => array(
				'FeedTag',
				'Country'
			)));
	}

	public function admin_tag_delete($id) {
		if($this->FeedItem->FeedItemsTag->delete($id)) {
			$this->set('deleted', 1);
			$this->Session->setFlash('Tag deleted');
		} else {
			$this->set('deleted', 0);
			$this->Session->setFlash('Tag NOT deleted');
		}
		if ($this->request->is('ajax')) {
			$this->layout = 'ajax';
		} else {
			$this->redirect($this->referer());
		}
	}

	/**
	 * admin_view method
	 *
	 * @param string $id
	 * @return void
	 */
	public function admin_view($id = null) {
		$this->FeedItem->id = $id;
		if (!$this->FeedItem->exists()) {
			throw new NotFoundException(__('Invalid feed item'));
		}
		$feedItem = $this->FeedItem->read(null, $id);
		$this->set(compact('feedItem'));
	}

///**
// * admin_add method
// *
// * @return void
// */
//	public function admin_add() {
//		if ($this->request->is('post')) {
//			$this->FeedItem->create();
//			if ($this->FeedItem->save($this->request->data)) {
//				$this->Session->setFlash(__('The feed item has been saved'));
//				$this->redirect(array('action' => 'index'));
//			} else {
//				$this->Session->setFlash(__('The feed item could not be saved. Please, try again.'));
//			}
//		}
//		$feeds = $this->FeedItem->Feed->find('list');
//		$feedTags = $this->FeedItem->FeedTag->find('list');
//		$this->set(compact('feeds', 'feedTags'));
//	}
//
///**
// * admin_edit method
// *
// * @param string $id
// * @return void
// */
//	public function admin_edit($id = null) {
//		$this->FeedItem->id = $id;
//		if (!$this->FeedItem->exists()) {
//			throw new NotFoundException(__('Invalid feed item'));
//		}
//		if ($this->request->is('post') || $this->request->is('put')) {
//			if ($this->FeedItem->save($this->request->data)) {
//				$this->Session->setFlash(__('The feed item has been saved'));
//				$this->redirect(array('action' => 'index'));
//			} else {
//				$this->Session->setFlash(__('The feed item could not be saved. Please, try again.'));
//			}
//		} else {
//			$this->request->data = $this->FeedItem->read(null, $id);
//		}
//		$feeds = $this->FeedItem->Feed->find('list');
//		$feedTags = $this->FeedItem->FeedTag->find('list');
//		$this->set(compact('feeds', 'feedTags'));
//	}
//
///**
// * admin_delete method
// *
// * @param string $id
// * @return void
// */
//	public function admin_delete($id = null) {
//		if (!$this->request->is('post')) {
//			throw new MethodNotAllowedException();
//		}
//		$this->FeedItem->id = $id;
//		if (!$this->FeedItem->exists()) {
//			throw new NotFoundException(__('Invalid feed item'));
//		}
//		if ($this->FeedItem->delete()) {
//			$this->Session->setFlash(__('Feed item deleted'));
//			$this->redirect(array('action'=>'index'));
//		}
//		$this->Session->setFlash(__('Feed item was not deleted'));
//		$this->redirect(array('action' => 'index'));
//	}
}
