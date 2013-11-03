<?php
namespace app\components\types;

class Collector extends \infinite\base\collector\Module
{
	public function getCollectorItemClass() {
		return '\app\components\types\Item';
	}


	public function getModulePrefix() {
		return 'Type';
	}

	/**
	 *
	 *
	 * @param unknown $parent
	 * @param unknown $child
	 * @param unknown $options (optional)
	 * @return unknown
	 */
	public function addRelationship($parent, $child, $options = array()) {
		$parentRef = $this->get($parent);
		$childRef = $this->get($child);
		$relationship = Relationship::get($parentRef, $childRef, $options);
		$parentRef->addChild($child, $relationship);
		$childRef->addParent($parent, $relationship);
		return true;
	}

}
?>