<?php
Yii::import('application.models._base.BaseUsersRobot');

/**
 * UsersRobot class
 *
 */
class UsersRobot extends BaseUsersRobot
{
	public $user_email;
	public $robot_serial_number;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UsersRobot the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
