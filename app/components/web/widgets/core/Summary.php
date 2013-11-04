<?php
/**
 * ./app/components/web/widgets/RDashboardSummaryWidget.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\web\widgets\core;

use Yii;

use \app\models\Registry;
use \app\models\ObjectFamiliarity;

use \infinite\web\Response;
use \infinite\helpers\Html;
use \infinite\db\behaviors\Relatable;
use \infinite\db\behaviors\Access;

class Summary extends \app\components\web\widgets\base\Widget {
	public $objectId;

	protected $_gridCellSize = 'single half-height';

	/**
	 *
	 */
	public function run() {
		$response = new Response('index', array(), $this);
		$this->widgetTag = 'li';
		$this->grid = true;
		$this->gridTitle = Yii::t('ic', $this->owner->title->getPlural(true));
		$this->gridTitleUrl = array('/app/browse', 'module' => $this->owner->systemId);
		$this->gridTitleTitle = Yii::t('ic', 'Browse '. $this->owner->title->getPlural(true));

		$this->gridTitleMenu = array();
		if (Yii::app()->gk->canGeneral('create', $this->owner->primaryModel)) {
			$this->gridTitleMenu[] = array('url' => array('/app/create', 'module' => $this->owner->systemId), 'icon' => 'ic-icon-plus', 'ajax' => true, 'title' => Yii::t('ic', 'Create a '. $this->owner->title->getSingular(true)));
		}

		$this->params['items'] = $this->getItems();
		$response->handlePartial();
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getItems() {
		return ObjectFamiliarity::familiarObjectsProvider($this->owner->primaryModel, $this->state);
	}


}


?>
