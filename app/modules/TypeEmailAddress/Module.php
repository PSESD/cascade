<?php

namespace app\modules\TypeEmailAddress;

use Yii;

class Module extends \app\components\types\Module
{
	protected $_title = 'Email Address';
	public $icon = 'ic-icon-at';
	public $uniparental = true;
	public $selfManaged = false;

	public $widgetNamespace = 'app\modules\TypeEmailAddress\widgets';
	public $modelNamespace = 'app\modules\TypeEmailAddress\models';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		
		Yii::$app->registerMigrationAlias('@app/modules/TypeEmailAddress/migrations');
	}

	/**
	 * @inheritdoc
	 */
	public function widgets()
	{
		return parent::widgets();
	}

	
	/**
	 * @inheritdoc
	 */
	public function parentSettings()
	{
		$settings = parent::parentSettings();
		$settings['title'] = false;
		$settings['showDescriptor'] = true;
		$settings['allow'] = 2;
		return $settings;
	}

	
	/**
	 * @inheritdoc
	 */
	public function parents()
	{
		return [
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
