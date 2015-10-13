<?php
App::uses('AppController', 'Controller');
/**
 * FeedTags Controller
 *
 * @property FeedTag $FeedTag
 */
class FeedTagsController extends AppController {


/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->FeedTag->recursive = 0;
		$this->set('feedTags', $this->paginate());
	}

/**
 * admin_view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->FeedTag->id = $id;
		if (!$this->FeedTag->exists()) {
			throw new NotFoundException(__('Invalid feed tag'));
		}
		$this->set('feedTag', $this->FeedTag->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->FeedTag->create();
			if ($this->FeedTag->save($this->request->data)) {
				$this->Session->setFlash(__('The feed tag has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The feed tag could not be saved. Please, try again.'));
			}
		}
		$feedItems = $this->FeedTag->FeedItem->find('list');
		$this->set(compact('feedItems'));
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->FeedTag->id = $id;
		if (!$this->FeedTag->exists()) {
			throw new NotFoundException(__('Invalid feed tag'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->FeedTag->save($this->request->data)) {
				$this->Session->setFlash(__('The feed tag has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The feed tag could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->FeedTag->read(null, $id);
		}
		$feedItems = $this->FeedTag->FeedItem->find('list');
		$this->set(compact('feedItems'));
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
		$this->FeedTag->id = $id;
		if (!$this->FeedTag->exists()) {
			throw new NotFoundException(__('Invalid feed tag'));
		}
		if ($this->FeedTag->delete()) {
			$this->Session->setFlash(__('Feed tag deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Feed tag was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
