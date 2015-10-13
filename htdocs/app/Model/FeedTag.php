<?php
App::uses('AppModel', 'Model');
/**
 * FeedTag Model
 *
 * @property FeedItem $FeedItem
 */
class FeedTag extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'normalized_value';
	public $actsAs = array('Containable');

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $hasMany = array(
		'FeedItemsTag'
	);

}
