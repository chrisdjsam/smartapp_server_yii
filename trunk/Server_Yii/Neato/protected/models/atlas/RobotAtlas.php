<?php
Yii::import('application.models._base.BaseRobotAtlas');

/**
 * RobotAtlas class.
*/
class RobotAtlas extends BaseRobotAtlas
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RobotAtlas the static model class
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
		$version_max = Yii::app()->db->createCommand("SELECT version FROM `robot_atlas` WHERE id = '$this->id'")->queryScalar();
		return $version_max;
	}

	/**
	 * Finds url for xml data
	 * @return string
	 *
	 */
	public function getXMLDataURL(){
		$file_url = Yii::app()->request->getBaseUrl(true) . "/" . Yii::app()->params['robot-atlas-data-directory-name']. "/" . $this->id_robot ."/" . Yii::app()->params['robot-atlas-xml-data-directory-name'] . "/" . $this->xml_data_file_name;
		return $file_url;
	}

	/**
	 * Check for existance of grid image
	 * @return boolean
	 */
	public function doesGridImageExist(){
		if($this->atlasGridImages){
			return true;
		}
		return false;
	}
	
}