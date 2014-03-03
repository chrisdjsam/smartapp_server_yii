<?php
Yii::import('application.models._base.BaseAliveRobot');

/**
 * AliveRobot class
 *
 */
class AliveRobot extends BaseAliveRobot
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AliveRobot the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}