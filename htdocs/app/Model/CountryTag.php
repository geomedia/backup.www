<?php
App::uses('AppModel', 'Model');
/**
 * CountryTag Model
 *
 * @property Country $Country
 */
class CountryTag extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	var $useDbConfig = 'default';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Country' => array(
			'className' => 'Country',
			'foreignKey' => 'country_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
