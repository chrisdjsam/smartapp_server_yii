<?php

/**
 * The API MessageController is meant for all message related API actions.
 */
class MessageController extends APIController {
	/**
	 * This method is used to send XMPP message to robot.
	 * Parameters:
	 * <ul>
	 * 	<li><b>api_key</b> 		:Your API Key</li>
	 * 	<li><b>user_id</b> 		:ID of user sending message.</li>
	 * 	<li><b>serial_number</b>:serial_number of robot to whom user want to send message.</li>
	 * 	<li><b>message</b> 		:Message Text</li>
	 * 	</li>
	 * </ul>
	 *
	 *
	 * Success Response:
	 * <ul>
	 * 	<li>If message is sent to robot
	 * 		<ul>
	 * 			<li>
	 * 				{"status":0,"result":{"success":true,"message":"Message is sent to robot 1."}}
	 * 			</li>
	 * 		</ul>
	 * 	</li>
	 * </ul>
	 *
	 * Failure Responses: <br />
	 * <ul>
	 * 	<li>If API Key is missing:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Method call failed the API
	 * 				Authentication"}</li>
	 * 		</ul>
	 * 	</li>
	 *
	 * 	<li>If serial_nubmer not found:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Robot serial number does not exist"}</li>
	 * 		</ul>
	 * 	</li>
	 *
	 * 	<li>If user_id not found:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"User ID does not exist"}</li>
	 * 		</ul>
	 * 	</li>
	 *
	 * 	<li>If user and robot association not exist:
	 *		<ul>
	 *			<li>{"status":-1,"message":"User robot association does not exist."}</li>
	 *		</ul>
	 *	</li>
	 * 	<li>If message sending failed:
	 * 		<ul>
	 * 			<li>{"status":-1,"message":"Message could not be sent to robot sr1."}</li>
	 * 		</ul>
	 * 	</li>
	 * </ul>
	 */
	public function actionSendXmppMessageToRobot(){
		$id_user = Yii::app()->request->getParam('user_id', '');
		$user = self::verify_for_user_id_existence($id_user);

		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		if(!self::check_for_user_robot_association($id_user, $serial_number)){
			$response_message = "User robot association does not exist.";
			self::terminate(-1, $response_message, APIConstant::USER_AND_ROBOT_ASSOCIATION_DOES_NOT_EXIST);
		}

		$message = Yii::app()->request->getParam('message', '');

		$status = RobotCore::send_chat_message($user->chat_id, $robot->chat_id, $message);
		if($status){
			$response_message = "Message is sent to robot $serial_number.";
			$response_data = array("success"=>true, "message"=>$response_message);
			self::success($response_data);
		}else{
			$response_message = "Message could not be sent to robot $serial_number.";
			self::terminate(-1, $response_message, APIConstant::MESSAGE_SENDING_FAILED);
		}

	}

	/**
	 * Check is user robot association exist or not.
	 * @param int $user_id
	 * @param string $serial_number
	 * @return boolean
	 */
	protected function check_for_user_robot_association($user_id, $serial_number){
		$user = User::model()->findByPk($user_id);

		foreach($user->usersRobots as $usersRobot){
			if($serial_number == $usersRobot->idRobot->serial_number){
				return true;
			}
		}
		return false;
	}

