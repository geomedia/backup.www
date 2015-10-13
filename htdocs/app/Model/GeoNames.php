<?php
/*
 * https://github.com/signified/CakePHP-GeoNames-DataSource
 */
class GeoNames extends AppModel {
	public $name = 'GeoNames';
	public $useDbConfig = 'geonames';
    public $useTable = false;
	
	/*
	 * Analyzes $string and format results for saving in FeedItemsTag model
	 */
	public function analyze($string) {
		
	}
}