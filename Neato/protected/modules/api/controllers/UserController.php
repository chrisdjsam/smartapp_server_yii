<?php

/**
 * The API UserController is meant for all user related API actions.
 */
class UserController extends APIController {

	/**
	 * Log in the user given a valid Facebook login object
	 */
	public function actionFBLogin() {
		$r = Yii::app()->request->getParam('r');
		$is_user_exist = true;

		if (!isset($r) || !is_array($r) || $r['status'] != 'connected') {
			$message = AppCore::yii_echo("login fail -1");
			$content = array('status' => -1, 'message' => $message);
			$this->renderPartial('/default/defaultView', array('content' => $content));
			Yii::app()->end();
		}

		// If the Facebook token doesn't authenticate, error.
		$token = $r['authResponse']['accessToken'];
		$fbid = $r['authResponse']['userID'];
		$url = "https://graph.facebook.com/me?access_token=$token";

		// Test access token.
		$me = json_decode(file_get_contents($url), TRUE);

		if ($me == NULL || $me['id'] != $fbid) {
			$message = AppCore::yii_echo("login fail -2");
			$content = array('status' => -1, 'message' => $message);
			$this->renderPartial('/default/defaultView', array('content' => $content));
			Yii::app()->end();
		}

		$social_service_type_model = Socialservicetype::model()->find('name=:name', array(':name' => "Facebook"));
		$social_service_type_id = $social_service_type_model->id;

		$user_model = User::model()->find('email=:email', array(':email' => $me['email']));

		if(is_null($user_model)){
			//user not exists
			$is_user_exist = false;
			$user_model = new User;

			$user_model->name = $me['name'];
			$user_model->email = $me['email'];

			$new_password = AppHelper::generateRandomString();
			$encrypted_new_password = AppHelper::one_way_encrypt($new_password);

			$user_model->password = $encrypted_new_password;
			$user_model->reset_password = $encrypted_new_password;
			$user_model->is_emailVerified = 1;

			$chat_details = UserCore::create_chat_user_for_user();
			if(!$chat_details['jabber_status']){
				//$message = "User could not be created because jabber service is not responding.";
				//self::terminate(-1, $message);
			}
			$user_model->chat_id = $chat_details['chat_id'];
			$user_model->chat_pwd = $chat_details['chat_pwd'];

			$user_role = '3'; //set deafult role as normal user

			if(!$user_model->save()){
				$message = AppCore::yii_echo("login fail - 3");
				$content = array('status' => -1, 'message' => $message);
				$this->renderPartial('/default/defaultView', array('content' => $content));
				Yii::app()->end();
			}

			$user_role_obj = new UserRole();
			$user_role_obj->user_id = $user_model->id;
			$user_role_obj->user_role_id = $user_role;
			if(!$user_role_obj->save()){
				Yii::app()->user->setFlash('success', 'user role not saved');
			}

			UserCore::setDefaultUserPushNotificationOptions($user_model->id);
			//send welocom messsage
			$login_link = $this->createUrl("/user/login");
			AppEmail::emailWelcomeNewUser($user_model->email, $user_model->name, $new_password, $login_link);
		}else{
			//user exists
			if($user_model->is_validated != 1){

				$user_model->is_validated = 1;
				if(!$user_model->update()){
					// TODO
				}
			}
		}
		// Delete previus UsersSocialservice data and entry new UsersSocialservice data.
		UsersSocialservice::model()->deleteAll('user_social_id=:fbid and id_socialservicetype=:sstid', array(':fbid' => $fbid, ':sstid'=>$social_service_type_id));

		$user_social_service_model = new UsersSocialservice;
		$user_social_service_model->id_socialservicetype = $social_service_type_id;
		$user_social_service_model->id_user = $user_model->id;
		$user_social_service_model->user_social_id = $fbid;
		$user_social_service_model->username = $fbid;
		$user_social_service_model->access_token = $r['authResponse']['accessToken'];
		$user_social_service_model->expires_on = date('Y-m-d H:m:s', time() + $r['authResponse']['expiresIn']);
		$user_social_service_model->raw_data = json_encode($r);

		if (!$user_social_service_model->save()) {
			$message = AppCore::yii_echo("login fail - 4");
			$content = array('status' => -1, 'message' => $message);
			$this->renderPartial('/default/defaultView', array('content' => $content));
			Yii::app()->end();
		}

		// Create a new user identity and log in
		$identity = new UserIdentity($user_model->email, 'dummyPass'); // The password here is fake on purpose.
		$identity->authenticateUsingEmail();
		Yii::app()->user->login($identity, 3600 * 24 * 30); // 30 days

		$message = AppCore::yii_echo("login successful");
		if(!$is_user_exist){
			$message = AppCore::yii_echo("login successful. Please check your email ". $user_model->email ." to know your password.");
		}
		$content = array('status' => 0, 'message' => $message);
		$this->renderPartial('/default/defaultView', array('content' => $content));
		Yii::app()->end();
	}

	/**
	 * API to get auth token
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>account_type</b> :Account type</li>
	 *	<li>If account type is native
	 *		<ul>
	 *			<li><b>email</b> :User's Email</li>
	 *			<li><b>password</b> :User's Password</li>
	 *		</ul>
	 *	</li>
	 *	<li>If account type is Facebook
	 *		<ul>
	 *			<li><b>external_social_id</b> :User's External Social ID</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>{"status":0,"result":"d769de7939af3e76a54ac4a4368e88af"}</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If incorrect email/password combination and account type is
	 *		Native
	 *		<ul>
	 *			<li>{"status":-1,"message":"User could not be authenticated"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If incorrect external social ID and account type is Facebook
	 *		<ul>
	 *			<li>{"status":-1,"message":"User could not be authenticated"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If unsupported account type is passed (e.g. Google, for the
	 *		time being)
	 *		<ul>
	 *			<li>{"status":-1,"message":"Account Type is not supported"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */

	public function actionGetAuthToken(){
		$account_type = Yii::app()->request->getParam('account_type', '');
		$user_email = Yii::app()->request->getParam('email', '');
		$user_password = Yii::app()->request->getParam('password', '');
		$user_social_id = Yii::app()->request->getParam('external_social_id', '');
		if($account_type == 'Native'){
			if ($user_email !== ''){
				if ($user_password !== ''){
					$password = AppHelper::one_way_encrypt($user_password);
					$user = User::model()->findByAttributes(array('email' => $user_email,'password' => $password));

					if($user == null){
						$user = User::model()->findByAttributes(array('email' => $user_email,'reset_password' => $password));
						if($user !== null){
							$user->password = $password;
							$user->update();
						}
					}

					if ($user !== null){
						$user_auth_token = UserCore::create_user_auth_token($user->id);
						if ($user_auth_token){

							$is_validated = ($user->is_validated == 0) ? -1 : 0 ;
							$message = '';

							$grace_period = UserCore::getGracePeriod();

							if(!(boolean)$user->is_validated){

								$user_created_on_timestamp = strtotime($user->created_on);

								$current_system_timestamp = time();

								$time_diff = ($current_system_timestamp - $user_created_on_timestamp) / 60;

								if($time_diff < $grace_period){

									$message = "Please activate your account, your account still inactive";

								} else {

									$message = "Sorry, Please validate your email first and then login again.";
									$is_validated = -2 ;
									$user_auth_token = null;

								}

							}

							$response_data = $user_auth_token;
							$extra_param['validation_status'] = $is_validated;
							$extra_param['message'] = $message;

							self::successWithExtraParam($response_data, $extra_param);

						}
					}else{
						$response_message = self::yii_api_echo('User could not be authenticated');
						self::terminate(-1, $response_message, APIConstant::AUTHENTICATION_FAILED);
					}
				}else{
					$response_message = self::yii_api_echo('Missing parameter password in method auth.get_user_auth_token');
					self::terminate(-1, $response_message, APIConstant::MISSING_PASSWORD);
				}
			}else{
				$response_message = self::yii_api_echo('Missing parameter email in method auth.get_user_auth_token');
				self::terminate(-1, $response_message, APIConstant::PARAMETER_MISSING);
			}
		}else if($account_type == 'Facebook'){
			if($user_social_id !== ''){
				$user_social_services = UsersSocialservice::model()->findByAttributes(array('user_social_id' => $user_social_id));
				if($user_social_services !== null){
					$user_auth_token = UserCore::create_user_auth_token($user_social_services->idUser->id);
					if ($user_auth_token){

						$is_validated = ($user_social_services->idUser->is_validated == 0) ? -1 : 0 ;
						$message = '';

						$grace_period = UserCore::getGracePeriod();

						if(!(boolean)$user_social_services->idUser->is_validated){

							$user_created_on_timestamp = strtotime($user_social_services->idUser->created_on);

							$current_system_timestamp = time();

							$time_diff = ($current_system_timestamp - $user_created_on_timestamp) / 60;

							if($time_diff < $grace_period){

								$message = "Please activate your account, your account still inactive";

							} else {

								$message = "Sorry, Please validate your email first and then login again.";
								$is_validated = -2 ;
								$user_auth_token = null;

							}

						}

						$response_data = $user_auth_token;
						$extra_param['validation_status'] = $is_validated;
						$extra_param['message'] = $message;

						self::successWithExtraParam($response_data, $extra_param);

					}
				}else{
					$response_message = self::yii_api_echo('User could not be authenticated');
					self::terminate(-1, $response_message, APIConstant::SOCIAL_ID_NOT_EXIST);
				}
			}else{
				$response_message = self::yii_api_echo('Missing parameter user_social_id in method auth.get_user_auth_token');
				self::terminate(-1, $response_message, APIConstant::PARAMETER_MISSING);
			}
		}else{
			$response_message = self::yii_api_echo('Account Type is not supported');
			self::terminate(-1, $response_message, APIConstant::UNSUPPORTED_ACCOUNT_TYPE);
		}

	}
	public function actionGetAuthToken2(){
		$account_type = Yii::app()->request->getParam('account_type', '');
		$user_email = Yii::app()->request->getParam('email', '');
		$user_password = Yii::app()->request->getParam('password', '');
		$user_social_id = Yii::app()->request->getParam('external_social_id', '');
		$extram_param = array();
		$extram_param['country_code'] = 'US';
		$extram_param['opt_in'] = 'true';
		$country_code = 'US';
		$opt_in = 1;

		if($account_type == 'Native'){
			//                    it verifies whether we check authentication for wp_neatouser or Yii_neatouser
			if ($user_email !== ''){
				if ($user_password !== ''){
					$encrypted_password = AppHelper::one_way_encrypt($user_password);
					$user_model_for_find_admin = User::model()->findByAttributes(array("email"=>$user_email, "password" => $encrypted_password, 'is_admin' => 1));
					if (Yii::app()->params['is_wp_enabled'] && empty($user_model_for_find_admin)) {
						$data_string = array();
						$data_string['log'] = $user_email;
						$data_string['pwd'] = urlencode($user_password);
						$url = Yii::app()->params['wordpress_api_url'].'?json=login';
						$headers = array();

						$result = AppHelper::curl_call($url, $headers, $data_string);
						$decoded_result = json_decode($result);

						if(isset($decoded_result->posts->errors->incorrect_password) || isset($decoded_result->posts->errors->invalid_username) ){
							$response_message = self::yii_api_echo('User could not be authenticated');
							self::terminate(-1, $response_message, APIConstant::AUTHENTICATION_FAILED);
						}

						if(isset($decoded_result->posts->errors) ){
							$response_message = self::yii_api_echo('User could not be authenticated');
							self::terminate(-1, $response_message, APIConstant::AUTHENTICATION_FAILED);
						}

						//  check authentication for wordpress user if success then register in our db but without password
						$user = User::model()->findByAttributes(array('email' => $user_email));
						$register_yiineato_db_data = $decoded_result->posts->data;

						if(isset($decoded_result->posts->data) && $user === null){
							$user_model = new User;
							$user_model->name = $register_yiineato_db_data->user_login;
							$user_model->email = $register_yiineato_db_data->user_login;

							$user_model->alternate_email = $register_yiineato_db_data->user_email;

							$user_model->extram_param = json_encode($extram_param);
							$user_model->country_code = $country_code;
							$user_model->opt_in = $opt_in;
							$user_model->wp_id = $register_yiineato_db_data->ID;

							$encrypted_pass_word = AppHelper::one_way_encrypt($user_password);
							$user_model->password = $encrypted_pass_word;
							$user_model->reset_password = $encrypted_pass_word;

							$chat_details = UserCore::create_chat_user_for_user();
							if(!$chat_details['jabber_status']){
								$message = self::yii_api_echo("User could not be created because jabber service is not responding.");
								self::terminate(-1, $message, APIConstant::UNAVAILABLE_JABBER_SERVICE);
							}
							$user_model->chat_id = $chat_details['chat_id'];
							$user_model->chat_pwd = $chat_details['chat_pwd'];

							if(!$user_model->save()){
								//TODO
							}
							// update extra attribute of user
							$user_id = $user_model->id;
							$validation_key = md5($user_id.'_'.$user_email);
							$user_model->validation_key =  $validation_key;

							$user_model->is_validated =1 ;

							$user_model->validation_counter = 0;

							if(!$user_model->save()){
								//TODO
							}
						}else if(isset($decoded_result->posts->data) && isset($user)){
							$user->password = AppHelper::one_way_encrypt($user_password);
							if(!$user->update()){
								//TODO
							}
						}
					}

					if (Yii::app()->params['is_wp_enabled'] && empty($user_model_for_find_admin)) {
						$user = User::model()->findByAttributes(array('email' => $register_yiineato_db_data->user_login));
					} else {
						$password = AppHelper::one_way_encrypt($user_password);
						$user = User::model()->findByAttributes(array('email' => $user_email, 'password' => $password));

						if ($user == null) {
							$user = User::model()->findByAttributes(array('email' => $user_email, 'reset_password' => $password));
							if ($user !== null) {
								$user->password = $password;
								$user->update();
							}
						}
					}

					if ($user !== null) {
						$user_auth_token = UserCore::create_user_auth_token($user->id);
						if ($user_auth_token) {

							$is_validated = ($user->is_validated == 0) ? -1 : 0;
							$message = '';

							$grace_period = UserCore::getGracePeriod();

							if (!(boolean) $user->is_validated) {

								$user_created_on_timestamp = strtotime($user->created_on);

								$current_system_timestamp = time();

								$time_diff = ($current_system_timestamp - $user_created_on_timestamp) / 60;

								if ($time_diff < $grace_period) {
									$message = "Please activate your account, your account still inactive";
								} else {
									$message = "Sorry, Please validate your email first and then login again.";
									$is_validated = -2;
									$user_auth_token = null;
								}
							}

							$response_data = $user_auth_token;
							$extra_param['validation_status'] = $is_validated;
							$extra_param['message'] = $message;

							self::successWithExtraParam($response_data, $extra_param);
						}
					} else {
						$response_message = self::yii_api_echo('User could not be authenticated');
						self::terminate(-1, $response_message, APIConstant::AUTHENTICATION_FAILED);
					}
				} else {
					$response_message = self::yii_api_echo('Missing parameter password in method auth.get_user_auth_token');
					self::terminate(-1, $response_message, APIConstant::MISSING_PASSWORD);
				}
			} else {
				$response_message = self::yii_api_echo('Missing parameter email in method auth.get_user_auth_token');
				self::terminate(-1, $response_message, APIConstant::PARAMETER_MISSING);
			}
		} else if ($account_type == 'Facebook') {
			if ($user_social_id !== '') {
				$user_social_services = UsersSocialservice::model()->findByAttributes(array('user_social_id' => $user_social_id));
				if ($user_social_services !== null) {
					$user_auth_token = UserCore::create_user_auth_token($user_social_services->idUser->id);
					if ($user_auth_token) {

						$is_validated = ($user_social_services->idUser->is_validated == 0) ? -1 : 0;
						$message = '';

						$grace_period = UserCore::getGracePeriod();

						if (!(boolean) $user_social_services->idUser->is_validated) {

							$user_created_on_timestamp = strtotime($user_social_services->idUser->created_on);

							$current_system_timestamp = time();

							$time_diff = ($current_system_timestamp - $user_created_on_timestamp) / 60;

							if ($time_diff < $grace_period) {
								$message = "Please activate your account, your account still inactive";
							} else {
								$message = "Sorry, Please validate your email first and then login again.";
								$is_validated = -2;
								$user_auth_token = null;
							}
						}

						$response_data = $user_auth_token;
						$extra_param['validation_status'] = $is_validated;
						$extra_param['message'] = $message;

						self::successWithExtraParam($response_data, $extra_param);
					}
				} else {
					$response_message = self::yii_api_echo('User could not be authenticated');
					self::terminate(-1, $response_message, APIConstant::SOCIAL_ID_NOT_EXIST);
				}
			} else {
				$response_message = self::yii_api_echo('Missing parameter user_social_id in method auth.get_user_auth_token');
				self::terminate(-1, $response_message, APIConstant::PARAMETER_MISSING);
			}
		} else {
			$response_message = self::yii_api_echo('Account Type is not supported');
			self::terminate(-1, $response_message, APIConstant::UNSUPPORTED_ACCOUNT_TYPE);
		}

	}

