<?php

/**
 * This is the model class for table "robot_config_key_values".
 *
 * The followings are the available columns in table 'robot_config_key_values':
 * @property string $id
 * @property string $robot_id
 * @property string $_key
 * @property string $value
 *
 * The followings are the available model relations:
 * @property Robots $robot
 */
class BaseRobotConfigKeyValues extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseRobotConfigKeyValues the static model class
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
		return 'robot_config_key_values';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('robot_id', 'required'),
			array('robot_id', 'length', 'max'=>11),
			array('_key', 'length', 'max'=>1000),
			array('value', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, robot_id, _key, value', 'safe', 'on'=>'search'),
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
			'robot' => array(self::BELONGS_TO, 'Robots', 'robot_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'robot_id' => 'Robot',
			'_key' => 'Key',
			'value' => 'Value',
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
		$criteria->compare('robot_id',$this->robot_id,true);
		$criteria->compare('_key',$this->_key,true);
		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}