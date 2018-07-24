<h3><?= __('Vimeo Update Queue'); ?></h3>
<?php if (count($ids)): ?>
<p><?= __('The video IDs to update are '). implode(', ', $ids); ?>.</p>
<?php else: ?>
<p><?= __('No videos to update'); ?></p>
<?php endif; ?>
<hr>
<h3><?= __('Status'); ?></h3>
