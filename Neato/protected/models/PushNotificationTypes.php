<?php
Yii::import('application.models._base.BasePushNotificationTypes');

class PushNotificationTypes extends BasePushNotificationTypes
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PushNotificationTypes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
