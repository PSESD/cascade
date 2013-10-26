<?php
/**
 * ./app/components/web/widgets/core/RGridView.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */


Yii::import('zii.widgets.CBaseListView');
Yii::import('zii.widgets.grid.*');
class RTableView extends RBaseListView {
	const FILTER_POS_HEADER='header';
	const FILTER_POS_FOOTER='footer';
	const FILTER_POS_BODY='body';

	private $_formatter;

	/**
	 *
	 *
	 * @var array grid column configuration. Each array element represents the configuration
	 * for one particular grid column which can be either a string or an array.
	 *
	 * When a column is specified as a string, it should be in the format of "name:type:header",
	 * where "type" and "header" are optional. A {@link RDataColumn} instance will be created in this case,
	 * whose {@link RDataColumn::name}, {@link RDataColumn::type} and {@link RDataColumn::header}
	 * properties will be initialized accordingly.
	 *
	 * When a column is specified as an array, it will be used to create a grid column instance, where
	 * the 'class' element specifies the column class name (defaults to {@link RDataColumn} if absent).
	 * Currently, these official column classes are provided: {@link RDataColumn},
	 * {@link CLinkColumn}, {@link CButtonColumn} and {@link CCheckBoxColumn}.
	 */
	public $columns=array();

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
	 *
	 *
	 * @var string a PHP expression that is evaluated for every table body row and whose result
	 * is used as additional HTML attributes for the row. In this expression, the variable <code>$row</code>
	 * stands for the row number (zero-based), <code>$data</code> is the data model associated with
	 * the row, and <code>$this</code> is the grid object.
	 * @since 1.1.13
	 */
	public $rowHtmlOptionsExpression;

	/**
	 *
	 *
	 * @var boolean whether to display the table even when there is no data. Defaults to true.
	 * The {@link emptyText} will be displayed to indicate there is no data.
	 */
	public $showTableOnEmpty=true;
	public $updateSelector='{page}, {sort}';
	public $ajaxData=array();

	/**
	 *
	 *
	 * @var mixed the URL for the AJAX requests should be sent to. {@link CHtml::normalizeUrl()} will be
	 * called on this property. If not set, the current page URL will be used for AJAX requests.
	 * @since 1.1.8
	 */
	public $ajaxUrl;



	/**
	 *
	 *
	 * @var string a javascript function that will be invoked after the row selection is changed.
	 * The function signature is <code>function(id)</code> where 'id' refers to the ID of the grid view.
	 * In this function, you may use <code>$(gridID).yiiGridView('getSelection')</code> to get the key values
	 * of the currently selected rows (gridID is the DOM selector of the grid).
	 * @see selectableRows
	 */
	public $selectionChanged;

	/**
	 *
	 *
	 * @var integer the number of table body rows that can be selected. If 0, it means rows cannot be selected.
	 * If 1, only one row can be selected. If 2 or any other number, it means multiple rows can be selected.
	 * A selected row will have a CSS class named 'selected'. You may also call the JavaScript function
	 * <code>$(gridID).yiiGridView('getSelection')</code> to retrieve the key values of the currently selected
	 * rows (gridID is the DOM selector of the grid).
	 */
	public $selectableRows=1;

	/**
	 *
	 *
	 * @var string the base script URL for all grid view resources (eg javascript, CSS file, images).
	 * Defaults to null, meaning using the integrated grid view resources (which are published as assets).
	 */
	public $baseScriptUrl;

	/**
	 *
	 *
	 * @var string the text to be displayed in a data cell when a data value is null. This property will NOT be HTML-encoded
	 * when rendering. Defaults to an HTML blank.
	 */
	public $nullDisplay='&nbsp;';

	/**
	 *
	 *
	 * @var string the text to be displayed in an empty grid cell. This property will NOT be HTML-encoded when rendering. Defaults to an HTML blank.
	 * This differs from {@link nullDisplay} in that {@link nullDisplay} is only used by {@link RDataColumn} to render
	 * null data values.
	 * @since 1.1.7
	 */
	public $blankDisplay='&nbsp;';

	/**
	 *
	 *
	 * @var string the CSS class name that will be assigned to the widget container element
	 * when the widget is updating its content via AJAX. Defaults to 'grid-view-loading'.
	 * @since 1.1.1
	 */
	public $loadingCssClass='grid-view-loading';

	/**
	 *
	 *
	 * @var string the jQuery selector of filter input fields.
	 * The token '{filter}' is recognized and it will be replaced with the grid filters selector.
	 * Defaults to '{filter}'.
	 *
	 * Note: if this value is empty an exception will be thrown.
	 *
	 * Example (adding a custom selector to the default one):
	 * <pre>
	 *  ...
	 *  'filterSelector'=>'{filter}, #myfilter',
	 *  ...
	 * </pre>
	 * @since 1.1.13
	 */
	public $filterSelector='{filter}';

