<?php
App::uses('AppController', 'Controller');

class ProcessController extends AppController {
	public $uses = array();
	
	public function admin_index() {
		
	}
	
	public function admin_geonames() {
		$this->uses = array('GeoNames');
		
		$result = $this->GeoNames->search(array('q' => 'verona'));
		debug($result);
	}
	
	public function admin_alchemy() {
		$this->uses = array('Alchemy');
		
//		$result = $this->Alchemy->TextGetRankedNamedEntities("Hello my name is Bob.  I am speaking to you at this very moment.  Are you listening to me, Bob? La France is Francia in italian language and France in English. USA. Italy. Italia", 'json');
		$result = $this->Alchemy->analyze("Hello my name is Bob.  I am speaking to you at this very moment.  Are you listening to me, Bob? La France is Francia in italian language and France in English. USA. Italy. Italia", 'json');
		
		debug($result);
	}
	
	public function admin_opencalais() {
		$this->uses = array('OpenCalais');
		
		$result = $this->OpenCalais->query("
			Hello my name is Bob.  I am speaking to you at this very moment.  
			Are you listening to me, Bob? 
			La France is Francia in italian language and France in English. USA. Italy. Italia e Verona.
			Che è la città in cui vivo e si trova nel Veneto. Parigi capitale Francia");
		debug($result);
	}
	
	public function admin_country() {
		$this->uses = array('Country');
		
		$result = $this->Country->analyze("
			Hello my name is Bob.  I am speaking to you at this very moment.  
			Are you listening to me, Bob? 
			La France is Francia in italian language and France in English. USA. Italy. Italia e Verona.
			Che è la città in cui vivo e si trova nel Veneto. Parigi capitale Francia");
		debug($result);
	}
	
	public function admin_analyze() {
		$this->uses = array('FeedItemApi');
		$this->FeedItemApi->analyze();
		$this->redirect($this->referer());
	}
}