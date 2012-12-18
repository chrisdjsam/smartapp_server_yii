<?php
Yii::import('application.models._base.BaseRobotMapBlobDataVersions');

/**
 * RobotMapBlobDataVersion class
 *
 */
class RobotMapBlobDataVersion extends BaseRobotMapBlobDataVersions
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RobotMapBlobDataVersion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}