<?php
Yii::import('application.models._base.BaseXmppNotificationViaMQ');

/**
 * OnlineChatId class
 *
 */
class XmppNotificationViaMQ extends BaseXmppNotificationViaMQ
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RobotCustom the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	
	
}
?>
