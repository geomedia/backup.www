<?php
/**
 * GeoNames Source
 * https://github.com/signified/CakePHP-GeoNames-DataSource
 */
class AlchemySource extends DataSource
{
	private $AlchemyAPI;
	
	public function __construct($config = array()) {
		parent::__construct($config);
		
		App::import('Vendor', 'Alchemy/AlchemyAPI');
		$this->AlchemyAPI = new AlchemyAPI();
		$this->AlchemyAPI->setAPIKey($this->config['apikey']);
	}
    /**
     * Query
     *
     * @param string $name The name of the method being called.
     * @param array $arguments The arguments to pass to the method.
     * @return mixed A result array if successful, false otherwise.
     */
    public function query($name = null, $arguments = array())
    {
        $cacheKey = md5(serialize($arguments));
        if ($this->config['cache'] === true) {
            if ($results = Cache::read($cacheKey)) {
                return $results;
            }
        }
		
        try {
			$response = call_user_func_array(array($this->AlchemyAPI, $name), $arguments);
            if ($response) { 
                if ($results = json_decode($response, true)) {
                    if (!isset($results['status']) || $results['status'] != 'OK') {
                        throw new CakeException($results['status']['message'], $results['status']['value']);
                    } else {
                        if ($this->config['cache'] === true) {
                            Cache::write($cacheKey, $results);
                        }
                        return $results;
                    }
                }
            }
        } catch (CakeException $e) {
            echo $e->getMessage() . "\n";
        }
        return false;
    }
}