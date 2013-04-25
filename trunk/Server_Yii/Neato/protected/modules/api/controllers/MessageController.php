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
			self::terminate(-1, $response_message);
		}
		
		$message = Yii::app()->request->getParam('message', '');
		
		$status = AppCore::send_chat_message($user->chat_id, $robot->chat_id, $message);
		if($status){
			$response_message = "Message is sent to robot $serial_number.";
			$response_data = array("success"=>true, "message"=>$response_message);
			self::success($response_data);
		}else{
			$response_message = "Message could not be sent to robot $serial_number.";
			self::terminate(-1, $response_message);
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
			self::terminate(-1, $response_message);
		}
	
		$serial_number = Yii::app()->request->getParam('serial_number', '');
		$robot = self::verify_for_robot_serial_number_existence($serial_number);
	
		$message = Yii::app()->request->getParam('message', '');
		$count= 0;
		foreach ($robot->usersRobots as $userRobot){
			$count += AppCore::send_chat_message($robot->chat_id, $userRobot->idUser->chat_id, $message);
		}
	
		$response_message = "Message is sent to $count user(s).";
		if($count){
			$response_data = array("success"=>true, "message"=>$response_message);
			self::success($response_data);
		}else{
			self::terminate(-1, $response_message);
		}
	}
        
        public function actionSendNotificationToGivenRegistrationIds(){
            
                $registration_ids = array_values( array_filter(array_unique(Yii::app()->request->getParam('registration_ids', ''))) );
		$message = Yii::app()->request->getParam('message', '');
                $notification_type = Yii::app()->request->getParam('notification_type', '1');
                
                if(empty($registration_ids)){
                    self::terminate(-1, 'Provide at least one registration id');
                }

		$response = AppCore::send_notification_to_given_registration_ids($registration_ids, $message, $notification_type);
                
                if($response['code'] == 1){
                    self::terminate(-1, $response['output']);
                }

                $response_data = array("success"=>true, "message"=>$response['output']);
		self::success($response_data);

        }
        
        public function actionSendNotificationToAllUsersOfRobot(){
            
                $serial_number = $message = Yii::app()->request->getParam('serial_number', '');
		$message = Yii::app()->request->getParam('message', '');
                $notification_type = Yii::app()->request->getParam('notification_type', '1');
                
                $robot = self::verify_for_robot_serial_number_existence($serial_number);
                
                $user_ids_to_send_notification = Array();
                foreach ($robot->usersRobots as $user) {
                    $user_ids_to_send_notification[] = $user->id_user;
                }
                
                if(empty($user_ids_to_send_notification)) {
                    self::terminate(-1, "Sorry, There is not single user who is associated with given robot");
                }
                
                $send_from = Array();
                $send_from['type'] = 'robot';
                $send_from['data'] = $serial_number;
                
		$response = AppCore::send_notification_to_all_users_of_robot($user_ids_to_send_notification, $message, $send_from, $notification_type);
                
                if($response['code'] == 1){
                    self::terminate(-1, $response['output']);
                }

                $response_data = array("success"=>true, "message"=>$response['output']);
		self::success($response_data);

        }
        
        public function actionSendNotificationToGivenEmails(){
            
                $emails = array_values( array_filter(array_unique(Yii::app()->request->getParam('emails', ''))) );
		$message = trim(Yii::app()->request->getParam('message', '')); 
                $notification_type = Yii::app()->request->getParam('notification_type', '1');
                
                if(!empty($emails)){
                    $invalid_emails = Array();
                    foreach ($emails as $email) {
                        if(!AppHelper::is_valid_email($email)){
                            $invalid_emails[] = $email;
                        }
                    }
                    if(!empty($invalid_emails)){
                        self::terminate(-1, 'Please provide valid email address (Invalid emails: ' . json_encode($invalid_emails) . ')');
                    }
                    
                } else {
                    self::terminate(-1, 'Please provide at least one email address');
                }
                
                if(empty($message)){
                    self::terminate(-1, 'Message field can not be blank');
                }
                
		$response = AppCore::send_notification_to_given_emails($emails, $message, $notification_type);
                
                if($response['code'] == 1){
                    self::terminate(-1, $response['output']);
                }

                $response_data = array("success"=>true, "message"=>$response['output']);
		self::success($response_data);

        }
        
        public function actionNotificationRegistration() {

                $user_email = Yii::app()->request->getParam('user_email', '');
                $registration_id = Yii::app()->request->getParam('registration_id', '');
                $device_type = Yii::app()->request->getParam('device_type', '');

                if (!AppHelper::is_valid_email($user_email)) {
                    self::terminate(-1, 'Please enter valid email address.');
                }

                $user_data = User::model()->findByAttributes(array('email' => $user_email));

                if (empty($user_data)) {
                    self::terminate(-1, 'Sorry, Provided user email address does not exist in our system.');
                }

                $response = AppCore::store_registration_id($user_data->id, $registration_id, $device_type);

                $response_data = array("success" => true, "message" => $response);
                self::success($response_data);
        }
        
        public function actionNotificationUnRegistration() {

                $registration_id = Yii::app()->request->getParam('registration_id', '');

                $response = AppCore::remove_registration_id($registration_id);

                if($response['code'] == 1 && $response['output'] == 'not_found') {
                    self::terminate(-1, 'Sorry, Provided registration id does not exist in our system');
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
                self::terminate(-1, 'Sorry, Provided registration ids are not registered');
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
                
//                $device_type_reg = get_data("SELECT `reg_key` FROM `gt_notification_registrations` reg inner join `gt_notification_key_value` kv on reg.id = kv.notification_registrations_id where _key = 'device_type' and value = '1' and is_active = 'true' ");
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
            if(!empty($select_reg_key_id_for_gcm)){
                $all_registration_ids['gcm'] = $select_reg_key_id_for_gcm;
                
                $send_from = Array();
                $send_from['type'] = 'user';
                $send_from['data'] = Yii::app()->user->id;
                
                $response = AppCore::send_notification($all_registration_ids, $message_to_send, $send_from, null, $filter_criteria);
            }else{
                self::terminate(-1, 'Sorry, There is not a single registration id to send notification');
            }
            
            if($response['code'] == 1){
                self::terminate(-1, 'Sorry, Provided registration ids are not registered');
            }else {
                $response_data = array("success" => true, "message" => $response['output']);
                self::success($response_data);
            }
            
        }
        
        public function actionNotificationHistoryDataTable() {
            
                $dataColumns = array('id', 'message', 'notification_type', 'created_on');
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
                    
                    switch ($data->notification_type) {
                        case '1':
                            $notification_type = 'System' ; 
                            break;

                        case '2':
                            $notification_type = 'Activities' ; 
                            break;

                        case '3':
                            $notification_type = 'SOS' ; 
                            break;

                        default:
                            $notification_type = 'System' ; 
                            break;
                    }
                    
                    $detail_link = '<div class="notification_history_details" data-notification_log_id = ' . $data->id . '>More</div>';
                    
                    $row[] = $data->id;
                    $row[] = $data->message;
                    $row[] = $notification_type;
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

}