	/**
	 *
	 *
	 * @var string the CSS class name for the table row element containing all filter input fields. Defaults to 'filters'.
	 * @see filter
	 * @since 1.1.1
	 */
	public $filterCssClass='filters';

	/**
	 *
	 *
	 * @var string whether the filters should be displayed in the grid view. Valid values include:
	 * <ul>
	 *    <li>header: the filters will be displayed on top of each column's header cell.</li>
	 *    <li>body: the filters will be displayed right below each column's header cell.</li>
	 *    <li>footer: the filters will be displayed below each column's footer cell.</li>
	 * </ul>
	 * @see filter
	 * @since 1.1.1
	 */
	public $filterPosition='body';

	/**
	 *
	 *
	 * @var CModel the model instance that keeps the user-entered filter data. When this property is set,
	 * the grid view will enable column-based filtering. Each data column by default will display a text field
	 * at the top that users can fill in to filter the data.
	 * Note that in order to show an input field for filtering, a column must have its {@link RDataColumn::name}
	 * property set or have {@link RDataColumn::filter} as the HTML code for the input field.
	 * When this property is not set (null) the filtering is disabled.
	 * @since 1.1.1
	 */
	public $filter;

	/**
	 *
	 *
	 * @var boolean whether to hide the header cells of the grid. When this is true, header cells
	 * will not be rendered, which means the grid cannot be sorted anymore since the sort links are located
	 * in the header. Defaults to false.
	 * @since 1.1.1
	 */
	public $hideHeader=false;

	public $widget;


	/**
	 *
	 *
	 * @return unknown
	 */
	public function getAjaxUpdate() {
		return !(empty($this->ajaxUrl) and empty($this->widget));
	}


	/**
	 * Initializes the grid view.
	 * This method will initialize required property values and instantiate {@link columns} objects.
	 */
	public function init() {
		parent::init();

		if (empty($this->updateSelector)) {
			throw new CException(Yii::t('zii', 'The property updateSelector should be defined.'));
		}
		if (empty($this->filterSelector)) {
			throw new CException(Yii::t('zii', 'The property filterSelector should be defined.'));
		}

		if (!isset($this->htmlOptions['class'])) {
			$this->htmlOptions['class']='grid-view';
		}

		if ($this->baseScriptUrl===null) {
			$this->baseScriptUrl='/themes/ic/js';
		}

		$this->initColumns();
	}


	/**
	 * Creates column objects and initializes them.
	 */
	protected function initColumns() {
		if ($this->columns===array()) {
			if ($this->dataProvider instanceof CActiveDataProvider)
				$this->columns=$this->dataProvider->model->attributeNames();
			elseif ($this->dataProvider instanceof IDataProvider) {
				// use the keys of the first row of data as the default columns
				$data=$this->dataProvider->getData();
				if (isset($data[0]) && is_array($data[0]))
					$this->columns=array_keys($data[0]);
			}
		}
		$id=$this->getId();
		foreach ($this->columns as  $i => $column) {
			if (is_string($column))
				$column=$this->createDataColumn($column);
			else {
				if (!isset($column['class']))
					$column['class']='RDataColumn';
				$column=Yii::createComponent($column, $this);
			}
			if (!$column->visible) {
				unset($this->columns[$i]);
				continue;
			}
			if ($column->id===null)
				$column->id=$id.'_c'.$i;
			$this->columns[$i]=$column;
		}

		foreach ($this->columns as $column)
			$column->init();
	}


