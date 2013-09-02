<?php
Yii::import('application.models._base.BaseOnlineChatId');

/**
 * OnlineChatId class
 *
 */
class OnlineChatId extends BaseOnlineChatId
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
