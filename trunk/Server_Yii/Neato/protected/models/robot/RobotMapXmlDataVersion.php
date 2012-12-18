<?php
Yii::import('application.models._base.BaseRobotMapXmlDataVersions');

/**
 * RobotMapXmlDataVersion class
 *
 */
class RobotMapXmlDataVersion extends BaseRobotMapXmlDataVersions
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RobotMapXmlDataVersion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

