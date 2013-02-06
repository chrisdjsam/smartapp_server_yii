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

			$chat_details = AppCore::create_chat_user_for_user();
			if(!$chat_details['jabber_status']){
				//$message = "User could not be created because jabber service is not responding.";
				//self::terminate(-1, $message);
			}
			$user_model->chat_id = $chat_details['chat_id'];
			$user_model->chat_pwd = $chat_details['chat_pwd'];

			if(!$user_model->save()){
				$message = AppCore::yii_echo("login fail - 3");
				$content = array('status' => -1, 'message' => $message);
				$this->renderPartial('/default/defaultView', array('content' => $content));
				Yii::app()->end();
			}
			//send welocom messsage
			$login_link = $this->createUrl("/user/login");
			AppEmail::emailWelcomeNewUser($user_model->email, $user_model->name, $new_password, $login_link);
		}else{
			//user exists , do not do anything
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
					if ($user !== null){
						$user_auth_token = AppCore::create_user_auth_token($user->id);
						if ($user_auth_token){
							self::success($user_auth_token);
						}
					}else{
						$response_message = self::yii_api_echo('User could not be authenticated');
						self::terminate(-1, $response_message);
					}
				}else{
					$response_message = self::yii_api_echo('Missing parameter password in method auth.get_user_auth_token');
					self::terminate(-1, $response_message);
				}
			}else{
				$response_message = self::yii_api_echo('Missing parameter email in method auth.get_user_auth_token');
				self::terminate(-1, $response_message);
			}
		}else if($account_type == 'Facebook'){
			if($user_social_id !== ''){
				$user_social_services = UsersSocialservice::model()->findByAttributes(array('user_social_id' => $user_social_id));
				if($user_social_services !== null){
					$user_auth_token = AppCore::create_user_auth_token($user_social_services->idUser->id);
					if ($user_auth_token){
						self::success($user_auth_token);
					}
				}else{
					$response_message = self::yii_api_echo('User could not be authenticated');
					self::terminate(-1, $response_message);
				}
			}else{
				$response_message = self::yii_api_echo('Missing parameter user_social_id in method auth.get_user_auth_token');
				self::terminate(-1, $response_message);
			}
		}else{
			$response_message = self::yii_api_echo('Account Type is not supported');
			self::terminate(-1, $response_message);
		}

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
		//$user_email = Yii::app()->request->getParam('email', '');
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
			$response_data = array("id"=>$user->id,"name"=>$user->name,"email"=>$user->email,"chat_id"=>$user->chat_id,"chat_pwd"=>$user->chat_pwd,
					"social_networks"=>$user_social_services_arr,"robots"=>$users_arr);
			self::success($response_data);
		}else{
			$response_message = self::yii_api_echo('APIException:UserAuthenticationFailed');
			self::terminate(-1, $response_message);
		}
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
		//$user_email = Yii::app()->request->getParam('email', '');
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
			self::terminate(-1, $response_message);
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
		//$user_email = Yii::app()->request->getParam('email', '');
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
			self::terminate(-1, $response_message);
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
		self::terminate(-1, $response_message);
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
					self::terminate(-1, $response_message);
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
			self::terminate(-1, $response_message);
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

		if(!AppHelper::is_valid_email($user_email)){
			$message = self::yii_api_echo("The email address you have provided does not appear to be a valid email address.");
			self::terminate(-1, $message);
		}
		$user_password = $_REQUEST['password'];
		$user_account_type = Yii::app()->request->getParam('account_type', '');
		$user_social_id = $_REQUEST['external_social_id'];

		if($user_account_type !== 'Native' && trim($user_social_id) == ''){
			$message = self::yii_api_echo("Missing parameter external_social_id in method user.create");
			self::terminate(-1, $message);
		}

		$user_social_additional_attributes = $_REQUEST['social_additional_attributes'];

		$user_encrypted_password = AppHelper::one_way_encrypt($user_password);

		if($user_account_type == 'Native' || $user_account_type == 'Facebook'){
			$user = User::model()->findByAttributes(array('email' => $user_email));
			if($user === null){
				$user_model = new User;

				$user_model->name = $user_name;
				$user_model->email = $user_email;

				$user_model->password = $user_encrypted_password;
				$user_model->reset_password = $user_encrypted_password;

				$chat_details = AppCore::create_chat_user_for_user();
				if(!$chat_details['jabber_status']){
					$message = self::yii_api_echo("User could not be created because jabber service is not responding.");
					self::terminate(-1, $message);
				}
				$user_model->chat_id = $chat_details['chat_id'];
				$user_model->chat_pwd = $chat_details['chat_pwd'];

				if(!$user_model->save()){
					//need to work
				}

				if($user_account_type == 'Native'){
					//nothing extra to do now
				}elseif($user_account_type == 'Facebook'){
					$social_service_type_model = Socialservicetype::model()->find('name=:name', array(':name' => "Facebook"));
					$social_service_type_id = $social_service_type_model->id;
					$social_auth_token = isset($user_social_additional_attributes['auth_token']) ? $user_social_additional_attributes['auth_token'] : '';

					$user_social_service_model = UsersSocialservice::model()->find('user_social_id=:fbid and id_socialservicetype=:sstid', array(':fbid' => $user_social_id, ':sstid'=>$social_service_type_id));
					if ($user_social_service_model == null){
						// Delete previus UsersSocialservice data and entry new UsersSocialservice data.
						//UsersSocialservice::model()->deleteAll('user_social_id=:fbid and id_socialservicetype=:sstid', array(':fbid' => $user_social_id, ':sstid'=>$social_service_type_id));

						$user_social_service_model = new UsersSocialservice;
						$user_social_service_model->id_socialservicetype = $social_service_type_id;
						$user_social_service_model->id_user = $user_model->id;
						$user_social_service_model->user_social_id = $user_social_id;
						$user_social_service_model->username = $user_social_id;
						$user_social_service_model->access_token = $social_auth_token;
						$user_social_service_model->expires_on = date('Y-m-d H:m:s', time()+15);
						$user_social_service_model->raw_data = array();

						if (!$user_social_service_model->save()) {
							//need to work
						}
					}else{
						//delete created user
						$user_model->delete();
						$response_message = self::yii_api_echo('This social information already exists.');
						self::terminate(-1, $response_message);
					}
				}

				$user_auth_token = AppCore::create_user_auth_token($user_model->id);
				$response_data = array();
				$response_data['success'] = true;
				$response_data['guid'] = $user_model->id;
				$response_data['user_handle'] = $user_auth_token;

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
						//AppHelper::dump($user_social_service_model->getErrors());
					}

					$response_data = array();
					$response_data['message'] = self::yii_api_echo("Merged");
					$user_auth_token = AppCore::create_user_auth_token($user->id);
					$response_data['success'] = true;
					$response_data['guid'] = $user->id;
					$response_data['user_handle'] = $user_auth_token;

					self::success($response_data);
				}else{
					$response_message = self::yii_api_echo('This email address has been already registered.');
					self::terminate(-1, $response_message);
				}
			}else{
				$response_message = self::yii_api_echo('This email address has been already registered.');
				self::terminate(-1, $response_message);
			}
		}else{
			$response_message = self::yii_api_echo('Account Type is NOT supported.');
			self::terminate(-1, $response_message);
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
					self::terminate(-1, $message);
				}
				switch ($key) {
					case "name":
						$user->name = $value;
						$user->save();
						break;
					case "facebook_external_social_id":
						self::update_facebook_social_service($user->id, $value);
						break;

					default:
						;
						break;
				}
			}
			self::success(1);
		}else{
			$response_message = self::yii_api_echo('APIException:UserAuthenticationFailed');
			self::terminate(-1, $response_message);
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
				//need to work
			}
		}else{
			$user_social_service_model->user_social_id = $user_social_id;
			$user_social_service_model->username = $user_social_id;
			$user_social_service_model->save();
		}
	}

}
