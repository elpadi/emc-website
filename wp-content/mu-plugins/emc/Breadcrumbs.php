<?php
namespace EMC;

class Breadcrumbs extends \MustUsePlugin\Breadcrumbs {

	protected function fetchTrailParent() {
		$this->_currentItem = NULL;
	}

}
