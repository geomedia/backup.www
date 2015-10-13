<!doctype html>
<html lang="en">

	<head>
		<meta charset="utf-8" />
		<title><?php echo $title_for_layout; ?></title>

		<?php echo $this->Html->meta('icon'); ?>

		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!-- 1140px Grid styles for IE -->
		<!--[if lte IE 9]><link rel="stylesheet" href="<?php echo $this->Html->url('/css/ie.css'); ?>" type="text/css" media="screen" /><![endif]-->

		<?php
		echo $this->Html->css('1140.css');
		echo $this->Html->css('styles.css');
		echo $this->Html->css('print.css', null, array('media' => 'print'));

		echo $this->Html->css('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/flick/jquery-ui.css');
		?>

	</head>

	<body>

		<div id="bg"></div>
		<div class="container" id="page-top">
			<div id="contents" class="row">
				<div class="col-1" id="col-1">
					<div id="header">

							<div id="logo">
								<?php
								echo $this->Html->image('logo.png', array('url' => '/', 'id' => 'logo-img'));
								?>
								<div id="title1">GeoMedia Mapper</div>
								<div id="title2">RSS archive</div>
							</div>
							<div id="nav">
								<div class="section">
									<ul class="archive-section">
										<h3>Collect</h3>
										<li><?php echo $this->Html->link('Feeds (journals)', array('controller' => 'feeds', 'action' => 'index')); ?>
										<li><?php echo $this->Html->link('Items (articles)', array('controller' => 'feed_items', 'action' => 'index')); ?></li>
										<li><?php echo $this->Html->link('Crons', array('controller' => 'cron_activities', 'action' => 'index')); ?></li>
									</ul>
								</div>
								<div class="section">
									<ul class="labeling-section">
										<h3>Process</h3>
										
									</ul>
								</div>
								<div class="section">
									<ul class="tools-section">
										<li><?php echo $this->Html->link($this->Html->image('icons/color_18/home.png') . 'Home', array('controller' => 'dashboard', 'action' => 'index'), array('escape' => false)); ?></li>
										<li><?php echo $this->Html->link($this->Html->image('icons/color_18/messenger.png') . 'Users', array('controller' => 'users', 'action' => 'index'), array('escape' => false)); ?></li>
										<li><?php echo $this->Html->link($this->Html->image('icons/color_18/cross.png') . 'Logout', array('controller' => 'users', 'action' => 'logout'), array('escape' => false)); ?></li>
									</ul>
								</div>
							</div>

					</div>
				</div>
				<div class="col-2" id="col-2">
					<div id="col-2-contents">


						<div id="content">
							<?php
							echo $this->Session->flash();
							echo $content_for_layout;
							echo $this->element('sql_dump');
							?>

							<a href="#page-top" id="linktotop">⇡ top</a>
						</div>
					</div>
				</div>
			</div>
			<div id="modal">
				<div class="close"></div>
				<div id="modal-content"><div id="modal-content-content"></div></div>
			</div>
		</div>

		<?php
		echo $this->Html->script('console');
		echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js');
		echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js');
		echo $this->Html->script('ckeditor/ckeditor.js');
		echo $this->Html->script('ckeditor/adapters/jquery.js');
		echo $scripts_for_layout;
		?>

		<script type="text/javascript">
			$(function(){
		
				//iphone/ipad detection
				isiPad = navigator.userAgent.match(/iPad/i) != null;
				isiPhone = navigator.userAgent.match(/iPhone/i) != null;
				if(isiPad || isiPhone) {
					$('body').addClass('ipad');
				}
				
				//animated scrolling
				$('a[href*=#]').click(function() {
					if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'')
						&& location.hostname == this.hostname) {
						var $target = $(this.hash);
						$target = $target.length && $target
							|| $('[name=' + this.hash.slice(1) +']');
						if ($target.length) {
							var targetOffset = $target.offset().top;
							$('html,body').animate({scrollTop: targetOffset}, 800);
							return false;
						}
					}
				});
				
				//hide flash message
				$('#flashMessage').delay(3000).fadeOut('slow');
				
				//modal window $('#modal');
				var modal = {
					is_open: false,
					url: false,

					//apre la finestra modal
					open: function (url) {
						main($, '#modal');
						$('#modal').css({display: 'block', height: 0}).animate({height: '95%'}, 300);
						this.is_open = true;
						this.url = url;
				
						if(isiPad) {
							modalWindowScroll = new iScroll($('#modal-content-content').get(0));
							$('body').animate({scrollTop: 0}, 500);
						}
					},

					//chiude la finestra modal
					close: function () {
						$('#modal').animate({height: 0}, 300, 'easeInBack', function(){ 
							$(this).css({display: 'none'}); 
							$('#modal-content-content').empty();
						});
						this.is_open = false;
				
						if(isiPad) {
							modalWindowScroll.destroy();
						}
					},
			
					reload: function() {
						$('#modal-content-content').load(this.url, function(){main($, '#modal');});
				
						if(isiPad) {
							modalWindowScroll.resetPosition();
							modalWindowScroll.refresh();
						}
					}
				};
				
				var main = function($, context) {}
				
				//modal window controller
				$('body').delegate('a.modal', 'click', function(event){
					event.preventDefault();
					var href = $(this).attr('href');
					$('#modal-content-content').load(href, function(){modal.open(href)});
				});
				$('#modal .close').click(modal.close);
			
				//accordion
				$('.expander').click(function(){
					$(this).toggleClass('closed').next('.accordion').slideToggle().toggleClass('closed');
				});
				
				
				//search box toggle
				$('.searchoptions-toggle').click(function(){
					$(this).next('.searchoptions').slideToggle().toggleClass('closed');
				});

				//form date helper
				$( ".date" ).datepicker({
					"dateFormat": 'yy-mm-dd' 
				});
				
				//wysiwyg textarea
				$('textarea.editor').ckeditor();
			});
		</script>

	</body>

</html>