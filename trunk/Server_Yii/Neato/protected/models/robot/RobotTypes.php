<?php
Yii::import('application.models._base.BaseRobotTypes');

/**
 * RobotPingLog class.
 */
class RobotTypes extends BaseRobotTypes
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RobotTypes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getConcatened()
        {
                return $this->name . ' (' . $this->type . ')';
        }

}