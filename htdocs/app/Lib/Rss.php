<?php

class RSS {

	public $valid = false;
	public $items = array();
	private $xml;
	private $data;

	public function __construct($source) {
		//verify if source is available and, if not, return $this->valid = false; return true;
		if(!$this->remoteFileExists($source)) {
			$this->valid = false;
			return true;
		}
		
		//if you want use SimplePie, use this
		App::import('Vendor','SimplePieAutoloader');
		$reader = new SimplePie();
		$reader->cache = false;
		$reader->set_feed_url($source);
		try {
			$reader->init();
		} catch (Exception $e) {
			$this->valid = false;
			return;
		}
		
		$errors = $reader->error();
		if(empty($errors)) {
			$this->valid = true;
		} else {
			$this->valid = false;
			return;
		}
		
		$simplePieItems = $reader->get_items();
		$this->items = $this->processSimplePieItems($simplePieItems);
		$reader->__destruct();
		
		//using internal Xml parser
//		App::uses('Xml', 'Lib');
//		libxml_use_internal_errors(false);
//		try {
//			$this->xml = Xml::build($source);
//		} catch (XmlException $e) {
//			$this->valid = false;
//		}
//		if($this->xml) {
//			$this->valid = true;
//		}
//		$this->data = Xml::toArray($this->xml);
//		if ($this->hasItems()) {
//			$this->items = $this->processItems($this->data['rss']['channel']['item']);
//		}
	}

	/*
	 * checks if there are <rss> and <channel> tags
	 * returns false if not
	 */
	public function hasItems() {
		if (isset($this->data['rss'])
			&& isset($this->data['rss']['channel'])
			&& isset($this->data['rss']['channel']['item'])
				) {
			return true;
		}
		return false;
	}

	/*
	 * provides a simple iteration on items
	 * - sets the guid tag with a unique value if it's not present
	 */
	private function processItems($items) {
		foreach ($items as $n => $item) {

			//set guid
			if (isset($item['guid'])) {
				if (is_array($item['guid']) && isset($item['guid']['@'])) {
					$items[$n]['guid'] = $item['guid']['@'];
				}
			} else {
				if (isset($item['title']) && isset($item['description'])) {
					$items[$n]['guid'] = md5($item['title'] . $item['description']);
				} else {
					$items[$n]['guid'] = md5(implode('', $item));
				}
			}
		}
		return $items;
	}
	
	protected function processSimplePieItems($simplePieItems) {
		$items = array();
		foreach($simplePieItems as $simplePieItem) {
			$title = $simplePieItem->get_title();
			$link = $simplePieItem->get_link();
			$description = $simplePieItem->get_description();
			$date = $simplePieItem->get_date();
			$author = $simplePieItem->get_author();
			if($author) {
				$authorName = $author->get_name();
				if(is_null($authorName)) {
					$authorName = false;
				}
			} else {
				$authorName = false;
			}
			$category = $simplePieItem->get_category();
			$item = array(
				'guid' => $simplePieItem->get_id(),
				'title' => is_null($title) ? '' : $title,
				'link' => is_null($link) ? '' : $link,
				'description' => is_null($description) ? '' : $description,
				'pubDate' => is_null($date) ? '' : $date,
				'author' => $authorName ? $authorName : '',
				'category' => $category ? $category->get_label() : ''
			);
			$items[] = $item;
		}
		return $items;
	}

	/*
	 * checks i f remote fil eexists using CURL
	 * return false i furl is not valid
	 */
	function remoteFileExists($url) {
		$file = @fopen($url,"r");
		if($file) {
			fclose($file);
			return true;
		} else {
			return false;
		}
	}

}