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
		$h_id = Yii::app()->request->getParam('h', AppHelper::two_way_string_encrypt(Yii::app()->user->id));
		$id = AppHelper::two_way_string_decrypt($h_id);
		self::check_function_argument($id);
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/user/userprofile');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}

		$this->render('userprofile',array(
				'model'=>$this->loadModel($id),
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
				$user_model->is_admin = $_POST['UserAddForm']['is_admin'];

				$user_model->password = $encrypted_pass_word;
				$user_model->reset_password = $encrypted_pass_word;
					
				$chat_details = AppCore::create_chat_user_for_user();

				if(!$chat_details['jabber_status']){
					$message = "User could not be created because jabber service in not responding.";
					Yii::app()->user->setFlash('warning', $message);
					throw new CHttpException(501, $message);
				}

				$user_model->chat_id = $chat_details['chat_id'];
				$user_model->chat_pwd = $chat_details['chat_pwd'];
					
				if($user_model->save()){
					$msg = AppCore::yii_echo("adduser:ok",$user_name);
					Yii::app()->user->setFlash('success', $msg);
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

	/**
	 * Displays the login page
	 */
	public function actionLogin()
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
				$message = AppCore::yii_echo("You have been logged in.");
				Yii::app()->user->setFlash('success', $message);
				if(Yii::app()->user->isAdmin){
					//$this->redirect(Yii::app()->user->returnUrl);
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
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * Registers a new user.
	 * This method also creates Jabber user id and password for this newly registered user.
	 * If creation is successful, the browser will be redirected to the 'userprofile' page.
	 *
	 */
	public function actionRegister()
	{
		if (!Yii::app()->user->getIsGuest()) {
			$this->redirect(Yii::app()->user->returnUrl);
		}
		$registration_model = new RegisterForm();

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($registration_model, 'register-form');

		if(isset($_POST['RegisterForm']))
		{
			$user_name = $_POST['RegisterForm']['name'];
			$email = $_POST['RegisterForm']['email'];
			$new_password = $_POST['RegisterForm']['password'];
			$registration_model->attributes=$_POST['RegisterForm'];
			if ($registration_model->validate()) {
				$user_model = new User();
					
				$pass_word = $registration_model->password;
				$encrypted_pass_word = AppHelper::one_way_encrypt($pass_word);

				$user_model->name = $registration_model->name;
				$user_model->email = $registration_model->email;
					
				$user_model->password = $encrypted_pass_word;
				$user_model->reset_password = $encrypted_pass_word;
					
				$chat_details = AppCore::create_chat_user_for_user();

				if(!$chat_details['jabber_status']){
					$message = "User could not be created because jabber service in not responding.";
					Yii::app()->user->setFlash('warning', $message);
					throw new CHttpException(501, $message);
				}

				$user_model->chat_id = $chat_details['chat_id'];
				$user_model->chat_pwd = $chat_details['chat_pwd'];
				$login_link = $this->createUrl("/user/login");
				if($user_model->save()){
					AppEmail::emailWelcomeNewUser($email, $user_name, $new_password, $login_link);
					$msg = AppCore::yii_echo("registeruser:ok",$user_name);
					Yii::app()->user->setFlash('success', $msg);
					$registration_model->login();
					$this->redirect(array('userprofile'));
				}else {
					$msg = AppCore::yii_echo("Registration failed.");
					Yii::app()->user->setFlash('error', $msg);
				}
			}
		}
		$this->render('register',array(
				'model'=>$registration_model
		));
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
					AppCore::delete_chat_user($chat_id);
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
							AppCore::delete_chat_user($chat_id);
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
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/user/list');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();

		$users_data = User::model()->findAll();
		$this->render('list',array(
				'users_data'=>$users_data,
		));
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
						AppEmail::emailForgotPassword($email, $user_name, $new_password, $login_link);
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
			AppEmail::emailResetPassword($email, $user_name, $new_password, $login_link);
			Yii::app()->user->setFlash('success', AppCore::yii_echo("You have successfully reset password of user %s.",$user_name));
			$this->redirect($this->createUrl('user/userprofile',array('h'=>$h_id)));
		}
	}


	/**
	 * Redirects to change_paasword.
	 */
	public function actionChangePassword()
	{
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
						AppEmail::emailChangePassword($email, $user_name, $new_password, $login_link);
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

}