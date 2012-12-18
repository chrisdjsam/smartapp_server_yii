<?php

/**
 * This is the model class for table "api_users".
 *
 * The followings are the available columns in table 'api_users':
 * @property string $id
 * @property string $id_site
 * @property string $api_key
 * @property string $secret_key
 * @property integer $active
 *
 * The followings are the available model relations:
 * @property Sites $idSite
 */
class BaseApiUser extends GxActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseApiUser the static model class
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
		return 'api_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('id_site, api_key, secret_key, active', 'required'),
				array('active', 'numerical', 'integerOnly'=>true),
				array('id_site', 'length', 'max'=>20),
				array('api_key, secret_key', 'length', 'max'=>100),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, id_site, api_key, secret_key, active', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'id' => 'ID',
				'id_site' => 'Id Site',
				'api_key' => 'Api Key',
				'secret_key' => 'Secret Key',
				'active' => 'Active',
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
		$criteria->compare('id_site',$this->id_site,true);
		$criteria->compare('api_key',$this->api_key,true);
		$criteria->compare('secret_key',$this->secret_key,true);
		$criteria->compare('active',$this->active);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
}