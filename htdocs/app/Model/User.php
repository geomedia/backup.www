<?php

App::uses('AppModel', 'Model');

/**
 * User Model
 *
 */
class User extends AppModel {

	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = 'full_name';
	public $virtualFields = array(
		'full_name' => 'CONCAT(User.first_name, " ", User.last_name)'
	);

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			//'message' => 'Your custom message here',
			//'allowEmpty' => false,
			//'required' => false,
			//'last' => false, // Stop validation after this rule
			//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			//'message' => 'Your custom message here',
			//'allowEmpty' => false,
			//'required' => false,
			//'last' => false, // Stop validation after this rule
			//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	function beforeSave($options = array()) {
        $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        return true;
    }
	
}
