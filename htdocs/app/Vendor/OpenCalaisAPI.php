<?php

/**
* Open Calais Tags
* Last updated 1/16/2012
* Copyright (c) 2012 Dan Grossman
* http://www.dangrossman.info
*
* Please see http://www.dangrossman.info/open-calais-tags
* for documentation and license information.
*/

class OpenCalaisException extends Exception {}

class OpenCalaisAPI {

    private $api_url = 'http://api.opencalais.com/enlighten/rest/';
    private $api_key = '';

    public $contentType = 'TEXT/HTML';
    public $outputFormat = 'Text/Simple';
    public $getGenericRelations = true;
    public $getSocialTags = true;
    public $docRDFaccessible = false;
    public $allowDistribution = false;
    public $allowSearch = false;
    public $externalID = '';
    public $submitter = '';
	private $HttpSocket;
	private $xml;
	private $openCalaisEntities = array(
		'Anniversary',
		'City',
		'Company',
		'Continent',
		'Country',
		'Currency',
		'EmailAddress',
		'EntertainmentAwardEvent',
		'Facility',
		'FaxNumber',
		'Holiday',
		'IndustryTerm',
		'MarketIndex',
		'MedicalCondition',
		'MedicalTreatment',
		'Movie',
		'MusicAlbum',
		'MusicGroup',
		'NaturalFeature',
		'OperatingSystem',
		'Organization',
		'Person',
		'PhoneNumber',
		'PoliticalEvent',
		'Product',
		'ProgrammingLanguage',
		'ProvinceOrState',
		'PublishedMedium',
		'RadioProgram',
		'RadioStation',
		'Region',
		'SportsEvent',
		'SportsGame',
		'SportsLeague',
		'TVShow',
		'TVStation',
		'Technology',
		'URL');

    public function __construct() {
		App::uses('HttpSocket', 'Network/Http');
		$this->HttpSocket = new HttpSocket();
		
		App::uses('Xml', 'Utility');
		$this->Xml = new Xml();
	}
	
	public function setAPIKey($apiKey) {
		$this->api_key = $apiKey;
	}

    public function getEntities($document) {
		
        if (empty($this->api_key)) {
            throw new OpenCalaisException('An OpenCalais API key is required to use this class. Use setAPIKey().');
        }

        $entities = $this->callAPI($document);

        return $entities;

    }

    private function getParamsXML() {
		
		$paramsXML = "<c:params xmlns:c=\"http://s.opencalais.com/1/pred/\" ". 
					"xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"> ".
					"<c:processingDirectives c:contentType=\"".$this->contentType."\" ".
					"c:outputFormat=\"".$this->outputFormat."\"".
					"></c:processingDirectives> ".
					"<c:userDirectives c:allowDistribution=\"".($this->allowDistribution ? 'true' : 'false')."\" ".
					"c:allowSearch=\"".($this->allowSearch ? 'true' : 'false')."\" c:externalID=\" \" ".
					"c:submitter=\"".htmlspecialchars($this->submitter)."\"></c:userDirectives> ".
					"<c:externalMetadata></c:externalMetadata>".
					"</c:params>";
		return $paramsXML;
    }

    private function callAPI($document) {

		// Construct the POST data string
//		$data = array(
//			"licenseID" => urlencode($this->api_key),
//			'paramsXML' => urlencode($this->getParamsXML()),
//			'content' => urlencode($document)); 
//		
//		$response = $this->HttpSocket->post($this->api_url, $data);debug($response);
		
		
		
		
		$data = "licenseID=".urlencode($this->api_key);
		$data .= "&paramsXML=".urlencode($this->getParamsXML());
		$data .= "&content=".urlencode($document);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		try {
			$response = curl_exec($ch);
		} catch(Exception $e) {
			throw new OpenCalaisException('Error during Curl connection');
		}
		curl_close($ch);
		
		try {
			$xmlResponse = $this->Xml->build($response);
		} catch (Exception $e) {
			throw new OpenCalaisException('Error parsing xml response');
		}

		//VERIFY !!!! ----------------------------------------------------------
        if ($xmlResponse->Error->Exception) {
            throw new OpenCalaisException($xmlResponse->Error->Exception);
        }
		
		if($xmlResponse->CalaisSimpleOutputFormat) {
			$entities = $xmlResponse->CalaisSimpleOutputFormat;
			
			$result = array();
			foreach($entities->children() as $element) {
				$entity = $element->getName();
				if(is_array($element)) {
					foreach($element as $el) {
						$result[] = $this->normalize($el, $entity);
					}
				} else {
					$result[] = $this->normalize($element, $entity);
				}
			}
//			foreach($this->openCalaisEntities as $entity) {
//				if($entities->{$entity}) { debug($entities->{$entity});
//					if(is_array($entities->{$entity})) {
//						foreach($entities->{$entity} as $element) {
//							$result[] = $this->normalize($element, $entity);
//						}
//					} else {
//						$result[] = $this->normalize($entities->{$entity}, $entity);
//					}
//				}
//			}
//			debug($entities);
//			foreach ($entities->City as $_country) {
//				debug($_country->attributes());
//			}
//			foreach ($entities->Country as $_country) {
//				debug($_country->attributes());
//			}
//			
//			foreach ($entities->Topics->Topic as $_topic) {
//				debug($_topic->attributes());
//			}
			return $result;
		}
		return false;
    }
	
	private function normalize($element, $entity) { 
		return array(
						'entity' => $entity,
						'name' => (string)$element,
						'normalized' => $element->attributes()->{'normalized'} ? (string)$element->attributes()->{'normalized'} : '',
						'count' => $element->attributes()->{'count'} ? (string)$element->attributes()->{'count'} : '',
						'relevance' => $element->attributes()->{'relevance'} ? (string)$element->attributes()->{'relevance'} : '',
						'raw_data' => $element->asXML()
					);
	}

}