	/**
	 * This method would send a message to all associated users of robot.
	 *
	 * * 	Parameters:
	 * 	<ul>
	 * 		<li><b>api_key</b> 		:Your API Key</li>
	 * 		<li><b>serial_number</b>:Serial number of robot sending robot.</li>
	 * 		<li><b>message_type</b> :Type of message (Only XMPP is supported for now).</li>
	 * 		<li><b>message</b> 		:Message Text.</li>
	 *
	 * 	</ul>
	 *
	 *
	 * 	Success Response:
	 * 	<ul>
	 * 		<li>If message is sent to users
	 * 			<ul>
	 * 				<li>
	 * 					{"status":0,"result":{"success":true,"message":"Message is sent to 3 user(s)."}}
	 * 				</li>
	 * 			</ul>
	 * 		</li>
	 * 	</ul>
	 *
	 * 	Failure Responses: <br />
	 * 	<ul>
	 * 		<li>If API Key is missing:
	 * 			<ul>
	 * 				<li>{"status":-1,"message":"Method call failed the API
	 * 					Authentication"}</li>
	 * 			</ul>
	 * 		</li>
	 *
	 * 		<li>If sending failed:
	 * 			<ul>
	 * 				<li>{"status":-1,"message":"Message is sent to 0 user(s)."}</li>
	 * 			</ul>
	 * 		</li>
	 *
	 * 		<li>If serial_nubmer not found:
	 * 			<ul>
	 * 				<li>{"status":-1,"message":"Robot serial number does not exist"}</li>
	 * 			</ul>
	 * 		</li>
	 *
	 * 		<li>If message_type does not match:
	 * 			<ul>
	 * 				<li>{"status":-1,"message":"XMP does not match supported message type XMPP"}</li>
	 * 			</ul>
	 * 		</li>
	 *
	 * 	</ul>
	 *
	 */
	public function actionSendMessageToAssociatedUsers(){

		$message_type = Yii::app()->request->getParam('message_type', '');

		if(!in_array($message_type, array('XMPP','xmpp','Xmpp'))){
			$response_message = "$message_type does not match supported message type XMPP";
			self::terminate(-1, $response_message, APIConstant::MESSAGE_TYPE_DOES_NOT_MATCH);
		}

		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		$message = Yii::app()->request->getParam('message', '');
		$count= 0;
		foreach ($robot->usersRobots as $userRobot){
			$count += RobotCore::send_chat_message($robot->chat_id, $userRobot->idUser->chat_id, $message);
		}

		$response_message = "Message is sent to $count user(s).";
		if($count){
			$response_data = array("success"=>true, "message"=>$response_message);
			self::success($response_data);
		}else{
			self::terminate(-1, $response_message, APIConstant::MESSAGE_SENDING_FAILED);
		}
	}

	public function actionSendXMPPMessageToAllAssociatedUsers2(){

		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$only_online = Yii::app()->request->getParam('only_online', '');
		$message = Yii::app()->request->getParam('message', '');

		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		$count= 0;
		foreach ($robot->usersRobots as $userRobot){
			$count += RobotCore::send_chat_message($robot->chat_id, $userRobot->idUser->chat_id, $message);
		}

		$response_message = "Message is sent to $count user(s).";
		$response_data = array("success"=>true, "message"=>$response_message);
		self::success($response_data);
	}

	public function actionSendNotificationToGivenRegistrationIds(){

		$registration_ids = array_values( array_filter(array_unique(Yii::app()->request->getParam('registration_ids', ''))) );
		$message = Yii::app()->request->getParam('message', '');

		if(empty($registration_ids)){
			self::terminate(-1, 'Provide at least one registration id', APIConstant::PARAMETER_MISSING);
		}

		$response = AppCore::send_notification_to_given_registration_ids($registration_ids, $message);

		if($response['code'] == 1){
			self::terminate(-1, $response['output'], APIConstant::REGISTRATION_IDS_NOT_VALID);
		}

		$response_data = array("success"=>true, "message"=>$response['output']);
		self::success($response_data);

	}

	public function actionSendNotificationToAllUsersOfRobot2(){

		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$message_oject = json_decode(Yii::app()->request->getParam('message', ''));

		if($message_oject === null) {
			self::terminate(-1, "The json message you have provided does not appear to be a valid.", APIConstant::JSON_OBJECT_NOT_VALID);
		}

		$robot = self::verify_for_robot_serial_number_existence($serial_number);

		$user_ids_to_send_notification = Array();
		foreach ($robot->usersRobots as $user) {
			// check for user's overall push notification preference
			if($user->idUser->push_notification_preference == 1){
				$user_ids_to_send_notification[] = $user->id_user;
			}
		}

		$message_description = Array();

		if(isset($message_oject->notifications)){
			foreach ($message_oject->notifications as $value) {
				if(isset($value->id)){
					$message_data = PushNotificationTypes::model()->find('id = :id', array(':id' => $value->id));
					if(!empty($message_data)){
						$message_description[$value->id] = $message_data->description;
					}
				}else{
					self::terminate(-1, "Provided JSON does not contain considered keys i.e 'id'.", APIConstant::JSON_WITH_INVALID_KEYS);
				}
			}
		}else {
			self::terminate(-1, "Provided JSON does not contain considered keys i.e 'notifications'.", APIConstant::JSON_WITH_INVALID_KEYS);
		}

		if(empty($message_description)) {
			self::terminate(-1, "Sorry, json which you have provided in message parameter is invalid", APIConstant::JSON_OBJECT_NOT_VALID);
		}

		$send_from = Array();
		$send_from['type'] = 'robot';
		$send_from['data'] = $serial_number;

		$response = AppCore::send_notification_to_all_users_of_robot2($user_ids_to_send_notification, $message_description, $send_from);

		if($response['code'] == 1){
			self::terminate(-1, $response['output'], APIConstant::MESSAGE_SENDING_FAILED);
		}

		$response_data = array("success"=>true);
		self::success($response_data);

	}

