<?php
namespace app\components\web\form\fields;

use \infinite\helpers\Html;
use \infinite\base\exceptions\Exception;

use \yii\helpers\Json;

class Cell extends \infinite\web\grid\Cell
{
	public $baseSize = 'tabletSize';

	public $phoneSize = false;
	public $tabletSize = 'auto';
	public $mediumDesktopSize = false; // baseline
	public $largeDesktopSize = false;
	protected $_defaultColumns = 'auto';
}
?>