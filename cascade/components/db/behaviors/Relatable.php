<?php
namespace app\components\db\behaviors;

class Relatable extends \infinite\db\behaviors\Relatable {
	public $relationClass = '\app\models\Relation';
	public $registryClass = '\app\models\Registry';
}
?>
