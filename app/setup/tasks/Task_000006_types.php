<?php
namespace app\setup\tasks;

use \infinite\setup\Exception;

class Task_000006_types extends \infinite\setup\Task {
	public function getTitle() {
		return 'Type Registration';
	}
	
	public function test() {
		return $this->setup->app()->types->isReady();
	}
	public function run() {
		return $this->setup->app()->types->prepareTypes();
		return true;
	}
	public function getFields() {
		return false;
	}
}
?>