	/**
	 * This method is used for generate new password and send by email.
	 * * Parameters:
	 * <ul>
	 * 	<li><b>api_key</b> :Your API Key</li>
	 * 	<li><b>email</b> :User's email address</li>
	 * </ul>
	 * Success Response:
	 *
	 * <ul>
	 * 	<li>If everything goes fine
	 * 		<ul>
	 * 			<li>{"status":0,"result":{"success":true,"message":"New password is sent to your email."}}</li>
	 * 		</ul>
	 * 	</li>
	 * </ul>
	 *
	 * Failure Responses: <br />
	 * <ul>
	 *
	 * 	<li>If API Key is missing or not correct:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Method call failed the API
	 * 				Authentication"}</li>
	 * 		</ul>
	 * 	</li>
	 *
	 * 	<li>If email address not found in database:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Email does not exist."}</li>
	 * 		</ul>
	 * 	</li>
	 *
	 * </ul>
	 */
	public function actionForgetPassword(){

		$email = Yii::app()->request->getParam('email', '');
		$email = trim($email);

		if(Yii::app()->params['is_wp_enabled']){
			if(!AppHelper::is_valid_email($email)){

				$message = self::yii_api_echo("The email address you have provided does not appear to be a valid email address.");
				self::terminate(-1, $message, APIConstant::EMAIL_NOT_VALID);
			}
		}else{
			if(!AppHelper::is_valid_email_for_all($email)){

				$message = self::yii_api_echo("The email address you have provided does not appear to be a valid email address.");
				self::terminate(-1, $message, APIConstant::EMAIL_NOT_VALID);
			}
		}
		if(Yii::app()->params['is_wp_enabled']){

			$data_string = array();
			$data_string['user_login'] = $email;

			$url = Yii::app()->params['wordpress_api_url'].'?json=lostPassword';
			$headers = array();
			$result = json_decode(AppHelper::curl_call($url, $headers, $data_string));

			if(isset($result->posts)){
				$response_message = "New password is sent to your email.";
				$response_data = array("success"=>true, "message"=>$response_message);
				self::success($response_data);
			}else{
				$response_message = "Email does not exist.";
				$error_key =  APIConstant::EMAIL_DOES_NOT_EXIST;
				self::terminate(-1, $response_message, $error_key);
			}
		}else{

			$user_model = User::model()->findByAttributes(array("email" => $email));
			if($user_model != null){
				$new_password = AppHelper::generateRandomString();
				$encrypted_new_password  = AppHelper::one_way_encrypt($new_password);

				$user_model->reset_password = $encrypted_new_password;
				$user_name = $user_model->name;
				$login_link = $this->createUrl("/user/login");
				if($user_model->save()){

					$alternate_user_email = $user_model->alternate_email;
					if (!empty($alternate_user_email)) {
						AppEmail::emailForgotPassword($email, $user_name, $new_password, $login_link, $alternate_user_email);
					} else {
						AppEmail::emailForgotPassword($email, $user_name, $new_password, $login_link);
					}

					$response_message = "New password is sent to your email.";
					$response_data = array("success"=>true, "message"=>$response_message);
					self::success($response_data);
				}else{
					$response_message = "Error in generating new password.";
					$error_key = APIConstant::PROBLEM_IN_SETTING_NEW_PASSWORD;
				}

			}else{
				$response_message = "Email does not exist.";
				$error_key =  APIConstant::EMAIL_DOES_NOT_EXIST;
				self::terminate(-1, $response_message, $error_key);
			}
		}

	}

	/**
	 * Method to changed password of user. And send by email.
	 *
	 * Parameters:
	 * <ul>
	 * 	<li><b>api_key</b> :Your API Key</li>
	 * 	<li><b>auth_token</b> :User's auth_token</li>
	 * 	<li><b>password_old</b> :User's old password</li>
	 * 	<li><b>password_new</b> :User's new password</li>
	 * </ul>
	 * Success Response:
	 *
	 * <ul>
	 * 	<li>If everything goes fine
	 * 		<ul>
	 * 			<li>{"status":0,"result":{"success":true,"message":"Your password is changed successfully."}}</li>
	 * 		</ul>
	 * 	</li>
	 * </ul>
	 *
	 * Failure Responses: <br />
	 * <ul>
	 *
	 * 	<li>If API Key is missing or not correct:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Method call failed the API
	 * 				Authentication"}</li>
	 * 		</ul>
	 * 	</li>
	 * 	<li>If Auth token does not exist:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"User could not be authenticated."}</li>
	 * 		</ul>
	 * 	</li>
	 * 	<li>If old password does not match with user's existing password:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Old password does not match with user password."}</li>
	 * 		</ul>
	 * 	</li>
	 *
	 * 	<li>If new password is empty or has only spaces:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Password should contain atleast one character."}</li>
	 * 		</ul>
	 * 	</li>
	 *
	 * </ul>
	 */
	public function actionChangePassword(){

		$user_auth_token = Yii::app()->request->getParam('auth_token', '');
		$user_api_session = UsersApiSession::model()->findByAttributes(array('token' =>$user_auth_token));
		$password_new = Yii::app()->request->getParam('password_new', '');
		$wp_user_id = isset($user_api_session) ? $user_api_session->idUser->wp_id : '';
		$wp_username = isset($user_api_session) ? $user_api_session->idUser->email : '';

		$user = User::model()->findByAttributes(array('id' => $user_api_session->id_user));
		if ($user !== null){

			if (Yii::app()->params['is_wp_enabled'] == true) {

				$password_old = Yii::app()->request->getParam('password_old', '');
				$url = Yii::app()->params['wordpress_api_url'].'?json=login';
				$headers = array();
				$data_string = array();
				$data_string['log'] = $wp_username;
				$data_string['pwd'] = urlencode($password_old);
				$result = json_decode(AppHelper::curl_call($url, $headers, $data_string));

				if(isset($result->posts->errors->incorrect_password) || isset($result->posts->errors->invalid_username) || isset($result->posts->errors) ){
					$response_message = "Old password does not match with user password.";
					$response_message = self::yii_api_echo('User could not be authenticated');
					self::terminate(-1, $response_message, APIConstant::OLD_PASS_NOT_MATCH_EXISTING_PASS);
				}

				$data_string = array();
				$data_string['passowrd'] = trim(urlencode($password_new));
				$data_string['id'] = $wp_user_id;

				$url = Yii::app()->params['wordpress_api_url'] . '?json=changePassword';
				$headers = array();
				$result = AppHelper::curl_call($url, $headers, $data_string);

				$response_message = "Your password is changed successfully.";
				$response_data = array("success" => true, "message" => $response_message );
				self::success($response_data);
			}else{
				$password_old = Yii::app()->request->getParam('password_old', '');
				if($user->password == AppHelper::one_way_encrypt($password_old)){
					$password_new = Yii::app()->request->getParam('password_new', '');
					if(trim($password_new)){
						$encripted_password = AppHelper::one_way_encrypt($password_new);
						$user->password = $encripted_password;
						$user->reset_password = $encripted_password;

						$email = trim($user->email);
						$user_name = $user->name;
						$login_link = $this->createUrl("/user/login");

						if($user->update()){

							$alternate_user_email = trim($user->alternate_email);

							if (!empty($alternate_user_email)) {
								AppEmail::emailChangePassword($email, $user_name, $password_new, $login_link, $alternate_user_email);
							} else {
								AppEmail::emailChangePassword($email, $user_name, $password_new, $login_link);
							}

							$response_message = "Your password is changed successfully.";
							$response_data = array("success" => true, "message" => $response_message );
							self::success($response_data);
						}else{
							$response_message = "Problem in setting new password.";
							$error_key = APIConstant::PROBLEM_IN_SETTING_NEW_PASSWORD ;
						}

					}else{
						$response_message = "Password should contain atleast one character.";
						$error_key = APIConstant::PASS_CONTAIN_ATLEAST_ONE_CHAR;
					}

				}else{
					$response_message = "Old password does not match with user password.";
					$error_key = APIConstant::OLD_PASS_NOT_MATCH_EXISTING_PASS;
				}
			}

		}else{
			$response_message = "User could not be authenticated.";
			$error_key = APIConstant::AUTH_TOKEN_AGAINST_EMAIL_DOES_NOT_EXIST;
		}

		self::terminate(-1, $response_message, $error_key);

	}

