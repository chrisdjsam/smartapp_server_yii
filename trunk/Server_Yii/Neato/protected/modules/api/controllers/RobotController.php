<?php

/**
 * The API RobotController is meant for all robot related API actions.
 */
class RobotController extends APIController {

	/**
	 * API to create new robot
	 *
	 * Parameters:
	 * <ul>
	 * 	<li><b>name</b> :Name of the robot</li>
	 * 	<li><b>serial_number</b> :Serial Number of the robot</li>
	 * </ul>
	 * Success Response:
	 * <ul>
	 * 	<li>If everything goes fine
	 * 		<ul>
	 * 			<li>{"status":0,"result":{"success":true,"message":"Robot
	 * 				created successfully."}}</li>
	 * 		</ul>
	 * 	</li>
	 * </ul>
	 * Failure Responses:
	 * <ul>
	 * 	<li>If Robot serial number is duplicate
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"This robot serial number already
	 * 				exists."}</li>
	 * 		</ul>
	 * 	</li>
	 *
	 * 	<li>If Jabber service is not able to create chat user
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Robot could not be created because
	 * 				jabber service in not responding."}</li>
	 * 		</ul>
	 * 	</li>
	 *
	 * </ul>
	 */
	public function actionCreate() {
		$robot_serial_no = trim(Yii::app()->request->getParam('serial_number', ''));
		$robot_name = Yii::app()->request->getParam('name', '');

		$robot = Robot::model()->findByAttributes(array('serial_number' => $robot_serial_no));
		if ($robot !== null) {
			$response_message = self::yii_api_echo('This robot serial number already exists.');
			self::terminate(-1, $response_message, APIConstant::ROBOT_SERIAL_NUMBER_EXISTS);
		}
		$model = new Robot();
		$model->name = $robot_name;
		$model->serial_number = $robot_serial_no;

		$chat_details = RobotCore::create_chat_user_for_robot();
		if (!$chat_details['jabber_status']) {
			$message = self::yii_api_echo("Robot could not be created because jabber service is not responding.");
			self::terminate(-1, $message, APIConstant::UNAVAILABLE_JABBER_SERVICE);
		}
		$model->chat_id = $chat_details['chat_id'];
		$model->chat_pwd = $chat_details['chat_pwd'];

		if ($model->save()) {
			$robot_robot_type = new RobotRobotTypes();
			$robot_robot_type->robot_id = $model->id;
			$robot_type_data = RobotTypes::model()->find('type = :type', array(':type' => Yii::app()->params['default_robot_type']));
			if (!empty($robot_type_data)) {
				$robot_robot_type->robot_type_id = $robot_type_data->id;
				$robot_robot_type->save();
			}
			$response_data = array("success" => true, "message" => self::yii_api_echo('Robot created successfully.'));
			self::success($response_data);
		}else {
			$response_message = self::yii_api_echo('Robot could not be created because jabber service is not responding');
			self::terminate(-1, $response_message, APIConstant::UNAVAILABLE_JABBER_SERVICE);
		}
	}

	public function actionCreate2() {
		$robot_serial_no = trim(Yii::app()->request->getParam('serial_number', ''));
		$robot_name = trim(Yii::app()->request->getParam('name', ''));
		$robot_type = trim(Yii::app()->request->getParam('robot_type', ''));

		if(empty($robot_type) && ($robot_type != '0')){
			$robot_type = Yii::app()->params['default_robot_type'];
		}

		$robot_type_data = RobotTypes::model()->find('type = :type', array(':type' => $robot_type));


		if(empty($robot_type_data)){
			$message = "'Robot Type is not valid'";
			self::terminate(-1, $message, APIConstant::ROBOT_TYPE_NOT_VALID);
		}

		$robot = Robot::model()->findByAttributes(array('serial_number' => $robot_serial_no));
		if ($robot !== null) {
			$response_message = self::yii_api_echo('This robot serial number already exists.');
			self::terminate(-1, $response_message, APIConstant::ROBOT_SERIAL_NUMBER_EXISTS);
		}

		$model = new Robot();
		$model->name = $robot_name;
		$model->serial_number = $robot_serial_no;

		$chat_details = RobotCore::create_chat_user_for_robot();

		if (!$chat_details['jabber_status']) {
			$message = self::yii_api_echo("Robot could not be created because jabber service is not responding.");
			self::terminate(-1, $message, APIConstant::UNAVAILABLE_JABBER_SERVICE);
		}

		$model->chat_id = $chat_details['chat_id'];
		$model->chat_pwd = $chat_details['chat_pwd'];

		if ($model->save()) {

			$robot_robot_type = new RobotRobotTypes();
			$robot_robot_type->robot_id = $model->id;
			$robot_robot_type->robot_type_id = $robot_type_data->id;
			$robot_robot_type->save();

			$response_data = array("success" => true, "message" => self::yii_api_echo('Robot created successfully.'));
			self::success($response_data);
		} else {
			$response_message = self::yii_api_echo('Robot could not be created because jabber service is not responding');
			self::terminate(-1, $response_message, APIConstant::UNAVAILABLE_JABBER_SERVICE);
		}
	}

	/**
	 * Method to check if robot is online for given robot serial number.
	 *
	 * Parameters:
	 * ul>
	 * 		<li><b>serial_number</b> :Serial Number of the robot</li>
	 * 	</ul>
	 * 	Success Response:
	 * 	<ul>
	 * 		<li>If everything goes fine
	 * 			<ul>
	 * 				<li>{"status":0,"result":{"success":true,"message":"Robot
	 * 					is online / offline."}}</li>
	 * 			</ul>
	 * 		</li>
	 * 	</ul>
	 *
	 * 	Failure Responses: <br />
	 * 	<ul>
	 *
	 * 		<li>If a serial_number is missing
	 * 			<ul>
	 * 				<li>{"status":-1,"message":"Missing parameter serial_number in method
	 * 					robot.is_robot_online"}</li>
	 * 			</ul>
	 * 		</li>
	 * 		<li>If serial number does not exist
	 * 			<ul>
	 * 				<li>{"status":-1,"message":"Serial number does not exist"}</li>
	 * 			</ul>
	 * 		</li>
	 *
	 * 	</ul>
	 */
	public function actionIsOnline() {
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);

