<?php

/**
 * This is the model class for table "robot_schedules".
 *
 * The followings are the available columns in table 'robot_schedules':
 * @property string $id
 * @property string $id_robot
 * @property string $type
 * @property string $xml_data_file_name
 * @property string $blob_data_file_name
 * @property string $created_on
 * @property string $updated_on
 *
 * The followings are the available model relations:
 * @property RobotScheduleBlobDataVersions[] $robotScheduleBlobDataVersions
 * @property RobotScheduleXmlDataVersions[] $robotScheduleXmlDataVersions
 * @property Robots $idRobot
 */
class BaseRobotSchedule extends GxActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseRobotSchedule the static model class
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
		return 'robot_schedules';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('id_robot', 'required'),
				array('id_robot, type', 'length', 'max'=>20),
				array('xml_data_file_name, blob_data_file_name', 'length', 'max'=>100),
				array('updated_on', 'safe'),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, id_robot, type, xml_data_file_name, blob_data_file_name, created_on, updated_on', 'safe', 'on'=>'search'),
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
				'robotScheduleBlobDataVersions' => array(self::HAS_MANY, 'RobotScheduleBlobDataVersion', 'id_robot_schedule'),
				'robotScheduleXmlDataVersions' => array(self::HAS_MANY, 'RobotScheduleXmlDataVersion', 'id_robot_schedule'),
				'idRobot' => array(self::BELONGS_TO, 'Robot', 'id_robot'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'id' => 'ID',
				'id_robot' => 'Id Robot',
				'type' => 'Type',
				'xml_data_file_name' => 'Xml Data File Name',
				'blob_data_file_name' => 'Blob Data File Name',
				'created_on' => 'Created On',
				'updated_on' => 'Updated On',
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
		$criteria->compare('id_robot',$this->id_robot,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('xml_data_file_name',$this->xml_data_file_name,true);
		$criteria->compare('blob_data_file_name',$this->blob_data_file_name,true);
		$criteria->compare('created_on',$this->created_on,true);
		$criteria->compare('updated_on',$this->updated_on,true);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
}