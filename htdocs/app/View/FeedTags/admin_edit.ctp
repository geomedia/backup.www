<div class="feedTags form">
<?php echo $this->Form->create('FeedTag');?>
	<fieldset>
		<legend><?php echo __('Admin Edit Feed Tag'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('FeedItem');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('FeedTag.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('FeedTag.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Feed Tags'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Feed Items'), array('controller' => 'feed_items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Feed Item'), array('controller' => 'feed_items', 'action' => 'add')); ?> </li>
	</ul>
</div>