	public function actionSendNotificationToGivenEmails(){

		$emails = array_values( array_filter(array_unique(Yii::app()->request->getParam('emails', ''))) );
		$message = trim(Yii::app()->request->getParam('message', ''));

		if(!empty($emails)){
			$invalid_emails = Array();
			foreach ($emails as $email) {
				if(!AppHelper::is_valid_email($email)){
					$invalid_emails[] = $email;
				}
			}
			if(!empty($invalid_emails)){
				self::terminate(-1, 'Please provide valid email address (Invalid emails: ' . json_encode($invalid_emails) . ')', APIConstant::EMAIL_NOT_VALID);
			}

		} else {
			self::terminate(-1, 'Please provide at least one email address', APIConstant::PARAMETER_MISSING);
		}

		if(empty($message)){
			self::terminate(-1, 'Message field can not be blank', APIConstant::PARAMETER_MISSING);
		}

		$response = AppCore::send_notification_to_given_emails($emails, $message);

		if($response['code'] == 1){
			self::terminate(-1, $response['output'], APIConstant::MESSAGE_SENDING_FAILED);
		}

		$response_data = array("success"=>true, "message"=>$response['output']);
		self::success($response_data);

	}

	public function actionNotificationRegistration() {

		$user_email = Yii::app()->request->getParam('user_email', '');
		$registration_id = Yii::app()->request->getParam('registration_id', '');
		$device_type = Yii::app()->request->getParam('device_type', '');
		$application_id = Yii::app()->request->getParam('application_id', '');
		$notification_server_type = Yii::app()->request->getParam('notification_server_type', '');

		if($application_id == ''){
			$application_id = isset($_SERVER['HTTP_APPLICATION_ID'])?$_SERVER['HTTP_APPLICATION_ID']:$application_id;
		}
		if($notification_server_type == ''){
			$notification_server_type = isset($_SERVER['HTTP_NOTIFICATION_SERVER_TYPE'])?$_SERVER['HTTP_NOTIFICATION_SERVER_TYPE']:$notification_server_type;
		}

		if (!AppHelper::is_valid_email($user_email)) {
			self::terminate(-1, 'Please enter valid email address.', APIConstant::EMAIL_NOT_VALID);
		}

		$user_data = User::model()->findByAttributes(array('email' => $user_email));

		if (empty($user_data)) {
			self::terminate(-1, 'Sorry, Provided user email address does not exist in our system.', APIConstant::EMAIL_DOES_NOT_EXIST);
		}

		$response = AppCore::store_registration_id($user_data->id, $registration_id, $device_type, $application_id, $notification_server_type);

		$response_data = array("success" => true, "message" => $response);
		self::success($response_data);
	}

	public function actionNotificationUnRegistration() {

		$registration_id = Yii::app()->request->getParam('registration_id', '');

		$response = AppCore::remove_registration_id($registration_id);

		if($response['code'] == 1 && $response['output'] == 'not_found') {
			self::terminate(-1, 'Sorry, Provided registration id does not exist in our system', APIConstant::REGISTRATION_IDS_NOT_VALID);
		}

		$response_data = array("success" => true, "message" => $response['output']);
		self::success($response_data);
	}

