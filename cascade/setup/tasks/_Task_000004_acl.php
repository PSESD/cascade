<?php
namespace app\setup\tasks;

class Task_000004_acl extends \infinite\setup\Task {
	public function getTitle() {
		return 'ACL';
	}
	public function getBaseRules() {
		return array(
			array(
				'action' => null,
				'controlled' => null,
				'accessing' => array('model' => 'Group', 'fields' => array('system' => 'top')),
				'object_model' => null,
				'task' => 'deny',
			),
			array(
				'action' => null,
				'controlled' => null,
				'accessing' => array('model' => 'Group', 'fields' => array('system' => 'staff')),
				'object_model' => null,
				'task' => 'deny',
			),
			array(
				'action' => null,
				'controlled' => null,
				'accessing' => array('model' => 'Group', 'fields' => array('system' => 'clients')),
				'object_model' => null,
				'task' => 'deny',
			),
			array(
				'action' => null,
				'controlled' => null,
				'accessing' => array('model' => 'Group', 'fields' => array('system' => 'guests')),
				'object_model' => null,
				'task' => 'deny',
			),
			array(
				'action' => null,
				'controlled' => null,
				'accessing' => array('model' => 'Group', 'fields' => array('system' => 'administrators')),
				'object_model' => null,
				'task' => 'allow',
			),
		);
	}
	public function test() {
		foreach ($this->baseRules as $rule) {
			if ($rule['task'] === 'allow') {
				$expected = true;
			} elseif ($rule['task'] === 'deny') {
				$expected = false;
			} else {
				$expected = null;
			}
			$controlled = $rule['controlled'];
			$accessing = $rule['accessing'];

			if (is_array($controlled)) {
				$model = $controlled['model'];
				$controlled = $model::find()->where($controlled['fields'])->one();
				if (!$controlled) {
					return false;
				}
			}

			if (is_array($accessing)) {
				$model = $accessing['model'];
				$accessing = $model::find()->where($accessing['fields'])->one();
				if (!$accessing) {
					return false;
				}
			}

			$result = $this->setup->app()->gk->can($rule['action'], $controlled, $accessing);
			if ($result !== $expected) {
				return false;
			}
		}
		return true;
	}
	public function run() {
		foreach ($this->baseRules as $rule) {
			$controlled = $rule['controlled'];
			$accessing = $rule['accessing'];

			if (is_array($controlled)) {
				$model = $controlled['model'];
				$controlled = $model::find()->where($controlled['fields'])->one();
				if (!$controlled) {
					return false;
				}
			}

			if (is_array($accessing)) {
				$model = $accessing['model'];
				$accessing = $model::model()->fields($accessing['fields'])->find();
				if (!$accessing) {
					return false;
				}
			}

			if(!$this->setup->app()->gk->{$rule['task']}($rule['action'], $controlled, $accessing)) {
				return false;
			}
		}
		return true;
	}
	public function getFields() {
		return false;
	}
}
?>