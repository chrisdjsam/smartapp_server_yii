<?php

/**
 * This class deals with all the user related operations.
 *
 */
class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
				'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Displays a particular user.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionUserprofile()
	{
		if(Yii::app()->user->UserRoleId == '2'){
			$this->layout = 'support';
		}
		$h_id = Yii::app()->request->getParam('h', AppHelper::two_way_string_encrypt(Yii::app()->user->id));
		$id = AppHelper::two_way_string_decrypt($h_id);

		$h = Yii::app()->request->getParam('h', false);
		$url = $this->createUrl('user/userprofile');
		if ($h){
			$url = $this->createUrl('user/userprofile',array('h'=>Yii::app()->request->getParam('h', '')));
		}
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl($url);
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}

		self::check_function_argument($id);

		$update_user = $this->loadModel($id);

		$update_user_data_flag = isset($_POST['update_user_data_flag']) ? $_POST['update_user_data_flag'] : 'N' ;

		if($update_user_data_flag == 'Y') {
			if(Yii::app()->user->UserRoleId != '2'){
				$name = isset($_POST['User']['name']) ? $_POST['User']['name'] : '' ;
				$update_user->name = $name;
			}

			$alternate_email = isset($_POST['User']['alternate_email']) ? $_POST['User']['alternate_email'] : '';
			$country_code = $_POST['CountryCodeList']['iso2'];
			$opt_in = $_POST['User']['opt_in'];
			$country_lang = AppCore::getCountryLanguage($country_code);

			if($opt_in == 1){
				$country_allow = 'true';
			}else{
				$country_allow = 'false';
			}

			$update_user->alternate_email = $alternate_email;

			$update_user->country_code = $country_code;
			$update_user->opt_in = $opt_in;
			$update_user->language = $country_lang;

			$extra_param = array();
			$extra_param['country_code'] = $country_code;
			$extra_param['opt_in'] = $country_allow;

			$update_user->extram_param = json_encode($extra_param);

			if(Yii::app()->user->isAdmin){
				$is_validated = $_POST['is_validated'];
				$update_user->is_validated = $is_validated;
			}

			if($update_user->save()){
				$message = "Profile updated successfully.";
				Yii::app()->user->setFlash('success', $message);
				$this->redirect($url);
			}

		}

		$model = $this->loadModel($id);

		$country_code_data = CountryCodeList::model()->find('iso2 = :iso2', array(':iso2' => $update_user->country_code));

		// next two line temp changes model data as we use widget in page to show data
		$model->country_code = $country_code_data->short_name;
		$model->opt_in = $update_user->opt_in ? 'Yes' : 'No';


		$this->render('userprofile',array(
				'model'=>$model,
				'update_user'=>$update_user,
		));
	}

	/**
	 * Displays a particular user for popup.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionUserprofilepopup()
	{
		$this->layout='//layouts/popup';
		$h_id = Yii::app()->request->getParam('h', AppHelper::two_way_string_encrypt(Yii::app()->user->id));
		$id = AppHelper::two_way_string_decrypt($h_id);
		self::check_function_argument($id);
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/user/userprofile');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}

		$this->render('userprofile_pop_up',array(
				'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new user.
	 * This method also creates Jabber user id and password for this newly generated user.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd()
	{
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/user/add');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();
		$user_add_model = new UserAddForm();

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($user_add_model, 'user-form');

		if(isset($_POST['UserAddForm']))
		{
			$user_name = $_POST['UserAddForm']['name'];
			$user_add_model->attributes = $_POST['UserAddForm'];
			if ($user_add_model->validate()) {
				$user_model = new User();

				$pass_word = $user_add_model->password;
				$encrypted_pass_word = AppHelper::one_way_encrypt($pass_word);

				$user_model->name = $user_add_model->name;
				$user_model->email = $user_add_model->email;

				$user_role = Yii::app ()->request->getParam ( 'user_role', '' );

				if($user_role == '-1'){
					Yii::app()->user->setFlash('error', "Please select user role");
					$this->render('add',array(
							'model'=>$user_add_model,
					));
					return;
				}

				$is_admin = '0';
				if($user_role == '1' || $user_role == '2'){
					$is_admin = '1';
				}

				$user_model->is_admin = $is_admin;

				$user_model->password = $encrypted_pass_word;
				$user_model->reset_password = $encrypted_pass_word;

				$chat_details = UserCore::create_chat_user_for_user();

				if(!$chat_details['jabber_status']){
					$message = "User could not be created because jabber service in not responding.";
					Yii::app()->user->setFlash('warning', $message);
					throw new CHttpException(501, $message, APIConstant::UNAVAILABLE_JABBER_SERVICE);
				}

				$user_model->chat_id = $chat_details['chat_id'];
				$user_model->chat_pwd = $chat_details['chat_pwd'];

				if($user_model->save()){

					$user_role_obj = new UserRole();
					$user_role_obj->user_id = $user_model->id;
					$user_role_obj->user_role_id = $user_role;
					if(!$user_role_obj->save()){
						Yii::app()->user->setFlash('success', 'user role not saved');
						$this->render('add',array(
								'model'=>$user_add_model,
						));
					}

					$msg = AppCore::yii_echo("adduser:ok",$user_name);
					Yii::app()->user->setFlash('success', $msg);

					UserCore::setDefaultUserPushNotificationOptions($user_model->id);
				}
				$this->redirect(array('list'));
			}else {
				$msg = AppCore::yii_echo("You may have entered wrong Email Id or Password.");
				Yii::app()->user->setFlash('error', $msg);
			}
		}

		$this->render('add',array(
				'model'=>$user_add_model,
		));
	}


	public function actionSupportLogin(){

		$this->layout = 'support';
		if (!Yii::app()->user->getIsGuest()) {
			$this->redirect(Yii::app()->user->returnUrl);
		}
		$login_model=new LoginForm;

		// Uncomment the following line if AJAX validation is needed

		// $this->performAjaxValidation($login_model, 'login-form');

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$password = $_POST['LoginForm']['password'];
			$encrypted_password = AppHelper::one_way_encrypt($password);
			$user_model_for_find_admin = User::model()->findByAttributes(array("email"=>$_POST['LoginForm']['email'],"password" => $encrypted_password, 'is_admin' => 1));

			if (Yii::app()->params['is_wp_enabled'] == true && empty($user_model_for_find_admin)) {
				$is_wpuser = self::is_wpuser();
				if (isset($is_wpuser->posts->data)) {
					$_POST['LoginForm']['email'] = $is_wpuser->posts->data->user_login;
				}

				if(isset($is_wpuser->posts->errors)){
					$_POST['errors']  = $is_wpuser->posts->errors;
					$error_result=$login_model->wpAuthenticateError();
				}
			}
			$login_model->attributes=$_POST['LoginForm'];
			if(!isset($is_wpuser->posts->errors)){
				// validate user input and redirect to the userprofile page if valid
				if($login_model->validate() && $login_model->login())
				{
					if(Yii::app()->user->UserRoleId == '3'){
						$this->redirect(array('unavailable'));
					}

					Yii::app()->session['cause_agent_id'] = UniqueToken::hash(time(), 8);

					$is_validated = (boolean)Yii::app()->user->isValidated;
					$message = 'You have been logged in Successfully.';

					$grace_period = UserCore::getGracePeriod();

					if(!$is_validated){

						$user_created_on_timestamp = strtotime(Yii::app()->user->createdOn);

						$current_system_timestamp = time();

						$time_diff = ($current_system_timestamp - $user_created_on_timestamp) / 60;

						if($time_diff < $grace_period){

							$message = "You have been logged in Successfully. Please validate your email.";

						} else {
							Yii::app()->user->logout();
							$message = "Sorry, Please validate your email first and then login again.";
							Yii::app()->user->setFlash('error', $message);
							$this->render('login',array('model'=>$login_model));
							exit();

						}

					}
					Yii::app()->user->setFlash('success', $message);
					if(Yii::app()->user->isAdmin){
						$this->redirect(array('/robot/list'));
					}else{
						$this->redirect(array('userprofile'));
					}
					echo $this->redirect(Yii::app()->user->returnUrl);
					$this->redirect(Yii::app()->user->returnUrl);

				}else{

					Yii::app()->user->setFlash('error', AppCore::yii_echo("We could not log you in. Please check your email and password."));
				}
			}
		}
		// display the login form
		$this->render('supportlogin',array('model'=>$login_model));
	}

	public function actionLogin(){
		$this->actionSupportLogin();
	}

	public function is_wpuser(){

		$wp_user = $_POST['LoginForm'];
		$url = Yii::app()->params['wordpress_api_url'].'?json=login';
		$headers = array();
		$data_string = array();
		$data_string['log'] = $wp_user['email'];
		$data_string['pwd'] = urlencode($wp_user['password']);
		$data_string['rememberme'] = $wp_user['rememberMe'];

		$result = json_decode(AppHelper::curl_call($url, $headers, $data_string));

		$user_email = isset($result->posts->data) ? $result->posts->data->user_login : '';
		$is_user_registered = User::model()->findByAttributes(array("email" => $user_email));

		$user_extram_param = array();
		$user_extram_param['country_code'] = 'US';
		$user_extram_param['opt_in'] = 'true';

		if(isset($result->posts->data) && !isset($is_user_registered)){

			$user_pass = $result->posts->data->user_pass;
			$encrypted_pass_word = AppHelper::one_way_encrypt($wp_user['password']);

			$save_user_from_wp  = New User();

			$save_user_from_wp->name = $result->posts->data->user_login;
			$save_user_from_wp->email = $result->posts->data->user_login;
			$save_user_from_wp->alternate_email = $result->posts->data->user_email;
			$save_user_from_wp->country_code = $user_extram_param['country_code'];
			$save_user_from_wp->opt_in = '1';
			$save_user_from_wp->extram_param = json_encode($user_extram_param);
			$save_user_from_wp->wp_id = isset($result->posts->data)? $result->posts->data->ID : '';
			$chat_details = UserCore::create_chat_user_for_user();
			if(!$chat_details['jabber_status']){
				$message = "User could not be created because jabber service in not responding.";
				Yii::app()->user->setFlash('warning', $message);
				throw new CHttpException(501, $message, APIConstant::UNAVAILABLE_JABBER_SERVICE);
			}

			$save_user_from_wp->password = $encrypted_pass_word;
			$save_user_from_wp->reset_password = $encrypted_pass_word;
			$save_user_from_wp->chat_id = $chat_details['chat_id'];
			$save_user_from_wp->chat_pwd = $chat_details['chat_pwd'];

			if($save_user_from_wp->save()){

				// update extra attribute of user
				$user_id = $save_user_from_wp->id;
				$validation_key = md5($user_id.'_'.$result->posts->data->user_email);

				$save_user_from_wp->validation_key =  $validation_key;
				$save_user_from_wp->is_validated = 1;

				$save_user_from_wp->validation_counter =  1;

				if(!$save_user_from_wp->save()){
					//TODO
				}

			}else{

			}
		}else if(isset($result->posts->data) && isset($is_user_registered)){
			$is_user_registered->password = AppHelper::one_way_encrypt($wp_user['password']);
			if(!$is_user_registered->update()){
				//TODO
			}

		}
		return $result;
	}

	/**
	 * Displays the login page
	 */
	public function actionLoginOld()
	{
		if (!Yii::app()->user->getIsGuest()) {
			$this->redirect(Yii::app()->user->returnUrl);
		}
		$login_model=new LoginForm;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($login_model, 'login-form');

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$login_model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the userprofile page if valid
			if($login_model->validate() && $login_model->login())
			{

				Yii::app()->session['cause_agent_id'] = UniqueToken::hash(time(), 8);

				$is_validated = (boolean)Yii::app()->user->isValidated;
				$message = 'You have been logged in Successfully.';

				$grace_period = UserCore::getGracePeriod();

				if(!$is_validated){

					$user_created_on_timestamp = strtotime(Yii::app()->user->createdOn);

					$current_system_timestamp = time();

					$time_diff = ($current_system_timestamp - $user_created_on_timestamp) / 60;

					if($time_diff < $grace_period){

						$message = "You have been logged in Successfully. Please validate your email.";

					} else {

						Yii::app()->user->logout();
						$message = "Sorry, Please validate your email first and then login again.";
						Yii::app()->user->setFlash('error', $message);
						$this->render('login',array('model'=>$login_model));
						exit();

					}

				}

				Yii::app()->user->setFlash('success', $message);
				if(Yii::app()->user->isAdmin){
					$this->redirect(array('list'));
				}else{
					$this->redirect(array('userprofile'));
				}
				echo $this->redirect(Yii::app()->user->returnUrl);
				$this->redirect(Yii::app()->user->returnUrl);

			}else{
				Yii::app()->user->setFlash('error', AppCore::yii_echo("We could not log you in. Please check your email and password."));
			}
		}
		// display the login form
		$this->render('login',array('model'=>$login_model));
	}


	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$msg = AppCore::yii_echo("You have been logged out");
		Yii::app()->user->setFlash('success', $msg);
		if(Yii::app()->user->UserRoleId == '2'){
			$this->redirect('SupportLogin');
		}else{
			$this->redirect(Yii::app()->homeUrl);
		}

	}

	/**
	 * Not available screen.
	 */
	public function actionUnavailable()
	{
		Yii::app()->user->logout();
		$this->render('unavailable');
	}

	/**
	 * Registers a new user.
	 * This method also creates Jabber user id and password for this newly registered user.
	 * If creation is successful, the browser will be redirected to the 'userprofile' page.
	 *
	 */
	public function actionRegister()
	{
		$this->actionSupportLogin();
	}

	/**
	 * Deletes a set of users that were selected by the admin from the front end.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the user to be deleted
	 */
	public function actionDelete()
	{

		self::check_for_admin_privileges();

		if(isset($_REQUEST['h'])){
			$h_id = Yii::app()->request->getParam('h', '');
			if($h_id == ''){
				$this->redirect(array('list'));
			}
			$user_id = AppHelper::two_way_string_decrypt($h_id);

			$user_model = User::model()->findByAttributes(array('id' => $user_id));

			if($user_model !== null ){
				$chat_id = $user_model->chat_id;
				if($user_model->delete()){
					RobotCore::delete_chat_user($chat_id);
					$message = AppCore::yii_echo("You have deleted a user successfully");
					Yii::app()->user->setFlash('success', $message);
				}
			}

		}else {
			if (isset($_REQUEST['chooseoption'])){
				foreach ($_REQUEST['chooseoption'] as $user_id){
					$user_model = User::model()->findByAttributes(array('id' => $user_id));
					if($user_model !== null ){
						$chat_id = $user_model->chat_id;
						if($user_model->delete()){
							RobotCore::delete_chat_user($chat_id);
						}
					}
				}

				$count = count($_REQUEST['chooseoption']);
				$message = AppCore::yii_echo("You have deleted %s user successfully", $count);
				if ($count > 1){
					$message = AppCore::yii_echo("You have deleted %s users successfully",$count);
				}
				Yii::app()->user->setFlash('success', $message);
			}else{
				Yii::app()->user->setFlash('error', AppCore::yii_echo("No user selected to delete"));
			}
		}
		$this->redirect(Yii::app()->request->baseUrl.'/user/list');
	}

	/**
	 * Lists all users.
	 */
	public function actionList()
	{
		if(Yii::app()->user->UserRoleId == '2'){
			$this->layout = 'support';
		}
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/user/list');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();

		$this->render('list');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model, $form_id)
	{
		if(isset($_POST['ajax']) && $_POST['ajax'] === $form_id)
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * Redirects to forgot_paasword.
	 */
	public function actionForgotpassword()
	{
		if (!Yii::app()->user->getIsGuest()) {
			$this->redirect(Yii::app()->user->returnUrl);
		}
		$forgotPassword_model = new ForgotPasswordForm();

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($forgotPassword_model, 'forgotpassword-form');

		if(isset($_POST['ForgotPasswordForm']))
		{
			$forgotPassword_model->attributes=$_POST['ForgotPasswordForm'];

			if ($forgotPassword_model->validate()) {
				$email = trim($_POST['ForgotPasswordForm']['email']);
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

						Yii::app()->user->setFlash('success', AppCore::yii_echo("New password is sent to your email."));
						$this->redirect("login");
					}
				}else{
					Yii::app()->user->setFlash('error', AppCore::yii_echo("Email does not exist."));
					$forgotPassword_model->addError("email", "Please enter valid email.");
				}
			}
		}
		$this->render('forgotpassword',array(
				'model'=>$forgotPassword_model
		));
	}

	/**
	 * Redirects to forgot_paasword.
	 */
	public function actionSupportForgotpassword()
	{
		$this->layout = 'support';
		if (!Yii::app()->user->getIsGuest()) {
			$this->redirect(Yii::app()->user->returnUrl);
		}
		$forgotPassword_model = new ForgotPasswordForm();

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($forgotPassword_model, 'forgotpassword-form');

		if(isset($_POST['ForgotPasswordForm']))
		{
			$forgotPassword_model->attributes=$_POST['ForgotPasswordForm'];

			if ($forgotPassword_model->validate()) {
				$email = trim($_POST['ForgotPasswordForm']['email']);
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

						Yii::app()->user->setFlash('success', AppCore::yii_echo("New password is sent to your email."));
						$this->redirect("login");
					}
				}else{
					Yii::app()->user->setFlash('error', AppCore::yii_echo("Email does not exist."));
					$forgotPassword_model->addError("email", "Please enter valid email.");
				}
			}
		}
		$this->render('supportforgotpassword',array(
				'model'=>$forgotPassword_model
		));
	}


	/**
	 * Resets users password.
	 * @param integer the ID of the model to be reset.
	 */
	public function actionResetpassword()
	{
		self::check_for_admin_privileges();
		$h_id = Yii::app()->request->getParam('h', AppHelper::two_way_string_encrypt(Yii::app()->user->id));
		$user_id = AppHelper::two_way_string_decrypt($h_id);
		$user_model = User::model()->findByAttributes(array("id" => $user_id));
		$new_password = AppHelper::generateRandomString();
		$encrypted_new_password  = AppHelper::one_way_encrypt($new_password);

		$user_model->reset_password = $encrypted_new_password;
		$user_model->password = $encrypted_new_password;
		$user_name = $user_model->name;
		$email = $user_model->email;
		$login_link = $this->createUrl("/user/login");
		if($user_model->save()){

			$country_lang = $user_model->language;
			$alternate_email = $user_model->alternate_email;
			if(!empty($alternate_email)){
				AppEmail::emailChangePassword($email, $user_name, $new_password, $login_link, $alternate_email, $country_lang);
			} else {
				AppEmail::emailChangePassword($email, $user_name, $new_password, $login_link, '', $country_lang);
			}

			Yii::app()->user->setFlash('success', AppCore::yii_echo("You have successfully reset password of user %s.",$user_name));
			$this->redirect($this->createUrl('user/userprofile',array('h'=>$h_id)));
		}
	}


	/**
	 * Redirects to change_paasword.
	 */
	public function actionChangePassword()
	{
		if(Yii::app()->user->UserRoleId == '2'){
			$this->layout = 'support';
		}
		if (Yii::app()->user->getIsGuest()) {
			$this->redirect(Yii::app()->user->returnUrl);
		}

		$changePassword_model = new ChangePasswordForm();

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($changePassword_model, 'changepassword-form');

		if(isset($_POST['ChangePasswordForm']))
		{
			$changePassword_model->attributes = $_POST['ChangePasswordForm'];

			if ($changePassword_model->validate()) {
				$password = $_POST['ChangePasswordForm']['password'];
				$encrypted_password = AppHelper::one_way_encrypt($password);
				$user_model = User::model()->findByAttributes(array("email"=>Yii::app()->user->email,"password" => $encrypted_password));
				if($user_model != null){
					$new_password = $_POST['ChangePasswordForm']['newpassword'];
					$encrypted_new_password = AppHelper::one_way_encrypt($new_password);

					$user_model->password = $encrypted_new_password;
					$user_model->reset_password = $encrypted_new_password;
					$email = trim($user_model->email);
					$user_name = $user_model->name;
					$login_link = $this->createUrl("/user/login");
					if($user_model->save()){

						$country_lang = $user_model->language;
						$alternate_user_email = trim($user_model->alternate_email);
						if (!empty($alternate_user_email)) {
							AppEmail::emailChangePassword($email, $user_name, $new_password, $login_link, $alternate_user_email, $country_lang);
						} else {
							AppEmail::emailChangePassword($email, $user_name, $new_password, $login_link, '', $country_lang);
						}

						Yii::app()->user->setFlash('success', AppCore::yii_echo("Your password is changed successfully."));
						$this->redirect("userprofile");
					}
				}else{
					Yii::app()->user->setFlash('error', AppCore::yii_echo("Failed to change password."));
					$changePassword_model->addError("password", "Please enter valid password.");
				}
			}
		}

		$this->render('changepassword',array(
				'model'=>$changePassword_model
		));
	}

	public function actionValidateEmail() {
		
		if(isset(Yii::app()->theme->name) && Yii::app()->theme->name == AppConstant::THEME_BASIC){
			$this->layout = 'landing_page';
		}
				
		$validation_key = Yii::app()->request->getParam('k', '');
		$is_user_active = 'N';

		$data = User::model()->find('validation_key = :validationKey', array(':validationKey' => $validation_key));
		if (!empty($data)) {

			$data->is_validated = 1;
			$data->validation_counter = 0;

			if ($data->save()) {

				Yii::app()->user->id = $data->id;
				$is_user_active = 'Y';

			}

		}
		if(isset(Yii::app()->theme->name) && Yii::app()->theme->name == AppConstant::THEME_BASIC){
			$this->render('vorwerk_validated', array('is_user_active' => $is_user_active));
		}else{
			$this->render('validated', array('is_user_active' => $is_user_active));
		}

	}

	public function actionUpdateUserCountry() {
		$userData = User::model()->findAll();
		foreach ($userData as $user){
			$userToSave = User::model()->findByPk($user->id);
			$country_code_data = CountryCodeList::model()->find('iso2 = :iso2', array(':iso2' => $userToSave->country_code));
			if(!$country_code_data){
				$extram_param = json_decode($userToSave->extram_param);
				$extram_param->country_code = 'DE';
				$extram_param = json_encode($extram_param);
				$userToSave->country_code = 'DE';
				$userToSave->extram_param = $extram_param;
				$userToSave->save();
			}
		}
		AppHelper::dump("Done");
	}

	public function actionUpdateUserLanguage() {
		$userData = User::model()->findAll();
		foreach ($userData as $user){
			$userToSave = User::model()->findByPk($user->id);
			$country_code_data = CountryCodeList::model()->find('iso2 = :iso2', array(':iso2' => $userToSave->country_code));
			if($country_code_data){
				$userToSave->language = $country_code_data->language;
				$userToSave->save();
			}
		}
		AppHelper::dump("Done");
	}


}
