<div class="feedItems view">
	
	<h2><?php echo $feedItem['FeedItem']['ItemTitle']; ?></h2>
	
	<div class="tags">
		<ul>
		<?php foreach ($feedItem['FeedItemsTag'] as $tag): ?>
			<li class="tag">
				<span class="name"><?php echo $tag['name']; ?></span>
				<?php if(!empty($tag['class'])): ?>
				<span class="class"><?php echo $tag['class']; ?></span>
				<?php endif; ?>
				<div class="options">
					<ul>
						<li>
							<?php 
							echo $this->Html->link(
								$this->Html->image('icons/color_18/pencil.png'),
								array('controller' => 'feed_items', 'action' => 'tag_edit', $tag['id']),
								array('escape' => false, 'class' => 'edit')); 
							?>
						</li>
						<li>
							<?php 
							echo $this->Html->link(
								$this->Html->image('icons/color_18/cross.png'),
								array('controller' => 'feed_items', 'action' => 'tag_delete', $tag['id']),
								array('escape' => false, 'class' => 'delete')); 
							?>
						</li>
					</ul>
				</div>
			</li>
		<?php endforeach; ?>
		</ul>
		<div class="clear"></div>
	</div>
	
	<div class="feed-source">from <strong><?php echo $feedItem['Feed']['title']; ?></strong></div>
	<div class="feed-date"><?php echo $feedItem['FeedItem']['ItemPubDate']; ?></div>
	<div class="feed-author"><?php echo $feedItem['FeedItem']['ItemAuthor']; ?></div>
	<div class="feed-category"><?php echo $feedItem['FeedItem']['ItemCategory']; ?></div>
	<div class="feed-link"><?php echo $this->Html->link($feedItem['FeedItem']['ItemLink'], $feedItem['FeedItem']['ItemLink']); ?></div>
	
	<?php echo $feedItem['FeedItem']['ItemDescription'] ?>
	
</div>