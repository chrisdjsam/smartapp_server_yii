<?php

/**
 * ChangePasswordForm class.
 */
class ChangePasswordForm extends CFormModel {

	public $password;
	public $newpassword;
	public $confirm_password;

	/**
	 * Declares the validation rules.
	 */
	public function rules() {
		return array(
				// username and password are required
				array('password, newpassword, confirm_password','required'),
				array('confirm_password', 'compare', 'compareAttribute'=>'newpassword'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels() {
		return array(
				'password' => 'Current password',
				'newpassword' => 'New password',
				'confirm_password'=>'Re-enter new password',
		);
	}


}
