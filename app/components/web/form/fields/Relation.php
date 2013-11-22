<?php
namespace app\components\web\form\fields;

class Relation extends Base {
	public $buildRelation = true;
	/**
	 *
	 *
	 * @param unknown $model        (optional)
	 * @param unknown $formSettings (optional)
	 * @return unknown
	 */
	public function generate() {
		$companion = $this->modelField->companion;
		//\var_dump($companion);exit;
		if ($this->buildRelation) {
			// we are matching with an existing document
			return 'existing';
		} else {
			$formSegment = $companion->formSegment;
			$formSegment->owner = $this;
			return $formSegment->generate();
		}
	}
}
?>