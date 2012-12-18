<?php

/**
 * This is the model class for table "socialservicetypes".
 *
 * The followings are the available columns in table 'socialservicetypes':
 * @property string $id
 * @property string $name
 * @property string $consumer_key
 * @property string $secret_key
 * @property string $username
 *
 * The followings are the available model relations:
 * @property UsersSocialservices[] $usersSocialservices
 */
class BaseSocialservicetype extends GxActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseSocialservicetype the static model class
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
		return 'socialservicetypes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, consumer_key, secret_key, username', 'required'),
			array('name, consumer_key, secret_key, username', 'length', 'max'=>250),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, consumer_key, secret_key, username', 'safe', 'on'=>'search'),
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
			'usersSocialservices' => array(self::HAS_MANY, 'UsersSocialservice', 'id_socialservicetype'),
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
			'consumer_key' => 'Consumer Key',
			'secret_key' => 'Secret Key',
			'username' => 'Username',
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
		$criteria->compare('consumer_key',$this->consumer_key,true);
		$criteria->compare('secret_key',$this->secret_key,true);
		$criteria->compare('username',$this->username,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}