	public function actionChangePasswordold(){

		$user_auth_token = Yii::app()->request->getParam('auth_token', '');
		$user_api_session = UsersApiSession::model()->findByAttributes(array('token' =>$user_auth_token));
		$user = User::model()->findByAttributes(array('id' => $user_api_session->id_user));
		if ($user !== null){

			$password_old = Yii::app()->request->getParam('password_old', '');
			if($user->password == AppHelper::one_way_encrypt($password_old)){
				$password_new = Yii::app()->request->getParam('password_new', '');
				if(trim($password_new)){
					$encripted_password = AppHelper::one_way_encrypt($password_new);
					$user->password = $encripted_password;
					$user->reset_password = $encripted_password;

					$email = trim($user->email);
					$user_name = $user->name;
					$login_link = $this->createUrl("/user/login");

					if($user->update()){

						$alternate_user_email = trim($user->alternate_email);

						if (!empty($alternate_user_email)) {
							AppEmail::emailChangePassword($email, $user_name, $password_new, $login_link, $alternate_user_email);
						} else {
							AppEmail::emailChangePassword($email, $user_name, $password_new, $login_link);
						}

						$response_message = "Your password is changed successfully.";
						$response_data = array("success" => true, "message" => $response_message );
						self::success($response_data);
					}else{
						$response_message = "Problem in setting new password.";
						$error_key = APIConstant::PROBLEM_IN_SETTING_NEW_PASSWORD ;
					}

				}else{
					$response_message = "Password should contain atleast one character.";
					$error_key = APIConstant::PASS_CONTAIN_ATLEAST_ONE_CHAR;
				}

			}else{
				$response_message = "Old password does not match with user password.";
				$error_key = APIConstant::OLD_PASS_NOT_MATCH_EXISTING_PASS;
			}
		}else{
			$response_message = "User could not be authenticated.";
			$error_key = APIConstant::AUTH_TOKEN_AGAINST_EMAIL_DOES_NOT_EXIST;
		}

		self::terminate(-1, $response_message, $error_key);

	}

	/**
	 * API to get user details
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>email</b> :User's email (Optional)</li>
	 *	<li><b>auth_token</b> :User's auth token (received from get user
	 *		handle call)</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine and social information does not exist
	 *		<ul>
	 *			<li>
	 *				{"status":0,"result":{"id":367,"name":"pradip37","email":"pradip_patro378@gmail.com","chat_id":"1350922773_user@rajatogo","chat_pwd":"1350922773_user","social_networks":[]}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and social information exists
	 *		<ul>
	 *			<li>
	 *				{"status":0,"result":{"id":357,"name":"pradip3","email":"pradip_patro3@gmail.com","chat_id":"1350911036_user@rajatogo","chat_pwd":"1350911036_user","social_networks":[{"provider":"Facebook"},{"external_social_id":"123456789"}]}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine,social information does not exist and robot association exists
	 *		<ul>
	 *			<li>
	 *				{"status":0,"result":{"id":542,"name":"pradip","email":"pradip@gmail.com","chat_id":"1351499916_user@rajatogo","chat_pwd":"1351499916_user","social_networks":[],"robots":[{"id":"68","name":"room
	 *				cleaner1","serial_number":"robo5","chat_id":"1350987452_robot@rajatogo"},{"id":"69","name":"desk
	 *				cleaner60","serial_number":"robo6","chat_id":"1350991375_robot@rajatogo"}]}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and both social information and robot association do not exist
	 *		<ul>
	 *			<li>
	 *				{"status":0,"result":{"id":543,"name":"pradip","email":"pradip1@gmail.com","chat_id":"1351500158_user@rajatogo","chat_pwd":"1351500158_user","social_networks":[],"robots":[]}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If Auth token Key is missing or not correct:
	 *		<ul>
	 *			<li>{"status":-1,"message":"Method call failed the Auth token
	 *				Authentication"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionGetAccountDetails(){
		$user_auth_token = Yii::app()->request->getParam('auth_token', '');
		$user_api_session = UsersApiSession::model()->findByAttributes(array('token' =>$user_auth_token));
		$user = User::model()->findByAttributes(array('id' => $user_api_session->id_user));

		if ($user !== null){
			$user_social_services_arr = array();
			foreach ($user->usersSocialservices as $user_soial_service){
				$user_social_services = array();
				$user_social_services['Provider'] = $user_soial_service->idSocialservicetype->name;
				$user_social_services['external_social_id'] = $user_soial_service->user_social_id;
				$user_social_services_arr[] = $user_social_services;
			}
			$users_arr = array();
			foreach ($user->usersRobots as $user_robots){
				$user_details = array();
				$user_details['id'] = $user_robots->idRobot->id;
				$user_details['name'] = $user_robots->idRobot->name;
				$user_details['serial_number'] = $user_robots->idRobot->serial_number;
				$user_details['chat_id'] = $user_robots->idRobot->chat_id;

				$users_arr[] = $user_details;
			}

			$is_validated = UserCore::getIsValidateStatus($user->is_validated, $user->id);

			$response_data = array("id"=>$user->id,"name"=>$user->name,"email"=>$user->email,"chat_id"=>$user->chat_id,"chat_pwd"=>$user->chat_pwd,
					"social_networks"=>$user_social_services_arr,"robots"=>$users_arr, "validation_status" => $is_validated, "alternate_email"=>$user->alternate_email, "extra_param"=> json_decode($user->extram_param));
			self::success($response_data);
		}else{
			$response_message = self::yii_api_echo('APIException:UserAuthenticationFailed');
			self::terminate(-1, $response_message, APIConstant::API_KEY_MISSING_OR_INCORRECT);
		}
	}

	/**
	 * This method sets user attributes in database.
	 *
	 *  Parameters:
	 * <ul>
	 * 	<li><b>api_key</b> :Your API Key</li>
	 * 	<li><b>auth_token</b> :User's auth token (received from get user
	 * 		handle call)</li>
	 * 	<li><b>profile</b> :Map of key=>value pairs, e.g.
	 * 		profile{'operating_system'=>'Android',
	 * 		'version'='4.0'}</li>
	 * </ul>
	 * Success Response:
	 * <ul>
	 * 	<li>{"status":0,"result":{"success":true,"message":"User attributes are set successfully."}}</li>
	 * </ul>
	 *
	 * Failure Responses: <br />
	 * <ul>
	 *
	 * 	<li>If API Key is missing or not correct:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Method call failed the API
	 * 				Authentication"}</li>
	 * 		</ul>
	 * 	</li>
	 * 	<li>If Auth token Key is missing or not correct:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Method call failed the User Authentication"}</li>
	 * 		</ul>
	 * 	</li>
	 *
	 * 	<li>If value not provided for profile key:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Invalid value for key operating_system."}</li>
	 * 		</ul>
	 * 	</li>
	 *
	 * 	<li>If problem in setting user attributes:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Error in setting user attributes."}</li>
	 * 		</ul>
	 * 	</li>
	 *
	 * </ul>
	 */
	public function actionSetAttributes(){

		$user_profile = Yii::app()->request->getParam('profile', '');
		$user_auth_token = Yii::app()->request->getParam('auth_token', '');
		$user_api_session = UsersApiSession::model()->findByAttributes(array('token' =>$user_auth_token));
		$user = User::model()->findByAttributes(array('id' => $user_api_session->id_user));
		$response_message ="";
		if ($user !== null){

			$device_details = new DeviceDetails;
			$userDevice = UserDevices::model()->findByAttributes(array('id_user' =>$user->id));

			if($userDevice && $userDevice->idDeviceDetails){
				$device_details = $userDevice->idDeviceDetails;
			}

			$supported_keys = array('name','version', 'operating_system');
			$keys = array();
			foreach ($user_profile as $key => $value){
				$keys[] = $key;
				if(trim($value) === ''){
					$message = self::yii_api_echo("Invalid value for key $key.");
					self::terminate(-1, $message, APIConstant::ERROR_INVALID_USER_ACCOUNT_DETAIL);
				}

				switch ($key) {
					case "name":
						$device_details->name = $value;
						break;

					case "operating_system":
						$device_details->operating_system = $value;
						break;

					case "version":
						$device_details->version = $value;
						break;

					default:;
					break;
				}
			}

			$result = array_intersect($supported_keys, $keys);
			if(empty($result)){

				//don't perform if no any key supported

				$response_message = "User attributes are set successfully.";
				$response_data = array("success"=>true,"message"=>$response_message);
				self::success($response_data);
			}


			if($device_details->id && $device_details->update()){

				$response_message = "User attributes are set successfully.";
				$response_data = array("success"=>true,"message"=>$response_message);
				self::success($response_data);

			}else if($device_details->save()){

				$userDevice = new UserDevices();
				$userDevice->id_user = $user->id;
				$userDevice->id_device_details = $device_details->id;

				if($userDevice->save()){

					$response_message = "User attributes are set successfully.";
					$response_data = array("success"=>true,"message"=>$response_message);
					self::success($response_data);

				}else{
					$response_message = "Error in setting user attributes.";
					self::terminate(-1, $response_message, APIConstant::COULD_NOT_SET_USER_ATTRIBUTES);

				}

			} else {
				$response_message = "Error in setting user attributes.";
				self::terminate(-1, $response_message, APIConstant::COULD_NOT_SET_USER_ATTRIBUTES);
			}


		}else{
			$response_message = self::yii_api_echo('APIException:UserAuthenticationFailed');
			self::terminate(-1, $response_message, APIConstant::USER_ID_NOT_FOUND);
		}
		self::terminate(-1, $response_message, APIConstant::USER_ID_NOT_FOUND);

	}

	/**
	 * Method to get user attributes like type of device and operating system.
	 *
	 * Parameters:
	 * <ul>
	 * 	<li><b>api_key</b> :Your API Key</li>
	 * 	<li><b>auth_token</b> :User's auth token (received from get user
	 * 		handle call)</li>
	 * </ul>
	 * Success Response:
	 * <ul>
	 * 	<li>{"status":0,"result":{"success":true,"user_attributes":{"name":"mac","operating_system":"","version":""}}}</li>
	 * </ul>
	 *
	 * Failure Responses: <br />
	 * <ul>
	 *
	 * 	<li>If API Key is missing or not correct:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Method call failed the API
	 * 				Authentication"}</li>
	 * 		</ul>
	 * 	</li>
	 * 	<li>If Auth token Key is missing or not correct:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Method call failed the User Authentication"}</li>
	 * 		</ul>
	 * 	</li>
	 *
	 * 	<li>If Attributes are not set:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Attributes not found for this user"}</li>
	 * 		</ul>
	 * 	</li>
	 *
	 *
	 * </ul>
	 */

	public function actionGetAttributes(){
		$user_auth_token = Yii::app()->request->getParam('auth_token', '');
		$user_api_session = UsersApiSession::model()->findByAttributes(array('token' =>$user_auth_token));
		$user = User::model()->findByAttributes(array('id' => $user_api_session->id_user));
		$response_message ="";
		$response_data = "";
		if ($user !== null){

			$user_device =  UserDevices::model()->findByAttributes(array("id_user"=>$user->id));
			if($user_device && $user_device->idDeviceDetails){
				$device_details = $user_device->idDeviceDetails;
				$user_attributes = array("name"=>$device_details->name, "operating_system" => $device_details->operating_system, "version" => $device_details->version);
				$response_data = array("success"=>true, "user_attributes"=> $user_attributes);
				self::success($response_data);
			}else{
				$response_message ="Attributes not found for this user";
				self::terminate(-1, $response_message, APIConstant::USER_ATTRIBUTE_NOT_FOUND);
			}

		}else{
			$response_message = self::yii_api_echo('APIException:UserAuthenticationFailed');
		}

		self::terminate(-1, $response_message, APIConstant::API_KEY_MISSING_OR_INCORRECT);

	}

