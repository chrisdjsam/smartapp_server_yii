<?php
Yii::import('application.models._base.BaseNotificationRegistrations');

/**
 * UsersRobot class
 *
 */
class NotificationRegistrations extends BaseNotificationRegistrations
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
