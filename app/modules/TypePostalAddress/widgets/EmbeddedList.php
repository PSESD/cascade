<?php
namespace app\modules\TypePostalAddress\widgets;

use infinite\helpers\Html;

class EmbeddedList extends \app\components\web\widgets\base\SideList
{
	public $renderContentTemplate = ['name' => ['class' => 'list-group-item-heading', 'tag' => 'h5'], 'address1', 'address2', 'csz', 'uniqueCountry'];
}
