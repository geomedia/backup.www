<div class="feeds index">

	<div class="actions">
		<h3><?php echo __('Actions'); ?></h3>
		<ul>
			<li><?php echo $this->Html->link(__('Update all active sources'), array('action' => 'update')); ?></li>
			<li><?php echo $this->Html->link(__('Start cron update'), array('action' => 'cron_update')); ?></li>
			<li><?php echo $this->Html->link(__('View cron activity'), array('controller' => 'cron_activities', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New Feed'), array('action' => 'add')); ?></li>
		</ul>
	</div>

	<h2><?php echo __('Feed sources'); ?></h2>

	<p></p>

	<div class="paging">
	</div>

	<div id="feeds"></div>
	
	<div class="paging">
		
	</div>

</div>

<script type="text/template" id="feed-item">
	<div class="feed-item">
		<div class="actions">
			<a class="details">Details</a>
			<a class="edit" href="<%= baseHref %>admin/edit/<%= Feed.id %>">Edit</a>
			<a class="delete" href="<%= baseHref %>admin/feeds/delete/<%= Feed.id %>" onclick="confirm('Do you want to delete this feed?'); return false;">Delete</a>
		</div>
		<h3><%= Feed.title %></h3>
		<div class="active"><%= Feed.active ? 'active' : 'not active' %></div>
		<div class="last-update">Last update: <%= Feed.last_update %></div>
		<div class="country">Country: <%= Feed.country %></div>
		<div class="language">Language: <%= Feed.language %></div>
		<div class="type">Type: <%= Feed.type %></div>
		<div class="details">
		</div>
	</div>
</script>

<?php
$this->Html->css('feed-list.css', null, array('inline' => false));
$this->Html->script('underscore-min.js', array('inline' => false));
$this->Html->script('backbone-min.js', array('inline' => false));
$this->Html->scriptStart(array('inline' => false)); 
?>
//
(function($) {
	//$.getJSON('<?php echo $this->Html->url(array('action' => 'index')); ?>', function(data) {
	//	console.log(data);
	//});
	
	window.baseHref = "<?php echo $this->Html->webroot; ?>";
	
	//model
	var Feed = Backbone.Model.extend({
		initialize: function() {
			console.log(this);
		}
	});
	
	//collection
	var FeedList = Backbone.Collection.extend({
		model: Feed,
		url: '<?php echo $this->Html->url(array('action' => 'index')); ?>',
		initialize: function() {}
	});
	
	
	//view
	var FeedItemView = Backbone.View.extend({
		el: '#feed-item',
		model: Feed,
		initialize: function() {},
		render: function(vars) {
			var tmpl = _.template($('#feed-item').html(), this.model.attributes);
			this.el = tmpl;
			return this.el;
		}
	});
	
	//App
	var FeedApp = Backbone.View.extend({
		Feeds: new FeedList(),
		FeedsView: new FeedItemView(),
		initialize: function(){
			this.Feeds.bind('reset', this.renderFeeds);
			this.Feeds.fetch();
		},
		renderFeeds: function() {
			this.each(function(feed){
				var view = new FeedItemView({model: feed});
				$('#feeds').append(view.render(view.model));
			});
		}
	});
	
	var App = new FeedApp;
	
})(jQuery);
<?php $this->Html->scriptEnd(); ?>