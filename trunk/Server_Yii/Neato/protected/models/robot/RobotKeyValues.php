<?php
Yii::import('application.models._base.BaseRobotKeyValues');

/**
 * RobotKeyValues class.
 */
class RobotKeyValues extends BaseRobotKeyValues
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RobotKeyValues the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}