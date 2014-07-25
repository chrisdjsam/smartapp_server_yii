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
		if(Yii::app()->user->UserRoleId == '2'){
			$this->layout = 'support';
		}
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

		$isOnline = 2; // 2 for offline
		if(RobotCore::jabberOnline($model->chat_id)){
			$isOnline = 1; // 1 for online
		}
// 		else {

// 			$sleep_lag_time = RobotCore::getSleepLagTime($model);
// 			$robot_ping_interval = $sleep_lag_time['sleep_time'];

// 			if(AppCore::getVirtuallyOnlinRobots($model->serial_number, $robot_ping_interval)){
// 				$isOnline = 3; // 3 for virtually online
// 			}
// 		}

		$last_ping = RobotCore::getLatestPingTimestampFromRobot($model->serial_number);

		if(!empty($last_ping)){
			$latest_ping_timestamp = strtotime($last_ping[0]->ping_timestamp);
			$current_system_timestamp = time();
			$last_ping = AppHelper::getTimeSummary($latest_ping_timestamp, $current_system_timestamp);
		} else {
			$last_ping = 'Unavailable';
		}

		$sleep_lag_time = RobotCore::getSleepLagTime($model);

		$this->render('view',array(
				'model'=>$model,
				'isOnline'=>$isOnline,
				'scroll_to'=>$scroll_to,
				'last_ping'=>$last_ping,
				'sleep_lag_time'=>$sleep_lag_time
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
			$model = new Robot;

			$robot_type_model = new RobotTypes();

			$this->performAjaxValidation(array($model,$robot_type_model));

			if(isset($_POST['Robot'], $_POST['RobotTypes']))
			{
				$model->attributes=$_POST['Robot'];
				$chat_details = RobotCore::create_chat_user_for_robot();

				if(!$chat_details['jabber_status']){
					$message = "Robot could not be created because jabber service in not responding.";
					Yii::app()->user->setFlash('warning', $message);
					throw new CHttpException(501, $message);
				}

				$model->chat_id = $chat_details['chat_id'];
				$model->chat_pwd = $chat_details['chat_pwd'];
				if($model->save()){
					// save robot type
					$robot_robot_type = new RobotRobotTypes();
					$robot_robot_type->robot_id = $model->id;
					$robot_robot_type->robot_type_id = $_POST['RobotTypes']['type'];
					$robot_robot_type->save();

					$utc = $model->updated_on;

					if(!empty($model->name)){

						$robot = $model;
						$robot_name = $model-> name;
						$key = Yii::app()->params['robot_name_key'];

						RobotCore::setRobotKeyValueDetail($robot, $key, $robot_name, $robot->updated_on);

						$user_id = Yii::app()->user->id;
						$user_data = User::model()->findByPk($user_id);
						$cause_agent_id = Yii::app()->session['cause_agent_id'];
						$message_to_set_robot_key_value = RobotCore::xmppMessageOfSetRobotProfile($robot, $cause_agent_id, $utc);

						RobotCore::sendXMPPMessageWhereUserSender($user_data, $robot, $message_to_set_robot_key_value);

					}

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
					'robot_type_model'=>$robot_type_model,
			));
		}

		/**
		 * Creates a new robot type.
		 */
		public function actionAddType()
		{

			if (Yii::app()->user->getIsGuest()) {
				Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/robot/addType');
				$this->redirect(Yii::app()->request->baseUrl.'/user/login');
			}
			self::check_for_admin_privileges();
			$robot_type_model = new RobotTypeMetadataForm();

			if(Yii::app()->getRequest()->getIsAjaxRequest()) {
				$validation_error = '{';

				$CActiveForm_str = str_replace("{","",CActiveForm::validate(array($robot_type_model)));
				$CActiveForm_str = str_replace("}","",$CActiveForm_str);

				if(!empty($_POST['RobotTypeMetadataForm']['type'])){
					$robot_type_check = RobotTypes::model()->find('type = :robot_type', array(':robot_type' => $_POST['RobotTypeMetadataForm']['type']));
					if(!empty($robot_type_check)){
						$robot_type_check =  '"RobotTypeMetadataForm_type":["Robot type ' . $_POST['RobotTypeMetadataForm']['type'] . ' has already been taken."],';

						if($CActiveForm_str == '[]'){
							$robot_type_check =  '"RobotTypeMetadataForm_type":["Robot type ' . $_POST['RobotTypeMetadataForm']['type'] . ' has already been taken."]';
						}

						$validation_error .= $robot_type_check;
					}
				}

				if($CActiveForm_str != '[]'){
					$validation_error .= $CActiveForm_str;//CActiveForm::validate(array($robot_type_model));
				}


				$validation_error .= '}';

				echo $validation_error;
				if($validation_error != '{[]}'){
					Yii::app()->end();
				}

			}

			if(isset($_POST['RobotTypeMetadataForm']))
			{

				$type_model = new RobotTypes();

				$criteria = new CDbCriteria;
				$criteria->select = array('id');
				$criteria->order = 'id DESC';
				$robot_type_data = RobotTypes::model()->findAll($criteria);

				if(!empty($robot_type_data)){
					$type_model->id = $robot_type_data[0]->id + 1;
				} else {
					$type_model->id = 1;
				}

				$type_model->type=$_POST['RobotTypeMetadataForm']['type'];
				$type_model->name=$_POST['RobotTypeMetadataForm']['name'];

				if($type_model->save()){

					$metadata_model = new RobotTypeMetadata();
					$metadata_model->robot_type_id = $type_model->id;
					$metadata_model->_key = 'sleep_time';
					$metadata_model->value = $_POST['RobotTypeMetadataForm']['sleep_time'] * 60;
					$metadata_model->save();

					$metadata_model = new RobotTypeMetadata();
					$metadata_model->robot_type_id = $type_model->id;
					$metadata_model->_key = 'lag_time';
					$metadata_model->value = $_POST['RobotTypeMetadataForm']['lag_time'];
					$metadata_model->save();

					$msg = AppCore::yii_echo("addrobot:type:ok", $type_model->type);
					Yii::app()->user->setFlash('success', $msg);

					$this->redirect(array('types'));

				}else {
					$msg = "You may have entered something wrong.";
					Yii::app()->user->setFlash('error', $msg);
				}
			}

			$this->render('add_type', array(
					'robot_type_model'=>$robot_type_model,
			));
		}


		/**
		 * Creates a new robot type.
		 */
		public function actionUpdateType()
		{
			if (Yii::app()->user->getIsGuest()) {
				$url = $this->createUrl('robot/updateType',array('h'=>Yii::app()->request->getParam('h', '')));
				Yii::app()->user->setReturnUrl($url);
				$this->redirect(Yii::app()->request->baseUrl.'/user/login');
			}

			$h_id = Yii::app()->request->getParam('h', '');

			if($h_id == ''){
				$this->redirect(array('types'));
			}
			$id = AppHelper::two_way_string_decrypt($h_id);

			self::check_for_admin_privileges();
			$robot_type_model = new RobotTypeMetadataForm();
			$robot_type_model->isNewRecord = false;

			$robot_type_data = RobotTypes::model()->find('id = :id', array(':id'=>$id));
			if(!empty($robot_type_data)){
				$robot_type_model->type = $robot_type_data->type;
				$robot_type_model->name = $robot_type_data->name;

				foreach ($robot_type_data->robotTypeMetadatas as $metadata) {

					if($metadata->_key == 'sleep_time'){
						$robot_type_model->sleep_time = $metadata->value/60;
					} elseif($metadata->_key == 'lag_time') {
						$robot_type_model->lag_time = $metadata->value;
					}

				}

			} else {
				$this->redirect(array('types'));
			}

			if(Yii::app()->getRequest()->getIsAjaxRequest()) {
				echo CActiveForm::validate(array($robot_type_model));
				Yii::app()->end();
			}

			if(isset($_POST['RobotTypeMetadataForm']))
			{

				$robot_type_data->type=$_POST['type'];
				$robot_type_data->name=$_POST['RobotTypeMetadataForm']['name'];

				foreach ($robot_type_data->robotTypeMetadatas as $metadata) {
					$robot_type_metadata = RobotTypeMetadata::model()->findByPk($metadata->id);
					if(!empty($robot_type_metadata)){
						if($robot_type_metadata->_key == 'sleep_time'){
							$robot_type_metadata->value = $_POST['RobotTypeMetadataForm']['sleep_time'] * 60;
						} elseif($robot_type_metadata->_key == 'lag_time') {
							$robot_type_metadata->value = $_POST['RobotTypeMetadataForm']['lag_time'];
						}
						$robot_type_metadata->save();
					}
				}

				if($robot_type_data->save()){

					$msg = AppCore::yii_echo("editrobot:type:ok", $robot_type_data->type);
					Yii::app()->user->setFlash('success', $msg);
					$this->redirect(array('types'));

				}else {
					$msg = "You may have entered something wrong.";
					Yii::app()->user->setFlash('error', $msg);
				}
			}

			$this->render('update_type', array(
					'robot_type_model'=>$robot_type_model,
			));
		}


		/**
		 * Updates a particular robot.
		 * If update is successful, the browser will be redirected to the 'view' page of the robot.
		 * @param integer $id the ID of the robot to be updated
		 */
		public function actionUpdate()
		{
			if(Yii::app()->user->UserRoleId == '2'){
				$this->layout = 'support';
			}
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
			$robot_name_before_update = $model->name;
			$robot_type_model = new RobotTypes();

			// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation(array($model,$robot_type_model));

			if(isset($model->robotRobotTypes)){
				$robot_type_model = $model->robotRobotTypes->robotType;
			}
			if(Yii::app()->user->UserRoleId == '2'){
				$update_robot_info = isset($_POST['Robot']) ? true: false;
			}else{
				$update_robot_info = isset($_POST['Robot'], $_POST['RobotTypes']) ? true: false;
			}
			if($update_robot_info)
			{

				$model->attributes=$_POST['Robot'];

				if($model->save()){
					if(Yii::app()->user->UserRoleId != '2'){  //because we do not get robot type for role_id == 2 i.e support role
						// update robot type
						$robot_robot_type = RobotRobotTypes::model()->find('robot_id = :robot_id', array(':robot_id' => $model->id));
						$robot_robot_type->robot_type_id = $_POST['RobotTypes']['type'];
						$robot_robot_type->save();
					}
					$utc = $model->updated_on;
					$msg = AppCore::yii_echo("editrobot:ok",$model->serial_number);
					Yii::app()->user->setFlash('success', $msg);

					$robot = $model;
					$robot_name = $model-> name;
					$key = Yii::app()->params['robot_name_key'];

					if($robot_name_before_update != $model-> name){

						RobotCore::setRobotKeyValueDetail($robot, $key, $robot_name, $robot->updated_on);

						$user_id = Yii::app()->user->id;
						$user_data = User::model()->findByPk($user_id);
						$cause_agent_id = Yii::app()->session['cause_agent_id'];
						$message_to_set_robot_key_value = RobotCore::xmppMessageOfSetRobotProfile($robot, $cause_agent_id, $utc);

						RobotCore::sendXMPPMessageWhereUserSender($user_data, $robot, $message_to_set_robot_key_value);

					}

					$this->redirect(array('list'));

				}else {
					$msg = "Updation failed.";
					Yii::app()->user->setFlash('error', $msg);
				}
			}

			$this->render('update',array(
					'model'=>$model,
					'robot_type_model'=>$robot_type_model,
			));
		}


		/**
		 * Lists all robots.
		 */
		public function actionList()
		{
			if(Yii::app()->user->UserRoleId == '2'){
				$this->layout = 'support';
			}
			if (Yii::app()->user->getIsGuest()) {
				Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/robot/list');
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

			$file_name= "";

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

		/**
		 * Lists all robots.
		 */
		public function actionTypes()
		{
			if (Yii::app()->user->getIsGuest()) {
				Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/robot/types');
				$this->redirect(Yii::app()->request->baseUrl.'/user/login');
			}
			self::check_for_admin_privileges();

			$robot_types = RobotTypes::model()->findAll();

			$this->render('types', array('robot_types' => $robot_types));

		}
	}
