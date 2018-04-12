<?php
namespace MustUsePlugin;

class Breadcrumbs extends \ArrayObject {
	
	protected $_currentItem;

	public function __construct() {
		global $post;
		parent::__construct();
		if ($post) {
			$this->_currentItem = $post;
			$this->buildTrail();
		}
	}

	protected function buildTrail() {
		if (isset($this->_currentItem) && $this->_currentItem) {
			$this->append($this->_currentItem);
			$this->fetchTrailParent();
			$this->buildTrail();
		}
	}

	protected function fetchTrailParent() {
		$this->_currentItem = NULL;
	}

}
