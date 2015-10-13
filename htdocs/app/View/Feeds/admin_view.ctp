<div class="feeds view">
<h2><?php  echo __('Feed');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($feed['Feed']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Active'); ?></dt>
		<dd>
			<?php echo h($feed['Feed']['active']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Url'); ?></dt>
		<dd>
			<?php echo h($feed['Feed']['url']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Update Interval'); ?></dt>
		<dd>
			<?php echo h($feed['Feed']['update_interval']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Last Update'); ?></dt>
		<dd>
			<?php echo h($feed['Feed']['last_update']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Next Update'); ?></dt>
		<dd>
			<?php echo h($feed['Feed']['next_update']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Update Status'); ?></dt>
		<dd>
			<?php echo h($feed['Feed']['update_status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Update Status Count'); ?></dt>
		<dd>
			<?php echo h($feed['Feed']['update_status_count']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($feed['Feed']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($feed['Feed']['type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Country'); ?></dt>
		<dd>
			<?php echo h($feed['Feed']['country']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Language'); ?></dt>
		<dd>
			<?php echo h($feed['Feed']['language']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Feed'), array('action' => 'edit', $feed['Feed']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Feed'), array('action' => 'delete', $feed['Feed']['id']), null, __('Are you sure you want to delete # %s?', $feed['Feed']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Feeds'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Feed'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Feed Items'), array('controller' => 'feed_items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Feed Item'), array('controller' => 'feed_items', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Feed Updates'), array('controller' => 'feed_updates', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Feed Update'), array('controller' => 'feed_updates', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Feed Items');?></h3>
	<?php if (!empty($feed['FeedItem'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Feed Id'); ?></th>
		<th><?php echo __('CreatedUniqueID'); ?></th>
		<th><?php echo __('ItemAddedTime'); ?></th>
		<th><?php echo __('ItemTitle'); ?></th>
		<th><?php echo __('ItemDescription'); ?></th>
		<th><?php echo __('ItemContentEncoded'); ?></th>
		<th><?php echo __('ItemLink'); ?></th>
		<th><?php echo __('ItemPubDate'); ?></th>
		<th><?php echo __('ItemPubDate T'); ?></th>
		<th><?php echo __('ItemEnclosureUrl'); ?></th>
		<th><?php echo __('ItemEnclosureType'); ?></th>
		<th><?php echo __('ItemEnclosureLength'); ?></th>
		<th><?php echo __('ItemGuid'); ?></th>
		<th><?php echo __('ItemAuthor'); ?></th>
		<th><?php echo __('ItemComments'); ?></th>
		<th><?php echo __('ItemSource'); ?></th>
		<th><?php echo __('ItemSourceUrl'); ?></th>
		<th><?php echo __('ItemCategory'); ?></th>
		<th><?php echo __('ItemCategoryDomain'); ?></th>
		<th><?php echo __('ItemCreativeCommons'); ?></th>
		<th><?php echo __('ItemITunesSubtitle'); ?></th>
		<th><?php echo __('ItemITunesSummary'); ?></th>
		<th><?php echo __('ItemITunesDuration'); ?></th>
		<th><?php echo __('ItemITunesKeywords'); ?></th>
		<th><?php echo __('ItemITunesAuthor'); ?></th>
		<th><?php echo __('ItemITunesExplicit'); ?></th>
		<th><?php echo __('ItemITunesBlocked'); ?></th>
		<th><?php echo __('ItemTrackBackPing'); ?></th>
		<th><?php echo __('ItemTrackBackAbout'); ?></th>
		<th><?php echo __('ItemDCTitle'); ?></th>
		<th><?php echo __('ItemDCDescription'); ?></th>
		<th><?php echo __('ItemDCDate'); ?></th>
		<th><?php echo __('ItemDCSubject'); ?></th>
		<th><?php echo __('ItemDCCreator'); ?></th>
		<th><?php echo __('ItemDCPublisher'); ?></th>
		<th><?php echo __('ItemDCContributor'); ?></th>
		<th><?php echo __('ItemDCLanguage'); ?></th>
		<th><?php echo __('ItemDCRights'); ?></th>
		<th><?php echo __('ItemDCType'); ?></th>
		<th><?php echo __('ItemDCFormat'); ?></th>
		<th><?php echo __('ItemDCIdentifier'); ?></th>
		<th><?php echo __('ItemDCSource'); ?></th>
		<th><?php echo __('ItemDCRelation'); ?></th>
		<th><?php echo __('ItemDCCoverage'); ?></th>
		<th><?php echo __('ItemITMSArtist'); ?></th>
		<th><?php echo __('ItemITMSArtistLink'); ?></th>
		<th><?php echo __('ItemITMSAlbum'); ?></th>
		<th><?php echo __('ItemITMSAlbumLink'); ?></th>
		<th><?php echo __('ItemITMSAlbumPrice'); ?></th>
		<th><?php echo __('ItemITMSCoverArt53'); ?></th>
		<th><?php echo __('ItemITMSCoverArt60'); ?></th>
		<th><?php echo __('ItemITMSCoverArt100'); ?></th>
		<th><?php echo __('ItemITMSFeatureArt'); ?></th>
		<th><?php echo __('ItemITMSSong'); ?></th>
		<th><?php echo __('ItemITMSSongLink'); ?></th>
		<th><?php echo __('ItemITMSRank'); ?></th>
		<th><?php echo __('ItemITMSRights'); ?></th>
		<th><?php echo __('ItemITMSReleaseDate'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($feed['FeedItem'] as $feedItem): ?>
		<tr>
			<td><?php echo $feedItem['id'];?></td>
			<td><?php echo $feedItem['feed_id'];?></td>
			<td><?php echo $feedItem['CreatedUniqueID'];?></td>
			<td><?php echo $feedItem['ItemAddedTime'];?></td>
			<td><?php echo $feedItem['ItemTitle'];?></td>
			<td><?php echo $feedItem['ItemDescription'];?></td>
			<td><?php echo $feedItem['ItemContentEncoded'];?></td>
			<td><?php echo $feedItem['ItemLink'];?></td>
			<td><?php echo $feedItem['ItemPubDate'];?></td>
			<td><?php echo $feedItem['ItemPubDate_t'];?></td>
			<td><?php echo $feedItem['ItemEnclosureUrl'];?></td>
			<td><?php echo $feedItem['ItemEnclosureType'];?></td>
			<td><?php echo $feedItem['ItemEnclosureLength'];?></td>
			<td><?php echo $feedItem['ItemGuid'];?></td>
			<td><?php echo $feedItem['ItemAuthor'];?></td>
			<td><?php echo $feedItem['ItemComments'];?></td>
			<td><?php echo $feedItem['ItemSource'];?></td>
			<td><?php echo $feedItem['ItemSourceUrl'];?></td>
			<td><?php echo $feedItem['ItemCategory'];?></td>
			<td><?php echo $feedItem['ItemCategoryDomain'];?></td>
			<td><?php echo $feedItem['ItemCreativeCommons'];?></td>
			<td><?php echo $feedItem['ItemITunesSubtitle'];?></td>
			<td><?php echo $feedItem['ItemITunesSummary'];?></td>
			<td><?php echo $feedItem['ItemITunesDuration'];?></td>
			<td><?php echo $feedItem['ItemITunesKeywords'];?></td>
			<td><?php echo $feedItem['ItemITunesAuthor'];?></td>
			<td><?php echo $feedItem['ItemITunesExplicit'];?></td>
			<td><?php echo $feedItem['ItemITunesBlocked'];?></td>
			<td><?php echo $feedItem['ItemTrackBackPing'];?></td>
			<td><?php echo $feedItem['ItemTrackBackAbout'];?></td>
			<td><?php echo $feedItem['ItemDCTitle'];?></td>
			<td><?php echo $feedItem['ItemDCDescription'];?></td>
			<td><?php echo $feedItem['ItemDCDate'];?></td>
			<td><?php echo $feedItem['ItemDCSubject'];?></td>
			<td><?php echo $feedItem['ItemDCCreator'];?></td>
			<td><?php echo $feedItem['ItemDCPublisher'];?></td>
			<td><?php echo $feedItem['ItemDCContributor'];?></td>
			<td><?php echo $feedItem['ItemDCLanguage'];?></td>
			<td><?php echo $feedItem['ItemDCRights'];?></td>
			<td><?php echo $feedItem['ItemDCType'];?></td>
			<td><?php echo $feedItem['ItemDCFormat'];?></td>
			<td><?php echo $feedItem['ItemDCIdentifier'];?></td>
			<td><?php echo $feedItem['ItemDCSource'];?></td>
			<td><?php echo $feedItem['ItemDCRelation'];?></td>
			<td><?php echo $feedItem['ItemDCCoverage'];?></td>
			<td><?php echo $feedItem['ItemITMSArtist'];?></td>
			<td><?php echo $feedItem['ItemITMSArtistLink'];?></td>
			<td><?php echo $feedItem['ItemITMSAlbum'];?></td>
			<td><?php echo $feedItem['ItemITMSAlbumLink'];?></td>
			<td><?php echo $feedItem['ItemITMSAlbumPrice'];?></td>
			<td><?php echo $feedItem['ItemITMSCoverArt53'];?></td>
			<td><?php echo $feedItem['ItemITMSCoverArt60'];?></td>
			<td><?php echo $feedItem['ItemITMSCoverArt100'];?></td>
			<td><?php echo $feedItem['ItemITMSFeatureArt'];?></td>
			<td><?php echo $feedItem['ItemITMSSong'];?></td>
			<td><?php echo $feedItem['ItemITMSSongLink'];?></td>
			<td><?php echo $feedItem['ItemITMSRank'];?></td>
			<td><?php echo $feedItem['ItemITMSRights'];?></td>
			<td><?php echo $feedItem['ItemITMSReleaseDate'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'feed_items', 'action' => 'view', $feedItem['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'feed_items', 'action' => 'edit', $feedItem['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'feed_items', 'action' => 'delete', $feedItem['id']), null, __('Are you sure you want to delete # %s?', $feedItem['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Feed Item'), array('controller' => 'feed_items', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Feed Updates');?></h3>
	<?php if (!empty($feed['FeedUpdate'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Feed Id'); ?></th>
		<th><?php echo __('Result'); ?></th>
		<th><?php echo __('New Items'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($feed['FeedUpdate'] as $feedUpdate): ?>
		<tr>
			<td><?php echo $feedUpdate['id'];?></td>
			<td><?php echo $feedUpdate['feed_id'];?></td>
			<td><?php echo $feedUpdate['result'];?></td>
			<td><?php echo $feedUpdate['new_items'];?></td>
			<td><?php echo $feedUpdate['created'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'feed_updates', 'action' => 'view', $feedUpdate['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'feed_updates', 'action' => 'edit', $feedUpdate['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'feed_updates', 'action' => 'delete', $feedUpdate['id']), null, __('Are you sure you want to delete # %s?', $feedUpdate['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Feed Update'), array('controller' => 'feed_updates', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
