<?php
namespace VimeoUpdater\Queue;

class Tries {

		public function __construct() {
				$this->max = 3;
				$this->name = 'vimeo_update_try_count';
		}

		public function increment() {
				$this->set($this->get() + 1);
		}

		public function get() {
				return ($current = get_transient($this->name)) ? $current : 1;
		}

		protected function set(int $n) {
				set_transient($this->name, $n, MINUTE_IN_SECONDS);
		}

		public function reset() {
				$this->set(1);
		}

		public function hasMaxedOut() {
				return $this->get() >= $this->max;
		}

}
