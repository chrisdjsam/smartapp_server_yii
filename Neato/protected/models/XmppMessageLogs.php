<?php
Yii::import('application.models._base.BaseXmppMessageLogs');

class XmppMessageLogs extends BaseXmppMessageLogs
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return XmppMessageLogs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