	/**
	 * API to get user associated robot list and details
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>email</b> :User's email (Optional)</li>
	 *	<li><b>auth_token</b> :User's auth token (received from get user
	 *		handle call)</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine and robot association exists
	 *		<ul>
	 *			<li>{"status":0,"result":[{"id":"68","name":"room
	 *				cleaner1","serial_number":"robo5","chat_id":"1350987452_robot@rajatogo"},{"id":"69","name":"desk
	 *				cleaner60","serial_number":"robo6","chat_id":"1350991375_robot@rajatogo"}]}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and robot association does not exist
	 *		<ul>
	 *			<li>{"status":0,"result":[]}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If Auth token Key is missing or not correct:
	 *		<ul>
	 *			<li>{"status":-1,"message":"Method call failed the Auth token
	 *				Authentication"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionGetAssociatedRobot(){
		$user_auth_token = Yii::app()->request->getParam('auth_token', '');
		$user_api_session = UsersApiSession::model()->findByAttributes(array('token' =>$user_auth_token));
		$user = User::model()->findByAttributes(array('id' => $user_api_session->id_user));
		if ($user !== null){
			$users_arr = array();
			foreach ($user->usersRobots as $user_robots){
				$user_details = array();
				$user_details['id'] = $user_robots->idRobot->id;
				$user_details['name'] = $user_robots->idRobot->name;
				$user_details['serial_number'] = $user_robots->idRobot->serial_number;
				$user_details['chat_id'] = $user_robots->idRobot->chat_id;

				$users_arr[] = $user_details;
			}
			self::success($users_arr);
		}else{
			$response_message = self::yii_api_echo('APIException:UserAuthenticationFailed');
			self::terminate(-1, $response_message, APIConstant::API_KEY_MISSING_OR_INCORRECT);
		}
	}

	/**
	 * API to logging out auth token
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>email</b> :User's email (Optional)</li>
	 *	<li><b>auth_token</b> :User's auth token (received from get user
	 *		handle call)</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"You are
	 *				successfully logged out."}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If Auth token against provided email does not exist:
	 *		<ul>
	 *			<li>{"status":-1,"message":"User could not be authenticated"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionLogoutAuthToken(){
		$user_auth_token = Yii::app()->request->getParam('auth_token', '');
		$user_api_session = UsersApiSession::model()->findByAttributes(array('token' =>$user_auth_token));
		$user = User::model()->findByAttributes(array('id' => $user_api_session->id_user));
		if ($user !== null){
			$user_api_delete = UsersApiSession::model()->deleteAllByAttributes(array('token' => $user_auth_token));
			if($user_api_delete){
				$response_data = array("success"=>true, "message"=>self::yii_api_echo('You are successfully logged off.'));
				self::success($response_data);
			}
		}else{
			$response_message = self::yii_api_echo('APIException:UserAuthenticationFailed');
			self::terminate(-1, $response_message, APIConstant::API_KEY_MISSING_OR_INCORRECT);
		}
	}

	/**
	 * API to updating auth token expiry
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>auth_token</b> :User's auth token (received from get user
	 *		handle call)</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"You are
	 *				successfully updated auth token expiry date."}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If Auth token is missing or not correct:
	 *		<ul>
	 *			<li>{"status":-1,"message":"Method call failed the User
	 *				Authentication"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionUpdateAuthTokenExpiry(){
		$user_auth_token = Yii::app()->request->getParam('auth_token', '');
		if ($user_auth_token){
			$user_api_session = UsersApiSession::model()->findByAttributes(array('token' =>$user_auth_token));
			if ($user_api_session != null) {
				$ts = time();
				$user_auth_tken_valid_till = Yii::app()->params['user-auth-token-valid-till'];
				$user_api_session->expires = $ts + 3600*24*$user_auth_tken_valid_till;
				$user_api_session->save();
				$response_data = array("success"=>true, "message"=>self::yii_api_echo('You have successfully updated auth token expiry date.'));
				self::success($response_data);
			}else{
				$response_message = self::yii_api_echo('Method call failed the User Authentication');
			}
		}else{
			$response_message = self::yii_api_echo('Method call failed the User Authentication');
		}
		self::terminate(-1, $response_message, APIConstant::AUTHENTICATION_FAILED);
	}

	/**
	 * API to DisAssociate robots from user
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>email</b> :User's email</li>
	 * 	<li><b>serial_number</b> :Serial Number of robot (If this field
	 *		is empty, it would delete all robot association for this
	 *		particular user)</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine, robot serial number provided and
	 *		user robot association exist
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"User Robot
	 *				association removed successfully."}}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine, robot serial number not provided
	 *		and user-robot association exist
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"User
	 *				association with all robot removed successfully."}}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and robot association does not exist
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"There is no
	 *				association between provided user and robot"}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If Email does not exist:
	 *		<ul>
	 *			<li>{"status":-1,"message":"Email does not exist."}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If serial number does not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Serial number does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionDisAssociateRobot(){
		$user_email = Yii::app()->request->getParam('email', '');
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$user = User::model()->findByAttributes(array('email' => $user_email));
		if($user !== null){
			if($robot_serial_no !== ''){
				$robot = Robot::model()->findByAttributes(array('serial_number' => $robot_serial_no));
				if($robot !== null){
					$user_robot_delete = UsersRobot::model()->deleteAllByAttributes(array('id_user' => $user->id,'id_robot' => $robot->id));
					if($user_robot_delete){
						$response_data = array("success"=>true, "message"=>self::yii_api_echo('User Robot association removed successfully.'));
						self::success($response_data);
					}else{
						$response_data = array("success"=>true, "message"=>self::yii_api_echo('There is no association between provided user and robot'));
						self::success($response_data);
					}
				}else {
					$response_message = self::yii_api_echo('Serial number does not exist');
					self::terminate(-1, $response_message, APIConstant::SERIAL_NUMBER_DOES_NOT_EXIST);
				}
			}else{
				$user_robots_delete = UsersRobot::model()->deleteAllByAttributes(array('id_user' => $user->id));
				if ($user_robots_delete){
					$response_data = array("success"=>true, "message"=>self::yii_api_echo('User association with all robot removed successfully.'));
					self::success($response_data);
				}else{
					$response_data = array("success"=>true, "message"=>self::yii_api_echo('There is no association between provided user and robot'));
					self::success($response_data);
				}
			}
		}else{
			$response_message = self::yii_api_echo('Email does not exist');
			self::terminate(-1, $response_message, APIConstant::EMAIL_DOES_NOT_EXIST);
		}

	}

	/**
	 * API to create new user
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>name</b> :Name of the user</li>
	 *	<li><b>email</b> :Email of the user</li>
	 *	<li><b>password</b> :Password of the user. It does not need to be
	 *		unique.</li>
	 *	<li><b>account_type</b> :Native OR Facebook (OR Google etc)</li>
	 *	<li><b>external_social_id</b> :External Social ID (e.g. Facebook
	 *		ID (numeric value) that is returned by the Facebook). This is
	 *		required ONLY when the account type is NOT Native.</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>
	 *				{"status":0,"result":{"success":true,"guid":1074,"user_handle":"d8828e4ef9596dd0be3b8c4cf0de9502"}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *	<li>If email exist but the social information does not exist
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"guid":55,"message":"Merged
	 *				user","user_handle":"ce475c5c9b84938f368efe99100b2a11"}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If unsupported account type is passed
	 *		<ul>
	 *			<li>{"status":-1,"message":"Account Type is NOT supported."}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If a parameter is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter name in method
	 *				user.create"}</li>
	 *		</ul>
	 *	<li>If Email does not valid
	 *		<ul>
	 *			<li>{"status":-1,"message":"The email address you provided does
	 *				not appear to be a valid email address."}</li>
	 *		</ul>
	 *	<li>If email already exists and account type is native
	 *		<ul>
	 *			<li>{"status":-1,"message":"This email address has already been
	 *				registered."}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If Social information exists and the account type is Facebook
	 *		<ul>
	 *			<li>{"status":-1,"message":"This social information already
	 *				exists."}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If Jabber service does not able to create chat user
	 *		<ul>
	 *			<li>{"status":-1,"message":"User could not be created because
	 *				jabber service in not responding."}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionCreate(){
		$user_name = Yii::app()->request->getParam('name', '');
		$user_email = Yii::app()->request->getParam('email', '');
		$alternate_user_email = Yii::app()->request->getParam('alternate_email', '');
		$is_create_user2 = Yii::app()->request->getParam('is_create_user2', '');
		if(!AppHelper::is_valid_email_for_all($user_email)){
			$message = self::yii_api_echo("The email address you have provided does not appear to be a valid email address.");
			self::terminate(-1, $message, APIConstant::EMAIL_NOT_VALID);
		}
		if(!empty($alternate_user_email)){
			if(!AppHelper::is_valid_email_for_all($alternate_user_email)){
				$message = self::yii_api_echo("The alternate email address you have provided does not appear to be a valid email address.");
				self::terminate(-1, $message, APIConstant::ALTERNATE_EMAIL_NOT_VALID);
			}
		}

		$user_password = $_REQUEST['password'];
		if(Yii::app()->params['is_wp_enabled'] && !AppHelper::is_valid_password($user_password)){
			$message = self::yii_api_echo("Password length should be 6 character");
			self::terminate(-1, $message, APIConstant::TOO_SHORT);
		}
		$user_account_type = Yii::app()->request->getParam('account_type', '');

		//                store the user in wpneato table
		if(Yii::app()->params['is_wp_enabled']){

			$data_string = array();
			$data_string['user_login'] = $user_email;
			$data_string['user_email'] = $user_email;
			// encode the post field as some character are not send directly as '@' AND donot forget to DECODE at server side
			$data_string['user_pass'] = urlencode($user_password);

			$url = Yii::app()->params['wordpress_api_url'].'?json=register';
			$headers = array();
			$result = AppHelper::curl_call($url, $headers, $data_string);
			//showing error of email exixting condition
			if  (json_decode($result)->posts == 'Email already exists') {
				$response_message = self::yii_api_echo('This email address has been already registered.');
				self::terminate(-1, $response_message, APIConstant::EMAIL_EXISTS);
			}

			//showing error of username exixting condition

			if(isset(json_decode($result)->posts->errors->existing_user_login)) {
				$error = json_decode($result)->posts->errors->existing_user_login;
				$response_message = self::yii_api_echo('This email address has been already registered.');
				self::terminate(-1, $response_message, APIConstant::EMAIL_EXISTS);
			}
			$data_string = array();
			$data_string['log'] = $user_email;
			$data_string['pwd'] = urlencode($user_password);
			$url = Yii::app()->params['wordpress_api_url'].'?json=login';

			$headers = array();

			$result = AppHelper::curl_call($url, $headers, $data_string);
			$decoded_result = json_decode($result);
		}

		if($user_account_type == 'Native' && empty($user_password) && $user_password != '0'){
			$message = self::yii_api_echo("Missing parameter password in method user.create");
			self::terminate(-1, $message, APIConstant::PARAMETER_MISSING);
		}

		$user_social_id = $_REQUEST['external_social_id'];

		if($user_account_type !== 'Native' && trim($user_social_id) == ''){
			$message = self::yii_api_echo("Missing parameter external_social_id in method user.create");
			self::terminate(-1, $message, APIConstant::PARAMETER_MISSING);
		}
		$user_social_additional_attributes = $_REQUEST['social_additional_attributes'];

		$user_encrypted_password = AppHelper::one_way_encrypt($user_password);

		if($user_account_type == 'Native' || $user_account_type == 'Facebook'){
			$user = User::model()->findByAttributes(array('email' => $user_email));
			if($user === null){
				$user_model = new User;

				$user_model->name = $user_name;
				$user_model->email = $user_email;
				$user_model->alternate_email = $alternate_user_email;

				$user_model->password = $user_encrypted_password;
				$user_model->reset_password = $user_encrypted_password;

				$chat_details = UserCore::create_chat_user_for_user();
				if(!$chat_details['jabber_status']){
					$message = self::yii_api_echo("User could not be created because jabber service is not responding.");
					self::terminate(-1, $message, APIConstant::UNAVAILABLE_JABBER_SERVICE);
				}

				$user_model->chat_id = $chat_details['chat_id'];
				$user_model->chat_pwd = $chat_details['chat_pwd'];
				$user_model->wp_id = isset($decoded_result->posts->data) ?  $decoded_result->posts->data->ID : '';

				if(!$user_model->save()){
					//TODO
				}
				$user_role = '3'; //set deafult role as normal user
				$user_role_obj = new UserRole();
				$user_role_obj->user_id = $user_model->id;
				$user_role_obj->user_role_id = $user_role;
				if(!$user_role_obj->save()){
					Yii::app()->user->setFlash('success', 'user role not saved');
				}
				// update extra attribute of user
				$user_id = $user_model->id;
				$validation_key = md5($user_id.'_'.$user_email);
				$user_model->validation_key =  $validation_key;

				$user_model->is_validated = 0;

				if (!empty($alternate_user_email)) {
					AppEmail::emailValidate($user_email, $user_name, $validation_key, $alternate_user_email);
				} else {
					AppEmail::emailValidate($user_email, $user_name, $validation_key);
				}

				$user_model->validation_counter =  1;

				if(!$user_model->save()){
					//TODO
				}

				if($user_account_type == 'Native'){
					//nothing extra to do now
				}elseif($user_account_type == 'Facebook'){
					$social_service_type_model = Socialservicetype::model()->find('name=:name', array(':name' => "Facebook"));
					$social_service_type_id = $social_service_type_model->id;
					$social_auth_token = isset($user_social_additional_attributes['auth_token']) ? $user_social_additional_attributes['auth_token'] : '';

					$user_social_service_model = UsersSocialservice::model()->find('user_social_id=:fbid and id_socialservicetype=:sstid', array(':fbid' => $user_social_id, ':sstid'=>$social_service_type_id));
					if ($user_social_service_model == null){

						$user_social_service_model = new UsersSocialservice;
						$user_social_service_model->id_socialservicetype = $social_service_type_id;
						$user_social_service_model->id_user = $user_model->id;
						$user_social_service_model->user_social_id = $user_social_id;
						$user_social_service_model->username = $user_social_id;
						$user_social_service_model->access_token = $social_auth_token;
						$user_social_service_model->expires_on = date('Y-m-d H:m:s', time()+15);
						$user_social_service_model->raw_data = array();

						if (!$user_social_service_model->save()) {
							//TODO
						}
					}else{
						//delete created user
						$user_model->delete();
						$response_message = self::yii_api_echo('This social information already exists.');

						self::terminate(-1, $response_message, APIConstant::SOCIAL_INFO_EXISTS);
					}
				}

				UserCore::setDefaultUserPushNotificationOptions($user_model->id);

				$user_auth_token = UserCore::create_user_auth_token($user_model->id);
				$response_data = array();
				$response_data['success'] = true;
				$response_data['guid'] = $user_model->id;
				$response_data['user_handle'] = $user_auth_token;
				$response_data['validation_status'] = ($user_model->is_validated == 0) ? -1 : 0 ;
				self::success($response_data);
			}elseif($user_account_type == 'Facebook'){
				$social_service_type_model = Socialservicetype::model()->find('name=:name', array(':name' => "Facebook"));
				$social_service_type_id = $social_service_type_model->id;
				$social_auth_token = isset($user_social_additional_attributes['auth_token']) ? $user_social_additional_attributes['auth_token'] : '';

				$user_social_service_model = UsersSocialservice::model()->find('user_social_id=:fbid and id_socialservicetype=:sstid', array(':fbid' => $user_social_id, ':sstid'=>$social_service_type_id));
				if ($user_social_service_model == null){
					// Delete previus UsersSocialservice data and entry new UsersSocialservice data.
					UsersSocialservice::model()->deleteAll('id_user=:userid and id_socialservicetype=:sstid', array(':userid' => $user->id, ':sstid'=>$social_service_type_id));

					$user_social_service_model = new UsersSocialservice;
					$user_social_service_model->id_socialservicetype = $social_service_type_id;
					$user_social_service_model->id_user = $user->id;
					$user_social_service_model->user_social_id = $user_social_id;
					$user_social_service_model->username = $user_social_id;
					$user_social_service_model->access_token = $social_auth_token;
					$user_social_service_model->expires_on = date('Y-m-d H:m:s', time()+15);
					$user_social_service_model->raw_data = array();

					if (!$user_social_service_model->save()) {
					}

					$response_data = array();
					$response_data['message'] = self::yii_api_echo("Merged");
					$user_auth_token = UserCore::create_user_auth_token($user->id);
					$response_data['success'] = true;
					$response_data['guid'] = $user->id;
					$response_data['user_handle'] = $user_auth_token;
					$response_data['validation_status'] = ($user->is_validated == 0) ? -1 : 0 ;

					self::success($response_data);
				}else{
					$response_message = self::yii_api_echo('This email address has been already registered.');
					self::terminate(-1, $response_message, APIConstant::EMAIL_EXISTS);
				}
			}else{
				$response_message = self::yii_api_echo('This email address has been already registered.');
				self::terminate(-1, $response_message, APIConstant::EMAIL_EXISTS);
			}
		}else{
			$response_message = self::yii_api_echo('Account Type is NOT supported.');
			self::terminate(-1, $response_message, APIConstant::UNSUPPORTED_ACCOUNT_TYPE);
		}
	}

	public function actionCreate2(){
		$user_name = Yii::app()->request->getParam('name', '');
		$user_email = Yii::app()->request->getParam('email', '');
		$alternate_user_email = Yii::app()->request->getParam('alternate_email', '');

		if(!AppHelper::is_valid_email_for_all($user_email)){
			$message = self::yii_api_echo("The email address you have provided does not appear to be a valid email address.");
			self::terminate(-1, $message, APIConstant::EMAIL_NOT_VALID);
		}
		if(!empty($alternate_user_email)){
			if(!AppHelper::is_valid_email_for_all($alternate_user_email)){
				$message = self::yii_api_echo("The alternate email address you have provided does not appear to be a valid email address.");
				self::terminate(-1, $message, APIConstant::ALTERNATE_EMAIL_NOT_VALID);
			}
		}

		$user_password = $_REQUEST['password'];
		$user_account_type = Yii::app()->request->getParam('account_type', '');
		if(Yii::app()->params['is_wp_enabled'] && !AppHelper::is_valid_password($user_password)){
			$message = self::yii_api_echo("Password length should be 6 character");
			self::terminate(-1, $message, APIConstant::TOO_SHORT);
		}

		$user_social_id = $_REQUEST['external_social_id'];

		if($user_account_type == 'Native' && empty($user_password) && $user_password != '0'){
			$message = self::yii_api_echo("Missing parameter password in method user.create");
			self::terminate(-1, $message, APIConstant::PARAMETER_MISSING);
		}

		if($user_account_type !== 'Native' && trim($user_social_id) == ''){
			$message = self::yii_api_echo("Missing parameter external_social_id in method user.create");
			self::terminate(-1, $message, APIConstant::PARAMETER_MISSING);
		}

		$user_social_additional_attributes = $_REQUEST['social_additional_attributes'];

		$user_encrypted_password = AppHelper::one_way_encrypt($user_password);

		//                store the user in wpneato table
		if(Yii::app()->params['is_wp_enabled']){

			$data_string = array();
			$data_string['user_login'] = $user_email;
			$data_string['user_email'] = $user_email;
			// encode the post field as some character are not send directly as '@' AND donot forget to DECODE at server side
			$data_string['user_pass'] = urlencode($user_password);

			$url = Yii::app()->params['wordpress_api_url'].'?json=register';
			$headers = array();

			$result = AppHelper::curl_call($url, $headers, $data_string);
			//showing error of email exixting condition
			if(json_decode($result)->posts == 'Email already exists') {
				$response_message = self::yii_api_echo('This email address has been already registered.');
		  		self::terminate(-1, $response_message, APIConstant::EMAIL_EXISTS);
			}

			//showing error of username exixting condition

			if(isset(json_decode($result)->posts->errors->existing_user_login)) {
				$error = json_decode($result)->posts->errors->existing_user_login;
				$response_message = self::yii_api_echo('This email address has been already registered.');
		  		self::terminate(-1, $response_message, APIConstant::EMAIL_EXISTS);
			}
			$data_string = array();
			$data_string['log'] = $user_email;
			$data_string['pwd'] = urlencode($user_password);
			$url = Yii::app()->params['wordpress_api_url'].'?json=login';
			$headers = array();

			$result = AppHelper::curl_call($url, $headers, $data_string);
			$decoded_result = json_decode($result);
		}

		if($user_account_type == 'Native' || $user_account_type == 'Facebook'){
			$user = User::model()->findByAttributes(array('email' => $user_email));

			if($user === null){
				$user_model = new User;

				$user_model->name = $user_name;
				$user_model->email = $user_email;
				$user_model->alternate_email = $alternate_user_email;

				$user_model->password = $user_encrypted_password;
				$user_model->reset_password = $user_encrypted_password;

				$chat_details = UserCore::create_chat_user_for_user();
				if(!$chat_details['jabber_status']){
					$message = self::yii_api_echo("User could not be created because jabber service is not responding.");
					self::terminate(-1, $message, APIConstant::UNAVAILABLE_JABBER_SERVICE);
				}
				$user_model->chat_id = $chat_details['chat_id'];
				$user_model->chat_pwd = $chat_details['chat_pwd'];
				$user_model->wp_id = isset($decoded_result->posts->data) ?  $decoded_result->posts->data->ID : '';

				if(!$user_model->save()){
					//TODO
				}
				$user_role = '3'; //set deafult role as normal user
				$user_role_obj = new UserRole();
				$user_role_obj->user_id = $user_model->id;
				$user_role_obj->user_role_id = $user_role;
				if(!$user_role_obj->save()){
					Yii::app()->user->setFlash('success', 'user role not saved');
				}

				// update extra attribute of user
				$user_id = $user_model->id;
				$validation_key = md5($user_id.'_'.$user_email);
				$user_model->validation_key =  $validation_key;

				$user_model->is_validated = 0;

				if (!empty($alternate_user_email)) {
					AppEmail::emailValidate($user_email, $user_name, $validation_key, $alternate_user_email);
				} else {
					AppEmail::emailValidate($user_email, $user_name, $validation_key);
				}

				$user_model->validation_counter =  1;

				if(!$user_model->save()){
					//TODO
				}

				if($user_account_type == 'Native'){
					//nothing extra to do now
				}elseif($user_account_type == 'Facebook'){
					$social_service_type_model = Socialservicetype::model()->find('name=:name', array(':name' => "Facebook"));
					$social_service_type_id = $social_service_type_model->id;
					$social_auth_token = isset($user_social_additional_attributes['auth_token']) ? $user_social_additional_attributes['auth_token'] : '';

					$user_social_service_model = UsersSocialservice::model()->find('user_social_id=:fbid and id_socialservicetype=:sstid', array(':fbid' => $user_social_id, ':sstid'=>$social_service_type_id));
					if ($user_social_service_model == null){
						$user_social_service_model = new UsersSocialservice;
						$user_social_service_model->id_socialservicetype = $social_service_type_id;
						$user_social_service_model->id_user = $user_model->id;
						$user_social_service_model->user_social_id = $user_social_id;
						$user_social_service_model->username = $user_social_id;
						$user_social_service_model->access_token = $social_auth_token;
						$user_social_service_model->expires_on = date('Y-m-d H:m:s', time()+15);
						$user_social_service_model->raw_data = array();
						if (!$user_social_service_model->save()) {
							//TODO
						}
					}else{
						//delete created user
						$user_model->delete();
						$response_message = self::yii_api_echo('This social information already exists.');
						self::terminate(-1, $response_message,  APIConstant::SOCIAL_INFO_EXISTS);
					}
				}

				UserCore::setDefaultUserPushNotificationOptions($user_model->id);

				$user_auth_token = UserCore::create_user_auth_token($user_model->id);
				$response_data = array();
				$response_data['success'] = true;
				$response_data['guid'] = $user_model->id;
				$response_data['user_handle'] = $user_auth_token;

				$response_data['validation_status'] = UserCore::getIsValidateStatus($user_model->is_validated, $user_model->id);

				self::success($response_data);
			}elseif($user_account_type == 'Facebook'){
				$social_service_type_model = Socialservicetype::model()->find('name=:name', array(':name' => "Facebook"));
				$social_service_type_id = $social_service_type_model->id;
				$social_auth_token = isset($user_social_additional_attributes['auth_token']) ? $user_social_additional_attributes['auth_token'] : '';

				$user_social_service_model = UsersSocialservice::model()->find('user_social_id=:fbid and id_socialservicetype=:sstid', array(':fbid' => $user_social_id, ':sstid'=>$social_service_type_id));
				if ($user_social_service_model == null){
					// Delete previus UsersSocialservice data and entry new UsersSocialservice data.
					UsersSocialservice::model()->deleteAll('id_user=:userid and id_socialservicetype=:sstid', array(':userid' => $user->id, ':sstid'=>$social_service_type_id));

					$user_social_service_model = new UsersSocialservice;
					$user_social_service_model->id_socialservicetype = $social_service_type_id;
					$user_social_service_model->id_user = $user->id;
					$user_social_service_model->user_social_id = $user_social_id;
					$user_social_service_model->username = $user_social_id;
					$user_social_service_model->access_token = $social_auth_token;
					$user_social_service_model->expires_on = date('Y-m-d H:m:s', time()+15);
					$user_social_service_model->raw_data = array();

					if (!$user_social_service_model->save()) {
						// TODO
					}

					$response_data = array();
					$response_data['message'] = self::yii_api_echo("Merged");
					$user_auth_token = UserCore::create_user_auth_token($user->id);
					$response_data['success'] = true;
					$response_data['guid'] = $user->id;
					$response_data['user_handle'] = $user_auth_token;
					$response_data['validation_status'] = UserCore::getIsValidateStatus($user->is_validated, $user->id);

					self::success($response_data);
				}else{
					$response_message = self::yii_api_echo('This email address has been already registered.');
					self::terminate(-1, $response_message, APIConstant::EMAIL_EXISTS);
				}
			}else{
				$response_message = self::yii_api_echo('This email address has been already registered.');
				self::terminate(-1, $response_message, APIConstant::EMAIL_EXISTS);
			}
		}else{
			$response_message = self::yii_api_echo('Account Type is NOT supported.');
			self::terminate(-1, $response_message, APIConstant::UNSUPPORTED_ACCOUNT_TYPE);
		}
	}

	//  Added extra info of user in actionCreate3 in json format with field name exram_param
	public function actionCreate3(){

		$user_name = Yii::app()->request->getParam('name', '');
		$user_email = Yii::app()->request->getParam('email', '');
		$alternate_user_email = Yii::app()->request->getParam('alternate_email', '');
		$extram_param = Yii::app()->request->getParam('extra_param','');

		$decode_extra_param = json_decode($extram_param);
		if(isset($decode_extra_param->country_code)){
			$country_code = $decode_extra_param->country_code;
		}else{
			$country_code = 'US';
		}
		if(isset($decode_extra_param->opt_in) && $decode_extra_param->opt_in == 'true'){
			$opt_in = 1;
		}else{
			$opt_in = 0;
		}

		if(!empty($extram_param) && json_decode($extram_param) === null) {
			self::terminate(-1, "The JSON Object you have provided does not appear to be a valid.", APIConstant::JSON_OBJECT_NOT_VALID);
		}

		if(!AppHelper::is_valid_email_for_all($user_email)){
			$message = self::yii_api_echo("The email address you have provided does not appear to be a valid email address.");
			self::terminate(-1, $message, APIConstant::EMAIL_NOT_VALID);
		}
		if(!empty($alternate_user_email)){
			if(!AppHelper::is_valid_email_for_all($alternate_user_email)){
				$message = self::yii_api_echo("The alternate email address you have provided does not appear to be a valid email address.");
				self::terminate(-1, $message, APIConstant::ALTERNATE_EMAIL_NOT_VALID);
			}
		}

		$user_password = $_REQUEST['password'];
		if(Yii::app()->params['is_wp_enabled'] && !AppHelper::is_valid_password($user_password)){
			$message = self::yii_api_echo("Password length should be 6 character");
			self::terminate(-1, $message, APIConstant::TOO_SHORT);
		}

		$user_account_type = Yii::app()->request->getParam('account_type', '');
		$user_social_id = $_REQUEST['external_social_id'];

		if($user_account_type == 'Native' && empty($user_password) && $user_password != '0'){
			$message = self::yii_api_echo("Missing parameter password in method user.create");
			self::terminate(-1, $message, APIConstant::PARAMETER_MISSING);
		}

		if($user_account_type !== 'Native' && trim($user_social_id) == ''){
			$message = self::yii_api_echo("Missing parameter external_social_id in method user.create");
			self::terminate(-1, $message, APIConstant::PARAMETER_MISSING);
		}

		$user_social_additional_attributes = $_REQUEST['social_additional_attributes'];

		$user_encrypted_password = AppHelper::one_way_encrypt($user_password);

		// store the user in wpneato table
		if(Yii::app()->params['is_wp_enabled']){

			$data_string = array();
			$data_string['user_login'] = $user_email;
			$data_string['user_email'] = $user_email;
			$data_string['user_pass'] = urlencode($user_password);

			$url = Yii::app()->params['wordpress_api_url'].'?json=register';
			$headers = array();

			$result = AppHelper::curl_call($url, $headers, $data_string);
			//showing error of email exixting condition
			if  (json_decode($result)->posts == 'Email already exists') {
				$response_message = self::yii_api_echo('This email address has been already registered.');
		  self::terminate(-1, $response_message, APIConstant::EMAIL_EXISTS);
			}
			//showing error of username exixting condition

			if  (isset(json_decode($result)->posts->errors->existing_user_login)) {
				$error = json_decode($result)->posts->errors->existing_user_login;
				$response_message = self::yii_api_echo('This email address has been already registered.');
		  self::terminate(-1, $response_message, APIConstant::EMAIL_EXISTS);
			}
		 $data_string = array();
		 $data_string['log'] = $user_email;
		 $data_string['pwd'] = urlencode($user_password);
		 $url = Yii::app()->params['wordpress_api_url'].'?json=login';
		 $headers = array();

		 $result = AppHelper::curl_call($url, $headers, $data_string);
		 $decoded_result = json_decode($result);
		}

		if($user_account_type == 'Native' || $user_account_type == 'Facebook'){
			$user = User::model()->findByAttributes(array('email' => $user_email));

			if($user === null){
				$user_model = new User;

				$user_model->name = $user_name;
				$user_model->email = $user_email;
				$user_model->alternate_email = $alternate_user_email;

				$user_model->extram_param = $extram_param;
				$user_model->country_code = $country_code;
				$user_model->opt_in = $opt_in;

				$user_model->password = $user_encrypted_password;
				$user_model->reset_password = $user_encrypted_password;

				$chat_details = UserCore::create_chat_user_for_user();
				if(!$chat_details['jabber_status']){
					$message = self::yii_api_echo("User could not be created because jabber service is not responding.");
					self::terminate(-1, $message, APIConstant::UNAVAILABLE_JABBER_SERVICE);
				}
				$user_model->chat_id = $chat_details['chat_id'];
				$user_model->chat_pwd = $chat_details['chat_pwd'];
				$user_model->wp_id = isset($decoded_result->posts->data) ?  $decoded_result->posts->data->ID : '';

				if(!$user_model->save()){
					//TODO
				}

				$user_role = '3'; //set deafult role as normal user
				$user_role_obj = new UserRole();
				$user_role_obj->user_id = $user_model->id;
				$user_role_obj->user_role_id = $user_role;
				if(!$user_role_obj->save()){
					Yii::app()->user->setFlash('success', 'user role not saved');
				}

				// update extra attribute of user
				$user_id = $user_model->id;
				$validation_key = md5($user_id.'_'.$user_email);
				$user_model->validation_key =  $validation_key;

				$user_model->is_validated = 0;

				if (!empty($alternate_user_email)) {
					AppEmail::emailValidate($user_email, $user_name, $validation_key, $alternate_user_email);
				} else {
					AppEmail::emailValidate($user_email, $user_name, $validation_key);
				}

				$user_model->validation_counter =  1;

				if(!$user_model->save()){
					//TODO
				}

				if($user_account_type == 'Native'){
					//nothing extra to do now
				}elseif($user_account_type == 'Facebook'){
					$social_service_type_model = Socialservicetype::model()->find('name=:name', array(':name' => "Facebook"));
					$social_service_type_id = $social_service_type_model->id;
					$social_auth_token = isset($user_social_additional_attributes['auth_token']) ? $user_social_additional_attributes['auth_token'] : '';

					$user_social_service_model = UsersSocialservice::model()->find('user_social_id=:fbid and id_socialservicetype=:sstid', array(':fbid' => $user_social_id, ':sstid'=>$social_service_type_id));
					if ($user_social_service_model == null){

						$user_social_service_model = new UsersSocialservice;
						$user_social_service_model->id_socialservicetype = $social_service_type_id;
						$user_social_service_model->id_user = $user_model->id;
						$user_social_service_model->user_social_id = $user_social_id;
						$user_social_service_model->username = $user_social_id;
						$user_social_service_model->access_token = $social_auth_token;
						$user_social_service_model->expires_on = date('Y-m-d H:m:s', time()+15);
						$user_social_service_model->raw_data = array();

						if (!$user_social_service_model->save()) {
							//TODO
						}
					}else{
						//delete created user
						$user_model->delete();
						$response_message = self::yii_api_echo('This social information already exists.');
						self::terminate(-1, $response_message,  APIConstant::SOCIAL_INFO_EXISTS);
					}
				}

				UserCore::setDefaultUserPushNotificationOptions($user_model->id);

				$user_auth_token = UserCore::create_user_auth_token($user_model->id);
				$response_data = array();
				$response_data['success'] = true;
				$response_data['guid'] = $user_model->id;
				$response_data['user_handle'] = $user_auth_token;

				$response_data['validation_status'] = UserCore::getIsValidateStatus($user_model->is_validated, $user_model->id);

				self::success($response_data);
			}elseif($user_account_type == 'Facebook'){
				$social_service_type_model = Socialservicetype::model()->find('name=:name', array(':name' => "Facebook"));
				$social_service_type_id = $social_service_type_model->id;
				$social_auth_token = isset($user_social_additional_attributes['auth_token']) ? $user_social_additional_attributes['auth_token'] : '';

				$user_social_service_model = UsersSocialservice::model()->find('user_social_id=:fbid and id_socialservicetype=:sstid', array(':fbid' => $user_social_id, ':sstid'=>$social_service_type_id));
				if ($user_social_service_model == null){
					// Delete previus UsersSocialservice data and entry new UsersSocialservice data.
					UsersSocialservice::model()->deleteAll('id_user=:userid and id_socialservicetype=:sstid', array(':userid' => $user->id, ':sstid'=>$social_service_type_id));

					$user_social_service_model = new UsersSocialservice;
					$user_social_service_model->id_socialservicetype = $social_service_type_id;
					$user_social_service_model->id_user = $user->id;
					$user_social_service_model->user_social_id = $user_social_id;
					$user_social_service_model->username = $user_social_id;
					$user_social_service_model->access_token = $social_auth_token;
					$user_social_service_model->expires_on = date('Y-m-d H:m:s', time()+15);
					$user_social_service_model->raw_data = array();

					if (!$user_social_service_model->save()) {
						//TODO
					}

					$response_data = array();
					$response_data['message'] = self::yii_api_echo("Merged");
					$user_auth_token = UserCore::create_user_auth_token($user->id);
					$response_data['success'] = true;
					$response_data['guid'] = $user->id;
					$response_data['user_handle'] = $user_auth_token;
					$response_data['validation_status'] = UserCore::getIsValidateStatus($user->is_validated, $user->id);

					self::success($response_data);
				}else{
					$response_message = self::yii_api_echo('This email address has been already registered.');
					self::terminate(-1, $response_message, APIConstant::EMAIL_EXISTS);
				}
			}else{
				$response_message = self::yii_api_echo('This email address has been already registered.');
				self::terminate(-1, $response_message, APIConstant::EMAIL_EXISTS);
			}
		}else{
			$response_message = self::yii_api_echo('Account Type is NOT supported.');
			self::terminate(-1, $response_message, APIConstant::UNSUPPORTED_ACCOUNT_TYPE);
		}
	}

	public function actionCreate4(){

		$user_name = Yii::app()->request->getParam('name', '');
		$user_email = Yii::app()->request->getParam('email', '');
		$password = Yii::app()->request->getParam('password', '');
		$alternate_user_email = Yii::app()->request->getParam('alternate_email', '');
		$extram_param = Yii::app()->request->getParam('extra_param','');

		if(!AppHelper::is_valid_email_for_all($user_email)){
			$message = self::yii_api_echo("The email address you have provided does not appear to be a valid email address.");
			self::terminate(-1, $message, APIConstant::EMAIL_NOT_VALID);
		}

		if(!empty($extram_param) && json_decode($extram_param) === null) {
			self::terminate(-1, "The JSON Object you have provided does not appear to be a valid.", APIConstant::JSON_OBJECT_NOT_VALID);
		}

		if(!empty($alternate_user_email)){
			if(!AppHelper::is_valid_email_for_all($alternate_user_email)){
				$message = self::yii_api_echo("The alternate email address you have provided does not appear to be a valid email address.");
				self::terminate(-1, $message, APIConstant::ALTERNATE_EMAIL_NOT_VALID);
			}
		}
		$user_password = $_REQUEST['password'];
		if(Yii::app()->params['is_wp_enabled'] && !AppHelper::is_valid_password($user_password)){
			$message = self::yii_api_echo("Password length should be 6 character");
			self::terminate(-1, $message, APIConstant::TOO_SHORT);
		}

		$user_account_type = Yii::app()->request->getParam('account_type', '');
		$user_social_id = $_REQUEST['external_social_id'];

		//                store the user in wpneato table
		if(Yii::app()->params['is_wp_enabled']){

			$data_string = array();
			$data_string['user_login'] = $user_email;
			$data_string['user_email'] = $user_email;
			// encode the post field as some character are not send directly as '@' AND donot forget to DECODE at server side
			$data_string['user_pass'] = urlencode($user_password);

			$url = Yii::app()->params['wordpress_api_url'].'?json=register';
			$headers = array();

			$result = AppHelper::curl_call($url, $headers, $data_string);
			//showing error of email exixting condition
			if (!(json_decode($result)->posts)) {
				$response_message = self::yii_api_echo('This email address has been already registered.');
				self::terminate(-1, $response_message, APIConstant::EMAIL_EXISTS);
			}

			//showing error of username exixting condition

			if (isset(json_decode($result)->posts->errors->existing_user_login)) {
				$error = json_decode($result)->posts->errors->existing_user_login;
				$response_message = self::yii_api_echo('This email address has been already registered.');
		  		self::terminate(-1, $response_message, APIConstant::EMAIL_EXISTS);
			}
			$data_string = array();
			$data_string['log'] = $user_email;
			$data_string['pwd'] = urlencode($user_password);
			$url = Yii::app()->params['wordpress_api_url'].'?json=login';
			$headers = array();

			$result = AppHelper::curl_call($url, $headers, $data_string);
			$decoded_result = json_decode($result);
		}

		$decode_extra_param = json_decode($extram_param);

		if(isset($decode_extra_param->country_code)){
			$country_code = $decode_extra_param->country_code;
		}else{
			$country_code = 'US';
		}
		if(isset($decode_extra_param->opt_in) && $decode_extra_param->opt_in == 'true'){
			$opt_in = 1;
		}else{
			$opt_in = 0;
		}

		if($user_account_type == 'Native' && empty($user_password) && $user_password != '0'){
			$message = self::yii_api_echo("Missing parameter password in method user.create");
			self::terminate(-1, $message, APIConstant::PARAMETER_MISSING);
		}

		if($user_account_type !== 'Native' && trim($user_social_id) == ''){
			$message = self::yii_api_echo("Missing parameter external_social_id in method user.create");
			self::terminate(-1, $message, APIConstant::PARAMETER_MISSING);
		}

		$user_social_additional_attributes = $_REQUEST['social_additional_attributes'];

		$user_encrypted_password = AppHelper::one_way_encrypt($user_password);

		if($user_account_type == 'Native' || $user_account_type == 'Facebook'){
			$user = User::model()->findByAttributes(array('email' => $user_email));

			if($user === null){
				$user_model = new User;

				$user_model->name = $user_name;
				$user_model->email = $user_email;
				$user_model->alternate_email = $alternate_user_email;

				$user_model->extram_param = $extram_param;
				$user_model->country_code = $country_code;
				$user_model->opt_in = $opt_in;

				$user_model->password = $user_encrypted_password;
				$user_model->reset_password = $user_encrypted_password;

				$chat_details = UserCore::create_chat_user_for_user();
				if(!$chat_details['jabber_status']){
					$message = self::yii_api_echo("User could not be created because jabber service is not responding.");
					self::terminate(-1, $message, APIConstant::UNAVAILABLE_JABBER_SERVICE);
				}
				$user_model->chat_id = $chat_details['chat_id'];
				$user_model->chat_pwd = $chat_details['chat_pwd'];
				$user_model->wp_id = isset($decoded_result->posts->data) ?  $decoded_result->posts->data->ID : '';

				if(!$user_model->save()){
					//TODO
				}

				$user_role = '3'; //set deafult role as normal user
				$user_role_obj = new UserRole();
				$user_role_obj->user_id = $user_model->id;
				$user_role_obj->user_role_id = $user_role;
				if(!$user_role_obj->save()){
					Yii::app()->user->setFlash('success', 'user role not saved');
				}

				// update extra attribute of user
				$user_id = $user_model->id;
				$validation_key = md5($user_id.'_'.$user_email);
				$user_model->validation_key =  $validation_key;

				$user_model->is_validated = 0;

				if (!empty($alternate_user_email)) {
					AppEmail::emailValidate($user_email, $user_name, $validation_key, $alternate_user_email);
				} else {
					AppEmail::emailValidate($user_email, $user_name, $validation_key);
				}

				$user_model->validation_counter =  1;

				if(!$user_model->save()){
					//TODO
				}

				if($user_account_type == 'Native'){
					//nothing extra to do now
				}elseif($user_account_type == 'Facebook'){
					$social_service_type_model = Socialservicetype::model()->find('name=:name', array(':name' => "Facebook"));
					$social_service_type_id = $social_service_type_model->id;
					$social_auth_token = isset($user_social_additional_attributes['auth_token']) ? $user_social_additional_attributes['auth_token'] : '';

					$user_social_service_model = UsersSocialservice::model()->find('user_social_id=:fbid and id_socialservicetype=:sstid', array(':fbid' => $user_social_id, ':sstid'=>$social_service_type_id));
					if ($user_social_service_model == null){

						$user_social_service_model = new UsersSocialservice;
						$user_social_service_model->id_socialservicetype = $social_service_type_id;
						$user_social_service_model->id_user = $user_model->id;
						$user_social_service_model->user_social_id = $user_social_id;
						$user_social_service_model->username = $user_social_id;
						$user_social_service_model->access_token = $social_auth_token;
						$user_social_service_model->expires_on = date('Y-m-d H:m:s', time()+15);
						$user_social_service_model->raw_data = array();

						if (!$user_social_service_model->save()) {
							//TODO
						}
					}else{
						//delete created user
						$user_model->delete();
						$response_message = self::yii_api_echo('This social information already exists.');
						self::terminate(-1, $response_message,  APIConstant::SOCIAL_INFO_EXISTS);
					}
				}

				UserCore::setDefaultUserPushNotificationOptions($user_model->id);

				$user_auth_token = UserCore::create_user_auth_token($user_model->id);
				$response_data = array();
				$response_data['success'] = true;
				$response_data['guid'] = $user_model->id;
				$response_data['user_handle'] = $user_auth_token;

				$response_data['validation_status'] = UserCore::getIsValidateStatus($user_model->is_validated, $user_model->id);

				self::success($response_data);
			}elseif($user_account_type == 'Facebook'){
				$social_service_type_model = Socialservicetype::model()->find('name=:name', array(':name' => "Facebook"));
				$social_service_type_id = $social_service_type_model->id;
				$social_auth_token = isset($user_social_additional_attributes['auth_token']) ? $user_social_additional_attributes['auth_token'] : '';

				$user_social_service_model = UsersSocialservice::model()->find('user_social_id=:fbid and id_socialservicetype=:sstid', array(':fbid' => $user_social_id, ':sstid'=>$social_service_type_id));
				if ($user_social_service_model == null){
					// Delete previus UsersSocialservice data and entry new UsersSocialservice data.
					UsersSocialservice::model()->deleteAll('id_user=:userid and id_socialservicetype=:sstid', array(':userid' => $user->id, ':sstid'=>$social_service_type_id));

					$user_social_service_model = new UsersSocialservice;
					$user_social_service_model->id_socialservicetype = $social_service_type_id;
					$user_social_service_model->id_user = $user->id;
					$user_social_service_model->user_social_id = $user_social_id;
					$user_social_service_model->username = $user_social_id;
					$user_social_service_model->access_token = $social_auth_token;
					$user_social_service_model->expires_on = date('Y-m-d H:m:s', time()+15);
					$user_social_service_model->raw_data = array();

					if (!$user_social_service_model->save()) {
						//TODO
					}

					$response_data = array();
					$response_data['message'] = self::yii_api_echo("Merged");
					$user_auth_token = UserCore::create_user_auth_token($user->id);
					$response_data['success'] = true;
					$response_data['guid'] = $user->id;
					$response_data['user_handle'] = $user_auth_token;
					$response_data['validation_status'] = UserCore::getIsValidateStatus($user->is_validated, $user->id);

					self::success($response_data);
				}else{
					$response_message = self::yii_api_echo('This email address has been already registered.');
					self::terminate(-1, $response_message, APIConstant::EMAIL_EXISTS);
				}
			}else{
				$response_message = self::yii_api_echo('This email address has been already registered.');
				self::terminate(-1, $response_message, APIConstant::EMAIL_EXISTS);
			}
		}else{
			$response_message = self::yii_api_echo('Account Type is NOT supported.');
			self::terminate(-1, $response_message, APIConstant::UNSUPPORTED_ACCOUNT_TYPE);
		}
	}

	/**
	 * API to set user account details
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>email</b> :User's email (Optional)</li>
	 *	<li><b>auth_token</b> :User's auth token (received from get user
	 *		handle call)</li>
	 *	<li><b>profile</b> :Map of key=>value pairs, e.g.
	 *		profile{'name'=>'james bond',
	 *		'facebook_external_social_id'='12312111'}</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>{"status":0,"result":"1"}</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If Auth token Key is missing or not correct:
	 *		<ul>
	 *			<li>{"status":-1,"message":"Method call failed the Auth token
	 *				Authentication"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionSetAccountDetails(){
		$user_profile = Yii::app()->request->getParam('profile', '');
		$user_auth_token = Yii::app()->request->getParam('auth_token', '');
		$user_api_session = UsersApiSession::model()->findByAttributes(array('token' =>$user_auth_token));
		$user = User::model()->findByAttributes(array('id' => $user_api_session->id_user));
		if ($user !== null){
			foreach ($user_profile as $key => $value){
				if($value === ''){
					$message = self::yii_api_echo("Invalid value for key $key.");
					self::terminate(-1, $message, APIConstant::ERROR_INVALID_USER_ACCOUNT_DETAIL);
				}
				switch ($key) {
					case "name":
						$user->name = $value;
						$user->save();
						break;
					case "facebook_external_social_id":
						self::update_facebook_social_service($user->id, $value);
						break;
					case "country_code":
						if($user_profile['country_code'] == '0'){
							$user->country_code = '0';
							$extram_param = array();
							$extram_param['country_code'] =  '0';
							if($user->opt_in == '0'){
								$extram_param['opt_in'] = 'false';
							}
							if($user->opt_in == '1'){
								$extram_param['opt_in'] = 'true';
							}
							$user->extram_param = json_encode($extram_param);
							$user->save();
							break;
						}else{
							$searched_country_code = isset($user_profile['country_code']) ? $user_profile['country_code'] : "";
							$country_code_data = CountryCodeList::model()->findByAttributes(array('iso2' => $searched_country_code));
							if($country_code_data){
								$user->country_code = strtoupper($value);
								$extram_param = array();
								$extram_param['country_code'] = strtoupper($value);
								if($user->opt_in == '0'){
									$extram_param['opt_in'] = 'false';
								}
								if($user->opt_in == '1'){
									$extram_param['opt_in'] = 'true';
								}
								$user->extram_param = json_encode($extram_param);
								$user->save();
								break;
							}else{
								$response_message = "invalid country_code";
								self::terminate(-1, $response_message, APIConstant::INVALID_COUNTRY_CODE);
							}
						}
					case "opt_in":
						if ((strtolower($value) == 'true' || strtolower($value) == 'false')) {
							if (strtolower($value) == 'true') {
								$user->opt_in = '1';
							} else {
								$user->opt_in = '0';
							}
							$extram_param = array();
							$extram_param['country_code'] = $user->country_code;
							$extram_param['opt_in'] = $value;
							$user->extram_param = json_encode($extram_param);
							$user->save();
							break;
						} else {
							$response_message = "Invalid opt in flag. It should be true or false";
							self::terminate(-1, $response_message, APIConstant::INVALID_OPT_IN_FLAG);
						}

					default:
						;
						break;
				}
			}
			$response_data = array('success' => true, 'user_id' => $user->id, 'name' => $user->name,'email' => $user->email, 'chat_id' => $user->chat_id, 'chat_pwd' => $user->chat_pwd, 'is_active' => $user->is_active, 'is_validated' => $user->is_validated, 'alternate_email' => $user->alternate_email, 'push_notification_preference' => $user->push_notification_preference, 'extram_param' => json_decode($user->extram_param));
			self::success($response_data);
		}else{
			$response_message = self::yii_api_echo('APIException:UserAuthenticationFailed');
			self::terminate(-1, $response_message, APIConstant::AUTH_TOKEN_AGAINST_EMAIL_DOES_NOT_EXIST);
		}
	}


	public function actionGetCountryCode(){

		$country_name = trim(Yii::app()->request->getParam('country_name', ''));

		if($country_name == '0'){
			$response_message = "Please Enter valid Country Name";
			self::terminate(-1, $response_message, APIConstant::INVALID_COUNTRY_NAME);
		}

		if(!empty($country_name)){
			$country_code_list_data = CountryCodeList::model()->findByAttributes(array('short_name' => $country_name));

			if(empty($country_code_list_data)){
				$response_message = "Please Enter valid Country Name";
				self::terminate(-1, $response_message, APIConstant::INVALID_COUNTRY_NAME);
			}else{
				$country_code = $country_code_list_data->iso2;
				$country_names = $country_code_list_data->short_name;
				$response_data = array('success' => true, 'country_name' => $country_names,'country_code' => $country_code);
				self::success($response_data);
			}
		}
		if(empty($country_name)){
			$country_code_list = array();
			$total_country_code_list = array();

			$country_code_lists = CountryCodeList::model()->findAll();

			foreach($country_code_lists as $country_code_data){
				$country_code_list['country_name']= $country_code_data->short_name;
				$country_code_list['country_code'] = $country_code_data->iso2;
				$total_country_code_list[] = $country_code_list;
			}
			$response = $total_country_code_list;
			self::success($response);
		}

	}

	/**
	 * Used to update facebook details
	 * @param int $user_id
	 * @param string $user_social_id
	 * @param array $user_social_additional_attributes
	 */
	protected function update_facebook_social_service($user_id, $user_social_id, $user_social_additional_attributes = array()){
		$social_service_type_model = Socialservicetype::model()->find('name=:name', array(':name' => "Facebook"));
		$social_service_type_id = $social_service_type_model->id;
		$social_auth_token = isset($user_social_additional_attributes['auth_token']) ? $user_social_additional_attributes['auth_token'] : '';

		$user_social_service_model = UsersSocialservice::model()->find('user_social_id=:fbid and id_socialservicetype=:sstid', array(':fbid' => $user_social_id, ':sstid'=>$social_service_type_id));
		if ($user_social_service_model == null){
			$user_social_service_model = new UsersSocialservice;
			$user_social_service_model->id_socialservicetype = $social_service_type_id;
			$user_social_service_model->id_user = $user_id;
			$user_social_service_model->user_social_id = $user_social_id;
			$user_social_service_model->username = $user_social_id;
			$user_social_service_model->access_token = $social_auth_token;
			$user_social_service_model->expires_on = date('Y-m-d H:m:s', time()+15);
			$user_social_service_model->raw_data = array();

			if (!$user_social_service_model->save()) {
				//TODO
			}
		}else{
			$user_social_service_model->user_social_id = $user_social_id;
			$user_social_service_model->username = $user_social_id;
			$user_social_service_model->save();
		}
	}

