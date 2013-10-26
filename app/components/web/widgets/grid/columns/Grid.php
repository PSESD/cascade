<?php
/**
 * ./app/components/web/widgets/core/RGridColumn.php
 *
 * @author Jacob Morrison <jacob@infinitecascade.com>
 * @package cascade
 */

Yii::import('zii.widgets.CBaseListView');
Yii::import('zii.widgets.*');
Yii::import('zii.widgets.grid.*');

class RGridColumn extends RDataColumn {
	protected $_dataLabel;
	
	public function getDataValue($row, $data, $clean = false) {
		if($this->value !== null) {
			$value = $this->evaluateExpression($this->value, array('data' => $data, 'row' => $row));
		} elseif($this->name !== null) {
			$value = CHtml::value($data, $this->name);
		}
    	$value = $value===null ? $this->grid->nullDisplay : $this->grid->getFormatter()->format($value,$this->type);
    	if ($clean AND is_string($value)) {
    		$value = strip_tags($value);
    	}
    	return $value;
	}

	public function setDataLabel($value) {
		$this->_dataLabel = $value;
	}

	public function getDataLabel() {
		if (!is_null($this->_dataLabel)) {
			return $this->_dataLabel;
		}
		if($this->grid->dataProvider instanceof CActiveDataProvider) {
            return CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
		} else {
            return CHtml::encode($this->name);
		}
	}
}


?>
