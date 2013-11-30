<?php
namespace app\components\web\widgets\base;

use Yii;

interface ListWidgetInterface
{
	public function renderItem($model, $key, $index);
	public function getDataProvider();
	public function getDataProviderSettings();
	public function generateContent();
	public function getPaginationSettings();
}