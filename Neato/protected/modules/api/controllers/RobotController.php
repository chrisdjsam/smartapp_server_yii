<?php

/**
 * The API RobotController is meant for all robot related API actions.
 */
class RobotController extends APIController {

	/**
	 * API to create new robot
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>name</b> :Name of the robot</li>
	 *	<li><b>serial_number</b> :Serial Number of the robot</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"Robot
	 *				created successfully."}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If Robot serial number is duplicate
	 *		<ul>
	 *			<li>{"status":-1,"message":"This robot serial number already
	 *				exists."}</li>
	 *		</ul>
	 *	</li>
	 *
	 *	<li>If Jabber service is not able to create chat user
	 *		<ul>
	 *			<li>{"status":-1,"message":"Robot could not be created because
	 *				jabber service in not responding."}</li>
	 *		</ul>
	 *	</li>
	 *
	 *</ul>
	 */
	public function actionCreate(){
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot_name = Yii::app()->request->getParam('name', '');

		$robot = Robot::model()->findByAttributes(array('serial_number' => $robot_serial_no));
		if($robot !== null ){
			if ($robot->serial_number === $robot_serial_no){
				$response_message = self::yii_api_echo('This robot serial number already exists.');
				self::terminate(-1, $response_message);
			}
		}
		$model = new Robot();
		$model->name = $robot_name;
		$model->serial_number = $robot_serial_no;

		$chat_details = AppCore::create_chat_user_for_robot();
		if(!$chat_details['jabber_status']){
			$message = self::yii_api_echo("Robot could not be created because jabber service is not responding.");
			self::terminate(-1, $message);
		}
		$model->chat_id = $chat_details['chat_id'];
		$model->chat_pwd = $chat_details['chat_pwd'];

		if($model->save()){
			$response_data = array("success"=>true, "message"=>self::yii_api_echo('Robot created successfully.'));
			self::success($response_data);
		}
		else{
			$response_message = self::yii_api_echo('Robot could not be created because jabber service is not responding');
			self::terminate(-1, $response_message);
		}
	}
   /**
    * Method to check if robot is online for given robot serial number.
    * 
    * Parameters:
	*ul>
	 *		<li><b>serial_number</b> :Serial Number of the robot</li>
	 *	</ul>
	 *	Success Response:
	 *	<ul>
	 *		<li>If everything goes fine
	 *			<ul>
	 *				<li>{"status":0,"result":{"success":true,"message":"Robot
	 *					is online / offline."}}</li>
	 *			</ul>
	 *		</li>
	 *	</ul>
	 *
	 *	Failure Responses: <br />
	 *	<ul>
	 *
	 *		<li>If a serial_number is missing
	 *			<ul>
	 *				<li>{"status":-1,"message":"Missing parameter serial_number in method
	 *					robot.is_robot_online"}</li>
	 *			</ul>
	 *		</li>
	 *		<li>If serial number does not exist
	 *			<ul>
	 *				<li>{"status":-1,"message":"Serial number does not exist"}</li>
	 *			</ul>
	 *		</li>
	 *
	 *	</ul>
    */
	public function actionIsOnline(){
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		
		if($robot !== null ){
			$online_users_chat_ids = AppCore::getOnlineUsers();
			if(in_array($robot->chat_id, $online_users_chat_ids)){
				$response_message = "Robot ".$robot_serial_no." is online.";
				$response_data = array("online"=>true, "message"=>$response_message);
			}else {
				$response_message = "Robot ".$robot_serial_no." is offline.";
				$response_data = array("online"=>false, "message"=>$response_message);
			}
			self::success($response_data);
		}
		
	}
	