	public function actionNotificationListDataTable() {

		$dataColumns = array('nr.id', 'email', 'registration_id', 'device_type', 'user_id');
		$dataIndexColumn = "nr.id";
		$dataTable = "notification_registrations nr left join users u on user_id = u.id";
		$dataWhere = "WHERE nr.is_active = 'Y'";

		$dataDataModelName = 'NotificationRegistrations';

		$result = AppCore::dataTableOperation($dataColumns, $dataIndexColumn, $dataTable, $_GET, $dataDataModelName, $dataWhere, true);

		/*
		 * Output
		*/
		$output = array(
				'sEcho' => $result['sEcho'],
				'iTotalRecords' => $result['iTotalRecords'],
				'iTotalDisplayRecords' => $result['iTotalDisplayRecords'],
				'aaData' => array()
		);

		foreach ($result['rResult'] as $data) {

			$row = array();
			$select_checkbox = '<input type="checkbox" name="chooseoption" value="' . $data['id'] . '" class="choose-option">';
			$email = '<a rel="'.$this->createUrl('/user/userprofilepopup', array('h'=>AppHelper::two_way_string_encrypt($data['user_id']))).'" href="'.$this->createUrl('/user/userprofile',array('h'=>AppHelper::two_way_string_encrypt($data['user_id']))).'" class="qtiplink" title="View details of ('.$data['email'].')">'.$data['email'].'</a>';

			$device_type = '';

			if ($data['device_type'] == 1) {
				$device_type = 'Android';
			} else if ($data['device_type'] == 2) {
				$device_type = 'iPhone';
			} else if ($data['device_type']) {
				$device_type = 'Other';
			}

			$row[] = $select_checkbox;
			$row[] = $email;
			$row[] = $data['registration_id'];
			$row[] = $device_type;

			$output['aaData'][] = $row;

		}

		$this->renderPartial('/default/defaultView', array('content' => $output));

	}

	public function actionDeleteChosenRegistrationIds(){

		$chosen_data = Yii::app()->request->getParam('chosen_data', '');

		$select_reg_key_id = Array();

		foreach ($chosen_data as $data) {

			if ($data['name'] == 'chooseoption') {

				$data = NotificationRegistrations::model()->find('id=:id AND is_active=:is_active', array(':id' => $data['value'], ':is_active' => 'Y'));

				if ($data) {
					$select_reg_key_id[] = $data->registration_id;
				}
			}
		}

		if (empty($select_reg_key_id)) {
			self::terminate(-1, 'Sorry, Provided registration ids are not registered', APIConstant::REGISTRATION_IDS_NOT_VALID);
		}

		$result = Array();
		foreach ($select_reg_key_id as $registration_id) {

			$result[] = AppCore::remove_registration_id($registration_id);

		}

		$response_data = array("success" => true, "message" => $result);
		self::success($response_data);

	}

	public function actionSendNotification(){

		$notification_data = Yii::app()->request->getParam('notification_data', '');
		$select_reg_key_id_for_gcm = Array();
		$select_reg_key_id_for_iphone = Array();
		$all_registration_ids = array();
		$message_to_send = '';

		$filter_criteria = 'Selected Devices';

		foreach ($notification_data as $data) {

			if ($data['name'] == 'message_to_send') {
				$message_to_send = $data['value'];
			} else if ($data['name'] == 'notification_send_by_device_type') {
				$send_message_to_which = $data['value'];
			} else if ($data['name'] == 'chooseoption') {
				$notification_to_reg = NotificationRegistrations::model()->find('id = :id AND is_active = :isActive', array(':id' => $data['value'], ':isActive' => 'Y'));

				if ($notification_to_reg) {

					if ($notification_to_reg->device_type == 2) {
						$select_reg_key_id_for_iphone[] = $notification_to_reg->registration_id;
					} else {
						$select_reg_key_id_for_gcm[] = $notification_to_reg->registration_id;
					}
				}
			}
		}

		if ($send_message_to_which == 1) {
			$filter_criteria = 'All Android devices';
			$select_reg_key_id_for_gcm = Array();
			$select_reg_key_id_for_iphone = Array();

			$device_type_reg = NotificationRegistrations::model()->findAll('device_type = :deviceType AND is_active = :isActive', array(':deviceType' => $send_message_to_which, ':isActive' => 'Y'));
			foreach ($device_type_reg as $value) {
				$select_reg_key_id_for_gcm[] = $value->registration_id;
			}

		} else if ($send_message_to_which == 2) {
			$filter_criteria = 'All iPhone devices';
			$select_reg_key_id_for_gcm = Array();
			$select_reg_key_id_for_iphone = Array();
			$device_type_reg = NotificationRegistrations::model()->findAll('device_type = :deviceType AND is_active = :isActive', array(':deviceType' => $send_message_to_which, ':isActive' => 'Y'));

			foreach ($device_type_reg as $value) {
				$select_reg_key_id_for_iphone[] = $value->registration_id;
			}
		} else if ($send_message_to_which == 'all') {
			$filter_criteria = 'All devices';
			$select_reg_key_id_for_gcm = Array();
			$select_reg_key_id_for_iphone = Array();
			$device_type_reg = NotificationRegistrations::model()->findAll('is_active = :isActive', array(':isActive' => 'Y'));
			foreach ($device_type_reg as $value) {
				if ($value->device_type == 2) {
					$select_reg_key_id_for_iphone[] = $value->registration_id;
				} else {
					$select_reg_key_id_for_gcm[] = $value->registration_id;
				}
			}
		}

		$response = '';
		if(!empty($select_reg_key_id_for_gcm) || !empty($select_reg_key_id_for_iphone)){
			$all_registration_ids['gcm'] = $select_reg_key_id_for_gcm;
			$all_registration_ids['ios'] = $select_reg_key_id_for_iphone;

			$send_from = Array();
			$send_from['type'] = 'user';
			$send_from['data'] = Yii::app()->user->id;

			$response = AppCore::send_notification($all_registration_ids, $message_to_send, $send_from, $filter_criteria);
		}else{
			self::terminate(-1, 'Sorry, There is not a single registration id to send notification', APIConstant::REGISTRATION_IDS_NOT_VALID);
		}

		if($response['code'] == 1){
			self::terminate(-1, 'Sorry, Provided registration ids are not registered', APIConstant::MESSAGE_SENDING_FAILED);
		}else {
			$response_data = array("success" => true, "message" => $response['output']);
			self::success($response_data);
		}

	}

