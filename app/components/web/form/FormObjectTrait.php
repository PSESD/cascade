<?php
namespace app\components\web\form;

trait FormObjectTrait {
	public $owner;
	public $isValid = true;

	public function getGenerator() {
		if (is_null($this->owner)) { return false; }
		if ($this->owner instanceof Generator) {
			return $this->owner;
		}
		return $this->owner->generator;
	}
}
?>