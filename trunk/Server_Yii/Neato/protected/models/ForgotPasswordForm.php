<?php

/**
 * ForgotPasswordForm class.
 */
class ForgotPasswordForm extends CFormModel {

	public $email;

	/**
	 * Declares the validation rules.
	 */
	public function rules() {
		return array(
				// username and password are required
				array('email','required'),
				array('email', 'email', 'checkMX'=>true),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels() {
		return array(
				'email' => 'Email',
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
