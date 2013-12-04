<?php

namespace app\modules\TypePostalAddress;

use Yii;

class Module extends \app\components\types\Module
{
	protected $_title = 'Postal Address';
	public $icon = 'fa fa-envelope';
	public $uniparental = true;
	public $hasDashboard = false;

	public $widgetNamespace = 'app\modules\TypePostalAddress\widgets';
	public $modelNamespace = 'app\modules\TypePostalAddress\models';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		
		Yii::$app->registerMigrationAlias('@app/modules/TypePostalAddress/migrations');
	}

	/**
	 * @inheritdoc
	 */
	public function widgets()
	{
		$widgets = parent::widgets();
		$widgets['EmbeddedPostalAddressBrowse']['section'] = Yii::$app->collectors['sections']->getOne('_side');
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