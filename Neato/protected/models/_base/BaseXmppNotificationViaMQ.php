<?php

/**
 * This is the model class for table "xmpp_notification_via_mq".
 *
 * The followings are the available columns in table 'xmpp_notification_via_mq':
 * @property string $id
 * @property string $xmpp_uid
 * @property string $from
 * @property string $to
 * @property string $message
 * @property integer $is_jabber_setup
 * @property integer $response
 * @property string $status
 * @property string $start_time
 * @property string $end_time
 */

class BaseXmppNotificationViaMQ extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseXmppNotificationViaMQ the static model class
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
		return 'xmpp_notification_via_mq';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('is_jabber_setup', 'numerical', 'integerOnly'=>true),
			array('xmpp_uid', 'length', 'max'=>500),
			array('from, to, message', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, xmpp_uid, from, to, message, is_jabber_setup, response, status, start_time, end_time', 'safe', 'on'=>'search'),
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
			'xmpp_uid' => 'Xmpp Uid',
			'from' => 'From',
			'to' => 'To',
			'message' => 'Message',
			'is_jabber_setup' => 'Is Jabber Setup',
			'response' => 'Response',
			'status' => 'Status',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
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
		$criteria->compare('xmpp_uid',$this->xmpp_uid,true);
		$criteria->compare('from',$this->from,true);
		$criteria->compare('to',$this->to,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('is_jabber_setup',$this->is_jabber_setup);
		$criteria->compare('response',$this->response);
		$criteria->compare('status',$this->status);
		$criteria->compare('start_time',$this->start_time);
		$criteria->compare('end_time',$this->end_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}