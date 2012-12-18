<?php

/**
 * This is the model class for table "users_socialservices".
 *
 * The followings are the available columns in table 'users_socialservices':
 * @property string $id
 * @property string $id_socialservicetype
 * @property string $id_user
 * @property string $user_social_id
 * @property string $username
 * @property string $access_token
 * @property string $expires_on
 * @property string $raw_data
 *
 * The followings are the available model relations:
 * @property Users $idUser
 * @property Socialservicetypes $idSocialservicetype
 */
class BaseUsersSocialservice extends GxActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseUsersSocialservice the static model class
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
		return 'users_socialservices';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_socialservicetype, id_user, user_social_id, username, expires_on', 'required'),
			array('id_socialservicetype, id_user', 'length', 'max'=>20),
			array('user_social_id', 'length', 'max'=>250),
			array('username', 'length', 'max'=>128),
			array('access_token', 'length', 'max'=>500),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_socialservicetype, id_user, user_social_id, username, access_token, expires_on, raw_data', 'safe', 'on'=>'search'),
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
			'idSocialservicetype' => array(self::BELONGS_TO, 'Socialservicetype', 'id_socialservicetype'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_socialservicetype' => 'Id Socialservicetype',
			'id_user' => 'Id User',
			'user_social_id' => 'User Social',
			'username' => 'Username',
			'access_token' => 'Access Token',
			'expires_on' => 'Expires On',
			'raw_data' => 'Raw Data',
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
		$criteria->compare('id_socialservicetype',$this->id_socialservicetype,true);
		$criteria->compare('id_user',$this->id_user,true);
		$criteria->compare('user_social_id',$this->user_social_id,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('access_token',$this->access_token,true);
		$criteria->compare('expires_on',$this->expires_on,true);
		$criteria->compare('raw_data',$this->raw_data,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}