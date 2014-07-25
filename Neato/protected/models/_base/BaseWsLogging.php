<?php

/**
 * This is the model class for table "ws_logging".
 *
 * The followings are the available columns in table 'ws_logging':
 * @property string $id
 * @property string $remote_address
 * @property string $method_name
 * @property string $request_data
 * @property string $response_data
 * @property integer $status
 * @property string $date_and_time
 * @property string $response_time
 * @property string $serial_number
 * @property string $email
 * @property string $api_request
 * @property string $start_time
 * @property string $end_time
 * @property string $internal_process_values
 * @property string $source
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
			array('remote_address, method_name', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('remote_address, method_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, remote_address, method_name, request_data, response_data, status, date_and_time, response_time, serial_number, email, api_request, start_time, end_time, internal_process_values, source, app_info_header', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'remote_address' => 'Remote Address',
			'method_name' => 'Method Name',
			'request_data' => 'Request Data',
			'response_data' => 'Response Data',
			'status' => 'Status',
			'date_and_time' => 'Date And Time',
            'response_time' => 'Response Time',
			'serial_number' => 'Serial Number',
			'email' => 'Email',
			'api_request' => 'API Request',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'internal_process_values' => 'Internal Process Values',
			'source' => 'Source',
			'app_info_header' => 'App Info Header',
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
		$criteria->compare('remote_address',$this->remote_address,true);
		$criteria->compare('method_name',$this->method_name,true);
		$criteria->compare('request_data',$this->request_data,true);
		$criteria->compare('response_data',$this->response_data,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_and_time',$this->date_and_time,true);
        $criteria->compare('response_time',$this->response_time,true);
        $criteria->compare('serial_number',$this->serial_number,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('api_request',$this->api_request,true);
        $criteria->compare('start_time',$this->start_time,true);
        $criteria->compare('end_time',$this->end_time,true);
        $criteria->compare('internal_process_values',$this->internal_process_values,true);
        $criteria->compare('source',$this->source,true);
        $criteria->compare('app_info_header',$this->app_info_header,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}