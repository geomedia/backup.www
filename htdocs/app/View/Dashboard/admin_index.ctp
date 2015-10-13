<div class="dashboard index">
	<div class="row">
		<div class="sixcol">
			<h2>Uncompleted cron updates</h2>
			<p><strong><?php echo count($criticalCronActivities); ?></strong> cron updates to check (ongoing update is shown here until it isn't completed)</p>
			<?php if(!empty($criticalCronActivities)): ?>
			<?php 
			echo $this->Html->image('icons/color_18/firewall.png', array('class' => 'floating-img'));
			echo $this->Html->link('Go to activity monitor', array('controller' => 'cron_activities', 'action' => 'index')); 
			?>
			<table id="cron-activities">
				<tr>
					<th><?php echo $this->Form->checkbox('verified', array('id' => 'verify-all')); ?></th>
					<th>Date</th>
				</tr>
				<?php foreach($criticalCronActivities as $cron): ?>
				<tr>
					<td>
						<?php 
						echo $this->Form->create('CronActivity'); 
						echo $this->Form->hidden('id', array('value' => $cron['CronActivity']['id']));
						echo $this->Form->checkbox('verified');
						echo $this->Form->end();
						?>
					</td>
					<td><?php echo $cron['CronActivity']['start_time']; ?></td>
				</tr>
				<?php endforeach; ?>
			</table>
			<?php endif; ?>
		</div>

		<div class="sixcol last">
			<h2>Critical feeds</h2>
			<p>There is/are <strong><?php echo count($criticalFeeds); ?></strong> critical feeds to check (a feed is critical if it doesn't work since at least 3 hours</p>
			<?php if(count($criticalFeeds) > 0): ?>
			<table>
				<tr>
					<th>Feed name</th>
					<th>Inactive from</th>
					<th class="actions">Actions</th>
				</tr>
			<?php foreach($criticalFeeds as $criticalFeed):	?>
				<tr>
					<td><?php echo $criticalFeed['Feed']['title']; ?></td>
					<td><?php echo $criticalFeed['Feed']['inactive_from_label']; ?></td>
					<td class="actions"><?php echo $this->Html->link('View history', array('controller' => 'feed_updates', 'action' => 'index', 'feed' => $criticalFeed['Feed']['id'])) ?></td>
				</tr>
			<?php endforeach; ?>
			</table>
			<?php endif; ?>
		</div>
	</div>
	
	<!-- <div class="row">
		<div class="sixcol">
			<h2>Cron activity today</h2>
			<div id="cron-activity-chart"></div>
		</div>
		<div class="sixcol last">			
			<h2>Most active feeds this week</h2>
			<div id="most-active-chart"></div>
		</div>
	</div>
	
	<?php echo $this->element('cron_settings'); ?>
</div> -->

<?php
$this->Html->script('https://www.google.com/jsapi', array('inline' => false));
$this->Html->scriptStart(array('inline' => false));
?>
// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawChart);

function drawChart() {
	// Data table and chart for feeds activity
	var mostactivedata = new google.visualization.DataTable({cols: <?php echo $mostActiveFeeds['columns']; ?>});
	mostactivedata.addRows(<?php echo $mostActiveFeeds['rows']; ?>);
	var mostactivechart = new google.visualization.BarChart(document.getElementById('most-active-chart'));
	mostactivechart.draw(mostactivedata, {height: 400, colors: ['#66bb33']});
	
	// Data table and chart for cron activity
	var cronactivitydata = new google.visualization.DataTable({cols: <?php echo $cronActivityData['columns']; ?>});
	cronactivitydata.addRows(<?php echo $cronActivityData['rows']; ?>);
	var cronactivitychart = new google.visualization.LineChart(document.getElementById('cron-activity-chart'));
	cronactivitychart.draw(cronactivitydata, {height: 400, colors: ['#66bb33']});
}


(function($) {

	//ajax form
	$('#cron-activities form').each(function(){		
		var $checkbox = $(this).find('input[type="checkbox"]');
		$checkbox.change(function(){
			update($(this));
		});
	});
	
	$('#verify-all').change(function(){
		var value = $(this).val();
		$('#cron-activities form').each(function(){
			var $checkbox = $(this).find('input[type="checkbox"]');
			$checkbox.attr('checked', value);
			$checkbox.trigger('change');
		});
	});
	
	function update(element) {
		var $form = element.parents('form');
		$.post(
			'<?php 
			echo $this->Html->url(array(
				'controller' => 'cron_activities', 
				'action' => 'verify')); 
			?>', 
			$form.serialize(),
			function(data) {
				if(data == '1') {
					element.attr('checked', 'checked');
				} else {
					element.removeAttr('checked');
				}				
			}
		);
	}
	
})($);

<?php $this->Html->scriptEnd(); ?>