	public function actionUserDataTable() {
		$user_role_id = Yii::app()->user->UserRoleId;
		$userColumns = array('id', 'email', 'name', 'is_admin');
		$userIndexColumn = "id";
		$userTable = "users";
		$userDataModelName = 'User';

		if($user_role_id == '2'){
			if($_GET['sSearch'] == ""){
				$_GET['sSearch'] = " ";
			}
		}

		$result = AppCore::dataTableOperation($userColumns, $userIndexColumn, $userTable, $_GET, $userDataModelName);

		/*
		 * Output
		*/
		$output = array(
				'sEcho' => $result['sEcho'],
				'iTotalRecords' => $result['iTotalRecords'],
				'iTotalDisplayRecords' => $result['iTotalDisplayRecords'],
				'aaData' => array()
		);

		foreach ($result['rResult'] as $user) {

			$user_role_data = UserRole::model()->findByAttributes(array('user_id' => $user->id ));
			$current_user_id = $user_role_data->user_role_id;
			$row = array();

			if($user->is_admin == '0'){
				$select_checkbox = '<input type="checkbox" name="chooseoption[]" value="'.$user->id.'" class="choose-option">';
			} else {
				if($current_user_id == '2'){
					$select_checkbox = "Support" ;
				}else{
					$select_checkbox = "Admin" ;
				}

			}

			$user_email = '<a rel="'.$this->createUrl('/user/userprofilepopup', array('h'=>AppHelper::two_way_string_encrypt($user->id))).'" href="'.$this->createUrl('/user/userprofile',array('h'=>AppHelper::two_way_string_encrypt($user->id))).'" class="qtiplink" title="View details of ('.$user->email.')">'.$user->email.'</a>';

			$associated_robots = '';
			if ($user->doesRobotAssociationExist()) {
				$is_first_robot = true;
				foreach ($user->usersRobots as $value) {
					if (!$is_first_robot) {
						$associated_robots .= ",";
					}
					$is_first_robot = false;
					$associated_robots .= "<a class='single-item qtiplink robot-qtip' title='View details of (" . $value->idRobot->serial_number . ")' rel='" . $this->createUrl('/robot/popupview', array('h' => AppHelper::two_way_string_encrypt($value->idRobot->id))) . "' href='" . $this->createUrl('/robot/view', array('h' => AppHelper::two_way_string_encrypt($value->idRobot->id))) . "'>" . $value->idRobot->serial_number . "</a>";
				}
			}


			if($user_role_id != '2'){
				$row [] = $select_checkbox;
				$row [] = $user_email;
				$row [] = $user->name;
				$row [] = $associated_robots;
				$output ['aaData'] [] = $row;
			}else{
				$row [] = $user_email;
				$row [] = $associated_robots;
				$output ['aaData'] [] = $row;
			}

		}

		$this->renderPartial('/default/defaultView', array('content' => $output));
	}

