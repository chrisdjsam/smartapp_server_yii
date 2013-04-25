<?php

/**
 * This is the model class for table "notification_logs".
 *
 * The followings are the available columns in table 'notification_logs':
 * @property string $id
 * @property string $message
 * @property string $action
 * @property string $filter_criteria
 * @property string $notification_to
 * @property string $request
 * @property string $response
 * @property string $created_on
 * @property string $updated_on
 */
class BaseNotificationLogs extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseNotificationLogs the static model class
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
		return 'notification_logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('message', 'required'),
			array('action', 'length', 'max'=>5),
			array('filter_criteria', 'length', 'max'=>100),
			array('notification_to, request, response, created_on, updated_on, send_from, notification_type', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, message, action, filter_criteria, notification_to, request, response, created_on, updated_on', 'safe', 'on'=>'search'),
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
			'message' => 'Message',
			'action' => 'Action',
			'filter_criteria' => 'Filter Criteria',
                        'nofitication_type' => 'Notification Type',
                        'send_from' => 'Send From',
			'notification_to' => 'Notification To',
			'request' => 'Request',
			'response' => 'Response',
			'created_on' => 'Created On',
			'updated_on' => 'Updated On',
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
		$criteria->compare('message',$this->message,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('filter_criteria',$this->filter_criteria,true);
                $criteria->compare('notification_type',$this->notification_type,true);
                $criteria->compare('send_from',$this->send_from,true);
		$criteria->compare('notification_to',$this->notification_to,true);
		$criteria->compare('request',$this->request,true);
		$criteria->compare('response',$this->response,true);
		$criteria->compare('created_on',$this->created_on,true);
		$criteria->compare('updated_on',$this->updated_on,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}