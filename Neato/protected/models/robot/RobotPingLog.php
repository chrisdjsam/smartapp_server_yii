<?php
Yii::import('application.models._base.BaseRobotPingLog');

/**
 * RobotPingLog class.
 */
class RobotPingLog extends BaseRobotPingLog
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RobotPingLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}