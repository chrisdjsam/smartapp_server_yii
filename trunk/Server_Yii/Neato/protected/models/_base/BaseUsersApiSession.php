<?php

/**
 * This is the model class for table "users_api_sessions".
 *
 * The followings are the available columns in table 'users_api_sessions':
 * @property string $id
 * @property string $id_user
 * @property string $id_site
 * @property string $token
 * @property integer $expires
 *
 * The followings are the available model relations:
 * @property Sites $idSite
 * @property Users $idUser
 */
class BaseUsersApiSession extends GxActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseUsersApiSession the static model class
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
		return 'users_api_sessions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, id_site, token, expires', 'required'),
			array('expires', 'numerical', 'integerOnly'=>true),
			array('id_user, id_site', 'length', 'max'=>20),
			array('token', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_user, id_site, token, expires', 'safe', 'on'=>'search'),
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
			'idSite' => array(self::BELONGS_TO, 'Site', 'id_site'),
			'idUser' => array(self::BELONGS_TO, 'User', 'id_user'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_user' => 'Id User',
			'id_site' => 'Id Site',
			'token' => 'Token',
			'expires' => 'Expires',
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
		$criteria->compare('id_site',$this->id_site,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('expires',$this->expires);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}