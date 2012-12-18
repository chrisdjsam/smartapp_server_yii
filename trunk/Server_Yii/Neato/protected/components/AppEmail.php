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
		$login_link = "http://".$_SERVER['SERVER_NAME'].$login_link;
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
	public static function emailChangePassword($email, $name, $new_password, $login_link){
		$login_link = "http://".$_SERVER['SERVER_NAME'].$login_link;
		$message = AppCore::yii_echo("changepassword_message", array($name, $email, $new_password, $login_link));
        $subject = AppCore::yii_echo("changepassword_subject");
        AppHelper::send_mail($email, $subject, $message);
		
	}
	
	/**
	 * Send email with system generated password to the existing user
	 * @param string $email
	 * @param string $name
	 * @param string $new_password
	 */
	public static function emailForgotPassword($email, $name, $new_password, $login_link){
		$login_link = "http://".$_SERVER['SERVER_NAME'].$login_link;
		$message = AppCore::yii_echo("forgotpassword_message", array($name, $email, $new_password, $login_link));
        $subject = AppCore::yii_echo("forgotpassword_subject");
        AppHelper::send_mail($email, $subject, $message);
		
	}
	
	/**
	 * Send email with system generated password to the existing user
	 * @param string $email
	 * @param string $name
	 * @param string $new_password
	 */
	public static function emailResetPassword($email, $name, $new_password, $login_link){
		$login_link = "http://".$_SERVER['SERVER_NAME'].$login_link;
		$message = AppCore::yii_echo("changepassword_message", array($name, $email, $new_password, $login_link));
		$subject = AppCore::yii_echo("changepassword_subject");
		AppHelper::send_mail($email, $subject, $message);
	
	}
}

?>
