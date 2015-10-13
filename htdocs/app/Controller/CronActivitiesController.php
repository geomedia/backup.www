<?php
App::uses('AppController', 'Controller');
/**
 * CronActivities Controller
 *
 * @property CronActivity $CronActivity
 */
class CronActivitiesController extends AppController {

	public $helpers = array('Html', 'Form', 'Js');

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->CronActivity->recursive = 0;
		$this->paginate = array(
			'order' => 'CronActivity.id DESC'
		);
		$this->set('cronActivities', $this->paginate());
	}
	
	public function admin_verify() {
		$response = false;
		if($this->request->is('post') || $this->request->is('put')) {
			if($this->CronActivity->save($this->request->data)) {
				if($this->request->data['CronActivity']['verified'] == 1) {
					$response = true;
				}
			}
		}
		$this->layout = false;
		$this->set('response', $response);
		if($this->request->is('ajax')) {
//			$this->response->type('json');
//			$this->response->disableCache();
		} else {
			$this->redirect($this->referer());
		}
	}
	
	function admin_verify_toggle($id) {
		$item = $this->CronActivity->read(null, $id);
		
		$item['CronActivity']['verified'] = !$item['CronActivity']['verified'];
		
		$this->CronActivity->save($item);
		
		$this->redirect($this->referer());
	}

}
