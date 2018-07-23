<h3>Vimeo Update Queue</h3>
<?php if (count($ids)): ?>
<p>The video IDs to update are <?= implode(', ', $ids); ?>.</p>
<?php else: ?>
<p>The queue is empty.</p>
<?php endif; ?>
<hr>
<h3>Queue Log</h3>
<p><?= nl2br(file_get_contents(VIMEO_VIDEO_UPDATER_DIR.'/log.txt')); ?></p>
