<div class="clear"></div>
<h2>Cron settings</h2>
	<p>The shell script to invoke to update the RSS databse is:</p>
	<pre><em><?php echo 'nohup '.ROOT.DS.'lib'.DS.'Cake'.DS.'Console'.DS.'cake -app '.ROOT.DS.APP_DIR.' feedcron > '.DS.'dev'.DS.'null &'; ?></em></pre>
	<p>The cron command is:</p>
	<pre><em>00 * * * * <?php echo ROOT.DS.APP_DIR.DS.'Console'.DS.'cake -app '.ROOT.DS.APP_DIR.' feedcron > '.DS.'dev'.DS.'null'; ?></em></pre>
	<pre><em>15 * * * * <?php echo ROOT.DS.APP_DIR.DS.'Console'.DS.'cake -app '.ROOT.DS.APP_DIR.' feedcron analyze > '.DS.'dev'.DS.'null'; ?></em></pre>
