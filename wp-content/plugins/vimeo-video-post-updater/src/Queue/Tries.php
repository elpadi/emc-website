<?php
namespace VimeoUpdater\Queue;

class Tries {

	protected $count;

	public function __construct() {
		$this->max = 3;
		$this->count = 1;
	}

	public function increment() {
		$this->count++;
	}

	public function get() {
		return $this->count;
	}

	public function reset() {
		$this->count = 1;
	}

	public function hasMaxedOut() {
		return $this->count >= $this->max;
	}

}
