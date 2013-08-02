<?php

/**
 * This is the model class for table "xmpp_message_logs".
 *
 * The followings are the available columns in table 'xmpp_message_logs':
 * @property string $id
 * @property string $xmpp_message
 */
class BaseXmppMessageLogs extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseXmppMessageLogs the static model class
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
		return 'xmpp_message_logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('xmpp_message, send_from, send_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, xmpp_message, send_from, send_at', 'safe', 'on'=>'search'),
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
			'xmpp_message' => 'Xmpp Message',
                        'send_from' => 'Send From',
                        'send_at' => 'Send at',
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
		$criteria->compare('xmpp_message',$this->xmpp_message,true);
                $criteria->compare('send_from',$this->send_from,true);
                $criteria->compare('send_at',$this->send_at,true);
                

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}