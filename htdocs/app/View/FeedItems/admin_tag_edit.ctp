<?php 
echo $this->Form->create('FeedItemsTag', array('url' => array(
	'controller' => 'feed_items',
	'action' => 'tag_edit',
	$this->data['FeedItemsTag']['id']
)));
echo $this->Form->input('FeedItemsTag.id');
echo $this->Form->input('FeedItemsTag.name');
echo $this->Form->input('FeedItemsTag.normalized_value');
echo $this->Form->input('FeedItemsTag.class');
echo $this->Form->input('FeedItemsTag.source', array('disabled' => 'disabled'));
echo $this->Form->end('Save');