<?php

/**
 * Email functions library
 */
class AppEmail {

	/**
	 * Send email to newly registered user with welcome message
	 * @param string $name
	 * @param string $email
	 * @param string $new_password
	 * @param string $login_link
	 */
	public static function emailWelcomeNewUser($email, $name, $new_password, $login_link){
		$login_link = Yii::app()->params['apiProtocol'].$_SERVER['SERVER_NAME'].$login_link;
		$message = AppCore::yii_echo("welcome_message", array($name, $email, $new_password, $login_link));
		$subject = AppCore::yii_echo("welocome_subject");
		AppHelper::send_mail($email, $subject, $message);
	}

	/**
	 * Send email to user after changing the password
	 * @param string $email
	 * @param string $name
	 * @param string $new_password
	 */
	public static function emailChangePassword($email, $name, $new_password, $login_link, $alternate_user_email = '', $country_lang=false){
		$login_link = Yii::app()->params['apiProtocol'].$_SERVER['SERVER_NAME'].$login_link;
		$template_key = AppCore::getMessageTemplateKey('change_password', $country_lang);
		$message = AppCore::yii_echo($template_key . '_message', array($name, $email, $new_password, $login_link));
		$subject = AppCore::yii_echo($template_key . "_subject");
		if(!empty($alternate_user_email)){
			AppHelper::send_mail($alternate_user_email, $subject, $message);
		}
		AppHelper::send_mail($email, $subject, $message);
	}

	/**
	 * Send email with system generated password to the existing user
	 * @param string $email
	 * @param string $name
	 * @param string $new_password
	 */
	public static function emailForgotPassword($email, $name, $new_password, $login_link, $alternate_user_email = '', $country_lang=false){
		$login_link = Yii::app()->params['apiProtocol'].$_SERVER['SERVER_NAME'].$login_link;
		$template_key = AppCore::getMessageTemplateKey('forgotpassword', $country_lang);
		$message = AppCore::yii_echo($template_key . "_message", array($name, $email, $new_password, $login_link));
		$subject = AppCore::yii_echo($template_key . "_subject");
		if(!empty($alternate_user_email)){
			AppHelper::send_mail($alternate_user_email, $subject, $message);
		}
		AppHelper::send_mail($email, $subject, $message);
	}

	/**
	 * Send email with system generated password to the existing user
	 * @param string $email
	 * @param string $name
	 * @param string $new_password
	 */
	public static function emailResetPassword($email, $name, $new_password, $login_link, $alternate_user_email = ''){
		$login_link = Yii::app()->params['apiProtocol'].$_SERVER['SERVER_NAME'].$login_link;
		$message = AppCore::yii_echo("change_password_message", array($name, $email, $new_password, $login_link));
		$subject = AppCore::yii_echo("change_password_subject");
		if(!empty($alternate_user_email)){
			AppHelper::send_mail($alternate_user_email, $subject, $message);
		}
		AppHelper::send_mail($email, $subject, $message);
	}

	/**
	 * Send email to validate primary email
	 * @param string $email
	 * @param string $name
	 * @param string $validation_key
	 * @param string $alternate_email
	 */
	public static function emailValidate($email, $name, $validation_key, $alternate_email = '', $country_lang = false){
		$validation_link = Yii::app()->params['apiProtocol'].$_SERVER['SERVER_NAME'] . '/user/validateEmail?k=' . $validation_key;

		$template_key = AppCore::getMessageTemplateKey('validate_email', $country_lang);
		$message = AppCore::yii_echo($template_key . '_message', array($name, $email, $validation_link));

		$subject = AppCore::yii_echo($template_key . '_subject');
		if(!empty($alternate_email)){
			AppHelper::send_mail($alternate_email, $subject, $message);
		}
		AppHelper::send_mail($email, $subject, $message);
	}

}

?>