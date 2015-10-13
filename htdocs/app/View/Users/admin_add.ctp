<div class="users form">

	<div class="actions">
		<h3><?php echo __('Actions'); ?></h3>
		<ul>

			<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?></li>
		</ul>
	</div>


	<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add User'); ?></legend>
		<?php
		echo $this->Form->input('username', array('value' => ''));
		echo $this->Form->input('password', array('value' => ''));
		echo $this->Form->input('first_name');
		echo $this->Form->input('last_name');
		
		$roles = array_combine(
			array_keys(Configure::read('user.roles')),
			array_keys(Configure::read('user.roles'))
			);
		echo $this->Form->input('role', array('options' => $roles));
		
		echo $this->Form->input('email');
		echo $this->Form->input('active');
		?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>