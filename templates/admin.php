<?php
style('webhooks', 'style');
?>

<div id="webhooks" class="section">
	<h2 class="inlineblock">Webhooks</h2>
	<a target="_blank" rel="noreferrer" class="icon-info" title="Open documentation" href="https://github.com/kffl/nextcloud-webhooks"></a>
	<ul>
		<li>The Webhooks app is <strong>enabled</strong> <span class="status success"></span></li>
		<?php if (!empty($_['secret'])) : ?>
			<li>The secret used for signing POST requests is <strong>defined</strong> <span class="status success"></span></li>
		<?php else : ?>
			<li>The secret used for signing POST requests is <strong>not defined</strong> <span class="status warning"></span></li>
		<?php endif ?>
		<?php if ($_['canCurl']) : ?>
			<li>The curl command appears to be <strong>working</strong> <span class="status success"></span></li>
		<?php else : ?>
			<li><code>curl --help</code> when called via <code>exec()</code> returns a non-0 exit code <span class="status error"></span></li>
		<?php endif ?>
	</ul>
	<h3>Active Events with corresponding Webhook URLs:</h3>
	<ol id="event-list">
		<?php foreach ($_['activeEvents'] as $eventName => $eventUrl) : ?>
			<li><?php p($eventName); ?>: <code><?php p($eventUrl); ?></code></li>
		<?php endforeach ?>
	</ol>
	<?php if(!empty($_['inactiveEvents'])): ?>
	<h3>Inactive Events with corresponding config.php names:</h3>
	<ol id="event-list">
		<?php foreach ($_['inactiveEvents'] as $eventName => $configName) : ?>
			<li><?php p($eventName); ?>: <code><?php p($configName); ?></code></li>
		<?php endforeach ?>
	</ol>
	<p id="webhooks-hint" class="settings-hint">You can enable the inactive events by providing their webhook URLs in config.php</p>
	<?php endif; ?>
</div>