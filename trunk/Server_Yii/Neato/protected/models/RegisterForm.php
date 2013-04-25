<?php

/**
 * RegisterForm class.
 * RegisterForm is the data structure for keeping
 * user register form data.
 */
class RegisterForm extends CFormModel {

	public $name;
	public $email;
//        public $alternate_email;
	public $password;
	public $confirm_password;
	public $is_admin;
	public $isNewRecord = true;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules() {
		return array(
				// username and password are required
				array('name, email, password, confirm_password','required'),
				array('email', 'email', 'checkMX'=>true),
				array('email', 'unique', 'className'=>'User'),
//                                array('alternate_email', 'email', 'checkMX'=>true),
				array('confirm_password', 'compare', 'compareAttribute'=>'password'),
//                                array('alternate_email', 'compare', 'compareAttribute'=>'email', 'operator'=>'!=', 'allowEmpty'=>true , 'message'=>'Alternate email must be differ from primary email.'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels() {
		return array(
				'name' => 'Name',
				'email' => 'Email',
//                                'alternate_email' => 'Alternate Email',
				'password' => 'Password',
				'confirm_password'=>'Re-enter password',
		);
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		$user_identity = new UserIdentity($this->email, $this->password);
		$user_identity->authenticate();
		if($user_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration = 0; // 30 days
			Yii::app()->user->login($user_identity,$duration);
			return true;
		}
		else{
			return false;
		}
	}

}