	/**
	 *  API to get robots information
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>serial_number</b> :Serial Number of robot</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"id":"65","name":"desk
	 *				cleaner59","serial_number":"robo1","chat_id":"1350924155_robot@rajatogo","chat_pwd":"1350924155_robot"}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and user association exist
	 *		<ul>
	 *			<li>{"status":0,"result":{"id":"68","name":"room
	 *				cleaner1","serial_number":"robo5","chat_id":"1350987452_robot@rajatogo","chat_pwd":"1350987452_robot","users":[{"id":"542","name":"pradip","email":"pradip@gmail.com","chat_id":"1351499916_user@rajatogo"},{"id":"543","name":"pradip","email":"pradip1@gmail.com","chat_id":"1351500158_user@rajatogo"}]}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and user association does not exist
	 *		<ul>
	 *			<li>{"status":0,"result":{"id":"70","name":"room
	 *				cleaner","serial_number":"robo1","chat_id":"1351501366_robot@rajatogo","chat_pwd":"1351501366_robot","users":[]}}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If serial number does not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Serial number does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionGetDetails(){
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		$users_arr = array();
		foreach ($robot->usersRobots as $user_robots){
			$user_details = array();
			$user_details['id'] = $user_robots->idUser->id;
			$user_details['name'] = $user_robots->idUser->name;
			$user_details['email'] = $user_robots->idUser->email;
			$user_details['chat_id'] = $user_robots->idUser->chat_id;

			$users_arr[] = $user_details;
		}
		$response_data = array("id"=>$robot->id,"name"=>$robot->name,"serial_number"=>$robot->serial_number,"chat_id"=>$robot->chat_id,"chat_pwd"=>$robot->chat_pwd, "users"=>$users_arr);
		self::success($response_data);
	}

	
	public function actionSetProfileDetails(){
	
		$robot = self::verify_for_robot_serial_number_existence(Yii::app()->request->getParam('serial_number', ''));
	
		$robot_profile = Yii::app()->request->getParam('profile', '');
		// 		$user_auth_token = Yii::app()->request->getParam('auth_token', '');
		// 		$user_api_session = UsersApiSession::model()->findByAttributes(array('token' =>$user_auth_token));
		// 		$user = User::model()->findByAttributes(array('id' => $user_api_session->id_user));
		if ($robot !== null){
			foreach ($robot_profile as $key => $value){
				if($value === ''){
					$message = self::yii_api_echo("Invalid value for key $key.");
					self::terminate(-1, $message);
				}
				switch ($key) {
					case "name":
						$robot->name = $value;
						$robot->save();
						break;
					
					default:
						;
						break;
				}
			}
			self::success(1);
		}else{
			$response_message = self::yii_api_echo('APIException:RobotAuthenticationFailed');
			self::terminate(-1, $response_message);
		}
	
	}
	
	
	
	/**
	 * API to get array of associated users with provided robot serial no
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>serial_number</b> :Serial Number of robot</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine and user association exist
	 *		<ul>
	 *			<li>
	 *				{"status":0,"result":[{"id":"542","name":"pradip","email":"pradip@gmail.com","chat_id":"1351499916_user@rajatogo"},{"id":"543","name":"pradip","email":"pradip1@gmail.com","chat_id":"1351500158_user@rajatogo"}]}
	 *			</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and user association does not exist
	 *		<ul>
	 *			<li>{"status":0,"result":[]}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If serial number does not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Serial number does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionGetAssociatedUser(){
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		$users_arr = array();
		foreach ($robot->usersRobots as $user_robots){
			$user_details = array();
			$user_details['id'] = $user_robots->idUser->id;
			$user_details['name'] = $user_robots->idUser->name;
			$user_details['email'] = $user_robots->idUser->email;
			$user_details['chat_id'] = $user_robots->idUser->chat_id;

			$users_arr[] = $user_details;
		}
		self::success($users_arr);
	}

	/**
	 * API to set user and robot association
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>email</b> :User Email ID</li>
	 *	<li><b>serial_number</b> :Serial Number of robot</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"Robot
	 *				ownership established successfully."}}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If ownership already exists.
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"This robot
	 *				ownership relation already exists."}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If Email is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter email in method
	 *				robot.set_user"}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If Robot serial number is missing
	 *		<ul>
	 *			<li>{"status":-1,"message":"Missing parameter serial_number in
	 *				method robot.set_user"}</li>
	 *		</ul>
	 *</ul>
	 */
	public function actionSetUsers(){
		$user_email = Yii::app()->request->getParam('email', '');
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$user = User::model()->findByAttributes(array('email' => $user_email));
		if($user !== null ){
			$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
			$user_robot = UsersRobot::model()->findByAttributes(array('id_user' => $user->id,'id_robot' => $robot->id));
			if($user_robot !== null){
				$response_data = array("success"=>true, "message"=>self::yii_api_echo('This robot ownership relation already exists.'));
				self::success($response_data);
			}
			else{
				$user_robots = new UsersRobot();
				$user_robots->id_user = $user->id;
				$user_robots->id_robot = $robot->id;
				if($user_robots->save()){
					$response_data = array("success"=>true, "message"=>self::yii_api_echo('Robot ownership established successfully.'));
					self::success($response_data);
				}
			}
		}
		else{
			$response_message = self::yii_api_echo('Email does not exist');
			self::terminate(-1, $response_message);
		}

	}

