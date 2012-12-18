<?php
Yii::import('application.models._base.BaseApiUser');

/**
 * ApiUser class.
 */
class ApiUser extends BaseApiUser
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ApiUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}