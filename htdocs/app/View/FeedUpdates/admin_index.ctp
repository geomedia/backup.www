<div class="feedUpdates index">

	<div class="actions">
		<h3><?php echo __('Actions'); ?></h3>
		<ul>
			<li><?php echo $this->Html->link(__('Settings'), array('controller' => 'feeds', 'action' => 'edit', $feed['Feed']['id'])); ?> </li>
			<?php if (isset($feed)): ?>
				<li><?php echo $this->Html->link(__('Update now'), array('controller' => 'feeds', 'action' => 'update', 'feed' => $feed['Feed']['id'])); ?></li>
			<?php else: ?>
				<li><?php echo $this->Html->link(__('Update now'), array('controller' => 'feeds', 'action' => 'update')); ?></li>
			<?php endif; ?>
			<li><?php echo $this->Html->link(__('Feed list'), array('controller' => 'feeds', 'action' => 'index')); ?> </li>
		</ul>
	</div>

	<?php if (isset($feed)): ?>	
		<h2><?php echo $feed['Feed']['title']; ?> - history</h2>
		<div class='feed-info'>
		
			<div class="info">
		<?php if ($feed['Feed']['first_update'] != '0000-00-00 00:00:00'): ?>
								<span>First update:</span> <?php echo date('d-m-Y', strtotime($feed['Feed']['first_update'])); ?>
							<?php endif; ?>
	</div>
			<div class="info">
				<span>Last update:</span> <?php echo date('F d Y, H\hi.s', strtotime($feed['Feed']['last_update'])); ?>
			</div>
			<!-- <div class="info">
				<span>Updated every:</span> <?php echo Configure::read('feed.update_intervals.' . $feed['Feed']['update_interval']); ?>
			</div> -->
		</div>
	<?php else: ?>
		<h2><?php echo __('Feed updates'); ?></h2>
	<?php endif; ?>
		
	<div class="paging">
		<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
		?>
	</div>

	<div id="chart" class="g-chart"></div>
	
	<div id="error-chart" class="g-chart"></div>

	<h3 class="expander closed">Detail table</h3>
	<div class="accordion closed">
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
				<th><?php echo $this->Paginator->sort('feed_id'); ?></th>
				<th><?php echo $this->Paginator->sort('created', 'Date'); ?></th>
				<th><?php echo $this->Paginator->sort('result', 'Status'); ?></th>
				<th><?php echo $this->Paginator->sort('new_items'); ?></th>
			</tr>
			<?php
			$i = 0;
			foreach ($feedUpdates as $feedUpdate):
				?>
				<tr>
					<td>
	<?php echo $this->Html->link($feedUpdate['Feed']['title'], array('controller' => 'feed_updates', 'action' => 'index', 'feed' => $feedUpdate['Feed']['id'])); ?>
					</td>
					<td><?php echo h($feedUpdate['FeedUpdate']['created']); ?>&nbsp;</td>
					<td><?php echo $feedUpdate['FeedUpdate']['result'] ? 'Ok' : 'Update error'; ?>&nbsp;</td>
					<td><?php echo $this->Html->link($feedUpdate['FeedUpdate']['new_items'], array('controller' => 'feed_items', 'action' => 'index', 'feed' => $feedUpdate['Feed']['id'])); ?>&nbsp;</td>
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
	</div>
</div>

<?php
$this->Html->script('https://www.google.com/jsapi', array('inline' => false));
$this->Html->scriptStart(array('inline' => false));
?>
// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawChart);

function drawChart() {
	// Data table and chart for feeds number
	var data = new google.visualization.DataTable({cols: <?php echo $jsonFeedUpdates['columns']; ?>});
	data.addRows(<?php echo $jsonFeedUpdates['rows']; ?>);
	var chart = new google.visualization.ColumnChart(document.getElementById('chart'));
	chart.draw(data, {title: 'Feed updates', colors: ['#669933'], hAxis: {slantedText: true, slantedTextAngle: 90, textStyle: {fontSize: 8}, showTextEvery: 1}, vAxis: {format: "0", minValue: 0}});
	
	// Data table and chart for feed errors
	var errorData = new google.visualization.DataTable({cols: <?php echo $jsonDailyErrors['columns']; ?>});
	errorData.addRows(<?php echo $jsonDailyErrors['rows']; ?>);
	var errorChart = new google.visualization.ColumnChart(document.getElementById('error-chart'));
	errorChart.draw(errorData, {title: 'Daily errors by day', colors: ['red']});
}

<?php $this->Html->scriptEnd(); ?>