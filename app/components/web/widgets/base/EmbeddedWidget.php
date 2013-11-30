<?php
/**
 * ./app/components/web/widgets/RBaseWidget.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\web\widgets\base;

use Yii;

use \app\components\helpers\StringHelper;

use \infinite\helpers\Html;

use \yii\bootstrap\Nav;


abstract class EmbeddedWidget extends PanelWidget implements ObjectWidgetInterface {
	use ObjectWidgetTrait;

	public $panelCssClass = 'embedded-panel';
}


?>