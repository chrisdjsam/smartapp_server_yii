<?php
Yii::import('application.models._base.BaseUser');

/**
 * User class
 *
 */
class User extends BaseUser
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @see CActiveRecord::beforeSave()
	 */
	public function beforeSave(){
		$this->is_emailVerified = Yii::app()->params['autoEmailVerification'];

		return parent::beforeSave();
	}

	/**
	 * Check for exist of robot user association
	 * @return boolean
	 */
	public function doesRobotAssociationExist(){
		if($this->usersRobots){
			return true;
		}
		return false;
	}
}