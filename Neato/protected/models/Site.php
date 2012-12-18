<?php
Yii::import('application.models._base.BaseSite');

/**
 * Site class
 *
 */
class Site extends BaseSite
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Site the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}