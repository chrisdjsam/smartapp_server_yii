<?php
class RobotCore {

	/**
	 * Create chat id and chat password for robot
	 * @return array
	 */
	public static function create_chat_user_for_robot() {
		$chat_details = array();
		$ts = time();

		$ejabberd_node = Yii::app()->params['ejabberdhost'];
		$chat_user = $ts . "_robot";

		$chat_id = $chat_user . '@' . $ejabberd_node;
		$chat_pwd = $ts . "_robot";

		$chat_details['jabber_status'] = true;
		$chat_details['chat_id'] = $chat_id;
		$chat_details['chat_pwd'] = $chat_pwd;

		if (Yii::app()->params['isjabbersetup']) {
			$jabberRegisterString = Yii::app()->params['ejabberdctl'] . ' register ' . $chat_user . ' ' . $ejabberd_node . ' ' . $chat_pwd . ' 2>&1';
			exec($jabberRegisterString, $output, $status);

			$success_string = strtolower("successfully registered");
			$message_string = isset($output[0]) ? $output[0] : '';
			$message_string = strtolower($message_string);

			preg_match("/$success_string/i", $message_string, $matches);

			if ($status != 0 || empty($matches)) {
				$chat_details['jabber_status'] = false;
			}
		}
		return $chat_details;
	}

	/**
	 * return connected users
	 * @return array of chat IDs of all online users and robots.
	 */
	public static function getOnlineUsers(){
		$online_users_array = array();
		if(Yii::app()->params['robot_always_connected']){
			if(Yii::app()->params['isjabbersetup']){
				$cmd = Yii::app()->params['ejabberdctl'] . " connected_users";
				$output = shell_exec($cmd);
				$output = strval($output);
				$online_users = array();
				$online_users = array_filter(explode("\n", $output));
				for($i=0; $i<count($online_users); $i++){
					$online_user = isset($online_users[$i])? $online_users[$i] : '' ;
					if(empty($online_user)){
						continue;
					}
					$online_user_str = $online_user;
					$pos = strpos($online_user_str, '/');
					if($pos){
						$online_user_str = substr($online_user_str,0, $pos);
					}
					$online_users_array[] = $online_user_str;
				}
			}
		}else{
			$online_users = OnlineChatId::model()->findAll();
			foreach ($online_users as $online_chat_ids) {
				$online_users_array[] = $online_chat_ids->chat_id;
			}
		}
		return $online_users_array;
	}

	public static function getLatestPingTimestampFromRobot($serial_number) {
		$criteria = new CDbCriteria;
		$criteria->select = array('id', 'serial_number', 'ping_timestamp');
		$criteria->condition = "serial_number = :serial_number";
		$criteria->params = array(':serial_number' => $serial_number);
		$criteria->order = 'ping_timestamp DESC';
		$data = RobotPingLog::model()->findAll($criteria);

		return $data;
	}

	public static function getSleepLagTime($robot) {

		$sleep_time = Yii::app()->params['default_sleep_time']; // in seconds
		$lag_time = Yii::app()->params['default_lag_time']; // in seconds

		if (isset($robot->sleep_time) && isset($robot->lag_time)) {
			$sleep_time = $robot->sleep_time;
			$lag_time = $robot->lag_time;
		} else {
			if (isset($robot->robotRobotTypes->robotType->robotTypeMetadatas)) {
				foreach ($robot->robotRobotTypes->robotType->robotTypeMetadatas as $metadata) {
					if ($metadata->_key == 'sleep_time') {
						$sleep_time = $metadata->value;
					} elseif ($metadata->_key == 'lag_time') {
						$lag_time = $metadata->value;
					}
				}
			}
		}

		return array('sleep_time' => $sleep_time, 'lag_time' => $lag_time);
	}

