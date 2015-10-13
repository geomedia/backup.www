<div class="feeds form">
	
	<div class="actions">
		<h3><?php echo __('Actions'); ?></h3>
		<ul>
			<li><?php echo $this->Html->link(__('Feed list'), array('action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('History'), array('controller' => 'feed_updates', 'action' => 'index', 'feed' => $this->request->data['Feed']['id'])); ?></li>
		</ul>
	</div>

<?php echo $this->Form->create('Feed');?>
	<fieldset>
		<legend><?php echo __('Edit feed'); ?> - <?php echo $this->request->data['Feed']['title']; ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('title');
		echo $this->Form->input('url');
		echo $this->Form->input('type', array('options' => Configure::read('feed.types'), 'empty' => true));
		echo $this->Form->input('country');
		echo $this->Form->input('language', array('options' => Configure::read('feed.languages'), 'empty' => true));
		echo $this->Form->input('first_update', array('label' => 'First update', 'type' => 'text', 'class' => 'date'));
		echo $this->Form->input('update_interval', array('options' => Configure::read('feed.update_intervals'), 'label' => 'Update every'));
		echo $this->Form->input('active');
		echo $this->Form->input('analyze', array('after' => 'Analyze this feed with external API'));
		echo $this->Form->input('irregular');
		echo $this->Form->input('notes', array('class' => 'editor'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>