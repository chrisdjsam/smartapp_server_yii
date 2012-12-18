<?php
Yii::import('application.models._base.BaseUsersSocialservice');

/**
 * UsersSocialservice class
 *
 */
class UsersSocialservice extends BaseUsersSocialservice
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UsersSocialservice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}