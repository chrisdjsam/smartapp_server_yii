<?php

/**
 * APIController is the parent of all API controllers
 * It contains logic and functions common to all API Controllers
 *
 */
class APIController extends Controller {

	/**
	 * Custom code to handle all errors and exceptions of API Controllers.
	 */
	public function init() {
		parent::init();

		Yii::app()->attachEventHandler('onError', array($this, 'handleError'));
		Yii::app()->attachEventHandler('onException', array($this, 'handleError'));
	}

	public function handleError(CEvent $event) {
		if ($event instanceof CExceptionEvent) {

			// handle exception
			self::terminate(-1, $event->exception->getMessage());

		} elseif ($event instanceof CErrorEvent) {

			// handle error
			self::terminate(-1, $event->message);

		}

		$event->handled = TRUE;
	}

	/**
	 * Override beforeAction mathod to set user session data
	 * @param type $action
	 * @return boolean
	 */
	function beforeAction($action) {
		parent::beforeAction($action);

		Yii::app()->params['start_time'] = round(microtime(true) * 1000);

		return true;

	}

	/**
	 * Common code to all API Controllers to terminate API call with an error
	 */
	protected function terminate($status, $message, $errorCode = 0, $callback = '') {
		$content = array('status' => $status, 'message' => $message, 'error' => array('code' => $errorCode, 'message' => APIConstant::getMessageForErrorCode($errorCode)));
		AppCore::ws_log_details(0, $content);
		$this->renderPartial('/default/defaultView', array('callback' => $callback, 'content' => $content));
		Yii::app()->end();
	}

	/**
	 * Common code to all API Controllers to terminate API call with an success response
	 */
	protected function success($response_data) {
		$content = array('status' => 0, 'result' => $response_data);
		AppCore::ws_log_details(1, $content);
		$this->renderPartial('/default/defaultView', array('content' => $content));
		Yii::app()->end();
	}

