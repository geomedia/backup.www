<div class="feedItems index">

	<div class="actions">
		<h3><?php echo __('Actions'); ?></h3>
		<ul>
			<?php
			if (isset($this->request->params['named']['search'])):
				if (isset($feed)):
					?>
					<li><?php echo $this->Html->link('Remove filters', array('action' => 'index', 'feed' => $feed['Feed']['id'])); ?></li>
				<?php else: ?>
					<li><?php echo $this->Html->link('Remove filters', array('action' => 'index')); ?></li>
				<?php
				endif;
			endif;
			?>

			<li><?php echo $this->Html->link(__('Feed list'), array('controller' => 'feeds', 'action' => 'index')); ?></li>

			<li><?php
			echo $this->Html->link(__('Export .csv'), array_merge(
					array(
					'controller' => $this->request->params['controller'],
					'action' => $this->request->params['action']), $this->request->params['named'], $this->request->params['pass'], array('export' => 'csv')
				));
			?></li>
		</ul>
	</div>

	<?php if (isset($feed)): ?>
		<h2><?php echo $feed['Feed']['title']; ?> - items archive</h2>
		<div class='feed-info'>
			<div class="info">	
				<?php echo $this->Html->link(__('feed history'), array('controller' => 'feed_updates', 'action' => 'index', 'feed' => $feed['Feed']['id']), array('escape' => false)); ?>
			</div>
			<div class="info">
				<span>Last update:</span> <?php echo date('F d Y, H\hi.s', strtotime($feed['Feed']['last_update'])); ?>
			</div>
			<!-- <div class="info">
				<span>Updated every:</span> <?php echo Configure::read('feed.update_intervals.' . $feed['Feed']['update_interval']); ?>
			</div> -->
		</div>
	<?php else: ?>
		<h2><?php echo __('Items (articles)'); ?></h2>
	<?php endif; ?>

	<div class="searchbox">
		<?php
		echo $this->Form->create('Search', array('url' => array_merge(
				array('controller' => 'feed_items', 'action' => 'index'), $this->request->params['named']
			)));
		echo $this->Form->text('key');
		echo $this->Form->submit('Search');
		?>
		<div class="searchoptions-toggle"><?php echo $this->Html->image('icons/color_18/directional_down.png', array('title' => 'More options')); ?></div>
		<div class="searchoptions closed">
			<h3>Search options</h3>
			<?php
			echo $this->Form->input('date_start', array('label' => 'Starting date', 'type' => 'text', 'class' => 'date', 'empty' => true, 'dateFormat' => 'DMY', 'maxYear' => date('Y')));
			echo $this->Form->input('date_end', array('label' => 'Ending date', 'type' => 'text', 'class' => 'date', 'empty' => true, 'dateFormat' => 'DMY', 'maxYear' => date('Y')));
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
		
		
	<?php
	$searchParams = array('key', 'date_start', 'date_end', 'country', 'type', 'language', 'irregular');
	$usedParams = array_intersect($searchParams, array_keys($this->request->params['named']));
	if(!empty($usedParams)):
		
		//filter list
		echo '<div class="search-params">';
		echo '<h3>Search filters: </h3>';
		echo '<dl>';
		foreach($usedParams as $_param) {
			if(Configure::read('feed.'.Inflector::pluralize($_param))) {
				$value = Configure::read('feed.'.Inflector::pluralize($_param).'.'.$this->request->params['named'][$_param]);
			} else {
				$value = $this->request->params['named'][$_param];
			}
			echo $this->Html->tag('dt', $_param);
			echo $this->Html->tag('dd', $value);
		}
		echo '</dl><div class="clear"></div>';
		
		//remove filters link
		if (isset($feed)):
		?>
			<?php echo $this->Html->link('Remove filters', array('action' => 'index', 'feed' => $feed['Feed']['id']), array('class' => 'remove')); ?>
		<?php else: ?>
			<?php echo $this->Html->link('Remove filters', array('action' => 'index'), array('class' => 'remove')); ?>
		<?php
		endif;
		
		echo '</div>';
	endif;
	?>

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
			<th><?php echo $this->Paginator->sort('ItemTitle', 'Title'); ?></th>
			<th><?php echo $this->Paginator->sort('ItemPubDate_t', 'Published'); ?></th>
			<th><?php echo $this->Paginator->sort('ItemAddedTime', 'Updated'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php
		$i = 0;
		foreach ($feedItems as $feedItem):
			?>
			<tr>
				<td>
					<?php
					echo $this->Html->link($feedItem['Feed']['title'], array('controller' => 'feed_items', 'action' => 'index', 'feed' => $feedItem['Feed']['id']));
					?>
				</td>
				<td><?php echo h($feedItem['FeedItem']['ItemTitle']); ?>&nbsp;</td>
				<td><?php echo h($feedItem['FeedItem']['ItemPubDate_t']); ?>&nbsp;</td>
				<td><?php echo h($feedItem['FeedItem']['ItemAddedTime']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $feedItem['FeedItem']['id']), array('class' => 'modal')); ?>
				</td>
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

<?php $this->Html->scriptStart(array('inline' => false)); ?>
(function(){
	$('#modal-content').delegate('.options a', 'click', function(e){
		e.preventDefault();
		var $this = $(this);
		var $tagRoot = $this.parents('li.tag');
		var $li = $this.parent('li');
		var link = $this.attr('href');
		
		if($this.hasClass('edit')) {
			if($li.find('.tag-edit').length < 1) {				
				$li.append('<div class="tag-edit"></div>');
				$.get(link, function(tagData){
					$li.find('.tag-edit').html(tagData);
				});
			} else {
				$li.find('.tag-edit').remove();
			}			
		}
		
		if($this.hasClass('delete')) {
			if(window.confirm('Are you sure?')) {
				$.get(link, function(result){
					if(result == '0') {
						$tagRoot.remove();
					}
				});
			}
		}
	});
	
	$('#modal-content').delegate('.tag-edit form', 'submit', function(e){ 
		e.preventDefault(); 
		var $this = $(this);
		var $container = $this.parent('.tag-edit');
		var url = $this.attr('action');
		var data = $this.serialize();
		$.post(url, data, function(data){
			$container.html(data);
		});
	});
})();

<?php $this->Html->scriptEnd(); ?>