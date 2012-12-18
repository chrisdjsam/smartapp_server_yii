<?php
Yii::import('application.models._base.BaseRobotScheduleBlobDataVersion');

/**
 * RobotScheduleBlobDataVersion class
 *
 */
class RobotScheduleBlobDataVersion extends BaseRobotScheduleBlobDataVersion
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RobotScheduleBlobDataVersion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}