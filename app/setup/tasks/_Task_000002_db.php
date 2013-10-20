<?php
namespace app\setup\tasks;

class Task_000002_db extends \infinite\setup\Task {
		public function getTitle() {
			return 'Database';
		}
		public function test() {
			return !$this->setup->migrator->check();
		}
		public function run() {
			return $this->setup->migrator->upgrade();
		}
		public function getVerification() {
			if ($this->setup->migrator->hasAnyMigrations) {
				return 'There are database upgrades available. Would you like to upgrade the database now?';
			}
			return false;
		}
	}
?>