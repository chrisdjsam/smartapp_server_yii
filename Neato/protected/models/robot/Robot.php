<?php
Yii::import('application.models._base.BaseRobot');

/**
 * Robot class.
 */
class Robot extends BaseRobot
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Robot the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Check for existance of robot user association
	 * @return boolean
	 */
	public function doesUserAssociationExist(){
		if($this->usersRobots){
			return true;
		}
		return false;
	}
	
	/**
	 * Check for existance of robot map
	 * @return boolean
	 */
	public function doesMapExist(){
		if($this->robotMaps){
			return true;
		}
		return false;
	}

	/**
	 * Check for existance of the robot schedule
	 * @return boolean
	 */
	public function doesScheduleExist(){
		if($this->robotSchedules){
			return true;
		}
		return false;
	}
	
	/**
	 * Check for existance of the robot atlas
	 * @return boolean
	 */
	public function doesAtlasExist(){
		if($this->robotAtlas){
			return true;
		}
		return false;
	}
        
        public function beforeSave() {
            
            $utc_str = gmdate("M d Y H:i:s", time());
            $utc = strtotime($utc_str);
        
            if ($this->isNewRecord) {
                $this->created_on = $utc;
            }
            $this->updated_on = $utc;

            return parent::beforeSave();
            
        }
}