	public function actionNotificationHistoryDataTable() {

		$dataColumns = array('id', 'message', 'send_from', 'created_on');
		$dataIndexColumn = "id";
		$dataTable = "notification_logs";

		$dataDataModelName = 'NotificationLogs';

		$result = AppCore::dataTableOperation($dataColumns, $dataIndexColumn, $dataTable, $_GET, $dataDataModelName);

		/*
		 * Output
		*/
		$output = array(
				'sEcho' => $result['sEcho'],
				'iTotalRecords' => $result['iTotalRecords'],
				'iTotalDisplayRecords' => $result['iTotalDisplayRecords'],
				'aaData' => array()
		);

		foreach ($result['rResult'] as $data) {

			$row = array();

			$message_to_display = '';
			$message_to_display = @unserialize($data->message);
			if ($message_to_display !== false) {
				$message_to_display = $message_to_display['message'];
			} else {
				$message_to_display = $data->message;
			}

			$send_from = unserialize($data->send_from);
			$send_from_type = isset($send_from['type']) ? $send_from['type'] : '' ;
			if($send_from_type == 'user'){

				$send_from = isset($send_from['data'])? $send_from['data'] : 'Unknown';
				$user = User::model()->findByPk($send_from);
				if(!empty($user)){
					$send_from = '<a rel="'.$this->createUrl('/user/userprofilepopup', array('h'=>AppHelper::two_way_string_encrypt($user->id))).'" href="'.$this->createUrl('/user/userprofile',array('h'=>AppHelper::two_way_string_encrypt($user->id))).'" class="qtiplink" title="View details of ('.$user->email.')">'.$user->email.'</a>';
				}

			}else if($send_from_type == 'robot') {

				$send_from = isset($send_from['data'])? $send_from['data'] : 'Unknown';
				$robot = Robot::model()->findByAttributes(array('serial_number' => $send_from));
				if(!empty($robot)){
					$send_from = '<a rel="'.$this->createUrl('/robot/popupview',array('h'=>AppHelper::two_way_string_encrypt($robot->id))).'" href="'.$this->createUrl('/robot/view',array('h'=>AppHelper::two_way_string_encrypt($robot->id))).'" class="qtiplink robot-qtip" title="View details of ('.$robot->serial_number.')">'.$robot->serial_number.'</a>';
				}

			}else {
				$send_from = 'Unknown';
			}

			$detail_link = '<div class="notification_history_details" data-notification_log_id = ' . $data->id . '>More</div>';

			$row[] = $data->id;
			$row[] = $message_to_display;
			$row[] = $send_from;
			$row[] = $data->created_on;
			$row[] = $detail_link;

			$output['aaData'][] = $row;

		}

		$this->renderPartial('/default/defaultView', array('content' => $output));

	}

