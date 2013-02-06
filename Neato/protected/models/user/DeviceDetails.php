<?php
Yii::import('application.models._base.BaseDeviceDetails');

/**
 * User class
 *
 */
class DeviceDetails extends BaseDeviceDetails
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DeviceDetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}



	/**
	 * Check for users having this device details
	 * @return boolean
	 */
	public function doesUserAssociationExist(){
		if($this->userDevices){
			return true;
		}
		return false;
	}
}