<?php
Yii::import('application.models._base.BaseRobotCustomData');

/**
 * RobotCustomData class
 *
 */
class RobotCustomData extends BaseRobotCustomData
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RobotCustomData the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * Finds url for data 
	 * @return string 
	 *
	 */

	public function getDataURL(){
		$file_url = '';
		if($this->file_name != ''){
			$file_url = Yii::app()->request->getBaseUrl(true) . "/" . Yii::app()->params['robot-custom-data-directory-name'] ."/" .$this->id_robot_custom . "/" . $this->file_name;
		}
		return $file_url;
	}
}