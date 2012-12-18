<?php
Yii::import('application.models._base.BaseRobotCustomDataType');

/**
 * RobotCustomDataType class
 *
 */
class RobotCustomDataType extends BaseRobotCustomDataType
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RobotCustomDataType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}