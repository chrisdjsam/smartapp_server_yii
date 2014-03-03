<?php
Yii::import('application.models._base.BaseUserRole');

class UserRole extends BaseUserRole
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserRole the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