	public function actionNotificationHistoryDetails() {

		$notification_log_id = Yii::app()->request->getParam('notification_log_id', '');

		if(empty($notification_log_id)){
			self::terminate(-1, "we didn't get notification_log_id");
		}

		$result = AppCore::fetch_notification_log_by_id($notification_log_id);

		$this->renderPartial('/default/defaultView', array('content' => $result['output']));

	}

	public function actionSetUserPushNotificationOptions() {
		$email = Yii::app()->request->getParam('email', '');
		$json_object = json_decode(Yii::app()->request->getParam('json_object', ''));

		if(!AppHelper::is_valid_email($email)){
			self::terminate(-1, "The email address you have provided does not appear to be a valid email address.", APIConstant::EMAIL_NOT_VALID);
		}

		if($json_object === null) {
			self::terminate(-1, "The JSON Object you have provided does not appear to be a valid.", APIConstant::JSON_OBJECT_NOT_VALID);
		}

		if(!isset($json_object->notifications) || !isset($json_object->global)){
			self::terminate(-1, "Provided JSON does not contain considered keys like 'notifications', 'global' etc.", APIConstant::JSON_WITH_INVALID_KEYS);
		}

		$user_data = User::model()->find('email = :email', array(':email' => $email));

		if(!empty($user_data)){

			$user_data->push_notification_preference = (int)filter_var($json_object->global, FILTER_VALIDATE_BOOLEAN);

			if($user_data->update()){

				$user_id = $user_data->id;
				$message = 'Successfully saved user push notification preferences.';

				$userPushNotificationPreferencesObj = UserPushNotificationPreferences::model()->findAll('user_id = :user_id', array(':user_id' => $user_id));

				if(empty($userPushNotificationPreferencesObj)){

					foreach ($json_object->notifications as $value) {
						if(isset($value->value) && isset($value->key) ) {
							$userPushNotificationPreferencesObj = new UserPushNotificationPreferences();
							UserCore::setUserPushNotificationOptions($userPushNotificationPreferencesObj, $user_id, $value->key, $value->value);
						}else {
							self::terminate(-1, "Provided JSON does not contain considered keys like 'value', 'key' etc.", APIConstant::JSON_WITH_INVALID_KEYS);
						}
					}

				} else {

					$message = 'Successfully updated user push notification preferences.';

					foreach ($json_object->notifications as $key => $value) {
						if(isset($value->value) && isset($value->key) ) {
							if (!isset($userPushNotificationPreferencesObj[$key])){
								$userPushNotificationPreferencesObj[$key] = new UserPushNotificationPreferences();
							}
							UserCore::setUserPushNotificationOptions($userPushNotificationPreferencesObj[$key], $user_id, $value->key, $value->value);
						}else {
							self::terminate(-1, "Provided JSON does not contain considered keys like 'value', 'key' etc.", APIConstant::JSON_WITH_INVALID_KEYS);
						}
					}

				}

				$response_data = array("success" => true, "message" => $message);
				self::success($response_data);

			}

		} else {
			self::terminate(-1, "Sorry, email address that you provided does not exist our database", APIConstant::EMAIL_DOES_NOT_EXIST);
		}

	}

	public function actionGetUserPushNotificationOptions() {
		$email = Yii::app()->request->getParam('email', '');

		if(!AppHelper::is_valid_email($email)){
			$message = self::yii_api_echo("The email address you have provided does not appear to be a valid email address.");
			self::terminate(-1, $message, APIConstant::EMAIL_NOT_VALID);
		}

		$user_data = User::model()->find('email = :email', array(':email' => $email));

		if(!empty($user_data)){

			$response_data = Array();
			$response_data['global'] = (bool)$user_data->push_notification_preference;

			$userPushNotificationPreferencesObj = UserPushNotificationPreferences::model()->findAll('user_id = :user_id', array(':user_id' => $user_data->id));
			if(!empty($userPushNotificationPreferencesObj)){

				$notifications = Array();

				foreach ($userPushNotificationPreferencesObj as $value) {
					$notifications[] = array('key' => $value->push_notification_types_id, 'value' => (bool)$value->preference);
				}

				$response_data['notifications'] = $notifications;

			} else {
				self::terminate(-1, "Please first set user push notification preferences for the email address that you have provided and then try again.", APIConstant::USER_PREFERENCE_NOT_VALID);
			}

			self::success($response_data);

		} else {
			self::terminate(-1, "Sorry, email address that you provided does not exist our database", APIConstant::EMAIL_DOES_NOT_EXIST);
		}

	}

}