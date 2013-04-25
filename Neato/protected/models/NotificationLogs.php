<?php
Yii::import('application.models._base.BaseNotificationLogs');

/**
 * UsersRobot class
 *
 */
class NotificationLogs extends BaseNotificationLogs
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return NotificationRegistrations the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
