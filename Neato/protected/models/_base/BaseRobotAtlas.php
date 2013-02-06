<?php

/**
 * This is the model class for table "robot_atlas".
 *
 * The followings are the available columns in table 'robot_atlas':
 * @property string $id
 * @property string $id_robot
 * @property string $xml_data_file_name
 * @property string $version
 *
 * The followings are the available model relations:
 * @property AtlasGeographies[] $atlasGeographies
 * @property Robots $idRobot
 */
class BaseRobotAtlas extends GxActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseRobotAtlas the static model class
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
		return 'robot_atlas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
// 			array('id_robot', 'required'),
			array('id_robot, version', 'length', 'max'=>20),
			array('xml_data_file_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_robot, xml_data_file_name, version', 'safe', 'on'=>'search'),
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
			'atlasGridImages' => array(self::HAS_MANY, 'AtlasGridImage', 'id_atlas'),
			'idRobot' => array(self::BELONGS_TO, 'Robots', 'id_robot'),
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
			'xml_data_file_name' => 'XML Data File Name',
			'version' => 'Version',
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
		$criteria->compare('xml_data_file_name',$this->xml_data_file_name,true);
		$criteria->compare('version',$this->version,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}