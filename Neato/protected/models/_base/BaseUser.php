<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property string $id
 * @property string $name
 * @property string $password
 * @property string $reset_password
 * @property string $email
 * @property integer $is_email_validated?
 * @property integer $is_admin
 * @property string $created_on
 * @property string $chat_id
 * @property string $chat_pwd
 * @property integer $is_active
 * @property integer $push_notification_preference
 *
 * The followings are the available model relations:
 * @property UsersApiSessions[] $usersApiSessions
 * @property UsersRobots[] $usersRobots
 * @property UsersSocialservices[] $usersSocialservices
 */
class BaseUser extends GxActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseUser the static model class
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
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, password,reset_password, email, chat_id, chat_pwd', 'required'),
			array('is_emailVerified, is_admin, is_active, is_validated, validation_counter', 'numerical', 'integerOnly'=>true),
			array('name, password, reset_password, email, chat_id, chat_pwd', 'length', 'max'=>128),
                        array('alternate_email', 'compare', 'compareAttribute'=>'email', 'operator'=>'!=', 'allowEmpty'=>true , 'message'=>'Alternate email must be differ from primary email.'),
                        array('alternate_email', 'email', 'allowName'=>true),
//                        array('alternate_email, validation_key', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, password, reset_password, email, is_emailVerified, is_admin, created_on, chat_id, chat_pwd, is_active, validation_key, is_validated, validation_counter, alternate_email, push_notification_preference', 'safe', 'on'=>'search'),
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
			'usersApiSessions' => array(self::HAS_MANY, 'UsersApiSession', 'id_user'),
			'usersRobots' => array(self::HAS_MANY, 'UsersRobot', 'id_user'),
			'usersSocialservices' => array(self::HAS_MANY, 'UsersSocialservice', 'id_user'),
                        'notificationRegistrations' => array(self::HAS_MANY, 'NotificationRegistrations', 'user_id'),
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
			'user' => 'User',
			'password' => 'Password',
			'reset_password' => 'Reset Password',
			'email' => 'Email',
                        'alternate_email' => 'Alternate Email',
			'is_emailVerified' => 'Is Email Verified',
			'is_admin' => 'Is Admin',
			'created_on' => 'Created On',
			'chat_id' => 'Chat ID',
			'chat_pwd' => 'Chat Password',
			'is_active' => 'Is Active',
                        'validation_key' => 'Validation Key',
                        'is_validated' => 'Is email validated?',
                        'validation_counter' => 'Validation Counter',
                        'push_notification_preference' => 'Push Notification Preference',
                    
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
		$criteria->compare('password',$this->password,true);
		$criteria->compare('reset_password',$this->reset_password,true);
		$criteria->compare('email',$this->email,true);
                $criteria->compare('alternate_email',$this->alternate_email,true);
		$criteria->compare('is_emailVerified',$this->is_emailVerified);
		$criteria->compare('is_admin',$this->is_admin);
		$criteria->compare('created_on',$this->created_on,true);
		$criteria->compare('chat_id',$this->chat_id,true);
		$criteria->compare('chat_pwd',$this->chat_pwd,true);
		$criteria->compare('is_active',$this->is_active);
                $criteria->compare('validation_key',$this->validation_key);
                $criteria->compare('is_validated',$this->is_validated);
                $criteria->compare('validation_counter',$this->validation_counter);
                $criteria->compare('push_notification_preference',$this->push_notification_preference);
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}