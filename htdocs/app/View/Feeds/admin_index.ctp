<?php
$this->Html->script('https://www.google.com/jsapi', array('inline' => false));
$this->Html->scriptStart(array('inline' => false));
?>
// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawAllCharts);

function drawAllCharts() {
<?php foreach ($feeds as $feed): ?>
	drawChart($('#chart_<?php echo $feed['Feed']['id']; ?>', '#feedlist').get(0), <?php echo $feed['JsonUpdates']['columns']; ?>, <?php echo $feed['JsonUpdates']['rows']; ?>);
<?php endforeach; ?>
}

function drawChart(element, columns, rows) {
// Create the data table.
var data = new google.visualization.DataTable({cols: columns});
data.addRows(rows);

var chart = new google.visualization.ColumnChart(element);
chart.draw(data, {
chartArea: {left:0,top:0,width:"100%",height:"100%"},
legend: 'none', backgroundColor: 'transparent', colors: ['#669933'], 
vAxis: {format: "0", minValue: 0},
titlePosition: 'in',
titleTextStyle: {color:"#cccccc"},
title: 'Last week activity'});
}

<?php $this->Html->scriptEnd(); ?>

<div class="feeds index">

	<div class="actions">
		<h3><?php echo __('Actions'); ?></h3>
		<ul>
			<li><?php echo $this->Html->link(__('Add Feed'), array('action' => 'add')); ?></li>
			<li><?php
echo $this->Html->link(__('Export .csv'), array_merge(
		array(
		'controller' => $this->request->params['controller'],
		'action' => $this->request->params['action']), $this->request->params['named'], $this->request->params['pass'], array('export' => 'csv')
	));
?></li>
		</ul>
	</div>

	<h2><?php echo __('Feeds (journals)'); ?></h2>

	<div class="searchbox">
		<?php
		echo $this->Form->create('Search', array('url' => array('controller' => 'feeds', 'action' => 'index')));
		echo $this->Form->text('key');
		echo $this->Form->submit('Search');
		?>
		<div class="searchoptions-toggle"><?php echo $this->Html->image('icons/color_18/directional_down.png', array('title' => 'More options')); ?></div>
		<div class="searchoptions closed">
			<h3>Search options</h3>
			<?php
			echo $this->Form->input('country', array('type' => 'text'));
			echo $this->Form->input('type', array('options' => Configure::read('feed.types'), 'empty' => true));
			echo $this->Form->input('language', array('options' => Configure::read('feed.languages'), 'empty' => true));
			echo $this->Form->input('irregular', array(
				'options' => array(
					'exclude' => 'exclude irregular feeds',
					'include' => 'only irregular feeds'
				),
				'empty' => true));
			echo $this->Form->submit('Search');
			?>
		</div>
		<?php
		echo $this->Form->end();
		?>
	</div>

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


	<div class="order-options">
		<h3>Order by</h3>
		<?php echo $this->Paginator->sort('id'); ?>
		<?php echo $this->Paginator->sort('title'); ?>
		<?php echo $this->Paginator->sort('country'); ?>
		<?php echo $this->Paginator->sort('language'); ?>
		<?php echo $this->Paginator->sort('active'); ?>
		<?php echo $this->Paginator->sort('analyze'); ?>
	</div>

	<div id="feedlist">
		<?php
		$i = 0;
		$update_intervals = Configure::read('feed.update_intervals');
		$types = Configure::read('feed.types');
		foreach ($feeds as $i => $feed): //debug($feed);
			if (($i % 2) != 0) {
				$classRow = ' altrow';
			} else {
				$classRow = '';
			}
			?>
			<div class="feed-index-item <?php echo $classRow; ?>">

				<div class="actions">
					<?php echo $this->Html->link($this->Html->image('icons/color_18/gear.png') . __('Settings'), array('action' => 'edit', $feed['Feed']['id']), array('escape' => false)); ?>
					<?php echo $this->Html->link($this->Html->image('icons/color_18/stats_lines.png') . __('History'), array('controller' => 'feed_updates', 'action' => 'index', 'feed' => $feed['Feed']['id']), array('escape' => false)); ?>
					<?php echo $this->Html->link($this->Html->image('icons/rss.png') . __('Source rss'), array('controller' => 'feeds', 'action' => 'show_feed_source', $feed['Feed']['id']), array('escape' => false, 'class' => 'modal')); ?>
					<?php echo $this->Html->link($this->Html->image('icons/color_18/list.png') . __('Items'), array('controller' => 'feed_items', 'action' => 'index', 'feed' => $feed['Feed']['id']), array('escape' => false)); ?>
					<?php echo $this->Form->postLink($this->Html->image('icons/color_18/delete.png') . __('Delete'), array('action' => 'delete', $feed['Feed']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $feed['Feed']['id'])); ?>
				</div>

				<div class="item">

					<div class="title">
						<h3><?php echo h($feed['Feed']['title']); ?></h3>
						<div class="active"><?php echo $feed['Feed']['active'] ? 'active' : 'not active'; ?></div>
						<div class="analyzed"><?php echo $feed['Feed']['analyze'] ? 'analyzed' : 'not analyzed'; ?></div>
					</div>

					<div class="info">
						<span>
							<?php
							if (array_key_exists($feed['Feed']['type'], $types)) {
								echo $types[$feed['Feed']['type']];
							} else {
								echo $feed['Feed']['type'];
							}
							?>
						</span>
						<span>Country: <?php echo h($feed['Feed']['country']); ?></span>
						<span>Language: <?php echo h($feed['Feed']['language']); ?></span>
						<br/>
						<div class="more-info">
							<!--<span>id: <?php echo $feed['Feed']['id']; ?></span> -->
							<span><?php echo $feed['Feed']['irregular'] ? 'irregular feed' : ''; ?></span>
							<?php if ($feed['Feed']['first_update'] != '0000-00-00 00:00:00'): ?>
								<span>First update: <?php echo date('d-m-Y', strtotime($feed['Feed']['first_update'])); ?></span>
							<?php endif; ?>
							<span>Last update: <?php echo date('d-m-Y', strtotime($feed['Feed']['last_update'])); ?></span>
							<!-- <span>Update interval: <?php echo $update_intervals[$feed['Feed']['update_interval']]; ?></span> -->
							<span>Feeds in the last week: <?php echo $feed['Feed']['last_week_feeds']; ?></span>
							<span>Weekly average: <?php echo $feed['Feed']['average_weekly_feeds']; ?> feeds</span>

							<?php
							$notes = trim(strip_tags($feed['Feed']['notes']));
							if (!empty($notes)):
								?>
								<div class="clear"><br/></div>
								<div class="expander closed">Notes</div>
								<div class="accordion closed">
									<?php echo $feed['Feed']['notes']; ?>
								</div>
							<?php endif; ?>

						</div>
					</div>

					<div class="activity-chart-container">
						<div class="activity-chart" id="chart_<?php echo $feed['Feed']['id']; ?>"></div>
					</div>

				</div>

			</div>
		<?php endforeach; ?>
	</div>

	<div class="paging">
		<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
		?>
	</div>

</div>