	/**
	 * API to disassociate users from robot
	 *
	 *Parameters:
	 *<ul>
	 *	<li><b>serial_number</b> :Serial Number of robot</li>
	 *	<li><b>email</b> :User's Email (If this field is empty, it will
	 *		delete all user association for this particular robot)</li>
	 *</ul>
	 *Success Response:
	 *<ul>
	 *	<li>If everything goes fine, user email provided and robot user
	 *		association exist
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"Robot User
	 *				association removed successfully."}}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine, user email not provided and robot
	 *		user association exist
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"Robot
	 *				association with all user removed successfully."}}</li>
	 *		</ul>
	 *	</li>
	 *	<li>If everything goes fine and robot user association does not
	 *		exist
	 *		<ul>
	 *			<li>{"status":0,"result":{"success":true,"message":"There is no
	 *				association between provided robot and user"}}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 *Failure Responses:
	 *<ul>
	 *	<li>If serial number does not exist
	 *		<ul>
	 *			<li>{"status":-1,"message":"Serial number does not exist"}</li>
	 *		</ul>
	 *	</li>
	 *</ul>
	 */
	public function actionDisAssociateUser(){
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$user_email = Yii::app()->request->getParam('email', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		if($user_email){
			$user = User::model()->findByAttributes(array('email' => $user_email));
			if($user !== null){
				$user_robot_delete = UsersRobot::model()->deleteAllByAttributes(array('id_user' => $user->id,'id_robot' => $robot->id));
				if($user_robot_delete){
					$response_message = self::yii_api_echo('Robot User association removed successfully.');
				}else{
					$response_message = self::yii_api_echo('There is no association between provided robot and user');
				}
			}else {
				$response_message=self::yii_api_echo('Email does not exist.');
				self::terminate(-1, $response_message);
			}
		}else{
			$user_robots_delete = UsersRobot::model()->deleteAllByAttributes(array('id_robot' => $robot->id));
			if ($user_robots_delete){
				$response_message = self::yii_api_echo('Robot association with all user removed successfully.');
			}else{
				$response_message = self::yii_api_echo('There is no association between provided robot and user');
			}
		}
		$response_data = array("success"=>true, "message"=>$response_message);
		self::success($response_data);
	}

	/**
	 * Send start command to a particular robot.
	 * It is called by ajax call.
	 */
	public function actionSendStartCommand()
	{
		$chat_id = Yii::app()->request->getParam('chat_id', '');
		$to = AppHelper::two_way_string_decrypt($chat_id);

		$from = User::model()->findByPk(Yii::app()->user->id)->chat_id;

		$start_command = Yii::app()->params['robot-start-cleaning-command'];
		$message= AppCore::send_chat_message($from, $to, $start_command);
		$content = array('status' => 0);

		$this->renderPartial('/default/defaultView', array('content' => $content));
	}

	/**
	 * Send stop command to a particular robot.
	 * It is called by ajax call.
	 */
	public function actionSendStopCommand()
	{
		$chat_id = Yii::app()->request->getParam('chat_id', '');
		$to = AppHelper::two_way_string_decrypt($chat_id);

		$from = User::model()->findByPk(Yii::app()->user->id)->chat_id;

		$stop_command = Yii::app()->params['robot-stop-cleaning-command'];
		$message= AppCore::send_chat_message($from, $to, $stop_command);
		$content = array('status' => 0);

		$this->renderPartial('/default/defaultView', array('content' => $content));
	}
}