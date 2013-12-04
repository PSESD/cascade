<?php

namespace app\modules\TypeEmailAddress\widgets;

class EmbeddedList extends \app\components\web\widgets\base\SideList
{
	public $renderContentTemplate = ['mailLink' => ['class' => 'list-group-item-heading', 'tag' => 'h5']];
}
