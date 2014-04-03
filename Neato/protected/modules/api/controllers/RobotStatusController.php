<?php

/**
 * The API RobotStatusController is meant for app related API actions.
 */
class RobotStatusController extends APIController {

	public function actionOnline(){

		$chat_user = Yii::app()->request->getParam('user', '');
		$server = Yii::app()->request->getParam('server', '');
		$message = "Came online";

		$chat_id = $chat_user . '@' . $server;

		self::callForSetRobotProfile($chat_id, 1);

		if(Yii::app()->params['robot_always_connected']){
			return;
		}

		$result = false;

		include_once Yii::app()->basePath . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database_config.php';

		$env = array();

		$env[] = $wp;
		$env[] = $dev;
		$env[] = $staging;
		//$env[] = $wp;

		foreach ($env as $db_config) {
			self::add_delete_chat_id($db_config, $chat_id, 1);
			$result = self::ping_from_robot($db_config, $chat_id, $message);
			if($result){
				break;
			}
		}

		if($result){
			echo 'data is stored';
		}

	}

	public static function ping_from_robot($env, $chat_id, $message) {

		$username = $env['username'];
		$password = $env['password'];
		$hostname = $env['hostname'];
		$dbname = $env['dbname'];

		//connection to the database
		$dbhandle = mysql_connect($hostname, $username, $password) or die("Unable to connect to MySQL");

		if ($dbhandle === false) {
			echo("Stale DB Connection");
			$dbhandle = mysql_connect($hostname, $username, $password, true) or die("Unable to connect to MySQL");
		}
		if ($dbhandle === false) {
			echo("DB Connection Failed");
			return;
		}

		mysql_select_db($dbname, $dbhandle);

		$robot_data = mysql_fetch_array(mysql_query("SELECT * FROM `robots` WHERE chat_id = '$chat_id'"));

		if(!empty($robot_data)){

			mysql_query("INSERT INTO `robot_ping_log`(`serial_number`, `ping_timestamp`, `status`) VALUES ('" . $robot_data['serial_number'] . "', " . new CDbExpression('NOW()') . ", '$message')");
			mysql_close($dbhandle);
			return true;

		}

		mysql_close($dbhandle);
		return false;

	}

	public function actionOffline(){

		$chat_user = Yii::app()->request->getParam('user', '');
		$server = Yii::app()->request->getParam('server', '');
		$message = "Went offline";

		$chat_id = $chat_user . '@' . $server;

		self::callForSetRobotProfile($chat_id, 0);

		if(Yii::app()->params['robot_always_connected']){
			return;
		}

		$result = false;

		include_once Yii::app()->basePath . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database_config.php';

		$env = array();

		$env[] = $dev;
		$env[] = $staging;

		foreach ($env as $db_config) {
			self::add_delete_chat_id($db_config, $chat_id, 2);
			$result = self::ping_from_robot($db_config, $chat_id, $message);
			if($result){
				break;
			}
		}

		if($result){
			echo 'data is stored';
		}

	}


	public static function add_delete_chat_id($env, $chat_id, $operation) {

		$username = $env['username'];
		$password = $env['password'];
		$hostname = $env['hostname'];
		$dbname = $env['dbname'];

		//connection to the database
		$dbhandle = mysql_connect($hostname, $username, $password) or die("Unable to connect to MySQL");

		if ($dbhandle === false) {
			echo("Stale DB Connection");
			$dbhandle = mysql_connect($hostname, $username, $password, true) or die("Unable to connect to MySQL");
		}
		if ($dbhandle === false) {
			echo("DB Connection Failed");
			return;
		}

		mysql_select_db($dbname, $dbhandle);
		if($operation == 1){
			mysql_query("INSERT INTO `online_chat_ids`(`chat_id`) VALUES ('" . $chat_id . "')");
			mysql_close($dbhandle);
			return true;
		}else{
			mysql_query("DELETE FROM `online_chat_ids` WHERE chat_id = '" . $chat_id . "'");
			mysql_close($dbhandle);
			return true;
		}

		mysql_close($dbhandle);
		return false;

	}

	public static function callForSetRobotProfile($chat_id, $operation) {
		$robot = Robot::model()->findByAttributes(array('chat_id' => $chat_id));

		$RobotKeyValues = $robot->RobotKeyValues;
		$operationStored = '-1';
		foreach ($RobotKeyValues as $robotkv){
			if($robotkv->_key == 'robotOnlineStatus'){
				$operationStored = $robotkv->value;
			}
		}
		if($operationStored == $operation) {
			return false;
		}

		if($robot) {
			$apiHostname = Yii::app()->params['apiHostname'];
			$apiProtocol = Yii::app()->params['apiProtocol'];
			$url = $apiProtocol . $apiHostname . "api/robot/setProfileDetails3";
			$headers = array();
			$data_string = array();
			$data_string['serial_number'] = $robot['serial_number'];
			$data_string['source_serial_number'] = $robot['serial_number'];
			$data_string['cause_agent_id'] = '';
			$data_string['notification_flag'] = '1';
			$data_string['profile[robotOnlineStatus]'] = $operation;
			echo AppHelper::curl_call($url, $headers, $data_string);
		}
	}


}
