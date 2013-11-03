<?php
namespace app\components\taxonomy;

use Yii;

use \infinite\base\exceptions\Exception;

class Collector extends \infinite\base\collector\Module
{
	const EVENT_AFTER_TAXONOMY_REGISTRY = 'afterTaxonomyRegistry';

	public $taxonomyClass = '\app\models\Taxonomy';
	public $taxonomyTypeClass = '\app\models\TaxonomyType';

	public function getCollectorItemClass() {
		return '\app\components\taxonomy\Item';
	}

	public function getModulePrefix() {
		return 'TaxonomyType';
	}

	public function register($owner, $itemComponent) {
		if ($itemComponent instanceof Module) {
			return parent::register($owner, $itemComponent->settings);
		}
		return parent::register($owner, $itemComponent);
	}

	public function prepareComponent($component) {
		$taxonomyTypeClass = $this->taxonomyTypeClass;
		$component['model'] = $taxonomyTypeClass::findOne(['system_id' => $component['systemId']]);
		if (empty($component['model'])) {
			$component['model'] = new $taxonomyTypeClass;
			$component['model']->name = $component['name'];
			$component['model']->system_id = $component['systemId'];
			$component['model']->system_version = 0;
			if (!$component['model']->save()) {
				throw new Exception("Couldn't save new taxonomy type {$component['systemId']}");
			}
			Yii::trace("Taxonomy type has been initialized {$component['name']} ({$component['systemId']})");
		}

		if (!isset($component['initialTaxonomies'])) { $component['initialTaxonomies'] = []; }
		
		if ($component['model']->system_version < $component['systemVersion']) {
			if ($this->initializeTaxonomies($component['model'], $component['initialTaxonomies'])) {
				$component['model']->system_version = $this->systemVersion;
				if (!$component['model']->save()) {
					throw new Exception("Couldn't save new taxonomy type {$component['systemId']} with new version");
				}
				Yii::trace("Taxonomy type has been upgraded {$this->name} ({$component['systemId']}) to version {$component['systemVersion']}");
			} else {
				throw new Exception("Couldn't upgrade taxonomy type {$component['systemId']} to version {$component['systemVersion']}");
			}
		}
	}

	public function initializeTaxonomies($model, $taxonomies) {
		$taxonomyClass = $this->taxonomyClass;
		foreach ($taxonomies as $systemId => $name) {
			$taxonomy = $taxonomyClass::findOne(['taxonomy_type_id' => $model->id, 'system_id' => $systemId]);
			if (empty($taxonomy)) {
				$taxonomy = new $taxonomyClass;
				$taxonomy->taxonomy_type_id = $model->id;
				$taxonomy->name = $name;
				$taxonomy->system_id = $systemId;
				if (!$taxonomy->save()) {
					return false;
				}
			}
		}
		return true;
	}
}
?>