	public function actionIsUserValidated() {

		$user_email = Yii::app()->request->getParam('email', '');
		$is_validated = -1;
		$message = "";

		if (!AppHelper::is_valid_email($user_email)) {
			$message = self::yii_api_echo("The email address you have provided does not appear to be a valid email address.");
			self::terminate(-1, $message, APIConstant::EMAIL_NOT_VALID);
		}

		$data = User::model()->find('email = :email', array(':email' => $user_email));
		if(!empty($data)) {
			if ($data->is_validated == 1) {
				$is_validated = 0;
				$message = "The email address you have provided is Active";
			} else{

				$message = "The email address you have provided does not appear to be a validated. Please validate it.";

				$grace_period = UserCore::getGracePeriod();
				$user_created_on_timestamp = strtotime($data->created_on);
				$current_system_timestamp = time();
				$time_diff = ($current_system_timestamp - $user_created_on_timestamp) / 60;

				if($time_diff > $grace_period){
					$is_validated = -2 ;
					$message = "Sorry, You must validate your account to proceed.";
				}

			}

			$response_data['validation_status'] = $is_validated;
			$response_data['message'] = $message;
			self::success($response_data);

		} else {
			$message = self::yii_api_echo("The email address you have provided does not exist in our system.");
			self::terminate(-1, $message, APIConstant::EMAIL_DOES_NOT_EXIST);
		}

	}

