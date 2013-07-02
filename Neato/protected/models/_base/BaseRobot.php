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
 * @property string $sleep_time
 * @property string $lag_time
 * @property string $value_extra
 * @property string $updated_on
 * @property string $created_on
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
                                array('sleep_time, lag_time', 'numerical', 'integerOnly'=>true),
                                array('lag_time, sleep_time', 'timeValidators'),
                                
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, name, serial_number, chat_id, chat_pwd, sleep_time, lag_time, value_extra, updated_on, created_on', 'safe', 'on'=>'search'),
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
                                'robotRobotTypes' => array(self::HAS_ONE, 'RobotRobotTypes', 'robot_id'),
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
                                'sleep_time' => 'Sleep Time',
                                'lag_time' => 'Wakeup Time',
                                'value_extra' => 'Extra Value',
                                'updated_on' => 'Updated on',
                                'created_on' => 'Created on',
                    
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
                $criteria->compare('sleep_time',$this->value_extra,true);
                $criteria->compare('lag_time',$this->value_extra,true);
                $criteria->compare('value_extra',$this->value_extra,true);
                $criteria->compare('updated_on',$this->updated_on,true);
                $criteria->compare('created_on',$this->created_on,true);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
        
        public function timeValidators()
        {
            if ($this->lag_time)
            {
                $labels = $this->attributeLabels(); // Getting labels of the attributes
                if($this->sleep_time == '')
                {
                   $this->addError("sleep_time", $labels["sleep_time"]." cannot be blank.");
                }
                // More dependent on type can be written here
            }
            
            if ($this->sleep_time)
            {
                $labels = $this->attributeLabels(); // Getting labels of the attributes
                if($this->lag_time == '')
                {
                   $this->addError("lag_time", $labels["lag_time"]." cannot be blank.");
                }
                // More dependent on type can be written here
            }
            
        }
        
}
