<?php
Yii::import('application.models._base.BaseCountryCodeList');

class CountryCodeList extends BaseCountryCodeList
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserPushNotificationPreferences the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
