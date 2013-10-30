<?php
namespace app\components\dataInterface;

abstract class DbModule extends Module {
	abstract public function getForeignSchema();
}
?>