	public static function microtime_float() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float) $usec + (float) $sec);
	}

	/**
	 * send message to jabber.
	 */
	public static function send_chat_message($from, $to, $message) {

		if (Yii::app()->params['isjabbersetup']) {

			$is_jabber_setup = Yii::app()->params['isjabbersetup'];

			$utc_str = self::microtime_float();
			$xmpp_uid = 'xmpp_' . $utc_str * 10000;

			$message = escapeshellarg($message);

			$XmppNotificationViaMQ = new XmppNotificationViaMQ();

			$XmppNotificationViaMQ->xmpp_uid = $xmpp_uid;
			$XmppNotificationViaMQ->from = $from;
			$XmppNotificationViaMQ->to = $to;
			$XmppNotificationViaMQ->message = $message;
			$XmppNotificationViaMQ->is_jabber_setup = $is_jabber_setup;
			$XmppNotificationViaMQ->start_time = round(microtime(true) * 1000);

			$XmppNotificationViaMQ->save();

			$cmdParam = $xmpp_uid;
			$cmdStr = "php " . Yii::app()->params['amqp_xmpp_notification_publisher_path'];
			shell_exec($cmdStr . " '" . $cmdParam . "'");

			return $xmpp_uid;
		} else {
			return false;
		}
	}

	public static function removeLinkingCode($robot){

		$robot_user_association_tokens = RobotUserAssociationTokens::model()->find('robot_id = :robot_id', array(':robot_id' => $robot->id));

		if( !empty($robot_user_association_tokens) ){

			$robot_linking_data = RobotLinkingCode::model()->find('serial_number = :serial_number', array(':serial_number' => $robot->serial_number));

			if(!empty($robot_linking_data)){
				$robot_linking_data->delete();
			}

			$robot_user_association_tokens->delete();

		}

	}

	public static function checkRobotStatus($robot){

		$content = array('code' => 10001);

		$data = RobotKeyValues::model()->find('robot_id = :robot_id and _key =:_key', array(':robot_id' => $robot->id, ':_key' => 'robotCurrentState'));

		if(!empty($data)){
			$content = array('code' => $data->value);
		}

		return $content;
	}

	/**
	 *
	 * Delete chat user
	 * @return array
	 */
	public static function delete_chat_user($chat_user) {
		$chat_details = array();
		$ejabberd_node = Yii::app()->params['ejabberdhost'];
		$chat_user = str_replace('@' . $ejabberd_node, "", $chat_user);

		$chat_details['jabber_status'] = true;
		if (Yii::app()->params['isjabbersetup']) {
			$jabberRegisterString = Yii::app()->params['ejabberdctl'] . ' unregister ' . $chat_user . ' ' . $ejabberd_node . ' 2>&1';
			exec($jabberRegisterString, $output, $status);
		}
		return $chat_details;
	}

	/**
	 * Delete all data for provided robot schedule ids
	 * @param unknown $robot_schedule_id_arr
	 */
	public static function delete_robot_schedule_data($robot_schedule_id_arr) {
		foreach ($robot_schedule_id_arr as $robot_schedule_id) {
			$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
			$uploads_dir_for_robot_schedule = Yii::app()->getBasePath() . $back . Yii::app()->params['robot-schedule_data-directory-name'] . DIRECTORY_SEPARATOR . $robot_schedule_id;
			AppHelper::deleteDirectoryRecursively($uploads_dir_for_robot_schedule);
		}
	}

	public static function setRobotKeyValueDetail($robot, $key, $value, $utc){
		switch ($key) {
			case "name":
				if(empty($value)){
					return array('code'=>1, 'error'=>APIConstant::ERROR_INVALID_ROBOT_ACCOUNT_DETAIL);
				}
				$robot->name = $value;
				$robot->save();
				break;

			default:
				$data = RobotKeyValues::model()->find('_key = :_key AND robot_id = :robot_id', array(':_key' => $key, ':robot_id' => $robot->id));
				if(!empty($data)){
					$data->value = $value;
					$data->timestamp = $utc;
					$data->update();
				} else {
					$robot_key_value = new RobotKeyValues();
					$robot_key_value->robot_id = $robot->id;
					$robot_key_value->_key = $key ;
					$robot_key_value->value = $value ;
					$robot_key_value->timestamp = $utc;
					$robot_key_value->save();
				}
				break;
		}
		return array('code'=>0);
	}

	public static function xmppMessageOfSetRobotProfile($robot, $cause_agent_id, $utc){

		$xmpp_message_model = new XmppMessageLogs();
		$xmpp_message_model->save();
		$message = '<?xml version="1.0" encoding="UTF-8"?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>5001</command><requestId>' . $xmpp_message_model->id . '</requestId><timeStamp>' . $utc . '</timeStamp><retryCount>0</retryCount><responseNeeded>false</responseNeeded><distributionMode>2</distributionMode><params><robotId>' . $robot->serial_number . '</robotId><causeAgentId>' . $cause_agent_id . '</causeAgentId></params></request></payload></packet>';

		$xmpp_message_model->send_from = $robot->id;
		$xmpp_message_model->send_at = $utc;

		$xmpp_message_model->xmpp_message = $message;
		$xmpp_message_model->save();

		return $message;
	}

	public static function sendXMPPMessageWhereUserSender($user_data, $robot, $message){
		$xmpp_uid = RobotCore::send_chat_message($user_data->chat_id, $robot->chat_id , $message);
		self::setXMPPUId($xmpp_uid);
		foreach ($robot->usersRobots as $userRobot){
			$xmpp_uid = RobotCore::send_chat_message($user_data->chat_id, $userRobot->idUser->chat_id, $message);
			self::setXMPPUId($xmpp_uid);
		}
	}

	public static function deleteRobotType($chosen_robot){

		foreach ($chosen_robot as $type_id) {

			RobotTypeMetadata::model()->deleteAll('robot_type_id = :robot_type_id', array(':robot_type_id' => $type_id));

			$robot_type_data = RobotTypes::model()->find('type = :type', array(':type'=>Yii::app()->params['default_robot_type']));
			RobotRobotTypes::model()->updateAll(array('robot_type_id'=>$robot_type_data->id), 'robot_type_id = :robot_type_id', array(':robot_type_id'=>$type_id));

			RobotTypes::model()->deleteAll('id = :id', array(':id' => $type_id));

		}
		return array('status'=> 0, 'message'=> 'Robot type have been deleted succussfully');
	}

	public static function sendXmppMessageToAssociatesUsers($robot, $utc) {
		$xmpp_message_model = new XmppMessageLogs();
		$xmpp_message_model->save();
		$message = '<?xml version="1.0" encoding="UTF-8"?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>5002</command><requestId>' . $xmpp_message_model->id . '</requestId><timeStamp>' . $utc . '</timeStamp><retryCount>0</retryCount><responseNeeded>false</responseNeeded><distributionMode>2</distributionMode><params><robotId>' . $robot->serial_number . '</robotId></params></request></payload></packet>';
		$xmpp_message_model->xmpp_message = $message;

		$xmpp_message_model->send_from = $robot->id;
		$xmpp_message_model->send_at = $utc;

		$xmpp_message_model->save();

		RobotCore::send_chat_message($robot->chat_id, $robot->chat_id, $message);
		foreach ($robot->usersRobots as $userRobot) {
			RobotCore::send_chat_message($robot->chat_id, $userRobot->idUser->chat_id, $message);
		}
	}

	public static function removeExpiredLinkingCode($robot){

		$robot_user_association_tokens = RobotUserAssociationTokens::model()->find('robot_id = :robot_id', array(':robot_id' => $robot->id));

		if( !empty($robot_user_association_tokens) ){

			$validity_of_linking_code = self::getExpiredTimeOfLinkingCode($robot_user_association_tokens);

			if ($validity_of_linking_code < 0) {

				$robot_linking_data = RobotLinkingCode::model()->find('serial_number = :serial_number', array(':serial_number' => $robot->serial_number));

				if(!empty($robot_linking_data)){
					$robot_linking_data->delete();
				}

				$robot_user_association_tokens->delete();

			}

		}

	}

	public static function getExpiredTimeOfLinkingCode($robot_user_association_tokens){

		$token_lifetime = Yii::app()->params['robot_user_association_token_lifetime'];

		$token_timestamp = strtotime($robot_user_association_tokens->created_on);

		if(empty($robot_user_association_tokens->created_on)){
			$token_timestamp = time();
		}

		$current_system_timestamp = time();
		$validity_of_linking_code = ($current_system_timestamp - $token_timestamp);

		$validity_of_linking_code = $token_lifetime - $validity_of_linking_code;

		return $validity_of_linking_code;

	}

	public static function getValidityOfLinkingCode($linking_code_created_on){

		$linking_code_lifetime = Yii::app()->params['robot_user_association_token_lifetime'];

		$linking_code_created_on = strtotime($linking_code_created_on);

		$current_system_timestamp = time();

		$validity_of_linking_code = $current_system_timestamp - $linking_code_created_on;

		$validity_of_linking_code = $linking_code_lifetime - $validity_of_linking_code;

		return $validity_of_linking_code;

	}

	public static function isLinkingCodeValid($linking_code_created_on){

		$validity_of_linking_code = self::getValidityOfLinkingCode($linking_code_created_on);

		if( $validity_of_linking_code < 0 ) {
			return false;
		}

		return true;

	}

	public static function sendXMPPMessageWhereRobotSender($robot, $message){
		foreach ($robot->usersRobots as $userRobot){
			$xmpp_uid = RobotCore::send_chat_message($robot->chat_id, $userRobot->idUser->chat_id, $message);
			self::setXMPPUId($xmpp_uid);
		}
	}

	public static function getLinkCode($robot_id){

		$token_legth = Yii::app()->params['link_code_length'];

		$token = UniqueToken::hash(($robot_id + (hexdec(uniqid())) / 100000), $token_legth);
		$token = trim($token);
		if (strlen($token) != $token_legth) {
			self::getLinkCode($robot_id);
		}
		$robot_user_association_token = RobotUserAssociationTokens::model()->find('token = :token', array(':token' => $token));
		if (!empty($robot_user_association_token)) {
			self::getLinkCode($robot_id);
		}
		return $token;
	}

	public static function refreshGetOnlineUsersData(){
		OnlineChatId::model()->deleteAll();

  		$online_users = self::getOnlineUsers();

		if ( !empty($online_users) ){
			$sql = "INSERT INTO online_chat_ids ( chat_id ) VALUES ";
			foreach ($online_users as $jabber_user) {
				$sql .= "('".$jabber_user."'),";
			}
			$sql = rtrim($sql, ",");
			Yii::app()->db->createCommand($sql)->execute();
		}
	}

	/**
	 * return status of requested ejabber user
	 */
	public static function jabberOnline($chat_id){
		$chat_args = explode("@", $chat_id);
		$user = $chat_args[0];
		$server = $chat_args[1];
		$cmd = Yii::app()->params['ejabberdctl'] . " user_resources " . $user ." " .$server;

		$output = shell_exec($cmd);
		$output = strval($output);
		if(!empty($output)){
			return true;
		}
		return false;
	}

	public static function setXMPPUId($xmpp_uid){
		$xmpp_uids = Yii::app()->params['xmpp_uids'];
		$xmpp_uids[] = $xmpp_uid;
		Yii::app()->params['xmpp_uids'] = $xmpp_uids;
	}

}

?>
