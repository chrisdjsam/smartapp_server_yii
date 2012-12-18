<?php
Yii::import('application.models._base.BaseRobotCustom');

/**
 * RobotCustom class
 *
 */
class RobotCustom extends BaseRobotCustom
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

	/**
	 * Finds latest version for blob data
	 * @param string $data_type_name
	 * @return int
	 */
	public function getBlobDataLatestVersion($data_type_name){
		$data_type_model = RobotCustomDataType::model()->findByAttributes(array('name' => $data_type_name));
		$version_max = 0;
		if($data_type_model !== null ){
			$version_max = Yii::app()->db->createCommand("SELECT version FROM `robot_custom_data` WHERE id_robot_custom = '$this->id'  and id_robot_custom_data_type = '$data_type_model->id'")->queryScalar();
			return $version_max;
		}
		return $version_max;
	}
}