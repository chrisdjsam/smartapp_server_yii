<?php

/**
 * This is the model class for table "users_robots".
 *
 * The followings are the available columns in table 'users_robots':
 * @property string $id
 * @property string $id_user
 * @property string $id_robot
 *
 * The followings are the available model relations:
 * @property Users $idUser
 * @property Robots $idRobot
 */
class BaseUsersRobot extends GxActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseUsersRobot the static model class
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
		return 'users_robots';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, id_robot', 'required'),
			array('id_user, id_robot', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_user, id_robot', 'safe', 'on'=>'search'),
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
			'idUser' => array(self::BELONGS_TO, 'User', 'id_user'),
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
			'id_user' => 'User',
			'id_robot' => 'Robot Serial Number',
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
		$criteria->compare('id_user',$this->id_user,true);
		$criteria->compare('id_robot',$this->id_robot,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}