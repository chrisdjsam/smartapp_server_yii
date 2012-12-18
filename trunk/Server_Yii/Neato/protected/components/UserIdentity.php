<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

	const ERROR_USEREMAIL_NOT_VERIFIED_YET = 99;
	const ERROR_USEREMAIL_BLANK = 98;
	public $userType = 'Front';
	private $_id;

	/**
	 * @see CUserIdentity::authenticate()
	 */
	public function authenticate()
	{
		if ($this->username != "") {
			$encrypted_password = AppHelper::one_way_encrypt($this->password);
			$record = User::model()->findByAttributes(array('email' => $this->username));
			if ($record === null) {
				$this->errorCode = self::ERROR_USERNAME_INVALID;
			}else if($record->password !== $encrypted_password){
				//$this->errorCode=self::ERROR_PASSWORD_INVALID;
				if ($record->reset_password !== $encrypted_password) {
					$this->errorCode = self::ERROR_PASSWORD_INVALID;
				}else {
					//$record->emailVerified = 'Y';
					$record->password = $record->reset_password;
					$record->save();
					$this->_id = $record->id;
					$this->errorCode = self::ERROR_NONE;
				}
			}else
			{
				$this->_id = $record->id;
				$this->errorCode=self::ERROR_NONE;
			}
		}
		return !$this->errorCode;
	}

	/**
	 * @see CUserIdentity::getId()
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * Authenticate user using only email (Ex: called by fb login)
	 * @return boolean
	 */
	public function authenticateUsingEmail() {
		$record = User::model()->findByAttributes(array('email' => $this->username));
		$this->_id = $record->id;
		$this->errorCode = self::ERROR_NONE;
		return !$this->errorCode;
	}
}