<?php

/**
 * This is the model class for table "app_info".
 *
 * The followings are the available columns in table 'app_info':
 * @property string $id
 * @property string $app_id
 * @property string $current_app_version
 * @property string $os_version
 * @property string $os_type
 * @property string $latest_version
 * @property string $latest_version_url
 * @property integer $upgrade_status
 */
class BaseAppInfo extends GxActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseAppInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'app_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('app_id, upgrade_status', 'required'),
			array('upgrade_status', 'numerical', 'integerOnly'=>true),
			array('app_id', 'length', 'max'=>20),
			array('current_app_version', 'length', 'max'=>200),
			array('os_version, os_type, latest_version, latest_version_url', 'length', 'max'=>500),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, app_id, current_app_version, os_version, os_type, latest_version, latest_version_url, upgrade_status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'app_id' => 'App ID',
			'current_app_version' => 'Current App Version',
			'os_version' => 'OS Version',
			'os_type' => 'OS Type',
			'latest_version' => 'Latest Version',
			'latest_version_url' => 'Latest Version Url',
			'upgrade_status' => 'Upgrade Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('app_id',$this->app_id,true);
		$criteria->compare('current_app_version',$this->current_app_version,true);
		$criteria->compare('os_version',$this->os_version,true);
		$criteria->compare('os_type',$this->os_type,true);
		$criteria->compare('latest_version',$this->latest_version,true);
		$criteria->compare('latest_version_url',$this->latest_version_url,true);
		$criteria->compare('upgrade_status',$this->upgrade_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}