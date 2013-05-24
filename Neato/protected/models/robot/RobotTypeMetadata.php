<?php
Yii::import('application.models._base.BaseRobotTypeMetadata');

/**
 * RobotPingLog class.
 */
class RobotTypeMetadata extends BaseRobotTypeMetadata
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RobotTypeMetadata the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}