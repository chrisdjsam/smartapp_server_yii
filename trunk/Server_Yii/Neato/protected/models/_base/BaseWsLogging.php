<?php

/**
 * This is the model class for table "ws_logging".
 *
 * The followings are the available columns in table 'ws_logging':
 * @property string $id
 * @property string $id_site
 * @property string $remote_address
 * @property string $method_name
 * @property string $api_key
 * @property string $response_type
 * @property string $handler_name
 * @property string $request_type
 * @property string $request_data
 * @property string $response_data
 * @property integer $status
 * @property string $date_and_time
 * @property string $response_time
 *
 * The followings are the available model relations:
 * @property Sites $idSite
 */
class BaseWsLogging extends GxActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseWsLogging the static model class
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
		return 'ws_logging';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_site, remote_address, method_name, api_key, response_type, handler_name, request_data, response_data', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('id_site', 'length', 'max'=>20),
			array('response_type, handler_name, request_type', 'length', 'max'=>30),
			array('remote_address, method_name, api_key', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_site, remote_address, method_name, api_key, response_type, handler_name, request_type, request_data, response_data, status, date_and_time, response_time', 'safe', 'on'=>'search'),
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
			'remote_address' => 'Remote Address',
			'method_name' => 'Method Name',
			'api_key' => 'Api Key',
			'response_type' => 'Response Type',
			'handler_name' => 'Handler Name',
			'request_type' => 'Request Type',
			'request_data' => 'Request Data',
			'response_data' => 'Response Data',
			'status' => 'Status',
			'date_and_time' => 'Date And Time',
                        'response_time' => 'Response Time',
                    
                    
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
		$criteria->compare('remote_address',$this->remote_address,true);
		$criteria->compare('method_name',$this->method_name,true);
		$criteria->compare('api_key',$this->api_key,true);
		$criteria->compare('response_type',$this->response_type,true);
		$criteria->compare('handler_name',$this->handler_name,true);
		$criteria->compare('request_type',$this->request_type,true);
		$criteria->compare('request_data',$this->request_data,true);
		$criteria->compare('response_data',$this->response_data,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_and_time',$this->date_and_time,true);
                $criteria->compare('response_time',$this->response_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}