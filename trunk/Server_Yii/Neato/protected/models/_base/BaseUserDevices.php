<?php

/**
 * This is the model class for table "user_devices".
 *
 * The followings are the available columns in table 'user_devices':
 * @property integer $id
 * @property string $id_user
 * @property string $id_device_details
 *
 * The followings are the available model relations:
 * @property DeviceDetails $idDeviceDetails
 * @property Users $idUser
 */
class BaseUserDevices extends GxActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseUserDevices the static model class
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
		return 'user_devices';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('id_user, id_device_details', 'required'),
			array('id_user, id_device_details', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_user, id_device_details', 'safe', 'on'=>'search'),
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
			'idDeviceDetails' => array(self::BELONGS_TO, 'DeviceDetails', 'id_device_details'),
			'idUser' => array(self::BELONGS_TO, 'Users', 'id_user'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_user' => 'Id User',
			'id_device_details' => 'Id Device Details',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('id_user',$this->id_user,true);
		$criteria->compare('id_device_details',$this->id_device_details,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}