	public function actionResendValidationEmail() {

		$user_email = Yii::app()->request->getParam('email', '');
		$is_wp_enabled = Yii::app()->params['is_wp_enabled'];
		$email_label = 'Email address';
		if($is_wp_enabled){
			$email_label = 'Username';
		}
		//get input is_validated if it from create user 2 web service API

		if (!AppHelper::is_valid_email($user_email)) {
			$message = 'The '.$email_label.' you have provided does not appear to be a valid email address.';
			self::terminate(-1, $message, APIConstant::EMAIL_NOT_VALID);
		}

		$data = User::model()->find('email = :email', array(':email' => $user_email));

		if(!empty($data)) {

			if (!$data->is_validated) {

				$validation_attempt = UserCore::getValidationAttempt();

				if ($data->validation_counter < $validation_attempt) {

					$user_name = $data->name;
					$validation_key = $data->validation_key;
					$alternate_user_email = isset($data->alternate_email) ? $data->alternate_email : '';
					$validation_counter = $data->validation_counter;

					if (!empty($alternate_user_email)) {
						AppEmail::emailValidate($user_email, $user_name, $validation_key, $alternate_user_email);
					} else {
						AppEmail::emailValidate($user_email, $user_name, $validation_key);
					}
					$data->validation_counter = $validation_counter + 1;

					if (!$data->save()) {
						//TODO
					}

					$response_data['success'] = true;
					$response_data['message'] = "We have resent validation email.";
					self::success($response_data);
				} else {
					$message = self::yii_api_echo("Sorry, You have crossed resend validation email limit.");
					self::terminate(-1, $message, APIConstant::CROSSED_RESEND_EMAIL_LIMIT);
				}

			} else {

				$message = "The ".$email_label." you have provided is already activated.";
				self::terminate(-1, $message, APIConstant::EMAIL_ALREADY_ACTIVATED);

			}
		} else {
			$message = "The ".$email_label." you have provided does not exist in our system.";
			self::terminate(-1, $message, APIConstant::EMAIL_DOES_NOT_EXIST);
		}

	}