	/**
	 *
	 * @param  $response_data
	 * @param  $extra_param
	 */
	protected function successWithExtraParam($response_data, $extra_param) {
		$content = array('status' => 0, 'result' => $response_data, 'extra_params' => $extra_param);
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
	 * @param boolean $prevent_termination
	 * @return object of cactiverecord for robot
	 */
	protected function verify_for_robot_serial_number_existence($robot_serial_no, $prevent_termination = false){
		$robot = Robot::model()->findByAttributes(array('serial_number' => $robot_serial_no));
		if($robot !== null ){
			return $robot;
		}
		else if(!$prevent_termination){
			$response_message = self::yii_api_echo('Robot serial number does not exist');
			self::terminate(-1, $response_message, APIConstant::SERIAL_NUMBER_DOES_NOT_EXIST);
		}
	}

	/**
	 * Common code to all API Controllers to check existence of user for provided id, if not terminate with error messge
	 * @param int $id_user
	 * @param boolean $prevent_termination
	 * @return object of cactiverecord for user
	 */
	protected function verify_for_user_id_existence($id_user, $prevent_termination = false){

		$user = User::model()->findByPk($id_user);
		if($user !== null ){
			return $user;
		}
		else if(!$prevent_termination){
			$response_message = self::yii_api_echo('User ID does not exist');
			self::terminate(-1, $response_message, APIConstant::USER_ID_NOT_FOUND);
		}
	}

	/**
	 * Common code to all API Controllers to check repetition, if not terminate with error messge
	 * @param int $robot_map_id
	 * @return object of cactiverecord for robot
	 */
	protected function verify_for_robot_atlas_repetition($robot_serial_no){
		$robot = Robot::model()->findByAttributes(array('serial_number' => $robot_serial_no));
		if($robot !== null ){

			if($robot->robotAtlas){
				$response_message = self::yii_api_echo('Robot can have only one atlas');
				self::terminate(-1, $response_message, APIConstant::ATLAS_ALREADY_ADDED);
			}else{
				return $robot;
			}
		}
		else{
			$response_message = self::yii_api_echo('Serial number does not exist');
			self::terminate(-1, $response_message, APIConstant::SERIAL_NUMBER_DOES_NOT_EXIST);
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
			self::terminate(-1, $response_message, APIConstant::ROBOT_MAP_ID_DOES_NOT_EXIST);
		}
	}

	/**
	 * Common code to all API Controllers to check existence of robot atlas for provided atlas id, if not terminate with error messge
	 * @param int $robot_atlas_id
	 * @return object of cactiverecord for robot atlas
	 */
	protected function verify_for_robot_atlas_id_existence($robot_atlas_id){
		$robot_atlas = RobotAtlas::model()->findByAttributes(array('id' => $robot_atlas_id));
		if($robot_atlas !== null ){
			return $robot_atlas;
		}
		else{
			$response_message = self::yii_api_echo('Robot atlas id does not exist');
			self::terminate(-1, $response_message, APIConstant::ROBOT_ATLAS_ID_DOES_NOT_EXIST);
		}
	}

	/**
	 * Common code to all API Controllers to check existence atlas grid image for provided database id of grid image, if not terminate with error messge
	 * @param int $id
	 * @return object of cactiverecord for atlas grid image
	 */
	protected function verify_for_grid_image_id_existence($id){
		$atlas_grid_image= AtlasGridImage::model()->findByPk($id);
		if($atlas_grid_image !== null ){
			return $atlas_grid_image;
		}
		else{
			$response_message = self::yii_api_echo('grid image id does not exist');
			self::terminate(-1, $response_message, APIConstant::GRID_IMAGE_DOES_NOT_EXIST_FOR_ATLAS_ID_AND_GRID_ID);
		}
	}

	/**
	 * Common code to all API Controllers to check existence of atlas grid image for provided atlas id and user specified grid id, if not terminate with error messge
	 * @param int $id_atlas
	 * @param string $id_grid
	 * @param boolean $prevent_termination: any val if want to prevent termination, else optional.
	 * @return object of cactiverecord for atlas grid image
	 */
	protected function verify_for_atlas_id_grid_id_existence($id_atlas,$id_grid, $prevent_termination=null){
		$atlas_grid_image= AtlasGridImage::model()->find('id_atlas = :id_atlas AND id_grid = :id_grid',array('id_atlas'=>$id_atlas ,'id_grid' => $id_grid));
		if($atlas_grid_image !== null ){
			return $atlas_grid_image;
		}
		else{
			if($prevent_termination === true) {
				return null;
			}
			$response_message = self::yii_api_echo('Combination of atlas id and grid id does not exist');
			self::terminate(-1, $response_message, APIConstant::GRID_IMAGE_DOES_NOT_EXIST_FOR_ATLAS_ID_AND_GRID_ID);
		}
	}

	/**
	 * Common code to all API Controllers to check if provided id is empty / blank, if not terminate with error messge
	 * @param int $id_grid
	 * @return provided $id_grid after trimming spaces
	 */
	protected function verify_for_empty_grid_id($id_grid){
		if(trim($id_grid) == ""){
			$response_message = self::yii_api_echo('id_grid should contain atleast one character or number .');
			self::terminate(-1, $response_message, APIConstant::GRID_ID_CONTAIN_ATLEAST_ONE_CHAR_OR_NUMBER);
		}else{
			return trim($id_grid);
		}
	}

	/**
	 * Common code to all API Controllers to check if provided atlas id and user specified grid is repeted, if not terminate with error messge
	 * @param int $id_atlas
	 * @param String $id_grid
	 * @return object of cactiverecord for atlas grid image
	 */
	protected function verify_for_atlas_id_grid_id_repetition($id_atlas,$id_grid){
		$atlas_grid_image= AtlasGridImage::model()->find('id_atlas = :id_atlas AND id_grid = :id_grid',array('id_atlas'=>$id_atlas ,'id_grid' => $id_grid));
		if($atlas_grid_image !== null ){

			$response_message = self::yii_api_echo('Combination of atlas id and grid id exist. Try updating for same.');
			self::terminate(-1, $response_message, APIConstant::GRID_IMAGE_EXISTS_FOR_ATLAS_ID_AND_GRID_ID);

		}
		else{
			return $atlas_grid_image;
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
			self::terminate(-1, $response_message, APIConstant::ROBOT_SCHEDULE_ID_DOES_NOT_EXIST);
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
			self::terminate(-1, $response_message, APIConstant::SCHEDULE_TYPE_NOT_VALID_OR_MISSING);
		}
	}

	/**
	 * Common code to all API Controllers to check existence of robot custom for provided custom id, if not terminate with error messge
	 * @param int $robot_custom_id
	 * @return object of cactiverecord for robot custom
	 */
	protected function verify_for_atlas_id_existence($id_atlas){
		$robotAtlas = RobotAtlas::model()->findByPk($id_atlas);
		if($robotAtlas !== null ){
			return $robotAtlas;
		}
		else{
			$response_message = self::yii_api_echo('Atlas id does not exist');
			self::terminate(-1, $response_message, APIConstant::ROBOT_ATLAS_ID_DOES_NOT_EXIST);
		}
	}
}

?>
