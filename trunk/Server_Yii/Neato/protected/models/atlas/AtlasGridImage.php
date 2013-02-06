<?php
Yii::import('application.models._base.BaseAtlasGridImage');

/**
 * RobotAtlas class.
*/
class AtlasGridImage extends BaseAtlasGridImage
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
	public function getBlobDataLatestVersion(){
		$version_max = Yii::app()->db->createCommand("SELECT version FROM `atlas_grid_image` WHERE id = '$this->id'")->queryScalar();
		return $version_max;
	}

/**
	 * Finds url for blob data
	 * @return string
	 *
	 */
	public function getBlobDataURL(){		
		$file_url = Yii::app()->request->getBaseUrl(true) . "/" . Yii::app()->params['robot-atlas-data-directory-name']. "/" . $this->idAtlas->id_robot ."/" . Yii::app()->params['robot-atlas-blob-data-directory-name'] . "/" . $this->blob_data_file_name;
		return $file_url;
	}
}