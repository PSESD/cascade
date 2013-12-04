<?php

namespace app\modules\TypeWebAddress;

use Yii;

class Module extends \app\components\types\Module
{
	protected $_title = 'Web Address';
	public $icon = 'fa fa-external-link';
	public $uniparental = true;
	public $hasDashboard = false;

	public $widgetNamespace = 'app\modules\TypeWebAddress\widgets';
	public $modelNamespace = 'app\modules\TypeWebAddress\models';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		
		Yii::$app->registerMigrationAlias('@app/modules/TypeWebAddress/migrations');
	}

	/**
	 * @inheritdoc
	 */
	public function widgets()
	{
		$widgets = parent::widgets();
		$widgets['EmbeddedWebAddressBrowse']['section'] = Yii::$app->collectors['sections']->getOne('_side');
		return $widgets;
	}

	
	/**
	 * @inheritdoc
	 */
	public function parents()
	{
		return [
			'Account' => [],
			'Individual' => [],
		];
	}

	
	/**
	 * @inheritdoc
	 */
	public function children()
	{
		return [];
	}

	
	/**
	 * @inheritdoc
	 */
	public function taxonomies()
	{
		return [];
	}
}