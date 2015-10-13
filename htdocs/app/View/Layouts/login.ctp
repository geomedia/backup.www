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
		?>

	</head>

	<body>

		<div id="bg"></div>
		<div class="container">
			<div id="contents" class="row">
				<div class="col-1" id="header">
					<div id="logo">
						<?php
						echo $this->Html->image('logo.png', array('url' => '/', 'id' => 'logo-img'));
						?>
						<div id="title1">GeoMedia Mapper</div>
						<div id="title2">RSS archive</div>
					</div>
				</div>
				<div class="col-2">

					<div id="content">
						<?php
						echo $this->Session->flash();
						echo $content_for_layout;
						echo $this->element('sql_dump');
						?>
					</div>
				</div>
			</div>
			<div id="modal">
				<div class="close"></div>
				<div id="modal-content"><div id="modal-content-content"></div></div>
			</div>
		</div>

	</body>

</html>