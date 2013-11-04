<?php
/**
 * ./app/components/web/widgets/RBaseWidget.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

namespace app\components\web\widgets\base;

use Yii;

use \infinite\helpers\StringHelper;

use \yii\helpers\Json;

class Widget extends \yii\base\Widget {
	public $owner;
	public $instanceSettings;

	public $params = array();
	public $recreateParams = array();
	public $classes = array('ic-widget');
	public $widgetTag = 'div';

	public $grid = false;
	public $gridCellHighlight = false;
	public $gridCellStuck = false;
	public $gridTitle = false;
	public $gridTitleUrl = false;
	public $gridTitleTitle = '';
	public $gridTitleIcon = 'ic-icon-document_alt_stroke';
	public $gridTitleMenu = array();
	public $gridTitleExtraVariables = array();
	public $sectionCount;

	public $viewStyleDefault = 'grid';
	public $skin = false;
	public $_widgetId;
	protected $_state;
	protected $_gridCellSize = 'single';

	/**
	 *
	 *
	 * @return unknown
	 */
	public function getState() {
		return $this->_state;
	}


	/**
	 *
	 *
	 * @param unknown $state
	 */
	public function setState($state) {
		$this->_state = $state;
	}

	public function getGridCellSize($sectionCount = null) {
		if ($this->_gridCellSize === 'auto') {
			if (is_null($this->sectionCount) AND isset($this->recreateParams['sectionCount'])) {
				$this->sectionCount = (int) $this->recreateParams['sectionCount'];
			}
			if (is_null($sectionCount) AND isset($this->sectionCount)) {
				$sectionCount = (int) $this->sectionCount;
			}
			if ($sectionCount === 2) {
				return 'half';
			}
			return 'full';
		}
		return $this->_gridCellSize;
	}

	public function setGridCellSize($value) {
		$this->_gridCellSize = $value;
	}

	/**
	 *
	 *
	 * @return unknown
	 */
	public function getWidgetId() {
		if (!is_null($this->_widgetId)) {
			return $this->_widgetId;
		}
		return $this->_widgetId = 'ic-widget-'.md5(uniqid());
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getNiceName() {
		return preg_replace('/R([A-Za-z]+)Widget/', '\\1', get_class($this));
	}


	/**
	 *
	 *
	 * @param unknown $view
	 * @param unknown $extra (optional)
	 */
	public function prepare($view, $extra = array()) {
		$this->beforePrepare();
		$this->renderPartial($view, $extra);
	}


	/**
	 *
	 */
	public function beforePrepare() {
	}


	public function getViewStyle() {
		$options = $this->getViewStyleOptions();
		if (empty($options)) { return false; }
		$sessionKey = 'viewStyle-'. get_class($this);
		if (!empty($this->state['viewStyle'])) {
			$style = $this->state['viewStyle'];
		} elseif (isset(Yii::$app->session) AND !empty(Yii::$app->session[$sessionKey])) {
			$style = Yii::$app->session[$sessionKey];
		}

		if (!empty($style) AND isset($options[$style])) {
			if (isset(Yii::$app->session)) {
				Yii::$app->session[$sessionKey] = $style;
			}
			return $style;
		}
		return $this->viewStyleDefault;
	}

	public function getViewStyleOptions() {
		return array(
			'list' => array('class' => '\app\components\web\widgets\grid\ListView', 'label' => 'List', 'icon' => 'ic-icon-list')
		);
	}

	public function getFilters() {
		return false;
	}
	
	public function getSortableAttributes() {
		return array(
			'familiarity' => 'Familiarity',
			'last_accessed' => 'Last Accessed',
		);
	}

	public function getGridColumns() {
		return array();
	}

	/**
	 *
	 *
	 * @param unknown $view
	 * @param unknown $extra (optional)
	 */
	public function renderPartial($view, $extra = array()) {
		if ($this->grid) {
			if (isset($this->owner->systemId)) {
				$this->classes[] = 'ic-type-'. $this->owner->systemId;
			}
			$this->classes[] = 'ic-widget-'. $this->niceName;
			$this->classes[] = 'cell';
			$this->classes[] = 'group';
			$this->classes[] = $this->gridCellSize;
			if ($this->gridCellHighlight) {
				$this->classes[] = 'highlight';
			}
			if ($this->gridCellStuck) {
				$this->classes[] = 'stuck';
			}
		}
		echo Html::beginTag($this->widgetTag, '', array(
				'id' => $this->getWidgetId(),
				'class' => implode($this->classes, ' ')
			));
		echo Html::beginTag('div', '', array('class' => 'ic-widget-box'));

		if ($this->grid and $this->gridTitle) {
			$titleContent = ucwords(StringHelper::parseText($this->gridTitle, $this->gridTitleExtraVariables));
			if (!empty($this->gridTitleIcon)) {
				$titleContent = '<span class="icon ic-icon-gray ic-icon-16 '.$this->gridTitleIcon .'"></span>'. $titleContent;
			}
			if ($this->gridTitleUrl) {
				$titleContent = Html::link($titleContent, $this->gridTitleUrl, array('title' => $this->gridTitleTitle, 'class' => 'title'));
			} else {
				$titleContent = Html::tag('div', $titleContent, array('title' => $this->gridTitleTitle, 'class' => 'title'));
			}
			$titleContent .= $this->buildHeaderMenu();
			echo Html::tag('div', $titleContent, array('class' => 'header'));
		}
		if ($this->grid) {
			echo '<div class="content">';
		}
		$this->render($view, array_merge($this->params, $extra));
		if ($this->grid) {
			echo '</div>'; // end content
		}
		echo '</div>'; // ic-widget-box
		echo '</'.$this->widgetTag.'>';
	}


	/**
	 *
	 *
	 * @param unknown $extra     (optional)
	 * @param unknown $onlyExtra (optional)
	 */
	public function json($extra = array(), $onlyExtra = false) {
		if ($onlyExtra) {
			$this->params = $extra;
		} else {
			$this->params = array_merge($this->params, $extra);
		}
		header('Content-Type: application/json');
		echo Json::encode($this->params);
		Yii::$app->end();
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function buildHeaderMenu() {
		$menuItems = array();
		if (!empty($this->gridTitleMenu)) {
			foreach ($this->gridTitleMenu as $item) {
				$classExtra = '';
				if (!empty($item['ajax'])) {
					$classExtra = ' ajax';
				}
				$menuItems[] = Html::tag('li', Html::link('', $item['url'], array('class' => 'ic-icon-gray ic-icon-16 '. $item['icon'] . $classExtra, 'title' => $item['title'])));
			}
			$titleMenu = Html::tag('ul', implode($menuItems, ''), array( 'class' => 'header-menu'));
			return $titleMenu;
		}
		return '';
	}


	/**
	 *
	 *
	 * @param unknown $checkTheme (optional)
	 * @return unknown
	 */
	public function getViewPath($checkTheme=false) {
		$className = get_class($this);
		$cleanName = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', preg_replace('/R([A-Za-z]+)Widget/', '\\1', $className)));
		if ($checkTheme && ($theme=Yii::$app->getTheme())!==null) {
			$path=$theme->getViewPath().DIRECTORY_SEPARATOR .'widgets'.DIRECTORY_SEPARATOR;
			if (strpos($className, '\\')!==false) { // namespaced class
				$path.=str_replace('\\', '_', ltrim($className, '\\'));
			} else {
				$path.=$cleanName;
			}
			if (is_dir($path)) {
				return self::$_viewPaths[$className]=$path;
			}
		}
		if (!empty($this->owner)) {
			return $this->owner->getViewPath().DIRECTORY_SEPARATOR.'widgets'.DIRECTORY_SEPARATOR.$cleanName;
		}
		return Yii::$app->getViewPath().DIRECTORY_SEPARATOR.'widgets'.DIRECTORY_SEPARATOR.$cleanName;
	}


}


?>
