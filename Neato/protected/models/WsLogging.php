<?php
Yii::import('application.models._base.BaseWsLogging');

/**
 * WsLogging class
 *
 */
class WsLogging extends BaseWsLogging
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WsLogging the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}