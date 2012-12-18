<?php

/**
 * APIController is the parent of all API controllers
 * It contains logic and functions common to all API Controllers
 *
 */
class APIController extends Controller {

	/**
	 * Common code to all API Controllers to terminate API call with an error
	 *
	 * @param  $errorCode
	 * @param  $message
	 * @param  $callback
	 */
	protected function terminate($errorCode, $message, $callback = '') {
		$content = array('status' => $errorCode, 'message' => $message);
		AppCore::ws_log_details(0, $content);
		$this->renderPartial('/default/defaultView', array('callback' => $callback, 'content' => $content));
		Yii::app()->end();
	}

	/**
	 * Common code to all API Controllers to terminate API call with an success response
	 *
	 * @param  $message
	 * @param  $callback
	 */
	protected function success($response_data) {
		$content = array('status' => 0, 'result' => $response_data);
		AppCore::ws_log_details(1, $content);
		$this->renderPartial('/default/defaultView', array('content' => $content));
		Yii::app()->end();
	}

	/**
	 * Common code to all API Controllers to terminate API call with an error
	 *
	 * @param  $errorCode
	 * @param  $message
	 * @param  $callback
	 */
	protected function error($errorCode, $message, $callback = '') {
		$content = array('code' => $errorCode, 'error' => $message);
		$this->renderPartial('/default/defaultView', array('callback' => $callback, 'content' => $content));
		Yii::app()->end();
	}

	/**
	 * Common code to all API Controllers to parsing of message into a specific language
	 * @param string $message_key
	 * @param array $args
	 * @return string
	 */
	protected function yii_api_echo($message_key, $args = array()){
		$string = AppCore::yii_echo($message_key, $args);
		return $string;
	}

	/**
	 * Common code to all API Controllers to check existence of robot for provided serial no, if not terminate with error messge
	 * @param int $robot_serial_no
	 * @return object of cactiverecord for robot
	 */
	protected function verify_for_robot_serial_number_existence($robot_serial_no){
		$robot = Robot::model()->findByAttributes(array('serial_number' => $robot_serial_no));
		if($robot !== null ){
			return $robot;
		}
		else{
			$response_message = self::yii_api_echo('Serial number does not exist');
			self::terminate(-1, $response_message);
		}
	}

	/**
	 * Common code to all API Controllers to check existence of robot map for provided map id, if not terminate with error messge
	 * @param int $robot_map_id
	 * @return object of cactiverecord for robot map
	 */
	protected function verify_for_robot_map_id_existence($robot_map_id){
		$robot_map = RobotMap::model()->findByAttributes(array('id' => $robot_map_id));
		if($robot_map !== null ){
			return $robot_map;
		}
		else{
			$response_message = self::yii_api_echo('Robot map id does not exist');
			self::terminate(-1, $response_message);
		}
	}

	/**
	 * Common code to all API Controllers to check existence of robot schedule for provided schedule id, if not terminate with error messge
	 * @param int $robot_schedule_id
	 * @return object of cactiverecord for robot schedule
	 */
	protected function verify_for_robot_schedule_id_existence($robot_schedule_id){
		$robot_schedule_model = RobotSchedule::model()->findByAttributes(array('id' => $robot_schedule_id));
		if($robot_schedule_model !== null ){
			return $robot_schedule_model;
		}
		else{
			$response_message = self::yii_api_echo('Robot schedule id does not exist');
			self::terminate(-1, $response_message);
		}
	}

	/**
	 * Common code to all API Controllers to check robot schedule type, if not terminate with error messge
	 * @param string $robot_schedule_type
	 * @return boolean
	 */
	protected function verify_robot_schedule_type($robot_schedule_type){
		if($robot_schedule_type == 'Basic' || $robot_schedule_type == 'Advanced' ){
			return true;
		}
		else{
			$response_message = self::yii_api_echo('Robot schedule type is  not valid');
			self::terminate(-1, $response_message);
		}
	}

	/**
	 * Common code to all API Controllers to check existence of robot custom for provided custom id, if not terminate with error messge
	 * @param int $robot_custom_id
	 * @return object of cactiverecord for robot custom
	 */
	protected function verify_for_robot_custom_id_existence($robot_custom_id){
		$robot_custom = RobotCustom::model()->findByAttributes(array('id' => $robot_custom_id));
		if($robot_custom !== null ){
			return $robot_custom;
		}
		else{
			$response_message = self::yii_api_echo('Robot custom id does not exist');
			self::terminate(-1, $response_message);
		}
	}
}

?>
