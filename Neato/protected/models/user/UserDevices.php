<?php
Yii::import('application.models._base.BaseUserDevices');

/**
 * User class
 *
 */
class UserDevices extends BaseUserDevices
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserDevices the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}