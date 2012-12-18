<?php
Yii::import('application.models._base.BaseRobotMaps');

/**
 * RobotMap class
 *
 */
class RobotMap extends BaseRobotMaps
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RobotMap the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Finds latest version for xml data
	 * @return int
	 */
	public function getXMLDataLatestVersion(){
		$version_max = Yii::app()->db->createCommand("SELECT version FROM `robot_map_xml_data_versions` WHERE id_robot_map = '$this->id'  and created_on =
				(SELECT max(created_on) cre_on FROM `robot_map_xml_data_versions` WHERE id_robot_map = '$this->id')")->queryScalar();
		return $version_max;
	}
	
	/**
	 * Finds latest version for blob data
	 * @return int
	 */	
	public function getBlobDataLatestVersion(){
		$version_max = Yii::app()->db->createCommand("SELECT version FROM `robot_map_blob_data_versions` WHERE id_robot_map = '$this->id'  and created_on =
				(SELECT max(created_on) cre_on FROM `robot_map_blob_data_versions` WHERE id_robot_map = '$this->id')")->queryScalar();
		return $version_max;
	}
	
	/**
	 * Finds url for xml data 
	 * @return string 
	 *
	 */
	public function getXMLDataURL(){
		$file_url = Yii::app()->request->getBaseUrl(true) . "/" . Yii::app()->params['robot-data-directory-name']. "/" . $this->id ."/" . Yii::app()->params['robot-xml-data-directory-name'] . "/" . $this->xml_data_file_name;
		return $file_url;
	}
	
	/**
	 * Finds url for blob data 
	 * @return string 
	 *
	 */
	public function getBlobDataURL(){
		$file_url = '';
		if($this->blob_data_file_name != ''){
			$file_url = Yii::app()->request->getBaseUrl(true) . "/" . Yii::app()->params['robot-data-directory-name']. "/" . $this->id ."/" . Yii::app()->params['robot-blob-data-directory-name'] . "/" . $this->blob_data_file_name;
		}
		return $file_url;
	}
}