<?php

/**
 * UserAddForm class.
 */
class UserAddForm extends CFormModel {

	public $name;
	public $email;
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
				array('confirm_password', 'compare', 'compareAttribute'=>'password'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels() {
		return array(
				'name' => 'Name',
				'email' => 'Email',
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