		if ($robot !== null) {
			$online_users_chat_ids = RobotCore::getOnlineUsers();
			if (in_array($robot->chat_id, $online_users_chat_ids)) {
				$response_message = "Robot " . $robot_serial_no . " is online.";
				$response_data = array("online" => true, "message" => $response_message);
			} else {
				$response_message = "Robot " . $robot_serial_no . " is offline.";
				$response_data = array("online" => false, "message" => $response_message);
			}
			self::success($response_data);
		}
	}

	public function actionGetRobotPresenceStatus() {

		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);

		if ($robot !== null) {
			$online_users_chat_ids = RobotCore::getOnlineUsers();
			if (in_array($robot->chat_id, $online_users_chat_ids)) {
				$response_message = "Robot " . $robot_serial_no . " is online.";
				$response_data = array("online" => true, "message" => $response_message);
			} else {
				$response_message = "Robot " . $robot_serial_no . " is offline.";
				$response_data = array("online" => false, "message" => $response_message);
			}
			self::success($response_data);
		}
	}

	public function actionIsRobotOnlineVirtual() {

		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		$expected_time = 1;

		if ($robot !== null) {

			$data = RobotCore::getLatestPingTimestampFromRobot($robot->serial_number);

			$online_users_chat_ids = RobotCore::getOnlineUsers();
			if (in_array($robot->chat_id, $online_users_chat_ids)) {
				$response_message = "Robot " . $robot_serial_no . " is online.";
				$response_data = array("online" => true, "message" => $response_message, "expected_time" => $expected_time);
			} else if (!empty($data)) {
				$latest_ping_timestamp = strtotime($data[0]->ping_timestamp);

				$sleep_lag_time = RobotCore::getSleepLagTime($robot);
				$robot_ping_interval = $sleep_lag_time['sleep_time'];

				$current_system_timestamp = time();
				$time_diff = ($current_system_timestamp - $latest_ping_timestamp);
				$expected_time = $robot_ping_interval - $time_diff;

				if ($time_diff > $robot_ping_interval) {
					$response_message = "Robot " . $robot_serial_no . " is offline.";
					$response_data = array("online" => false, "message" => $response_message, "expected_time" => $expected_time);
				} else {
					$response_message = "Robot " . $robot_serial_no . " is online.";
					$response_data = array("online" => true, "message" => $response_message, "expected_time" => $expected_time);
				}
			} else {
				$response_message = "Robot " . $robot_serial_no . " is offline.";
				$response_data = array("online" => false, "message" => $response_message);
			}
			self::success($response_data);
		}
	}

	/**
	 *  API to get robots information
	 *
	 * Parameters:
	 * <ul>
	 * 	<li><b>serial_number</b> :Serial Number of robot</li>
	 * </ul>
	 * Success Response:
	 * <ul>
	 * 	<li>If everything goes fine
	 * 		<ul>
	 * 			<li>{"status":0,"result":{"id":"65","name":"desk
	 * 				cleaner59","serial_number":"robo1","chat_id":"1350924155_robot@rajatogo","chat_pwd":"1350924155_robot"}}
	 * 			</li>
	 * 		</ul>
	 * 	</li>
	 * 	<li>If everything goes fine and user association exist
	 * 		<ul>
	 * 			<li>{"status":0,"result":{"id":"68","name":"room
	 * 				cleaner1","serial_number":"robo5","chat_id":"1350987452_robot@rajatogo","chat_pwd":"1350987452_robot","users":[{"id":"542","name":"pradip","email":"pradip@gmail.com","chat_id":"1351499916_user@rajatogo"},{"id":"543","name":"pradip","email":"pradip1@gmail.com","chat_id":"1351500158_user@rajatogo"}]}}
	 * 			</li>
	 * 		</ul>
	 * 	</li>
	 * 	<li>If everything goes fine and user association does not exist
	 * 		<ul>
	 * 			<li>{"status":0,"result":{"id":"70","name":"room
	 * 				cleaner","serial_number":"robo1","chat_id":"1351501366_robot@rajatogo","chat_pwd":"1351501366_robot","users":[]}}
	 * 			</li>
	 * 		</ul>
	 * 	</li>
	 * </ul>
	 * Failure Responses:
	 * <ul>
	 * 	<li>If serial number does not exist
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Serial number does not exist"}</li>
	 * 		</ul>
	 * 	</li>
	 * </ul>
	 */
	public function actionGetDetails() {
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		$users_arr = array();
		foreach ($robot->usersRobots as $user_robots) {
			$user_details = array();
			$user_details['id'] = $user_robots->idUser->id;
			$user_details['name'] = $user_robots->idUser->name;
			$user_details['email'] = $user_robots->idUser->email;
			$user_details['chat_id'] = $user_robots->idUser->chat_id;

			$users_arr[] = $user_details;
		}
		$response_data = array("id" => $robot->id, "name" => $robot->name, "serial_number" => $robot->serial_number, "chat_id" => $robot->chat_id, "chat_pwd" => $robot->chat_pwd, "users" => $users_arr);
		self::success($response_data);
	}

	/**
	 * Deletes a set of robots that were selected by the user from the front end.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDeleteRobot() {
		self::check_for_admin_privileges();
		if (isset($_REQUEST['chooseoption'])) {
			foreach ($_REQUEST['chooseoption'] as $robo_id) {
				$robot = Robot::model()->findByAttributes(array('id' => $robo_id));

				$_POST['serial_number'] = $robot->serial_number;
				self::actionDelete(true);
			}

			$count = count($_REQUEST['chooseoption']);
			$message = AppCore::yii_echo("You have deleted %s robot successfully", $count);
			if ($count > 1) {
				$message = AppCore::yii_echo("You have deleted %s robots successfully", $count);
			}
			Yii::app()->user->setFlash('success', $message);
		} else {
			Yii::app()->user->setFlash('error', AppCore::yii_echo("No robot selected to delete"));
		}
		$this->redirect(Yii::app()->request->baseUrl . '/robot/list');
	}

	/**
	 * Metod to delete a robot for given serial number.
	 *
	 * Parameters:
	 * <ul>
	 * 	<li><b>serial_number</b> :Serial number of robot</li>
	 * 	<ul>
	 * Success Responses:
	 * <ul>
	 * 		<li>If everything goes fine
	 * 			<ul>
	 * 				<li>{"status":0,"result":{"success":true,"message":"You have deleted robot 123 successfully"}}
	 * 				</li>
	 * 			</ul>
	 * 		</li>
	 *
	 * 	</ul>
	 *
	 * 	Failure Responses: <br />
	 * 	<ul>
	 * 		<li>If parameter serial_number is missing
	 * 			<ul>
	 * 				<li>{"status":-1,"message":"Missing parameter serial_number in
	 * 					method robot.get_details"}</li>
	 * 			</ul>
	 *
	 * 		<li>If serial number does not exist
	 * 			<ul>
	 * 				<li>{"status":-1,"message":"Robot serial number does not exist"}</li>
	 * 			</ul>
	 * 		</li>
	 * 	</ul>
	 *
	 *
	 */
	public function actionDelete($prevent_termination = false) {

		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);

		if ($robot !== null) {
			$robot_map_id_arr = array();
			$robot_schedule_id_arr = array();
			foreach ($robot->robotSchedules as $robot_schedule) {
				$robot_schedule_id_arr[] = $robot_schedule->id;
			}

			$chat_id = $robot->chat_id;
			if ($robot->delete()) {
				RobotCore::delete_chat_user($chat_id);
				RobotCore::delete_robot_schedule_data($robot_schedule_id_arr);

				$response_message = "You have deleted robot $robot_serial_no successfully";
				$response_data = array("success" => true, "message" => $response_message);

				if (!$prevent_termination) {
					self::success($response_data);
				}
			} else if (!$prevent_termination) {
				$message = self::yii_api_echo("error deleting robot $robot_serial_no.");
				self::terminate(-1, $message, APIConstant::COULD_NOT_DELETE_ROBOT);
			}
		}
	}

	public function actionSetProfileDetails3() {

		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		$source_serial_number = Yii::app()->request->getParam('source_serial_number', '');
		$source_smartapp_id = Yii::app()->request->getParam('source_smartapp_id', '');
		$cause_agent_id = Yii::app()->request->getParam('cause_agent_id', '');
		$value_extra = json_decode(Yii::app()->request->getParam('value_extra', ''));
		$notification_flag = $_REQUEST['notification_flag'];
		$robot_profile = Yii::app()->request->getParam('profile', '');

		$utc_str = gmdate("M d Y H:i:s", time());
		$utc = strtotime($utc_str);
		$expected_time = 1;

		if (empty($source_serial_number) && empty($source_smartapp_id)) {
			self::terminate(-1, "Please provide atleast one source(source_serial_number or source_smartapp_id)", APIConstant::MISSING_SOURCE_SERIAL_NUMBER_OR_SOURCE_SMARTAPP_ID);
		}

		if (!empty($source_smartapp_id)) {
			if (!AppHelper::is_valid_email($source_smartapp_id)) {
				self::terminate(-1, 'Please enter valid email address in field source_smartapp_id.', APIConstant::SOURCE_SMARTAPP_ID_NOT_VALID);
			}

			$user_data = User::model()->find('email = :email', array(':email' => $source_smartapp_id));
			if (empty($user_data)) {
				self::terminate(-1, 'Sorry, Provided source_smartapp_id(email) does not exist in our system.', APIConstant::SOURCE_SMARTAPP_ID_NOT_EXIST);
			}

			$associated_user_check = false;
			foreach ($user_data->usersRobots as $usersRobot) {
				if ($usersRobot->id_robot == $robot->id) {
					$associated_user_check = true;
				}
			}
			if (!$associated_user_check) {
				self::terminate(-1, 'Sorry, Provided source_smartapp_id(email) is not associated with given robot', APIConstant::SOURCE_SMARTAPP_ID_IS_NOT_ASSOCIATED_WITH_ROBOT);
			}
		}

		if ($value_extra != null) {
			$value_extra = serialize($value_extra);
		}

		if ($robot !== null) {

			$robot->value_extra = $value_extra;
			$robot->save();

			foreach ($robot_profile as $key => $value) {
				$key = trim($key);
				$key_value_result = RobotCore::setRobotKeyValueDetail($robot, $key, $value, $utc);

				if ($key_value_result['code'] == 1) {
					self::terminate(-1, 'Robot name can not be empty', $key_value_result['error']);
				}
			}

			$message = RobotCore::xmppMessageOfSetRobotProfile($robot, $cause_agent_id, $utc);

			$online_users_chat_ids = RobotCore::getOnlineUsers();

			if (!in_array($robot->chat_id, $online_users_chat_ids)) {

				$robot_ping_data = RobotCore::getLatestPingTimestampFromRobot($robot->serial_number);
				$sleep_lag_time = RobotCore::getSleepLagTime($robot);
				$robot_ping_interval = $sleep_lag_time['sleep_time'];

				$expected_time = $robot_ping_interval;

				if (isset($robot_ping_data[0]->ping_timestamp)) {

					$latest_ping_timestamp = strtotime($robot_ping_data[0]->ping_timestamp);
					$current_system_timestamp = time();
					$time_diff = ($current_system_timestamp - $latest_ping_timestamp);
					$expected_time = $robot_ping_interval - $time_diff;
				}
			}

			if (!empty($source_serial_number) && $source_serial_number == $serial_number && $notification_flag) {
				RobotCore::sendXMPPMessageWhereRobotSender($robot, $online_users_chat_ids, $message);
			} else if (!empty($source_smartapp_id)) {
				if ($notification_flag) {
					RobotCore::sendXMPPMessageWhereUserSender($user_data, $robot, $message, $online_users_chat_ids);
				}
			}

			self::successWithExtraParam(1, array('expected_time' => $expected_time, 'timestamp' => $utc));
		} else {
			$response_message = self::yii_api_echo('APIException:RobotAuthenticationFailed');
			self::terminate(-1, $response_message, APIConstant::MISSING_SERIAL_NUMBER);
		}
	}

	public function actionGetProfileDetails() {

		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$key = Yii::app()->request->getParam('key', '');

		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		if ($robot !== null) {
			$data = RobotKeyValues::model()->findAll('robot_id= :robot_id', array(':robot_id' => $robot->id));
			$profileArray = array();
			$profileArray['name'] = $robot->name;
			$profileArray['serial_number'] = $robot->serial_number;
			if (!empty($data)) {

				foreach ($data as $datarow) {
					if ($key == $datarow->_key || empty($key)) {
						$profileArray[$datarow->_key] = $datarow->value;
					}
				}
				if (count($profileArray) == 2) {
					self::terminate(-1, "Sorry, entered key is invalid", APIConstant::KEY_NOT_VALID);
				}
			}
			$response_data = array("success" => true, "profile_details" => $profileArray);
			self::success($response_data);
		} else {
			$response_message = self::yii_api_echo('APIException:RobotAuthenticationFailed');
			self::terminate(-1, $response_message, APIConstant::MISSING_SERIAL_NUMBER);
		}
	}

	public function actionGetProfileDetails2() {

		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$key = Yii::app()->request->getParam('key', '');

		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		if ($robot !== null) {
			$data = RobotKeyValues::model()->findAll('robot_id= :robot_id', array(':robot_id' => $robot->id));
			$profileArray = array();
			$profileArray['name'] = array('value' => $robot->name, 'timestamp' => 0);
			$profileArray['serial_number'] = array('value' => $robot->serial_number, 'timestamp' => 0);
			if (!empty($data)) {

				foreach ($data as $datarow) {
					if ($key == $datarow->_key || empty($key)) {
						$profileArray[$datarow->_key] = array('value' => $datarow->value, 'timestamp' => $datarow->timestamp);
					}
				}
				if (count($profileArray) == 2) {
					self::terminate(-1, "Sorry, entered key is invalid", APIConstant::KEY_NOT_VALID);
				}
			}
			$response_data = array("success" => true, "profile_details" => $profileArray);
			self::success($response_data);
		} else {
			$response_message = self::yii_api_echo('APIException:RobotAuthenticationFailed');
			self::terminate(-1, $response_message, APIConstant::MISSING_SERIAL_NUMBER);
		}
	}

	public function actionDeleteRobotProfileKey2() {

		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$key = Yii::app()->request->getParam('key', '');
		$cause_agent_id = Yii::app()->request->getParam('cause_agent_id', '');
		$source_serial_number = Yii::app()->request->getParam('source_serial_number', '');
		$source_smartapp_id = Yii::app()->request->getParam('source_smartapp_id', '');
		$notification_flag = $_REQUEST['notification_flag'];

		$utc_str = gmdate("M d Y H:i:s", time());
		$utc = strtotime($utc_str);

		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		if (!empty($source_smartapp_id)) {
			if (!AppHelper::is_valid_email($source_smartapp_id)) {
				self::terminate(-1, 'Please enter valid email address in field source_smartapp_id.', APIConstant::SOURCE_SMARTAPP_ID_NOT_VALID);
			}

			$user_data = User::model()->find('email = :email', array(':email' => $source_smartapp_id));
			if (empty($user_data)) {
				self::terminate(-1, 'Sorry, Provided source_smartapp_id(email) does not exist in our system.', APIConstant::SOURCE_SMARTAPP_ID_NOT_EXIST);
			}

			$associated_user_check = false;
			foreach ($user_data->usersRobots as $usersRobot) {
				if ($usersRobot->id_robot == $robot->id) {
					$associated_user_check = true;
				}
			}
			if (!$associated_user_check) {
				self::terminate(-1, 'Sorry, Provided source_smartapp_id(email) is not associated with given robot', APIConstant::SOURCE_SMARTAPP_ID_IS_NOT_ASSOCIATED_WITH_ROBOT);
			}
		}

		if ($robot !== null) {

			$result = RobotKeyValues::model()->deleteAll('robot_id = :robot_id AND _key = :_key', array(':robot_id' => $robot->id, ':_key' => $key));

			if ($result) {

				$xmpp_message_model = new XmppMessageLogs();
				$xmpp_message_model->save();
				$message = '<?xml version="1.0" encoding="UTF-8"?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>5001</command><requestId>' . $xmpp_message_model->id . '</requestId><timeStamp>' . $utc . '</timeStamp><retryCount>0</retryCount><responseNeeded>false</responseNeeded><distributionMode>2</distributionMode><params><robotId>' . $robot->serial_number . '</robotId><causeAgentId>' . $cause_agent_id . '</causeAgentId></params></request></payload></packet>';

				$xmpp_message_model->send_from = $robot->id;
				$xmpp_message_model->send_at = $utc;

				$xmpp_message_model->xmpp_message = $message;
				$xmpp_message_model->save();

				$online_users_chat_ids = RobotCore::getOnlineUsers();

				if (!empty($source_serial_number) && $source_serial_number == $serial_number && $notification_flag) {

					foreach ($robot->usersRobots as $userRobot) {
						if (in_array($userRobot->idUser->chat_id, $online_users_chat_ids)) {
							RobotCore::send_chat_message($robot->chat_id, $userRobot->idUser->chat_id, $message);
						}
					}
					RobotCore::send_chat_message($robot->chat_id, $robot->chat_id, $message);
				} else if (!empty($source_smartapp_id) && $notification_flag) {

					RobotCore::send_chat_message($user_data->chat_id, $robot->chat_id, $message);
					foreach ($robot->usersRobots as $userRobot) {
						if (in_array($userRobot->idUser->chat_id, $online_users_chat_ids)) {
							RobotCore::send_chat_message($user_data->chat_id, $userRobot->idUser->chat_id, $message);
						}
					}
				} else if ($notification_flag) {
					foreach ($robot->usersRobots as $userRobot) {
						if (in_array($userRobot->idUser->chat_id, $online_users_chat_ids)) {
							RobotCore::send_chat_message($robot->chat_id, $userRobot->idUser->chat_id, $message);
						}
					}
					RobotCore::send_chat_message($robot->chat_id, $robot->chat_id, $message);
				}
				$response_data = array("success" => true);
				self::success($response_data);
			} else {
				self::terminate(-1, "Sorry, entered key is invalid", APIConstant::KEY_NOT_VALID);
			}
		} else {
			$response_message = self::yii_api_echo('APIException:RobotAuthenticationFailed');
			self::terminate(-1, $response_message, APIConstant::MISSING_SERIAL_NUMBER);
		}
	}

	/**
	 * API to get array of associated users with provided robot serial no
	 *
	 * Parameters:
	 * <ul>
	 * 	<li><b>serial_number</b> :Serial Number of robot</li>
	 * </ul>
	 * Success Response:
	 * <ul>
	 * 	<li>If everything goes fine and user association exist
	 * 		<ul>
	 * 			<li>
	 * 				{"status":0,"result":[{"id":"542","name":"pradip","email":"pradip@gmail.com","chat_id":"1351499916_user@rajatogo"},{"id":"543","name":"pradip","email":"pradip1@gmail.com","chat_id":"1351500158_user@rajatogo"}]}
	 * 			</li>
	 * 		</ul>
	 * 	</li>
	 * 	<li>If everything goes fine and user association does not exist
	 * 		<ul>
	 * 			<li>{"status":0,"result":[]}</li>
	 * 		</ul>
	 * 	</li>
	 * </ul>
	 * Failure Responses:
	 * <ul>
	 * 	<li>If serial number does not exist
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Serial number does not exist"}</li>
	 * 		</ul>
	 * 	</li>
	 * </ul>
	 */
	public function actionGetAssociatedUser() {
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		$users_arr = array();
		foreach ($robot->usersRobots as $user_robots) {
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
	 * Parameters:
	 * <ul>
	 * 	<li><b>email</b> :User Email ID</li>
	 * 	<li><b>serial_number</b> :Serial Number of robot</li>
	 * </ul>
	 * Success Response:
	 * <ul>
	 * 	<li>If everything goes fine
	 * 		<ul>
	 * 			<li>{"status":0,"result":{"success":true,"message":"Robot
	 * 				ownership established successfully."}}</li>
	 * 		</ul>
	 * 	</li>
	 * 	<li>If ownership already exists.
	 * 		<ul>
	 * 			<li>{"status":0,"result":{"success":true,"message":"This robot
	 * 				ownership relation already exists."}}</li>
	 * 		</ul>
	 * 	</li>
	 * </ul>
	 * Failure Responses:
	 * <ul>
	 * 	<li>If Email is missing
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Missing parameter email in method
	 * 				robot.set_user"}</li>
	 * 		</ul>
	 * 	</li>
	 * 	<li>If Robot serial number is missing
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Missing parameter serial_number in
	 * 				method robot.set_user"}</li>
	 * 		</ul>
	 * </ul>
	 */
	public function actionSetUsers() {
		$user_email = Yii::app()->request->getParam('email', '');
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$user = User::model()->findByAttributes(array('email' => $user_email));
		if ($user !== null) {
			$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
			$user_robot = UsersRobot::model()->findByAttributes(array('id_user' => $user->id, 'id_robot' => $robot->id));
			if ($user_robot !== null) {
				$response_data = array("success" => true, "message" => self::yii_api_echo('This robot ownership relation already exists.'));
				self::success($response_data);
			} else {
				$user_robots = new UsersRobot();
				$user_robots->id_user = $user->id;
				$user_robots->id_robot = $robot->id;
				if ($user_robots->save()) {
					$response_data = array("success" => true, "message" => self::yii_api_echo('Robot ownership established successfully.'));
					self::success($response_data);
				}
			}
		} else {
			$response_message = self::yii_api_echo('Email does not exist');
			self::terminate(-1, $response_message, APIConstant::EMAIL_DOES_NOT_EXIST);
		}
	}

	/**
	 * API to disassociate users from robot
	 *
	 * Parameters:
	 * <ul>
	 * 	<li><b>serial_number</b> :Serial Number of robot</li>
	 * 	<li><b>email</b> :User's Email (If this field is empty, it will
	 * 		delete all user association for this particular robot)</li>
	 * </ul>
	 * Success Response:
	 * <ul>
	 * 	<li>If everything goes fine, user email provided and robot user
	 * 		association exist
	 * 		<ul>
	 * 			<li>{"status":0,"result":{"success":true,"message":"Robot User
	 * 				association removed successfully."}}</li>
	 * 		</ul>
	 * 	</li>
	 * 	<li>If everything goes fine, user email not provided and robot
	 * 		user association exist
	 * 		<ul>
	 * 			<li>{"status":0,"result":{"success":true,"message":"Robot
	 * 				association with all user removed successfully."}}</li>
	 * 		</ul>
	 * 	</li>
	 * 	<li>If everything goes fine and robot user association does not
	 * 		exist
	 * 		<ul>
	 * 			<li>{"status":0,"result":{"success":true,"message":"There is no
	 * 				association between provided robot and user"}}</li>
	 * 		</ul>
	 * 	</li>
	 * </ul>
	 * Failure Responses:
	 * <ul>
	 * 	<li>If serial number does not exist
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Serial number does not exist"}</li>
	 * 		</ul>
	 * 	</li>
	 * </ul>
	 */
	public function actionDisAssociateUser() {
		$robot_serial_no = Yii::app()->request->getParam('serial_number', '');
		$user_email = Yii::app()->request->getParam('email', '');
		$robot = self::verify_for_robot_serial_number_existence($robot_serial_no);
		if ($user_email) {
			$user = User::model()->findByAttributes(array('email' => $user_email));
			if ($user !== null) {
				$user_robot_delete = UsersRobot::model()->deleteAllByAttributes(array('id_user' => $user->id, 'id_robot' => $robot->id));
				if ($user_robot_delete) {
					$response_message = self::yii_api_echo('Robot User association removed successfully.');
				} else {
					$response_message = self::yii_api_echo('There is no association between provided robot and user');
				}
			} else {
				$response_message = self::yii_api_echo('Email does not exist.');
				self::terminate(-1, $response_message, APIConstant::EMAIL_DOES_NOT_EXIST);
			}
		} else {
			$user_robots_delete = UsersRobot::model()->deleteAllByAttributes(array('id_robot' => $robot->id));
			if ($user_robots_delete) {
				$response_message = self::yii_api_echo('Robot association with all user removed successfully.');
			} else {
				$response_message = self::yii_api_echo('There is no association between provided robot and user');
			}
		}
		$response_data = array("success" => true, "message" => $response_message);
		self::success($response_data);
	}

	/**
	 * Send start command to a particular robot.
	 * It is called by ajax call.
	 */
	public function actionSendStartCommand() {
		$chat_id = Yii::app()->request->getParam('chat_id', '');
		$to = AppHelper::two_way_string_decrypt($chat_id);
		$robot = Robot::model()->find('chat_id = :chat_id', array(':chat_id' => $to));

		$utc_str = gmdate("M d Y H:i:s", time());
		$utc = strtotime($utc_str);

		$key = Yii::app()->params['cleaning_command'];

		$xmpp_message_model = new XmppMessageLogs();
		$xmpp_message_model->save();
		$message = "<?xml version='1.0' encoding='UTF-8' standalone='yes' ?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>101</command><requestId>" . $xmpp_message_model->id . "</requestId><timeStamp>" . $utc . "</timeStamp><retryCount>0</retryCount><responseRequired>false</responseRequired><distributionMode>2</distributionMode><replyTo>" . Yii::app()->user->id . "</replyTo><params><cleaningModifier>1</cleaningModifier><cleaningMode>2</cleaningMode><cleaningCategory>2</cleaningCategory></params></request></payload></packet>";

		$xmpp_message_model->send_from = $robot->id;
		$xmpp_message_model->send_at = $utc;

		$xmpp_message_model->xmpp_message = $message;
		$xmpp_message_model->save();

		RobotCore::setRobotKeyValueDetail($robot, $key, $message, $utc);

		$user_id = Yii::app()->user->id;
		$user_data = User::model()->findByPk($user_id);
		$cause_agent_id = Yii::app()->session['cause_agent_id'];
		$message_to_set_robot_key_value = RobotCore::xmppMessageOfSetRobotProfile($robot, $cause_agent_id, $utc);
		$online_users_chat_ids = RobotCore::getOnlineUsers();
		RobotCore::sendXMPPMessageWhereUserSender($user_data, $robot, $message_to_set_robot_key_value, $online_users_chat_ids);

		$content = array('status' => 0);

		$this->renderPartial('/default/defaultView', array('content' => $content));
	}

	/**
	 * Send stop command to a particular robot.
	 * It is called by ajax call.
	 */
	public function actionSendStopCommand() {
		$chat_id = Yii::app()->request->getParam('chat_id', '');

		$to = AppHelper::two_way_string_decrypt($chat_id);

		$robot = Robot::model()->find('chat_id = :chat_id', array(':chat_id' => $to));

		$utc_str = gmdate("M d Y H:i:s", time());
		$utc = strtotime($utc_str);

		$key = Yii::app()->params['cleaning_command'];
		$message = "<?xml version='1.0' encoding='UTF-8' standalone='yes' ?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>102</command><requestId>aa8edd62-7eee-4cc0-9f5d-c34d0e0d6759</requestId><timeStamp>" . $utc . "</timeStamp><retryCount>0</retryCount><responseRequired>false</responseRequired><distributionMode>2</distributionMode><replyTo>" . Yii::app()->user->id . "</replyTo><params /></request></payload></packet>";

		RobotCore::setRobotKeyValueDetail($robot, $key, $message, $utc);

		$user_id = Yii::app()->user->id;
		$user_data = User::model()->findByPk($user_id);
		$cause_agent_id = Yii::app()->session['cause_agent_id'];
		$message_to_set_robot_key_value = RobotCore::xmppMessageOfSetRobotProfile($robot, $cause_agent_id, $utc);
		$online_users_chat_ids = RobotCore::getOnlineUsers();

		RobotCore::sendXMPPMessageWhereUserSender($user_data, $robot, $message_to_set_robot_key_value, $online_users_chat_ids);

		$content = array('status' => 0);

		$this->renderPartial('/default/defaultView', array('content' => $content));
	}

	/**
	 * Send to base command to a particular robot.
	 * It is called by ajax call.
	 */
	public function actionSendToBaseCommand() {
		$chat_id = Yii::app()->request->getParam('chat_id', '');
		$to = AppHelper::two_way_string_decrypt($chat_id);

		$robot = Robot::model()->find('chat_id = :chat_id', array(':chat_id' => $to));

		$utc_str = gmdate("M d Y H:i:s", time());
		$utc = strtotime($utc_str);

		$key = Yii::app()->params['cleaning_command'];
		$message = "<?xml version='1.0' encoding='UTF-8' standalone='yes' ?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>104</command><requestId>7990013f-e2a1-4942-ab0f-edd0afeffb1a</requestId><timeStamp>" . $utc . "</timeStamp><retryCount>0</retryCount><responseRequired>false</responseRequired><distributionMode>2</distributionMode><replyTo>" . Yii::app()->user->id . "</replyTo><params /></request></payload></packet>";

		RobotCore::setRobotKeyValueDetail($robot, $key, $message, $utc);

		$user_id = Yii::app()->user->id;
		$user_data = User::model()->findByPk($user_id);
		$cause_agent_id = Yii::app()->session['cause_agent_id'];
		$message_to_set_robot_key_value = RobotCore::xmppMessageOfSetRobotProfile($robot, $cause_agent_id, $utc);
		$online_users_chat_ids = RobotCore::getOnlineUsers();

		RobotCore::sendXMPPMessageWhereUserSender($user_data, $robot, $message_to_set_robot_key_value, $online_users_chat_ids);

		$content = array('status' => 0);

		$this->renderPartial('/default/defaultView', array('content' => $content));
	}

	public function actionRobotDataTable() {
		$user_role_id = Yii::app()->user->UserRoleId;
		$userColumns = array('id', 'serial_number');
		$userIndexColumn = "id";
		$userTable = "robots";
		$userDataModelName = 'Robot';

		if($user_role_id == '2'){
			if(($_GET['sSearch'] == "")){
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

		foreach ($result['rResult'] as $robot) {

			$row = array();

			$schedule = '';

			$select_checkbox = '<input type="checkbox" name="chooseoption[]" value="' . $robot->id . '" class="choose-option">';
			$serial_number = '<a rel="' . $this->createUrl('/robot/popupview', array('h' => AppHelper::two_way_string_encrypt($robot->id))) . '" href="' . $this->createUrl('/robot/view', array('h' => AppHelper::two_way_string_encrypt($robot->id))) . '" class="qtiplink robot-qtip" title="View details of (' . $robot->serial_number . ')">' . $robot->serial_number . '</a>';
			$robot_type = isset($robot->robotRobotTypes->robotType) ? $robot->robotRobotTypes->robotType->name . ' (' . $robot->robotRobotTypes->robotType->type . ')' : '';

			$associated_users = '';
			if ($robot->doesUserAssociationExist()) {
				$is_first_user = true;
				foreach ($robot->usersRobots as $value) {
					if (!$is_first_user) {
						$associated_users .= ",";
					}
					$is_first_user = false;
					$associated_users .= "<a class='single-item qtiplink' title='View details of (" . $value->idUser->email . ")' rel='" . $this->createUrl('/user/userprofilepopup', array('h' => AppHelper::two_way_string_encrypt($value->idUser->id))) . "' href='" . $this->createUrl('/user/userprofile', array('h' => AppHelper::two_way_string_encrypt($value->idUser->id))) . "'>" . $value->idUser->email . "</a>";
				}
			}


			if ($robot->doesScheduleExist()) {
				$schedule = '<a href="' . $this->createUrl('/robot/view', array('h' => AppHelper::two_way_string_encrypt($robot->id), 'scroll_to' => 'schedule_section')) . '" title="View schedule details of robot (' . $robot->serial_number . ')"> Yes </a>';
			}

			$edit = '<a href="' . $this->createUrl('/robot/update', array('h' => AppHelper::two_way_string_encrypt($robot->id))) . '" title="Edit robot ' . $robot->serial_number . '">edit</a>';
			
			$row[] = $select_checkbox;
			$row[] = $serial_number;			
			if($user_role_id != '2'){
				$row[] = $robot_type;
				$row[] = $associated_users;
				$row[] = $schedule;
				$row[] = $edit;
			}else{
				$row[] = $associated_users;
			}

			$output['aaData'][] = $row;
		}

		$this->renderPartial('/default/defaultView', array('content' => $output));
	}

	public function actionPingFromRobot() {

		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$status = Yii::app()->request->getParam('status', '');

		$message = 'robot ping have been recorded';

		$robot_ping_log = new RobotPingLog();
		$robot_ping_log->serial_number = $serial_number;
		$robot_ping_log->ping_timestamp = new CDbExpression('NOW()');
		$robot_ping_log->status = $status;
		$robot_ping_log->save();

		$response_data = array("success" => true, "message" => $message);
		self::success($response_data);
	}

	public function actionGetRobotTypeMetadataUsingType() {

		$robot_type = Yii::app()->request->getParam('robot_type', '');

		$robot_type_data = RobotTypes::model()->find('type = :type', array(':type' => $robot_type));

		if (!empty($robot_type_data)) {

			$metadata = array();
			foreach ($robot_type_data->robotTypeMetadatas as $value) {
				$metadata[$value->_key] = $value->value;
			}

			$response_data = array("success" => true, "robot_metadata" => array('type' => $robot_type_data->type, 'metadata' => $metadata));
			self::success($response_data);
		} else {
			self::terminate(-1, "Provided robot type is not valid", APIConstant::ROBOT_TYPE_NOT_VALID);
		}
	}

	public function actionGetRobotTypeMetadataUsingId() {

		$serial_number = Yii::app()->request->getParam('serial_number', '');

		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		$robot_robot_type = $robot->robotRobotTypes;

		$robot_type_data = RobotTypes::model()->findByPk($robot_robot_type->robot_type_id);

		if (!empty($robot_type_data)) {

			$metadata = array();
			foreach ($robot_type_data->robotTypeMetadatas as $value) {
				$metadata[$value->_key] = $value->value;
			}

			$response_data = array("success" => true, "robot_metadata" => array('type' => $robot_type_data->type, 'metadata' => $metadata));
			self::success($response_data);
		} else {
			self::terminate(-1, "Associated robot type does not exist", APIConstant::ROBOT_TYPE_NOT_VALID);
		}
	}

	public function actionDeleteType() {
		$chosen_type = Yii::app()->request->getParam('chosen_type', array());
		$result = RobotCore::deleteRobotType($chosen_type);
		$this->renderPartial('/default/defaultView', array('content' => $result));
		Yii::app()->end();
	}

	public function actionSetRobotConfiguration() {

		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$sleep_time = Yii::app()->request->getParam('sleep_time', '');
		$wakeup_time = Yii::app()->request->getParam('wakeup_time', '');
		$config_key_value = Yii::app()->request->getParam('config_key_value', '');

		if (!ctype_digit($sleep_time) || !ctype_digit($wakeup_time)) {
			self::terminate(-1, "Please enter valid sleep time or wakeup time", APIConstant::SLEEP_OR_WAKEUP_TIME_NOT_VALID);
		}

		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		$robot->sleep_time = $sleep_time;
		$robot->lag_time = $wakeup_time;

		if (!$robot->save()) {
			self::terminate(-1, "Set robot configuration failed due to database problem", APIConstant::CONFIGURATION_FAILED);
		}

		$utc = $robot->updated_on;

		if (!empty($config_key_value)) {

			foreach ($config_key_value as $key => $value) {
				$key = trim($key);
				$data = RobotConfigKeyValues::model()->find('_key = :_key AND robot_id = :robot_id', array(':_key' => $key, ':robot_id' => $robot->id));
				if (!empty($data)) {
					$data->value = $value;
					$data->update();
				} else {
					$robot_config_key_value = new RobotConfigKeyValues();
					$robot_config_key_value->robot_id = $robot->id;
					$robot_config_key_value->_key = $key;
					$robot_config_key_value->value = $value;
					$robot_config_key_value->save();
				}
			}
		}

		RobotCore::sendXmppMessageToAssociatesUsers($robot, $utc);

		$response_data = array(
				"success" => true,
				"timestamp" => $utc
		);

		self::success($response_data);
	}

	public function actionSetRobotConfiguration2() {

		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$sleep_time = Yii::app()->request->getParam('sleep_time', '');
		$wakeup_time = Yii::app()->request->getParam('wakeup_time', '');
		$config_key_value = Yii::app()->request->getParam('config_key_value', '');
		$robot_type = trim(Yii::app()->request->getParam('robot_type', ''));

		if (!ctype_digit($sleep_time) || !ctype_digit($wakeup_time)) {
			self::terminate(-1, "Please enter valid sleep time or wakeup time", APIConstant::SLEEP_OR_WAKEUP_TIME_NOT_VALID);
		}
		$serial_number = trim($serial_number);
		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		$robot_id = $robot->id;

		$robot_type_data = RobotTypes::model()->find('type = :type', array(':type' => $robot_type));

		if((!empty($robot_type) && empty($robot_type_data)) || ($robot_type == '0')){
			$message = "Robot Type is not valid";
			self::terminate(-1, $message, APIConstant::ROBOT_TYPE_NOT_VALID);
		}

		$robot->sleep_time = $sleep_time;
		$robot->lag_time = $wakeup_time;

		if (!$robot->save()) {
			self::terminate(-1, "Set robot configuration failed due to database problem", APIConstant::CONFIGURATION_FAILED);
		}

		$utc = $robot->updated_on;

		if(!empty($robot_type)){
			$robot_robot_type_data = RobotRobotTypes::model()->find('robot_id = :robot_id', array(':robot_id' => $robot_id));
			if (!empty($robot_robot_type_data)) {
				$robot_robot_type_data->robot_type_id = $robot_type_data->id;
				$robot_robot_type_data->update();
			}
		}

		if (!empty($config_key_value)) {
			foreach ($config_key_value as $key => $value) {
				$key = trim($key);
				$data = RobotConfigKeyValues::model()->find('_key = :_key AND robot_id = :robot_id', array(':_key' => $key, ':robot_id' => $robot->id));
				if (!empty($data)) {
					$data->value = $value;
					$data->update();
				} else {
					$robot_config_key_value = new RobotConfigKeyValues();
					$robot_config_key_value->robot_id = $robot->id;
					$robot_config_key_value->_key = $key;
					$robot_config_key_value->value = $value;
					$robot_config_key_value->save();
				}
			}
		}

		RobotCore::sendXmppMessageToAssociatesUsers($robot, $utc);

		$response_data = array(
				"success" => true,
				"timestamp" => $utc
		);

		self::success($response_data);
	}


	public function actionGetRobotConfiguration() {

		$serial_number = Yii::app()->request->getParam('serial_number', '');

		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		$result = RobotCore::getSleepLagTime($robot);

		$sleep_time = $result['sleep_time'];
		$lag_time = $result['lag_time'];

		$response_data = array(
				"success" => true,
				"serial_number" => $robot->serial_number,
				"sleep_time" => $sleep_time,
				"wakeup_time" => $lag_time,
				"timestamp" => $robot->updated_on,
		);

		$robotConfigKeyValues = RobotConfigKeyValues::model()->findAll('robot_id = :robot_id', array(':robot_id' => $robot->id));
		if (!empty($robotConfigKeyValues)) {
			foreach ($robotConfigKeyValues as $key_value) {
				$response_data['config_key_value'][$key_value->_key] = $key_value->value;
			}
		}

		self::success($response_data);
	}

	public function actionGetTokenForRobotUserAssociation() {

		$serial_number = Yii::app()->request->getParam('serial_number', '');

		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		$token = UniqueToken::hash(($robot->id + (hexdec(uniqid())) / 100000), 8);

		$token = preg_replace('/0*/', '', $token, 1);

		RobotUserAssociationTokens::model()->deleteAll('robot_id = :robot_id', array(':robot_id' => $robot->id));

		$robot_user_association_token = new RobotUserAssociationTokens();

		$robot_user_association_token->robot_id = $robot->id;
		$robot_user_association_token->token = $token;

		$robot_user_association_token->save();

		$response_data = array('success' => true, 'token' => $robot_user_association_token->token);

		self::success($response_data);
	}

	public function actionClearRobotAssociation() {

		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$is_delete = Yii::app()->request->getParam('is_delete', '');
		$email = Yii::app()->request->getParam('email', '');

		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		if (!AppHelper::is_valid_email($email)) {
			$message = self::yii_api_echo("The email address you have provided does not seem to be a valid email address.");
			self::terminate(-1, $message, APIConstant::EMAIL_NOT_VALID);
		}

		$user = User::model()->findByAttributes(array('email' => $email));
		if (empty($user)) {
			$response_message = self::yii_api_echo('Email does not exist in system');
			self::terminate(-1, $response_message, APIConstant::EMAIL_DOES_NOT_EXIST);
		}

		$user_robot_model = UsersRobot::model()->findByAttributes(array("id_user" => $user->id, "id_robot" => $robot->id));
		if (is_null($user_robot_model)) {
			self::terminate(-1, "Robot is not associated with given user.", APIConstant::USER_AND_ROBOT_ASSOCIATION_DOES_NOT_EXIST);
		}

		if ($is_delete == '0') {

			$robot->sleep_time = Yii::app()->params['default_sleep_time'];
			$robot->lag_time = Yii::app()->params['default_lag_time'];
			$robot->update();
			$robot_id_type = $robot->id;

			$robot_robot_type = RobotRobotTypes::model()->find('robot_id = :robot_id', array(':robot_id' => $robot_id_type));

			$default_robot_type = Yii::app()->params['default_robot_type'];
			$robot_defualt_type_id = RobotTypes::model()->find('type = :type', array(':type' => $default_robot_type));
			$defualt_robot_type_id = $robot_defualt_type_id->id;
			$current_robot_type_id = $robot_robot_type->robot_type_id;

			if ($current_robot_type_id != $defualt_robot_type_id) {
				$robot_robot_type->robot_type_id = $defualt_robot_type_id;
				$robot_robot_type->save();
			}

			RobotConfigKeyValues::model()->deleteAll('robot_id = :robot_id', array(':robot_id' => $robot_id_type));

			RobotKeyValues::model()->deleteAll('robot_id = :robot_id', array(':robot_id' => $robot_id_type));

			$robot_arr = array();
			foreach ($robot->robotSchedules as $user_robots) {
				$robot_arr[] = $user_robots->id;
			}

			foreach ($robot_arr as $robot_schedule) {
				RobotScheduleXmlDataVersion::model()->deleteAll('id= :id', array(':id' => $robot_schedule));
				RobotScheduleBlobDataVersion::model()->deleteAll('id= :id', array(':id' => $robot_schedule));
				RobotSchedule::model()->deleteAll('id= :id', array(':id' => $robot_schedule));
			}

			foreach ($robot->usersRobots as $user_robots) {
				$user_robots->delete();
			}

			$response_data = array('success' => true, 'message' => 'Robot is Cleaned.');
			self::success($response_data);
		}
		if ($is_delete == '1') {

			if (!empty($robot)) {
				Robot::model()->deleteAll('serial_number = :serial_number', array(':serial_number' => $serial_number));
				$response_data = array('success' => true, 'message' => 'Robot is Deleted.');
				self::success($response_data);
			}
		}
		if (($is_delete != '0') || ($is_delete != '1')){
			$message = "Please enter 1 for delete robot data and 0 for clear the robot data.";
			self::terminate(-1, $message, APIConstant::INVALID_DELETE_TYPE);
		}
	}

	public function actionRequestLinkCode() {

		$serial_number = Yii::app()->request->getParam('serial_number', '');

		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		$token = UniqueToken::hash(($robot->id + (hexdec(uniqid())) / 100000), 4);
		$token = preg_replace('/0*/', '', $token, 1);
		$token = trim($token);

		while (strlen($token) != 4) {
			$token = UniqueToken::hash(($robot->id + (hexdec(uniqid())) / 100000), 4);
			$token = trim($token);
		}

		RobotCore::removeExpiredLinkingCode($robot);

		$robot_user_association_token = RobotUserAssociationTokens::model()->find('robot_id = :robot_id', array(':robot_id' => $robot->id));

		if(empty($robot_user_association_token)){

			$robot_user_association_token = new RobotUserAssociationTokens();
			$robot_user_association_token->robot_id = $robot->id;
			$robot_user_association_token->token = $token;
			$robot_user_association_token->created_on = date('Y-m-d H:i:s');
			$robot_user_association_token->save();

		} else {

			$robot_linking_data = RobotLinkingCode::model()->find('serial_number = :serial_number', array(':serial_number' => $robot->serial_number));

			if(!empty($robot_linking_data)){

				$user_email = $robot_linking_data->email;

				$rejected_linking_code = $robot_linking_data->linking_code;

				$robot_linking_data->delete();

				$utc_str = gmdate("M d Y H:i:s", time());
				$utc = strtotime($utc_str);

				$xmpp_message_model = new XmppMessageLogs();
				$xmpp_message_model->save();
				$message = '<?xml version="1.0" encoding="UTF-8"?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>10003</command><requestId>' . $xmpp_message_model->id . '</requestId><timeStamp>' . $utc . '</timeStamp><retryCount>0</retryCount><responseNeeded>false</responseNeeded><distributionMode>2</distributionMode><params><robotId>' . $robot->serial_number . '</robotId><linkCode>' .$rejected_linking_code. '</linkCode></params></request></payload></packet>';
				$xmpp_message_model->send_from = $robot->id;
				$xmpp_message_model->send_at = $utc;
				$xmpp_message_model->xmpp_message = $message;
				$xmpp_message_model->save();

				$user_model = User::model()->findByAttributes(array("email" => $user_email));

				$user_chat_id = $user_model->chat_id;
				RobotCore::send_chat_message($robot->chat_id, $user_chat_id, $message);

				foreach ($robot->usersRobots as $usersRobot) {
					$user_chat_id = $usersRobot->idUser->chat_id;
					RobotCore::send_chat_message($robot->chat_id, $user_chat_id, $message);
				}

			}

			$robot_user_association_token->token = $token;
			$robot_user_association_token->created_on = date('Y-m-d H:i:s');
			$robot_user_association_token->save();

		}

		$validity_of_linking_code = RobotCore::getValidityOfLinkingCode($robot_user_association_token->created_on);

		$response_data = array('success' => true, 'linking_code' => $robot_user_association_token->token, 'expiry_time' => $validity_of_linking_code);
		self::success($response_data);

	}

	public function actionInitiateLinkToRobot() {

		$email = Yii::app()->request->getParam('email', '');
		$token = Yii::app()->request->getParam('linking_code', '');

		$utc_str = gmdate("M d Y H:i:s", time());
		$utc = strtotime($utc_str);

		if (!AppHelper::is_valid_email($email)) {

			$message = self::yii_api_echo("The email address you have provided does not appear to be a valid email address.");
			self::terminate(-1, $message, APIConstant::EMAIL_NOT_VALID);

		}

		$robot_user_association_token = RobotUserAssociationTokens::model()->find('token = :token', array(':token' => $token));
		$db_token = isset($robot_user_association_token) ? $robot_user_association_token->token : '';

		if (empty($robot_user_association_token)) {
			self::terminate(-1, "Please enter valid linking code", APIConstant::TOKEN_NOT_INVALID);
		}

		if(strcmp($token, $db_token) != 0){
			self::terminate(-1, "Please enter valid linking code", APIConstant::TOKEN_NOT_INVALID);
		}

		$robot_id = $robot_user_association_token->robot_id;
		$linking_code_created_on = $robot_user_association_token->created_on;

		$robot_model_data = Robot::model()->find('id = :id', array(':id' => $robot_id));

		if(isset($robot_user_association_token->created_on)){
			if(!RobotCore::isLinkingCodeValid($robot_user_association_token->created_on)){
				RobotCore::removeExpiredLinkingCode($robot_model_data);
				self::terminate(-1, "Sorry, provided linking code is expired", APIConstant::TOKEN_EXPIRED);
			}
		}else {
			self::terminate(-1, "Creation time of linking code does not exist in database", APIConstant::UNEXPECTED_ERROR);
		}

		$user_model = User::model()->findByAttributes(array("email" => $email));

		if (empty($user_model)) {
			self::terminate(-1, "Sorry, provided email does not exist", APIConstant::EMAIL_DOES_NOT_EXIST);
		}

		$user_id = $user_model->id;
		$user_robot_model = UsersRobot::model()->findByAttributes(array("id_user" => $user_id, "id_robot" => $robot_id));

		if ((!is_null($user_robot_model))) {

			self::terminate(-1, "Association for Robot-user pair already exists", APIConstant::ROBOT_USER_ASSOCIATION_ALREADY_EXIST);

		}

		RobotCore::removeExpiredLinkingCode($robot_model_data);

		$default_state = Yii::app()->params['default_linking_process'];

		$robot_linking_data = RobotLinkingCode::model()->findAll('email = :email', array(':email' => $email));

		if (!empty($robot_linking_data)) {

			foreach ($robot_linking_data as $robot_linking) {

				$current_linking_state = $robot_linking->current_linking_state;
				if (($current_linking_state == $default_state)) {
					if($robot_linking->linking_code == $token){
						self::terminate(-1, "Linking process is going on for the robot", APIConstant::LINKING_CODE_PROCESS);
					}
				}

			}

		}

		$from = $user_model->chat_id;

		$robot_linking_code = RobotLinkingCode::model()->find('linking_code = :linking_code', array(':linking_code' => $token));

		if (isset($robot_linking_code->email) && $robot_linking_code->email == $email) {
			self::terminate(-1, "Requested linking code is already used", APIConstant::TOKEN_ALREADY_USED);
		}

		$associated_robot_serial_number = $robot_model_data->serial_number;
		$to = $robot_model_data->chat_id;

		$robot_linking_code = new RobotLinkingCode();

		$robot_linking_code->email = $email;
		$robot_linking_code->serial_number = $associated_robot_serial_number;
		$robot_linking_code->current_linking_state = $default_state;
		$robot_linking_code->linking_code = $token;
		$robot_linking_code->linking_code_created_on = $linking_code_created_on;
		$robot_linking_code->timestamp = new CDbExpression('NOW()');

		$robot_linking_code->save();

		$xmpp_message_model = new XmppMessageLogs();
		$xmpp_message_model->save();
		$message = '<?xml version="1.0" encoding="UTF-8"?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>10001</command><requestId>' . $xmpp_message_model->id . '</requestId><timeStamp>' . $utc . '</timeStamp><retryCount>0</retryCount><responseNeeded>false</responseNeeded><distributionMode>2</distributionMode><params><emailId>' . $email . '</emailId><linkCode>' . $token . '</linkCode></params></request></payload></packet>';
		$xmpp_message_model->send_from = $user_model->id;
		$xmpp_message_model->send_at = $utc;
		$xmpp_message_model->xmpp_message = $message;
		$xmpp_message_model->save();

		$validity_of_linking_code = RobotCore::getValidityOfLinkingCode($robot_user_association_token->created_on);

		$response_data = array('success' => true, 'serial_number' => $robot_model_data->serial_number,'expiry_time' => $validity_of_linking_code, 'message' => 'Request For Robot-User association is done successfully');

		RobotCore::send_chat_message($from, $to, $message);

		self::success($response_data);
	}

	public function actionLinkToRobot() {

		$email = Yii::app()->request->getParam('email', '');
		$token = Yii::app()->request->getParam('linking_code', '');

		$utc_str = gmdate("M d Y H:i:s", time());
		$utc = strtotime($utc_str);

		if (!AppHelper::is_valid_email($email)) {

			$message = self::yii_api_echo("The email address you have provided does not appear to be a valid email address.");
			self::terminate(-1, $message, APIConstant::EMAIL_NOT_VALID);
		}

		$robot_user_association_token = RobotUserAssociationTokens::model()->find('token = :token', array(':token' => $token));
		$db_token = isset($robot_user_association_token) ? $robot_user_association_token->token : '';

		if (empty($robot_user_association_token)) {
			self::terminate(-1, "Please enter valid linking code", APIConstant::TOKEN_NOT_INVALID);
		}

		if(strcmp($token, $db_token) != 0){
			self::terminate(-1, "Please enter valid linking code", APIConstant::TOKEN_NOT_INVALID);
		}

		$robot_id = $robot_user_association_token->robot_id;

		$robot_model_data = Robot::model()->find('id = :id', array(':id' => $robot_id));

		if(isset($robot_user_association_token->created_on)){
			if(!RobotCore::isLinkingCodeValid($robot_user_association_token->created_on)){
				RobotCore::removeExpiredLinkingCode($robot_model_data);
				self::terminate(-1, "Sorry, provided linking code is expired", APIConstant::TOKEN_EXPIRED);
			}
		}else {
			self::terminate(-1, "Creation time of linking code does not exist in database", APIConstant::UNEXPECTED_ERROR);
		}

		$user_model = User::model()->findByAttributes(array("email" => $email));

		if (empty($user_model)) {
			self::terminate(-1, "Sorry, provided email does not exist", APIConstant::EMAIL_DOES_NOT_EXIST);
		}

		$user_id = $user_model->id;

		$user_robot_model = UsersRobot::model()->findByAttributes(array("id_user" => $user_id, "id_robot" => $robot_id));
		if ((!is_null($user_robot_model))) {
			self::terminate(-1, "Association for Robot-user pair already exists", APIConstant::ROBOT_USER_ASSOCIATION_ALREADY_EXIST);
		}

		$user_robot_model = UsersRobot::model()->findByAttributes(array("id_robot" => $robot_id));
		if ((!is_null($user_robot_model))) {
			self::terminate(-1, "Robot already has a user associated with it.", APIConstant::ROBOT_ALREADY_HAS_A_USER_ASSOCIATED);
		}

		RobotCore::removeExpiredLinkingCode($robot_model_data);

		$online_users_chat_ids = RobotCore::getOnlineUsers();
		if (!in_array($robot_model_data->chat_id, $online_users_chat_ids)) {
			self::terminate(-1, "Robot is offline", APIConstant::OFFLINE_ROBOT);
		}

		$requested_user_email = $email;

		$xmpp_message_model = new XmppMessageLogs();
		$xmpp_message_model->save();
		$xmpp_message = '<?xml version="1.0" encoding="UTF-8"?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>10004</command><requestId>' . $xmpp_message_model->id . '</requestId><timeStamp>' . $utc . '</timeStamp><retryCount>0</retryCount><responseNeeded>false</responseNeeded><distributionMode>2</distributionMode><params><robotId>' . $robot_model_data->serial_number . '</robotId>><emailId>' . $requested_user_email . '</emailId></params></request></payload></packet>';
		$xmpp_message_model->send_from = $robot_id;
		$xmpp_message_model->send_at = $utc;
		$xmpp_message_model->xmpp_message = $xmpp_message;
		$xmpp_message_model->save();

		if (!empty($robot_model_data->usersRobots)) {
			foreach ($robot_model_data->usersRobots as $user_robots) {
				$user_chat_id = $user_robots->idUser->chat_id;
				RobotCore::send_chat_message($robot_model_data->chat_id, $user_chat_id, $xmpp_message);
			}
		}
		$user_robot_obj = new UsersRobot();
		$user_robot_obj->id_user = $user_id;
		$user_robot_obj->id_robot = $robot_id;
		$user_robot_obj->save();

		$response_data = array('success' => true, 'serial_number' => $robot_model_data->serial_number, 'message' => 'Robot-User association is done successfully');

		$xmpp_message_model = new XmppMessageLogs();
		$xmpp_message_model->save();
		$message = '<?xml version="1.0" encoding="UTF-8"?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>10001</command><requestId>' . $xmpp_message_model->id . '</requestId><timeStamp>' . $utc . '</timeStamp><retryCount>0</retryCount><responseNeeded>false</responseNeeded><distributionMode>2</distributionMode><params><emailId>' . $email . '</emailId><linkCode>' . $token . '</linkCode></params></request></payload></packet>';
		$xmpp_message_model->send_from = $user_model->id;
		$xmpp_message_model->send_at = $utc;
		$xmpp_message_model->xmpp_message = $message;
		$xmpp_message_model->save();

		RobotCore::send_chat_message($user_model->chat_id, $robot_model_data->chat_id, $message);

		$xmpp_message_model = new XmppMessageLogs();
		$xmpp_message_model->save();
		$message = '<?xml version="1.0" encoding="UTF-8"?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>10002</command><requestId>' . $xmpp_message_model->id . '</requestId><timeStamp>' . $utc . '</timeStamp><retryCount>0</retryCount><responseNeeded>false</responseNeeded><distributionMode>2</distributionMode><params><robotId>' . $robot_model_data->serial_number . '</robotId><linkCode>' . $token . '</linkCode></params></request></payload></packet>';
		$xmpp_message_model->send_from = $robot_id;
		$xmpp_message_model->send_at = $utc;
		$xmpp_message_model->xmpp_message = $message;
		$xmpp_message_model->save();

		RobotCore::send_chat_message($robot_model_data->chat_id, $user_model->chat_id, $message);

		RobotCore::removeLinkingCode($robot_model_data);

		self::success($response_data);
	}

	public function actionConfirmLinking() {

		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$linking_code = Yii::app()->request->getParam('linking_code', '');

		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		$utc_str = gmdate("M d Y H:i:s", time());
		$utc = strtotime($utc_str);

		$from = $robot->chat_id;

		$robot_user_association_tokens = RobotUserAssociationTokens::model()->find('token = :token', array(':token' => $linking_code));
		$db_token = isset($robot_user_association_tokens) ? $robot_user_association_tokens->token : '';

		if (empty($robot_user_association_tokens)) {
			self::terminate(-1, "Please enter valid linking code", APIConstant::TOKEN_NOT_INVALID);
		}

		if(strcmp($linking_code, $db_token) != 0){
			self::terminate(-1, "Please enter valid linking code", APIConstant::TOKEN_NOT_INVALID);
		}

		$associated_robot_ids = $robot_user_association_tokens->robot_id;

		$robot_model_data = Robot::model()->find('id = :id', array(':id' => $associated_robot_ids));

		$associated_robot_serial_number = $robot_model_data->serial_number;

		if(isset($robot_user_association_tokens->created_on)){
			if(!RobotCore::isLinkingCodeValid($robot_user_association_tokens->created_on)){
				RobotCore::removeExpiredLinkingCode($robot_model_data);
				self::terminate(-1, "Sorry, provided linking code is expired", APIConstant::TOKEN_EXPIRED);
			}
		}else {
			self::terminate(-1, "Creation time of linking code does not exist in database", APIConstant::UNEXPECTED_ERROR);
		}

		$robot_linking_code_data = RobotLinkingCode::model()->find('linking_code = :linking_code', array(':linking_code' => $linking_code));

		if (empty($robot_linking_code_data)) {
			self::terminate(-1, "Please enter valid linking code", APIConstant::TOKEN_NOT_INVALID);
		}

		if ($associated_robot_serial_number == $serial_number) {

			$robot_linking_code_data->current_linking_state = '0';
			$robot_linking_code_data->update();

		} else {

			self::terminate(-1, "Please enter valid serial number", APIConstant::SERIAL_NUMBER_DOES_NOT_EXIST);

		}

		$requested_user_email = $robot_linking_code_data->email;

		$user_model = User::model()->findByAttributes(array("email" => $requested_user_email));

		$robot_user_association_token = RobotUserAssociationTokens::model()->find('token = :token', array(':token' => $linking_code));

		$user_id = $user_model->id;
		$robot_id = $robot_user_association_token->robot_id;

		$user_robot_model = UsersRobot::model()->findByAttributes(array("id_user" => $user_id, "id_robot" => $robot_id));
		if ((!is_null($user_robot_model))) {

			RobotCore::removeLinkingCode($robot);
			self::terminate(-1, "Association for Robot-user pair already exists", APIConstant::ROBOT_USER_ASSOCIATION_ALREADY_EXIST);

		} else if ($associated_robot_serial_number == $serial_number) {

			$xmpp_message_model = new XmppMessageLogs();
			$xmpp_message_model->save();
			$xmpp_message = '<?xml version="1.0" encoding="UTF-8"?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>10004</command><requestId>' . $xmpp_message_model->id . '</requestId><timeStamp>' . $utc . '</timeStamp><retryCount>0</retryCount><responseNeeded>false</responseNeeded><distributionMode>2</distributionMode><params><robotId>' . $robot->serial_number . '</robotId>><emailId>' . $requested_user_email . '</emailId></params></request></payload></packet>';
			$xmpp_message_model->send_from = $robot_id;
			$xmpp_message_model->send_at = $utc;
			$xmpp_message_model->xmpp_message = $xmpp_message;
			$xmpp_message_model->save();

			if (!empty($robot->usersRobots)) {

				foreach ($robot->usersRobots as $user_robots) {

					$user_chat_id = $user_robots->idUser->chat_id;
					RobotCore::send_chat_message($from, $user_chat_id, $xmpp_message);

				}
			}

			$user_robot_obj = new UsersRobot();
			$user_robot_obj->id_user = $user_id;
			$user_robot_obj->id_robot = $robot_id;
			$user_robot_obj->save();

			$response_data = array('success' => true, 'message' => 'Robot-User association is done successfully');
		} else {

			self::terminate(-1, "Sorry, provided serial number is not match", APIConstant::SERIAL_NUMBER_DOES_NOT_EXIST);
		}

		$xmpp_message_model = new XmppMessageLogs();
		$xmpp_message_model->save();
		$message = '<?xml version="1.0" encoding="UTF-8"?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>10002</command><requestId>' . $xmpp_message_model->id . '</requestId><timeStamp>' . $utc . '</timeStamp><retryCount>0</retryCount><responseNeeded>false</responseNeeded><distributionMode>2</distributionMode><params><robotId>' . $robot->serial_number . '</robotId><linkCode>' . $linking_code . '</linkCode></params></request></payload></packet>';
		$xmpp_message_model->send_from = $robot_id;
		$xmpp_message_model->send_at = $utc;
		$xmpp_message_model->xmpp_message = $message;
		$xmpp_message_model->save();

		RobotCore::send_chat_message($from, $user_model->chat_id, $message);

		RobotCore::removeLinkingCode($robot);

		self::success($response_data);
	}

	public function actionRejectLinking() {

		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$token = Yii::app()->request->getParam('linking_code', '');

		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		$utc_str = gmdate("M d Y H:i:s", time());
		$utc = strtotime($utc_str);

		$from = $robot->chat_id;

		$robot_linking_code_data = RobotLinkingCode::model()->find('linking_code = :linking_code', array(':linking_code' => $token));
		$db_token = isset($robot_linking_code_data) ? $robot_linking_code_data->linking_code : '';

		if (empty($robot_linking_code_data)) {
			self::terminate(-1, "Please enter valid linking code", APIConstant::TOKEN_NOT_INVALID);
		}

		if(strcmp($token, $db_token) != 0){
			self::terminate(-1, "Please enter valid linking code", APIConstant::TOKEN_NOT_INVALID);
		}

		$user_email = $robot_linking_code_data->email;
		$associated_robot_serial_number = $robot_linking_code_data->serial_number;

		$xmpp_message_model = new XmppMessageLogs();
		$xmpp_message_model->save();
		$message = '<?xml version="1.0" encoding="UTF-8"?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>10003</command><requestId>' . $xmpp_message_model->id . '</requestId><timeStamp>' . $utc . '</timeStamp><retryCount>0</retryCount><responseNeeded>false</responseNeeded><distributionMode>2</distributionMode><params><robotId>' . $robot->serial_number . '</robotId><linkCode>' .$token. '</linkCode></params></request></payload></packet>';
		$xmpp_message_model->send_from = $robot->id;
		$xmpp_message_model->send_at = $utc;
		$xmpp_message_model->xmpp_message = $message;
		$xmpp_message_model->save();

		if ($associated_robot_serial_number == $serial_number) {

			RobotLinkingCode::model()->deleteAll('linking_code = :linking_code', array(':linking_code' => $token));
			$msg = 'linking_code was not accepted';
			$response_data = array('success' => true, 'message' => $msg);

			$user_model = User::model()->findByAttributes(array("email" => $user_email));

			$user_chat_id = $user_model->chat_id;
			RobotCore::send_chat_message($from, $user_chat_id, $message);

			foreach ($robot->usersRobots as $usersRobot) {
				$user_chat_id = $usersRobot->idUser->chat_id;
				RobotCore::send_chat_message($from, $user_chat_id, $message);
			}

			self::success($response_data);

		} else {

			self::terminate(-1, "Sorry, provided serial number is not match", APIConstant::SERIAL_NUMBER_DOES_NOT_EXIST);

		}
	}

	public function actionCancelLinking() {

		$serial_number = Yii::app()->request->getParam('serial_number', '');

		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		$from = $robot->chat_id;

		$utc_str = gmdate("M d Y H:i:s", time());
		$utc = strtotime($utc_str);

		$robot_linking_code_data = RobotUserAssociationTokens::model()->find('robot_id = :robot_id', array(':robot_id' => $robot->id));

		if(empty($robot_linking_code_data)){
			self::terminate(-1, "Sorry, provided serial number is not match", APIConstant::SERIAL_NUMBER_DOES_NOT_EXIST);
		}

		$rejected_linking_code = $robot_linking_code_data->token;

		$robot_linking_code_data = RobotLinkingCode::model()->find('serial_number = :serial_number', array(':serial_number' => $serial_number));

		RobotCore::removeLinkingCode($robot);

		if(!empty($robot_linking_code_data)) {

			$xmpp_message_model = new XmppMessageLogs();
			$xmpp_message_model->save();
			$message = '<?xml version="1.0" encoding="UTF-8"?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>10003</command><requestId>' . $xmpp_message_model->id . '</requestId><timeStamp>' . $utc . '</timeStamp><retryCount>0</retryCount><responseNeeded>false</responseNeeded><distributionMode>2</distributionMode><params><robotId>' . $robot->serial_number . '</robotId><linkCode>' .$rejected_linking_code. '</linkCode></params></request></payload></packet>';
			$xmpp_message_model->send_from = $robot->id;
			$xmpp_message_model->send_at = $utc;
			$xmpp_message_model->xmpp_message = $message;
			$xmpp_message_model->save();

			$user_model = User::model()->findByAttributes(array("email" => $robot_linking_code_data->email));

			$user_chat_id = $user_model->chat_id;
			RobotCore::send_chat_message($from, $user_chat_id, $message);

			foreach ($robot->usersRobots as $usersRobot) {
				$user_chat_id = $usersRobot->idUser->chat_id;
				RobotCore::send_chat_message($from, $user_chat_id, $message);
			}

		}

		$msg = 'Discard the generated link_code.';
		$response_data = array('success' => true, 'message' => $msg);

		self::success($response_data);
	}

	public function actionRobotCurrentStatus() {
		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($serial_number);
		$robot_status = RobotCore::checkRobotStatus($robot);
		$this->renderPartial('/default/defaultView', array('content' => $robot_status));
	}

	public function actionIsRobotAlive(){
		if (Yii::app()->user->getIsGuest()) {
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		$serial_number = Yii::app()->request->getParam('robotSerailNo', '');

		$robot = self::verify_for_robot_serial_number_existence($serial_number);
		$utc = gmdate("M d Y H:i:s", time());
		$xmpp_message_model = new XmppMessageLogs();
		$xmpp_message_model->save();

		$message = "<?xml version='1.0' encoding='UTF-8' standalone='yes' ?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>105</command><requestId>" . $xmpp_message_model->id . "</requestId><timeStamp>" . $utc . "</timeStamp><retryCount>0</retryCount><responseRequired>false</responseRequired><distributionMode>2</distributionMode></request></payload></packet>";

		$xmpp_message_model->send_from = $robot->id;
		$xmpp_message_model->send_at = $utc;

		$xmpp_message_model->xmpp_message = $message;
		$xmpp_message_model->save();

		$user_id = Yii::app()->user->id;
		$user_data = User::model()->findByPk($user_id);
		RobotCore::send_chat_message($user_data->chat_id, $robot->chat_id, $message);

		$response = array('code' => 0, 'message' => 'Send XMPP message successfully');
		$this->renderPartial('/default/defaultView', array('content' => $response));
	}

	public function actionCheckRobotAvailability(){

		if (Yii::app()->user->getIsGuest()) {
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		$serial_number = Yii::app()->request->getParam('robotSerailNo', '');

		$alive_robot = AliveRobot::model()->findByAttributes(array('serial_number' => $serial_number));

		$response = array('code' => 0, 'message' => 'Robot is alive');
		if(empty($alive_robot)) {
			$response = array('code' => -1, 'message' => 'Robot is dead');
		}else {
			$alive_robot->delete();
		}
		$this->renderPartial('/default/defaultView', array('content' => $response));
	}

	public function actionAliveRobot(){

		if (Yii::app()->user->getIsGuest()) {
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		$model = new AliveRobot();
		$model->serial_number = $serial_number;

		if($model->save()) {
			$response_message = self::yii_api_echo('Robot is alive');
			$response_data = array("success" => true, "message"=> $response_message);
			self::success($response_data);
		}else {
			$response_message = self::yii_api_echo('Robot is dead');
			$response_data = array("success" => true, "message"=> $response_message);
			self::success($response_data);
		}
	}

}
