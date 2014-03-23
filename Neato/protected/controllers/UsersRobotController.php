<?php
/**
 * This controller class manages all the user and robots related interactions.
 *
 */
class UsersRobotController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * Displays a all the robot associations.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView()
	{
		$h_id = Yii::app()->request->getParam('h', '');
		if($h_id == ''){
			$this->redirect(array('list'));
		}
		$id = AppHelper::two_way_string_decrypt($h_id);
		self::check_function_argument($id);

		$this->render('view',array(
				'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new user robot association.
	 * If association is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd()
	{
		if(Yii::app()->user->UserRoleId == '2'){
			$this->layout = 'support';
		}
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/UsersRobot/add');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();
		$model=new UsersRobot;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['UsersRobot']))
		{
			$model->attributes = $_POST['UsersRobot'];

			$is_valide = true;
			if(!$_POST['UsersRobot']['id_user']){
				$is_valide = false;
				$model->addError("id_user", "Email can not be blank.");
			}
			if(!$_POST['UsersRobot']['id_robot']){
				$is_valide = false;
				$model->addError("id_robot", "Serial number can not be blank.");
			}

			if($is_valide){
				$user_id = trim($_POST['UsersRobot']['id_user']);
				$robot_id = trim($_POST['UsersRobot']['id_robot']);
				$user_robot_model = UsersRobot::model()->findByAttributes(array("id_user" => $user_id, "id_robot" => $robot_id));
				$user_robot_model_by_robot = UsersRobot::model()->findByAttributes(array("id_robot" => $robot_id));
				if(! is_null($user_robot_model)){
					$msg = AppCore::yii_echo("User robot association already exists.");
					Yii::app()->user->setFlash('error', $msg);
				}else if(! is_null($user_robot_model_by_robot)){
					$msg = AppCore::yii_echo("Robot already has a user associated with it.");
					Yii::app()->user->setFlash('error', $msg);
				}else{
					if($model->save()){
						$msg = AppCore::yii_echo("You have successfully created user robot association.");
						Yii::app()->user->setFlash('success', $msg);
						$this->redirect(array('list'));
					}else {
						$msg = "Failed to create user robot association.";
						Yii::app()->user->setFlash('error', $msg);
					}
				}
			}else{
				$msg = "Failed to create user robot association.";
				Yii::app()->user->setFlash('error', $msg);
			}
		}

		$this->render('add',array(
				'model'=>$model,
		));
	}

	/**
	 * Updates a particular user robot association.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UsersRobot']))
		{
			$model->attributes=$_POST['UsersRobot'];
			if($model->save()){

				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('update',array(
				'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
		self::check_for_admin_privileges();
		if (isset($_REQUEST['chooseoption'])){
			foreach ($_REQUEST['chooseoption'] as $user_robo_id){
				UsersRobot::model()->deleteByPk($user_robo_id);
			}

			$count = count($_REQUEST['chooseoption']);
			$message = AppCore::yii_echo("You have deleted %s user and robot association successfully", $count);
			if ($count > 1){
				$message = AppCore::yii_echo("You have deleted %s user and robot associations successfully",$count);
			}
			Yii::app()->user->setFlash('success', $message);
		}else{
			Yii::app()->user->setFlash('error', AppCore::yii_echo("No user and robot association selected to delete"));
		}
		$this->redirect(Yii::app()->request->baseUrl.'/usersRobot/list');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('UsersRobot');
		$this->render('index',array(
				'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionList()
	{
		if(Yii::app()->user->UserRoleId == '2'){
			$this->layout = 'support';
		}
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/UsersRobot/list');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();
		$users_robots = UsersRobot::model()->findAll();
		$this->render('list',array(
				'users_robots'=>$users_robots,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=UsersRobot::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-robot-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