	/**
	 * Creates a {@link RDataColumn} based on a shortcut column specification string.
	 *
	 * @param string  $text the column specification string
	 * @return RDataColumn the column instance
	 */
	protected function createDataColumn($text) {
		if (!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $text, $matches))
			throw new CException(Yii::t('zii', 'The column must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));
		$column=new RDataColumn($this);
		$column->name=$matches[1];
		if (isset($matches[3]) && $matches[3]!=='')
			$column->type=$matches[3];
		if (isset($matches[5]))
			$column->header=$matches[5];
		return $column;
	}


	/**
	 * Registers necessary client scripts.
	 */
	public function registerClientScript() {
		$id=$this->getId();
		$options=array(
			'widget' => $this->widget,
			'data' => $this->ajaxData,
			'pagerClass' => $this->pagerCssClass,
			'loadingClass' => $this->loadingCssClass,
			'filterClass' => $this->filterCssClass,
			'tableClass' => $this->itemsCssClass,
			'selectableRows' => $this->selectableRows
		);
		if ($this->ajaxUrl!==null)
			$options['url']=CHtml::normalizeUrl($this->ajaxUrl);
		if ($this->enablePagination)
			$options['pageVar']=$this->dataProvider->getPagination()->pageVar;

		$options=json_encode($options);
		$this->htmlOptions['data-list-view-options'] = $options;
	}


	/**
	 * Renders the data items for the grid view.
	 */
	public function renderItems() {
		if ($this->dataProvider->getItemCount()>0 || $this->showTableOnEmpty) {
			echo "<table class=\"{$this->itemsCssClass}\">\n";
			$this->renderTableHeader();
			ob_start();
			$this->renderTableBody();
			$body=ob_get_clean();
			$this->renderTableFooter();
			echo $body; // TFOOT must appear before TBODY according to the standard.
			echo "</table>";
		}
		else
			$this->renderEmptyText();
	}


	/**
	 * Renders the table header.
	 */
	public function renderTableHeader() {
		if (!$this->hideHeader) {
			echo "<thead>\n";

			if ($this->filterPosition===self::FILTER_POS_HEADER)
				$this->renderFilter();

			echo "<tr>\n";
			foreach ($this->columns as $column)
				$column->renderHeaderCell();
			echo "</tr>\n";

			if ($this->filterPosition===self::FILTER_POS_BODY)
				$this->renderFilter();

			echo "</thead>\n";
		}
		elseif ($this->filter!==null && ($this->filterPosition===self::FILTER_POS_HEADER || $this->filterPosition===self::FILTER_POS_BODY)) {
			echo "<thead>\n";
			$this->renderFilter();
			echo "</thead>\n";
		}
	}


	/**
	 * Renders the filter.
	 *
	 * @since 1.1.1
	 */
	public function renderFilter() {
		if ($this->filter!==null) {
			echo "<tr class=\"{$this->filterCssClass}\">\n";
			foreach ($this->columns as $column)
				$column->renderFilterCell();
			echo "</tr>\n";
		}
	}


	/**
	 * Renders the table footer.
	 */
	public function renderTableFooter() {
		$hasFilter=$this->filter!==null && $this->filterPosition===self::FILTER_POS_FOOTER;
		$hasFooter=$this->getHasFooter();
		if ($hasFilter || $hasFooter) {
			echo "<tfoot>\n";
			if ($hasFooter) {
				echo "<tr>\n";
				foreach ($this->columns as $column)
					$column->renderFooterCell();
				echo "</tr>\n";
			}
			if ($hasFilter)
				$this->renderFilter();
			echo "</tfoot>\n";
		}
	}


	/**
	 * Renders the table body.
	 */
	public function renderTableBody() {
		$data=$this->dataProvider->getData();
		$n=count($data);
		echo "<tbody>\n";

		if ($n>0) {
			for ($row=0;$row<$n;++$row)
				$this->renderTableRow($row);
		}
		else {
			echo '<tr><td colspan="'.count($this->columns).'" class="empty">';
			$this->renderEmptyText();
			echo "</td></tr>\n";
		}
		echo "</tbody>\n";
	}


	/**
	 * Renders a table body row.
	 *
	 * @param integer $row the row number (zero-based).
	 */
	public function renderTableRow($row) {
		$htmlOptions=array();
		if ($this->rowHtmlOptionsExpression!==null) {
			$data=$this->dataProvider->data[$row];
			$options=$this->evaluateExpression($this->rowHtmlOptionsExpression, array( 'row' => $row, 'data' => $data));
			if (is_array($options))
				$htmlOptions = $options;
		}

		if ($this->rowCssClassExpression!==null) {
			$data=$this->dataProvider->data[$row];
			$class=$this->evaluateExpression($this->rowCssClassExpression, array( 'row' => $row, 'data' => $data));
		}
		elseif (is_array($this->rowCssClass) && ($n=count($this->rowCssClass))>0)
			$class=$this->rowCssClass[$row%$n];

		if (!empty($class)) {
			if (isset($htmlOptions['class']))
				$htmlOptions['class'].=' '.$class;
			else
				$htmlOptions['class']=$class;
		}

		echo CHtml::openTag('tr', $htmlOptions)."\n";
		foreach ($this->columns as $column)
			$column->renderDataCell($row);
		echo "</tr>\n";
	}


	/**
	 * This is true if any of the {@link columns} has a true {@link CGridColumn::hasFooter} value.
	 *
	 * @return boolean whether the table should render a footer.
	 */
	public function getHasFooter() {
		foreach ($this->columns as $column)
			if ($column->getHasFooter())
				return true;
			return false;
	}


	/**
	 *
	 *
	 * @return CFormatter the formatter instance. Defaults to the 'format' application component.
	 */
	public function getFormatter() {
		if ($this->_formatter===null)
			$this->_formatter=Yii::app()->format;
		return $this->_formatter;
	}


	/**
	 *
	 *
	 * @param CFormatter $value the formatter instance
	 */
	public function setFormatter($value) {
		$this->_formatter=$value;
	}


}


?>