	public function actionGetErrorCode() {

		$error_code = Yii::app()->request->getParam('error_code', '');

		$response = array();

		if(!empty($error_code)){

			$message = APIConstant::getMessageForErrorCode($error_code);

			$response = array($error_code=>  $message);

			if($message == $error_code) {

				self::terminate(-1, $message, APIConstant::ERROR_CODE_NOT_EXIST);

			}

		}else {
			$response = APIConstant::$english;
			krsort($response);
		}

		self::success($response);
	}

	public function actionStartEjabbered() {
		$start_ejabbered = shell_exec("sudo service ejabberd restart");
		$this->renderPartial('/default/defaultView', array('content' => $start_ejabbered));
	}

	public function actionStopEjabbered() {
		$stop_ejabberd = shell_exec("sudo service ejabberd stop");
		$this->renderPartial('/default/defaultView', array('content' => $stop_ejabberd));
	}
	public function actionStartRabbitMQ() {
		$mq_cmd = Yii::app()->params['mq_server_path'];
		shell_exec('sudo ' .$mq_cmd. ' restart');

		shell_exec("sudo nohup php amqp_consumer.php");
		$start_rabbitMQ = 'successfully restart';

		$this->renderPartial('/default/defaultView', array('content' => $start_rabbitMQ));
	}

	public function actionStopRabbitMQ() {
		$cmd = 'php '.Yii::app()->params['neato_amqp_publisher_path']. ' quit';
		shell_exec($cmd);

		$mq_cmd = 'sudo ' .Yii::app()->params['mq_server_path']. 'stop';
		shell_exec($mq_cmd);
		$stop_ejabbered = 'successfully stop';
		$this->renderPartial('/default/defaultView', array('content' => $stop_ejabbered));
	}

}
