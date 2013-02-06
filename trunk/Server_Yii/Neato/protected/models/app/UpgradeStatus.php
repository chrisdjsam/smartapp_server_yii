<?php
Yii::import('application.models._base.BaseUpgradeStatus');

/**
 * UpgradeStatus class.
 */
class UpgradeStatus extends BaseUpgradeStatus
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UpgradeStatus the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 
	 * @return array of upgrade status key => value.
	 */	
	public static function getUpgradeStatusValue(){
		$allStatus  = UpgradeStatus::model()->findAll();
		$statusArray = array();
		
		foreach ($allStatus as $status){
			$statusArray[$status->upgrade_status_key] = $status->upgrade_status_value; 
		}
		return $statusArray; 
	}
	
}