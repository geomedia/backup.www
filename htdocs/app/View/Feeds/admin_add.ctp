<div class="feeds form">

	<div class="actions">
		<h3><?php echo __('Actions'); ?></h3>
		<ul>
			<li><?php echo $this->Html->link(__('Feed list'), array('action' => 'index')); ?></li>
		</ul>
	</div>

	<?php echo $this->Form->create('Feed'); ?>
	<fieldset>
		<legend><?php echo __('Add Feed'); ?></legend>
		<?php
		echo $this->Form->input('title');
		echo $this->Form->input('url');
		echo $this->Form->input('type', array('options' => Configure::read('feed.types'), 'empty' => true));
		echo $this->Form->input('country');
		echo $this->Form->input('language', array('options' => Configure::read('feed.languages'), 'empty' => true));
		echo $this->Form->input('update_interval', array('options' => Configure::read('feed.update_intervals'), 'label' => 'Update every', 'value' => Configure::read('feed.update_intervals_default')));
		echo $this->Form->input('active', array('checked' => 'checked'));
		echo $this->Form->input('analyze', array('after' => 'Analyze this feed with external API'));
		echo $this->Form->input('irregular');
		echo $this->Form->input('notes', array('class' => 'editor'));
		?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>