<?php

/**
 * GeoNames Source
 * https://github.com/signified/CakePHP-GeoNames-DataSource
 */
class OpenCalaisSource extends DataSource {

	private $OpenCalaisAPI;

	public function __construct($config = array()) {
		parent::__construct($config);

		App::import('Vendor', 'OpenCalaisAPI');
		$this->OpenCalaisAPI = new OpenCalaisAPI();
		$this->OpenCalaisAPI->setAPIKey($this->config['apikey']);
	}

	/**
	 * Query
	 *
	 * @param string $name The name of the method being called.
	 * @return mixed A result array if successful, false otherwise.
	 */
	public function query($document) {

		$cacheKey = md5(serialize($document));
		if ($this->config['cache'] === true) {
			$results = Cache::read($cacheKey);
			if ($results) {
				return $results;
			}
		}

		try {
			$response = $this->OpenCalaisAPI->getEntities($document);
			if ($response) {				
				if ($this->config['cache'] === true) {
					Cache::write($cacheKey, $results);
				}
				return $response;
			}
		} catch (CakeException $e) {
			echo $e->getMessage() . "\n";
		}
		return false;
	}

}