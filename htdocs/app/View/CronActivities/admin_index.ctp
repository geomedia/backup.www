<div class="actions">
		<h3><?php echo __('Actions'); ?></h3>
		<ul>
			<li><?php echo $this->Html->link(__('Update all feeds'), array('controller' => 'feeds', 'action' => 'update')); ?></li>
			<li><?php echo $this->Html->link(__('Run cron (only feeds where the update interval is expired)'), array('controller' => 'feeds', 'action' => 'cron_update')); ?></li>
		</ul>
	</div>

<div class="cronActivities index">
	<h2><?php echo __('Cron Activities');?></h2>
	
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
	
	<table cellpadding="0" cellspacing="0">
	<tr>
		<th>&nbsp;&nbsp;&nbsp;</th>
		<th><?php echo $this->Paginator->sort('start_time');?></th>
		<th><?php echo $this->Paginator->sort('end_time');?></th>
		<th><?php echo $this->Paginator->sort('updated_feeds');?></th>
		<th><?php echo $this->Paginator->sort('updated_feeds_number', 'n.feeds');?></th>
		<th><?php echo $this->Paginator->sort('new_feed_items', 'n.items');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($cronActivities as $cronActivity): ?>
	<tr>
		<td><?php 
		$value = $cronActivity['CronActivity']['verified'] ? $this->Html->image('icons/color_18/checkmark2.png') : $this->Html->image('icons/color_18/cross.png');
		echo $this->Html->link($value, array('action' => 'verify_toggle', $cronActivity['CronActivity']['id']), array('escape' => false)); 
		?></td>
		<td><?php echo $cronActivity['CronActivity']['start_time']; ?>&nbsp;</td>
		<td><?php echo $cronActivity['CronActivity']['end_time']; ?>&nbsp;</td>
		<td><?php echo $cronActivity['CronActivity']['updated_feeds']; ?>&nbsp;</td>
		<td><?php echo $cronActivity['CronActivity']['updated_feeds_number'] ? $cronActivity['CronActivity']['updated_feeds_number'] : ''; ?>&nbsp;</td>
		<td><?php echo $cronActivity['CronActivity']['new_feed_items'] ? $cronActivity['CronActivity']['new_feed_items'] : ''; ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
	</table>
	
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
	
	<?php echo $this->element('cron_settings'); ?>
	
</div>

