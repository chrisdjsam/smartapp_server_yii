<?php
Yii::import('application.models._base.BaseSMTPViaMQ');

class SMTPViaMQ extends BaseSMTPViaMQ
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SMTPViaMQ the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}