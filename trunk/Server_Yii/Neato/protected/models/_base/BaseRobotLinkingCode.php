<?php

/**
 * This is the model class for table "robot_linking_code".
 *
 * The followings are the available columns in table 'robot_linking_code':
 * @property string $id
 * @property string $email
 * @property string $serial_number
 * @property string $current_linking_state
 * @property string $linking_code
 * @property string $timestamp
 */
class BaseRobotLinkingCode extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RobotLinkingCode the static model class
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
		return 'robot_linking_code';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, serial_number, current_linking_state, timestamp', 'required'),
			array('email', 'length', 'max'=>128),
			array('serial_number', 'length', 'max'=>100),
			array('current_linking_state', 'length', 'max'=>20),
			array('linking_code', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, serial_number, current_linking_state, linking_code, timestamp', 'safe', 'on'=>'search'),
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
			'email' => 'Email',
			'serial_number' => 'Serial Number',
			'current_linking_state' => 'Current Linking State',
			'linking_code' => 'Linking Code',
			'timestamp' => 'Timestamp',
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
		$criteria->compare('email',$this->email,true);
		$criteria->compare('serial_number',$this->serial_number,true);
		$criteria->compare('current_linking_state',$this->current_linking_state,true);
		$criteria->compare('linking_code',$this->linking_code,true);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}