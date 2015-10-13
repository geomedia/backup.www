<?php

App::uses('AppController', 'Controller');

/**
 * FeedUpdates Controller
 *
 * @property FeedUpdate $FeedUpdate
 */
class FeedUpdatesController extends AppController {

	public $components = array('GChartDataTable');

	/**
	 * admin_index method
	 *
	 * @return void
	 */
	public function admin_index() {
		$this->FeedUpdate->recursive = 0;
		$this->paginate = array('limit' => 200, 'contain' => array('Feed' => array('id', 'title')));

		if (isset($this->request->params['named']['feed'])) {
			$this->paginate = array(
				'conditions' => array(
					'feed_id' => $this->request->params['named']['feed']
				),
				'order' => 'FeedUpdate.id DESC',
				'limit' => 200,
				'contain' => array('Feed' => array('id', 'title'))
			);
			$feed = $this->FeedUpdate->Feed->find('first', array(
				'conditions' => array('id' => $this->request->params['named']['feed']),
				'recursive' => -1
				));
			$this->set('feed', $feed);
		}

		$feedUpdates = $this->paginate();
		$feedUpdates_reversed = array_reverse($feedUpdates);
		
		//format the date for json visualization
		foreach($feedUpdates_reversed as $n => $f) {
			$feedUpdates_reversed[$n]['FeedUpdate']['created'] = date('d M, H\h', strtotime($f['FeedUpdate']['created']));
		}

		$jsonFeedUpdates = $this->GChartDataTable->toJson($feedUpdates_reversed, array(
			'/FeedUpdate/created' => array(
				'label' => 'Date',
				'type' => 'string'
			),
			'/FeedUpdate/new_items' => array(
				'label' => 'items',
				'type' => 'number'
			)
		));
		
		if (isset($this->request->params['named']['feed'])) {
			$traceErrorsFrom = end($feedUpdates);
			$traceErrorsTo = reset($feedUpdates);
			$dailyErrors = $this->FeedUpdate->find('all', array(
				'conditions' => array(
					'feed_id' => $this->request->params['named']['feed'],
					'created >= ' => date('y-m-d H:i', strtotime($traceErrorsFrom['FeedUpdate']['created'])),
					'created <= ' => date('y-m-d H:i', strtotime($traceErrorsTo['FeedUpdate']['created'])),
					'result' => 0
				),
				'fields' => array(
					'COUNT(result) as errors',
					'DATE(created) as created'
				),
				'group' => array(
					'YEAR(created)',
					'MONTH(created)',
					'DAY(created)'
				),
				'recursive' => -1
			));
			$jsonDailyErrors = $this->GChartDataTable->toJson($dailyErrors, array(
				'/0/created' => array(
					'label' => 'Date',
					'type' => 'string'
				),
				'/0/errors' => array(
					'label' => 'errors',
					'type' => 'number'
				)
			));
		}

		$this->set(compact('feedUpdates', 'jsonFeedUpdates', 'dailyErrors', 'jsonDailyErrors'));
	}

///**
// * admin_view method
// *
// * @param string $id
// * @return void
// */
//	public function admin_view($id = null) {
//		$this->FeedUpdate->id = $id;
//		if (!$this->FeedUpdate->exists()) {
//			throw new NotFoundException(__('Invalid feed update'));
//		}
//		$this->set('feedUpdate', $this->FeedUpdate->read(null, $id));
//	}
//
///**
// * admin_add method
// *
// * @return void
// */
//	public function admin_add() {
//		if ($this->request->is('post')) {
//			$this->FeedUpdate->create();
//			if ($this->FeedUpdate->save($this->request->data)) {
//				$this->Session->setFlash(__('The feed update has been saved'));
//				$this->redirect(array('action' => 'index'));
//			} else {
//				$this->Session->setFlash(__('The feed update could not be saved. Please, try again.'));
//			}
//		}
//		$feeds = $this->FeedUpdate->Feed->find('list');
//		$this->set(compact('feeds'));
//	}
//
///**
// * admin_edit method
// *
// * @param string $id
// * @return void
// */
//	public function admin_edit($id = null) {
//		$this->FeedUpdate->id = $id;
//		if (!$this->FeedUpdate->exists()) {
//			throw new NotFoundException(__('Invalid feed update'));
//		}
//		if ($this->request->is('post') || $this->request->is('put')) {
//			if ($this->FeedUpdate->save($this->request->data)) {
//				$this->Session->setFlash(__('The feed update has been saved'));
//				$this->redirect(array('action' => 'index'));
//			} else {
//				$this->Session->setFlash(__('The feed update could not be saved. Please, try again.'));
//			}
//		} else {
//			$this->request->data = $this->FeedUpdate->read(null, $id);
//		}
//		$feeds = $this->FeedUpdate->Feed->find('list');
//		$this->set(compact('feeds'));
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
//		$this->FeedUpdate->id = $id;
//		if (!$this->FeedUpdate->exists()) {
//			throw new NotFoundException(__('Invalid feed update'));
//		}
//		if ($this->FeedUpdate->delete()) {
//			$this->Session->setFlash(__('Feed update deleted'));
//			$this->redirect(array('action'=>'index'));
//		}
//		$this->Session->setFlash(__('Feed update was not deleted'));
//		$this->redirect(array('action' => 'index'));
//	}
}
