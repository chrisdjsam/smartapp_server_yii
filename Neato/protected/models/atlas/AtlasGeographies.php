<?php
Yii::import('application.models._base.BaseAtlasGeographies');

/**
 * AtlasGeographies class.
*/
class AtlasGeographies extends BaseAtlasGeographies
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AtlasGeographies the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}