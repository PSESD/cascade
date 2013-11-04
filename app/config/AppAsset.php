<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\config;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';

	// public $basePath = '@webroot';
	// public $baseUrl = '@web';
	public $css = ['css/site.css'];
	public $js = [];
	public $depends = [
		'\infinite\web\InfiniteAsset',
	];
}
