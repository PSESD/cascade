<?php
namespace app\setup\tasks;

class Task_000005_admin_user extends \infinite\setup\Task {
	public function getTitle() {
		return 'Admin User';
	}
	public function test() {
		return User::model()->count() > 0;
	}
	public function run() {
		$user = new User;
		$user->attributes = $this->input['admin'];
		$user->active = 1;
		$user->passwordPlain = $user->password;
		$user->password = User::hashPassword($user->passwordPlain);
		$user->is_administrator = 2;

		if ($user->save()) {
			$rel = new Relation;
			$rel->parent_object_id = Group::model()->field('system', 'super_administrators')->find();
			$rel->child_object_id = $user->id;
			$rel->active = 1;
			if ($rel->save()) {
				return true;
			} else {
				$this->errors[] = "Could not assign user to the Super Administrators group.";
			}
		}
		foreach ($user->errors as $field => $errors) {
			$this->fieldErrors[$field] = implode('; ', $errors);
		}
		return false;
	}
	public function getFields() {
		$fields = array();
		$fields['admin'] = array('label' => 'First Admin User', 'fields' => array());
		$fields['admin']['fields']['first_name'] = array('type' => 'text', 'label' => 'First Name', 'required' => true, 'value' => function() { return ''; });
		$fields['admin']['fields']['last_name'] = array('type' => 'text', 'label' => 'Last Name', 'required' => true, 'value' => function() { return ''; });
		$fields['admin']['fields']['username'] = array('type' => 'text', 'label' => 'Username', 'required' => true, 'value' => function() { return 'admin'; });
		$fields['admin']['fields']['password'] = array('type' => 'text', 'label' => 'Password', 'required' => true, 'value' => function() { return 'admin'; });
		return $fields;
	}
}
?>