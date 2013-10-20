<?php
namespace app\setup\tasks;

class Task_000003_groups extends \infinite\setup\Task {
	public function getTitle() {
		return 'Groups';
	}
	public function getBaseGroups() {
		return array('Top' => array('Staff' => array('Contractors', 'Administrators' => array('Super Administrators')), 'Clients', 'Guests'));
	}

	public function test() {
		return Group::model()->field('system', 'top')->count() > 0;
	}
	public function run() {
		$groups = $this->baseGroups;
		array_walk($groups, array($this, 'groupWalker'));

		return empty($this->errors);
	}

	public function groupWalker(&$item, $key, $mparent = null) {
		if (is_array($item)) {
			$parent  = Group::model()->field('name', $key)->find();
			if (empty($parent)) {
				$parent = new Group;
				$parent->disableAcl();
				$parent->name = $key;
				$parent->system = preg_replace('/ /', '_', strtolower($parent->name));
				$parent->level = $this->getGroupLevel($key);

				if (!$parent->save()) {
					$this->errors[] = "Failed to create group {$key}!";
					return false;
				}
				if (!empty($mparent)) {
					$r = new Relation;
					$r->parent_object_id = $mparent;
					$r->child_object_id = $parent->id;
					$r->active = 1;
					if (!$r->save()) {
						$this->errors[] = "Failed to create group relationship {$key}!";
						return false;
					}
				}
			}
			$item = array_walk($item, array($this, 'groupWalker'), $parent->id);
		} else {
			$sitem = Group::model()->field('name', $item)->find();
			if (empty($sitem)) {
				$sitem = new Group;
				$sitem->disableAcl();
				$sitem->name = $item;
				$sitem->system = preg_replace('/ /', '_', strtolower($sitem->name));
				$sitem->level = $this->getGroupLevel($item);

				if (!$sitem->save()) {
					$this->errors[] = "Failed to create group {$item}!";
					return false;
				}
				if (!empty($mparent)) {
					$r = new Relation;
					$r->parent_object_id = $mparent;
					$r->child_object_id = $sitem->id;
					$r->active = 1;
					if (!$r->save()) {
						$this->errors[] = "Failed to create group relationship {$key}!";
						return false;
					}
				}
			}
			$setup->registry['Group'][$item] = $sitem->id;
		}
	}

	public function getGroupLevel($k) {
		switch ($k) {
		case 'Super Administrators':
			return 1001;
			break;
		case 'Administrators':
			return 1000;
			break;
		case 'Clients':
			return 1;
			break;
		case 'Top':
			return 0;
			break;
		default:
			return 100;
			break;
		}
	}
}
?>