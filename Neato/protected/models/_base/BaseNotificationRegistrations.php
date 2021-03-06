<?php

/**
 * This is the model class for table "notification_registrations".
 *
 * The followings are the available columns in table 'notification_registrations':
 * @property string $id
 * @property string $user_id
 * @property string $registration_id
 * @property string $device_type
 * @property string $created_on
 * @property string $application_id
 * @property string $notification_server_type
 * The followings are the available model relations:
 * @property Users $user
 */
class BaseNotificationRegistrations extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseNotificationRegistrations the static model class
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
		return 'notification_registrations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('registration_id', 'required'),
			array('user_id', 'length', 'max'=>20),
			array('registration_id', 'length', 'max'=>1000),
			array('device_type, is_active', 'length', 'max'=>5),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, registration_id, device_type, created_on, application_id, notification_server_type', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'registration_id' => 'Registration',
			'device_type' => 'Device Type',
			'created_on' => 'Created On',
                        'application_id' => 'Application Id',
                        'notification_server_type' => 'Notification Server Type',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('registration_id',$this->registration_id,true);
		$criteria->compare('device_type',$this->device_type,true);
		$criteria->compare('created_on',$this->created_on,true);
                $criteria->compare('application_id',$this->application_id,true);
                $criteria->compare('notification_server_type',$this->notification_server_type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}