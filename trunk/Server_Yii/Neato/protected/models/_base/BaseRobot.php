<?php

/**
 * This is the model class for table "robots".
 *
 * The followings are the available columns in table 'robots':
 * @property string $id
 * @property string $name
 * @property string $serial_number
 * @property string $chat_id
 * @property string $chat_pwd
 *
 * The followings are the available model relations:
 * @property UsersRobots[] $usersRobots
 */
class BaseRobot extends GxActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseRobot the static model class
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
		return 'robots';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('serial_number, chat_id, chat_pwd', 'required'),
				array('name, serial_number, chat_id, chat_pwd', 'length', 'max'=>100),
				array('serial_number', 'unique', 'className'=>'Robot'),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, name, serial_number, chat_id, chat_pwd', 'safe', 'on'=>'search'),
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
				'usersRobots' => array(self::HAS_MANY, 'UsersRobot', 'id_robot'),
				'robotMaps' => array(self::HAS_MANY, 'RobotMap', 'id_robot'),
				'robotSchedules' => array(self::HAS_MANY, 'RobotSchedule', 'id_robot'),
				'robotCustoms' => array(self::HAS_MANY, 'RobotCustom', 'id_robot'),
				'robotAtlas' => array(self::HAS_ONE, 'RobotAtlas', 'id_robot'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'id' => 'ID',
				'name' => 'Name',
				'serial_number' => 'Serial Number',
				'robot_serial_number' => 'Robot Serial Number',
				'chat_id' => 'Chat ID',
				'chat_pwd' => 'Chat Password',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('serial_number',$this->serial_number,true);
		$criteria->compare('chat_id',$this->chat_id,true);
		$criteria->compare('chat_pwd',$this->chat_pwd,true);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
}