<?php

/**
 * This is the model class for table "robot_custom_data".
 *
 * The followings are the available columns in table 'robot_custom_data':
 * @property string $id
 * @property string $id_robot_custom
 * @property string $id_robot_custom_data_type
 * @property string $file_name
 * @property string $version
 * @property string $created_on
 *
 * The followings are the available model relations:
 * @property RobotCustomDataTypes $idRobotCustomDataType
 * @property RobotCustoms $idRobotCustom
 */
class BaseRobotCustomData extends GxActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseRobotCustomData the static model class
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
		return 'robot_custom_data';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_robot_custom, id_robot_custom_data_type, file_name, version', 'required'),
			array('id_robot_custom, id_robot_custom_data_type, version', 'length', 'max'=>20),
			array('file_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_robot_custom, id_robot_custom_data_type, file_name, version, created_on', 'safe', 'on'=>'search'),
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
			'idRobotCustomDataType' => array(self::BELONGS_TO, 'RobotCustomDataType', 'id_robot_custom_data_type'),
			'idRobotCustom' => array(self::BELONGS_TO, 'RobotCustom', 'id_robot_custom'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_robot_custom' => 'Id Robot Custom',
			'id_robot_custom_data_type' => 'Id Robot Custom Data Type',
			'file_name' => 'File Name',
			'version' => 'Version',
			'created_on' => 'Created On',
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
		$criteria->compare('id_robot_custom',$this->id_robot_custom,true);
		$criteria->compare('id_robot_custom_data_type',$this->id_robot_custom_data_type,true);
		$criteria->compare('file_name',$this->file_name,true);
		$criteria->compare('version',$this->version,true);
		$criteria->compare('created_on',$this->created_on,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}