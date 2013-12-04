<?php
namespace app\components\section\base;

class Side extends \app\components\section\Section {
	public $sectionWidgetClass = 'app\components\web\widgets\base\SideSection';
	protected $_systemId = '_side';
	protected $_title = 'Side Panel';
}
?>