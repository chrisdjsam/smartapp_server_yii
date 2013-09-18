<?php
Yii::import('application.models._base.BaseRobotLinkingCode');

/**
 * RobotLinkingCode class.
 */
class RobotLinkingCode extends BaseRobotLinkingCode
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RobotRobotTypes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}