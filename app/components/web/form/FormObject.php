<?php
namespace app\components\web\form;

class FormObject extends \infinite\base\Object {
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