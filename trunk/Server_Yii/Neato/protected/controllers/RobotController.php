<?php

/**
 * This class deals with all the robot related operations.
 *
 */
class RobotController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * Displays a particular robot.
	 * @param integer $id the ID of the robot to be displayed
	 */
	public function actionView()
	{
		if (Yii::app()->user->getIsGuest()) {
			$url = $this->createUrl('robot/view',array('h'=>Yii::app()->request->getParam('h', '')));
			Yii::app()->user->setReturnUrl($url);
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}

		$h_id = Yii::app()->request->getParam('h', '');
		if($h_id == ''){
			$this->redirect(array('list'));
		}
		$id = AppHelper::two_way_string_decrypt($h_id);
		self::check_function_argument($id);
		
		$scroll_to = Yii::app()->request->getParam('scroll_to', '');
		
		$model =$this->loadModel($id);
		$isOnline = in_array($model->chat_id, AppCore::getOnlineUsers()); 
		 
		$this->render('view',array(
				'model'=>$model,
				'isOnline'=>$isOnline,
				'scroll_to'=>$scroll_to,
		));
	}

	/**
	 * Displays a particular robot for popup.
	 * @param integer $id the ID of the robot to be displayed
	 */
	public function actionPopupview()
	{
		$this->layout='//layouts/popup';
		$h_id = Yii::app()->request->getParam('h', '');
		if($h_id == ''){
			$this->redirect(array('list'));
		}
		$id = AppHelper::two_way_string_decrypt($h_id);
		self::check_function_argument($id);

		$this->render('robot_popup_view',array(
				'model'=>$this->loadModel($id),
		));
	}
	
	/**
	 * Creates a new robot.
	 * This method also creates Jabber user id and password for this newly generated robot.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd()
	{
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/robot/add');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();
		$model=new Robot;

		$this->performAjaxValidation($model);

		if(isset($_POST['Robot']))
		{
			$model->attributes=$_POST['Robot'];
			$chat_details = AppCore::create_chat_user_for_robot();

			if(!$chat_details['jabber_status']){
				$message = "Robot could not be created because jabber service in not responding.";
				Yii::app()->user->setFlash('warning', $message);
				throw new CHttpException(501, $message);
			}

			$model->chat_id = $chat_details['chat_id'];
			$model->chat_pwd = $chat_details['chat_pwd'];
			if($model->save()){
				$msg = AppCore::yii_echo("addrobot:ok", $model->serial_number);
				Yii::app()->user->setFlash('success', $msg);
				$this->actionList();
				Yii::app()->end();
			}else {
				$msg = "You may have entered wrong serial number or name.";
				Yii::app()->user->setFlash('error', $msg);
			}
		}

		$this->render('add',array(
				'model'=>$model,
		));
	}
	
	/**
	 * Updates a particular robot.
	 * If update is successful, the browser will be redirected to the 'view' page of the robot.
	 * @param integer $id the ID of the robot to be updated
	 */
	public function actionUpdate()
	{
		if (Yii::app()->user->getIsGuest()) {
			$url = $this->createUrl('robot/update',array('h'=>Yii::app()->request->getParam('h', '')));
			Yii::app()->user->setReturnUrl($url);
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}

		$h_id = Yii::app()->request->getParam('h', '');
		if($h_id == ''){
			$this->redirect(array('list'));
		}
		$id = AppHelper::two_way_string_decrypt($h_id);
		self::check_function_argument($id);
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Robot']))
		{
			$model->attributes=$_POST['Robot'];
			if($model->save()){
				$msg = AppCore::yii_echo("editrobot:ok",$model->serial_number);
				Yii::app()->user->setFlash('success', $msg);
				$this->redirect(array('list'));
			}else {
				$msg = "Updation failed.";
				Yii::app()->user->setFlash('error', $msg);
			}
		}

		$this->render('update',array(
				'model'=>$model,
		));
	}

	/**
	 * Deletes a set of robots that were selected by the user from the front end.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
		self::check_for_admin_privileges();
		if (isset($_REQUEST['chooseoption'])){
			foreach ($_REQUEST['chooseoption'] as $robo_id){
				$robot = Robot::model()->findByAttributes(array('id' => $robo_id));
				if($robot !== null ){
					$robot_map_id_arr = array();
					$robot_schedule_id_arr = array();
					foreach ($robot->robotMaps as $robot_map){
						$robot_map_id_arr[] = $robot_map->id;
					}
					foreach ($robot->robotSchedules as $robot_schedule){
						$robot_schedule_id_arr[] = $robot_schedule->id;
					}

					$chat_id = $robot->chat_id;
					if($robot->delete()){
						AppCore::delete_chat_user($chat_id);
						AppCore::delete_robot_map_data($robot_map_id_arr);
						AppCore::delete_robot_schedule_data($robot_schedule_id_arr);
						AppCore::delete_robot_atlas_data($robot->id);
					}
				}
			}

			$count = count($_REQUEST['chooseoption']);
			$message = AppCore::yii_echo("You have deleted %s robot successfully", $count);
			if ($count > 1){
				$message = AppCore::yii_echo("You have deleted %s robots successfully",$count);
			}
			Yii::app()->user->setFlash('success', $message);
		}else{
			Yii::app()->user->setFlash('error', AppCore::yii_echo("No robot selected to delete"));
		}
		$this->redirect(Yii::app()->request->baseUrl.'/robot/list');
	}
	
	/**
	 * Lists all robots.
	 */
	public function actionList()
	{
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/robot/list');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();
		$robot_data = Robot::model()->findAll();
		$this->render('list',array(
				'robot_data'=>$robot_data,
		));
	}

	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Robot::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='robot-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * Finds the latest file for robot map and robot schedule for download.
	 * @param integer the ID of the model to be downloaded.
	 * @param string type to check xml or blob.
	 * @param string for to check map or schedule.
	 */
	public function actionDownloadLatestFile(){

		$for_e = Yii::app()->request->getParam('for', '');
		$requested_for = AppHelper::two_way_string_decrypt($for_e);
		self::check_function_argument($requested_for);

		$type_e = Yii::app()->request->getParam('type', '');
		$type = AppHelper::two_way_string_decrypt($type_e);
		self::check_function_argument($type);

		$id_e = Yii::app()->request->getParam('data_id', '');
		$id = AppHelper::two_way_string_decrypt($id_e);
		self::check_function_argument($id);
		

		$full_file_path = '';
		switch ($requested_for) {
			case 'map' :
				$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
				$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-data-directory-name']. DIRECTORY_SEPARATOR . $id;
				$uploads_dir = $uploads_dir_for_robot . DIRECTORY_SEPARATOR . $type;

				$robot_map_model = RobotMap::model()->findByAttributes(array('id' => $id));
				if($robot_map_model !== null ){
					if($type === Yii::app()->params['robot-xml-data-directory-name']){
						$file_name= $robot_map_model->xml_data_file_name;
					}else if($type === Yii::app()->params['robot-blob-data-directory-name']){
						$file_name= $robot_map_model->blob_data_file_name;
					}

					$full_file_path = $uploads_dir. DIRECTORY_SEPARATOR . $file_name;
				}
				break;
			case 'schedule' :
				$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
				$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-schedule_data-directory-name']. DIRECTORY_SEPARATOR . $id;
				$uploads_dir = $uploads_dir_for_robot . DIRECTORY_SEPARATOR . $type;

				$robot_schedule_model = RobotSchedule::model()->findByAttributes(array('id' => $id));
				if($robot_schedule_model !== null ){
					if($type === Yii::app()->params['robot-schedule_xml-data-directory-name']){
						$file_name= $robot_schedule_model->xml_data_file_name;
					}else if($type === Yii::app()->params['robot-schedule_blob-data-directory-name']){
						$file_name= $robot_schedule_model->blob_data_file_name;
					}
					$full_file_path = $uploads_dir. DIRECTORY_SEPARATOR . $file_name;
				}
				break;

			case 'atlas' :
				$id_robot = Yii::app()->request->getParam('id_robot', '');
				$id_robot = AppHelper::two_way_string_decrypt($id_robot);
				self::check_function_argument($id_robot);
				
				$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
				$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-atlas-data-directory-name']. DIRECTORY_SEPARATOR . $id_robot;
				$uploads_dir = $uploads_dir_for_robot . DIRECTORY_SEPARATOR . $type;
					
				$robot_atlas_model = RobotAtlas::model()->findByAttributes(array('id' => $id));
				if($robot_atlas_model !== null ){
					if($type === Yii::app()->params['robot-atlas-xml-data-directory-name']){
						$file_name= $robot_atlas_model->xml_data_file_name;
					}
					$full_file_path = $uploads_dir. DIRECTORY_SEPARATOR . $file_name;
				}
				break;
			
			case 'atlasGridImage' :
				$id_robot = Yii::app()->request->getParam('id_robot', '');
				$id_robot = AppHelper::two_way_string_decrypt($id_robot);
				self::check_function_argument($id_robot);
				
				$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
				$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-atlas-data-directory-name']. DIRECTORY_SEPARATOR . $id_robot;
				$uploads_dir = $uploads_dir_for_robot . DIRECTORY_SEPARATOR . $type;

				
				$grid_image_model = AtlasGridImage::model()->findByAttributes(array('id' => $id));
				
				if($grid_image_model !== null ){
					
					if($type === Yii::app()->params['robot-atlas-blob-data-directory-name']){
						
						$file_name= $grid_image_model->blob_data_file_name;
					}
					$full_file_path = $uploads_dir. DIRECTORY_SEPARATOR . $file_name;
				}
				break;
			
				
			default :
		}

		self::actionDownloadFile($full_file_path) ;
	}

	/**
	 * Returns the file that was requested by the browser.
	 * While setting the content type for the browser, it checks for the file extension.
	 * @param string $fullPath.
	 */
	public function actionDownloadFile($fullPath){
		if(headers_sent()){
			die('Headers Sent');
		}
		// Required for some browsers
		if(ini_get('zlib.output_compression')){
			ini_set('zlib.output_compression', 'Off');
		}

		// File Exists?
		if(file_exists($fullPath)){

			// Parse Info / Get Extension
			$fsize = filesize($fullPath);
			$path_parts = pathinfo($fullPath);
			$ext = strtolower($path_parts["extension"]);

			// Determine Content Type
			switch ($ext) {
				case "pdf": $ctype="application/pdf"; break;
				case "exe": $ctype="application/octet-stream"; break;
				case "zip": $ctype="application/zip"; break;
				case "doc": $ctype="application/msword"; break;
				case "xls": $ctype="application/vnd.ms-excel"; break;
				case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
				case "gif": $ctype="image/gif"; break;
				case "png": $ctype="image/png"; break;
				case "jpeg":
				case "jpg": $ctype="image/jpg"; break;
				default: $ctype="application/force-download";
			}

			header("Pragma: public"); // required
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false); // required for certain browsers
			header("Content-Type: $ctype");
			header("Content-Disposition: attachment; filename=\"".basename($fullPath)."\";" );
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".$fsize);
			error_reporting(0);
			ini_set("display_errors",0);
			ob_clean();
			flush();
			readfile( $fullPath );

		} else
			die('File Not Found');

	}
}
