<?php
/**
 * ./app/components/web/widgets/core/RListView.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */


Yii::import('zii.widgets.CBaseListView');
Yii::import('zii.widgets.*');
class RListView extends RBaseListView {
	var $pager = 'RLinkPager';
	public $widget;
	public $ajaxData=array();

	public $styles = false;
	public $filters = false;

	/**
	 *
	 *
	 * @var array the CSS class names for the table body rows. If multiple CSS class names are given,
	 * they will be assigned to the rows sequentially and repeatedly. This property is ignored
	 * if {@link rowCssClassExpression} is set. Defaults to <code>array('odd', 'even')</code>.
	 * @see rowCssClassExpression
	 */
	public $rowCssClass=array('odd', 'even');

	/**
	 * @var string the base script URL for all list view resources (e.g. javascript, CSS file, images).
	 * Defaults to null, meaning using the integrated list view resources (which are published as assets).
	 */
	public $baseScriptUrl;

	/**
	 *
	 *
	 * @var string a PHP expression that is evaluated for every table body row and whose result
	 * is used as the CSS class name for the row. In this expression, the variable <code>$row</code>
	 * stands for the row number (zero-based), <code>$data</code> is the data model associated with
	 * the row, and <code>$this</code> is the grid object.
	 * @see rowCssClass
	 * @deprecated in 1.1.13
	 */
	public $rowCssClassExpression;

	/**
	 * @var string the view used for rendering each data item.
	 * This property value will be passed as the first parameter to either {@link CController::renderPartial}
	 * or {@link CWidget::render} to render each data item.
	 * In the corresponding view template, the following variables can be used in addition to those declared in {@link viewData}:
	 * <ul>
	 * <li><code>$this</code>: refers to the owner of this list view widget. For example, if the widget is in the view of a controller,
	 * then <code>$this</code> refers to the controller.</li>
	 * <li><code>$data</code>: refers to the data item currently being rendered.</li>
	 * <li><code>$index</code>: refers to the zero-based index of the data item currently being rendered.</li>
	 * <li><code>$widget</code>: refers to this list view widget instance.</li>
	 * </ul>
	 */
	public $itemView;

	/**
	 * @var array list of sortable attribute names. In order for an attribute to be sortable, it must also
	 * appear as a sortable attribute in the {@link IDataProvider::sort} property of {@link dataProvider}.
	 * @see enableSorting
	 */
	public $sortableAttributes;


	/**
	 * @var string the CSS class name for the sorter container. Defaults to 'sorter'.
	 */
	public $sorterCssClass='sorter';
	/**
	 * @var string the text shown before sort links. Defaults to 'Sort by: '.
	 */
	public $sorterHeader;
	/**
	 * @var string the text shown after sort links. Defaults to empty.
	 */
	public $sorterFooter='';

	/**
	 * @var mixed the URL for the AJAX requests should be sent to. {@link CHtml::normalizeUrl()} will be
	 * called on this property. If not set, the current page URL will be used for AJAX requests.
	 * @since 1.1.8
	 */
	public $ajaxUrl;

	/**
	 * @var string the HTML tag name for the container of all data item display. Defaults to 'div'.
	 * @since 1.1.4
	 */
	public $itemsTagName='div';

	/**
	 * @var array additional data to be passed to {@link itemView} when rendering each data item.
	 * This array will be extracted into local PHP variables that can be accessed in the {@link itemView}.
	 */
	public $viewData=array();

	/**
	 * @var string the HTML code to be displayed between any two consecutive items.
	 * @since 1.1.7
	 */
	public $separator;

	public function renderFilters() {
		
	}


	/**
	 * Initializes the list view.
	 * This method will initialize required property values and instantiate {@link columns} objects.
	 */
	public function init()
	{
		if($this->itemView===null)
			throw new CException(Yii::t('zii','The property "itemView" cannot be empty.'));
		parent::init();

		if(!isset($this->htmlOptions['class']))
			$this->htmlOptions['class']='list-view';
	}

	/**
	 * Renders the data item list.
	 */
	public function renderItems() {
		echo CHtml::openTag($this->itemsTagName, array( 'class' => $this->itemsCssClass .' group'))."\n";
		$data=$this->dataProvider->getData();
		if (($n=count($data))>0) {
			$owner=$this->getOwner();
			$viewFile=$owner->getViewFile($this->itemView);
			$j=0;
			foreach ($data as  $i => $item) {
				$data=$this->viewData;
				$data['index']=$i;
				$data['data']=$item;
				$data['widget']=$this;
				$data['htmlOptions']=array();
				if ($this->rowCssClassExpression!==null) {
					$data=$this->dataProvider->data[$row];
					$data['htmlOptions']['class'] = $this->evaluateExpression($this->rowCssClassExpression, array( 'data' => $item));
				} elseif (is_array($this->rowCssClass) && ($n=count($this->rowCssClass))>0) {
					$data['htmlOptions']['class'] = $this->rowCssClass[$i%$n];
				}
				$owner->renderFile($viewFile, $data);
				if ($j++ < $n-1) {
					echo $this->separator;
				}
			}
		}
		else
			$this->renderEmptyText();
		echo CHtml::closeTag($this->itemsTagName);
	}


	/**
	 *
	 */
	public function registerClientScript() {
		$id=$this->getId();
		$options=array(
			'widget' => $this->widget,
			'data' => $this->ajaxData,
			'pagerClass' => $this->pagerCssClass,
			'loadingClass' => $this->loadingCssClass,
			'tableClass' => $this->itemsCssClass
		);
		if ($this->ajaxUrl!==null)
			$options['url']=CHtml::normalizeUrl($this->ajaxUrl);
		if ($this->enablePagination)
			$options['pageVar']=$this->dataProvider->getPagination()->pageVar;

		$options=json_encode($options);
		$this->htmlOptions['data-list-view-options'] = $options;
	}

	/**
	 * Renders the sorter.
	 */
	public function renderSorter()
	{
		if($this->dataProvider->getItemCount()<=0 || !$this->enableSorting || empty($this->sortableAttributes))
			return;
		echo CHtml::openTag('div',array('class'=>$this->sorterCssClass))."\n";
		echo $this->sorterHeader===null ? Yii::t('zii','Sort by: ') : $this->sorterHeader;
		echo "<ul>\n";
		$sort=$this->dataProvider->getSort();
		foreach($this->sortableAttributes as $name=>$label)
		{
			echo "<li>";
			if(is_integer($name))
				echo $sort->link($label);
			else
				echo $sort->link($name,$label);
			echo "</li>\n";
		}
		echo "</ul>";
		echo $this->sorterFooter;
		echo CHtml::closeTag('div');
	}

}


?>
