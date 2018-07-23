<?php
namespace VimeoUpdater\Queue;

class Cron {

		public $name;
		public $recurrence;
		protected $next;

		public function __construct() {
				$this->name = 'update_next_vimeo_video';
				$this->recurrence = 'five_seconds';
				$this->mainPluginFile = VIMEO_VIDEO_UPDATER_FILE;
				
				$this->next = wp_next_scheduled($this->name);
				$this->deactivationCleanUp();
		}

		protected function deactivationCleanUp() {
				register_deactivation_hook($this->mainPluginFile, [$this, 'disable']);
		}

		public function disable() {
				if ($this->next) wp_unschedule_event($this->next, $this->name);
		}

		public function enable() {
				wp_schedule_event(time(), $this->recurrence, $this->name);
		}

}
