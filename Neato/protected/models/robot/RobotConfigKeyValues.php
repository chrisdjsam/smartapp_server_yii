<?php
Yii::import('application.models._base.BaseRobotConfigKeyValues');

/**
 * RobotConfigKeyValues class.
 */
class RobotConfigKeyValues extends BaseRobotConfigKeyValues
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RobotConfigKeyValues the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}