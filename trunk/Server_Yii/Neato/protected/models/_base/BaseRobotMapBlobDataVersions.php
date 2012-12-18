<?php

/**
 * This is the model class for table "robot_map_blob_data_versions".
 *
 * The followings are the available columns in table 'robot_map_blob_data_versions':
 * @property string $id
 * @property string $id_robot_map
 * @property string $version
 * @property string $created_on
 *
 * The followings are the available model relations:
 * @property RobotMaps $idRobotMap
 */
class BaseRobotMapBlobDataVersions extends GxActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseRobotMapBlobDataVersions the static model class
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
		return 'robot_map_blob_data_versions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('id_robot_map, version', 'required'),
				array('id_robot_map, version', 'length', 'max'=>20),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, id_robot_map, version, created_on', 'safe', 'on'=>'search'),
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
				'idRobotMap' => array(self::BELONGS_TO, 'RobotMap', 'id_robot_map'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'id' => 'ID',
				'id_robot_map' => 'Id Robot Map',
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
		$criteria->compare('id_robot_map',$this->id_robot_map,true);
		$criteria->compare('version',$this->version,true);
		$criteria->compare('created_on',$this->created_on,true);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
}