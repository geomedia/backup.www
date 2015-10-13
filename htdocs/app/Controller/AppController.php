<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');

/**
 * This is a placeholder class.
 * Create the same file in app/app_controller.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       Cake.Controller
 * @link http://book.cakephp.org/view/957/The-App-Controller
 */
class AppController extends Controller {

	var $components = array('Session', 'Auth');
	var $helpers = array('Html', 'Form', 'Session', 'Text');

	public function beforeFilter() {

		//gestisco l'autenticazione
		$this->Auth->authorize = array('Controller');
		$this->Auth->authenticate = array(
			'Form' => array(
				'userModel' => 'User',
				'scope' => array(
					'User.active' => 1
				)
			));
		$this->Auth->deny($this->request->params['action']);

//		debug($this->Auth->user());
//		debug($this->request->params['controller']);
//		$user_role = $this->Auth->user('role');
//		if ($user_role) {
//			$roleResctictions = Configure::read('user.roles.' . $user_role);
//			if (isset($roleResctictions['controllers'][$this->request->params['controller']])) {
//				$notAllowedActions = array_merge(
//					$roleResctictions['controllers'][$this->request->params['controller']], $roleResctictions['actions']);
//			} else {
//				$notAllowedActions = $roleResctictions['actions'];
//			}
//			if(in_array($this->request->params['action'], $notAllowedActions)) {
//				
//			}
//		}
	}

	public function beforeRender() {

		//ajax requests without layout
		if ($this->request) {
			if ($this->request->is('ajax')) {
				$this->layout = false;
			}
		}
	}
	
	public function isAuthorized($user) {
		if(empty($user)) {
			return false;
		}
		$user_role = $user['role'];
		if ($user_role) {
			$roleResctictions = Configure::read('user.roles.' . $user_role);
			if (isset($roleResctictions['controllers'][$this->request->params['controller']])) {
				$notAllowedActions = array_merge(
					$roleResctictions['controllers'][$this->request->params['controller']], $roleResctictions['actions']);
			} else {
				$notAllowedActions = isset($roleResctictions['actions']) ?  $roleResctictions['actions'] : array();
			}
			if(in_array($this->request->params['action'], $notAllowedActions)) {
				$this->Session->setFlash('Action not allowed');
				return false;
			}
			return true;
		}
		return false;
	}

}