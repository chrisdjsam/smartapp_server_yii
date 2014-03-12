<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'UserController'.
 */

class LoginForm extends CFormModel
{
	public $email;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
				// email and password are required
				array('email, password', 'required'),
				// rememberMe needs to be a boolean
				array('rememberMe', 'boolean'),
				// password needs to be authenticated
				array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{

		if(!Yii::app()->params['is_wp_enabled']){
			return array(
					'email'=>'Email',
					'password'=>'Password',
					'rememberMe'=>'Remember me',
			);
		}else{
			return array(
					'email'=>'Username',
					'password'=>'Password',
					'rememberMe'=>'Remember me',
			);
		}
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->email, $this->password);
			if(!$this->_identity->authenticate())
			{
				$this->addError('email','');
				$this->addError('password','Incorrect email and password combination.');
			}
		}
	}

	public function wpAuthenticateError(){

		$wperror = $_POST['errors'];
		if(isset($wperror->incorrect_password)){
			$error_message = $wperror->incorrect_password[0];
		}
		if(isset($wperror->invalid_username)){
			$error_message = $wperror->invalid_username[0];
		}
		if(isset($wperror->empty_username)){
			$error_message = $wperror->empty_username[0];
		}
		if(isset($wperror->empty_password)){
			$error_message = $wperror->empty_password[0];
		}

		if(empty($wperror)){
			$error_message = 'The email and password field is empty';
		}

		$this->addError('password',$error_message);
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->email, $this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
