<?php

/**
 * The API RestController is meant for validating all the API calls.
 */

class RestController extends APIController {

	public $API_METHODS;

	/**
	 * It is called by the API consumer who need Json response
	 * Set response type Json for API call
	 */
	public function actionJson() {
		$_REQUEST['handler'] = 'rest';
		$_REQUEST['response_type'] = 'Json';
		self::api_init();
	}

	/**
	 * It is called by the API consumer who need XML response
	 * Set response type XML for API call
	 */
	public function actionXML() {
		$_REQUEST['handler'] = 'rest';
		$_REQUEST['response_type'] = 'XML';
		self::api_init();
	}

	/**
	 * It will expose all the functions for API calls
	 * Then invoke the authenticate_method and excute_method
	 */
	protected function api_init() {
		self::expose_function("system.api.list", "list_all_apis", NULL, self::yii_api_echo("system.api.list"), "GET", false, false);

		self::expose_function("site.get_api_version",
				"site/ApiVersion",
				array(),
				'Get API version of the platform',
				'POST',
				true,
				false);

		self::expose_function("site.get_timestamp_delta",
				"site/getTimestampDelta",
				array('timestamp' => array ('type' => 'string', 'required' => true)),
				'Get Timestamp Delta',
				'POST',
				true,
				false);

		self::expose_function('auth.get_user_auth_token2',
				"user/GetAuthToken2",
				array( 'account_type' => array ('type' => 'string', 'required' => true),
						'email' => array ('type' => 'string', 'required' => ''),
						'password' => array ('type' => 'string', 'required' => ''),
						'external_social_id' => array ('type' => 'string', 'required' => false),
				),
				"Get user profile labels",
				'POST',
				true,
				false);

		self::expose_function('auth.get_user_auth_token',
				"user/GetAuthToken2",
				array( 'account_type' => array ('type' => 'string', 'required' => true),
						'email' => array ('type' => 'string', 'required' => ''),
						'password' => array ('type' => 'string', 'required' => ''),
						'external_social_id' => array ('type' => 'string', 'required' => false),
				),
				"Get user profile labels",
				'POST',
				true,
				false);


		self::expose_function('user.change_password',
				"user/ChangePassword",
				array(
						'password_new' => array ('type' => 'string', 'required' => true),
						'password_old' => array ('type' => 'string', 'required' => true),
				),
				"Change user password",
				'POST',
				true,
				true);

		self::expose_function('user.forget_password',
				"user/ForgetPassword",
				array(
						'email' => array ('type' => 'string', 'required' => true),
				),
				"Request a new password by email",
				'POST',
				true,
				false);


		self::expose_function('user.check_for_upgrades',
				"app/CheckForUpgrades",
				array('app_id' => array ('type' => 'string', 'required' => true),
						'current_appversion' => array ('type' => 'string', 'required' => false),
						'os_type' => array ('type' => 'string', 'required' => false),
						'os_version' => array ('type' => 'string', 'required' => false),
				),
				"Check for upgrade for application.",
				'POST',
				true,
				false);

		self::expose_function('user.create',
				"user/create",
				array('name' => array ('type' => 'string'),
						'email' => array ('type' => 'string', 'default' => ''),
						'password' => array ('type' => 'string', 'required' => true),
						'account_type' => array ('type' => 'string'),
						'external_social_id' => array ('type' => 'string', 'required' => false, 'default'=>""),
						'social_additional_attributes' => array('type'=>'array', 'default'=>array()),
				),
				"Register user",
				'POST',
				true,
				false);

		self::expose_function('user.create2',
				"user/create2",
				array('name' => array ('type' => 'string'),
						'email' => array ('type' => 'string', 'default' => ''),
						'alternate_email' => array ('type' => 'string', 'default' => ''),
						'password' => array ('type' => 'string', 'required' => true),
						'account_type' => array ('type' => 'string'),
						'external_social_id' => array ('type' => 'string', 'required' => false, 'default'=>""),
						'social_additional_attributes' => array('type'=>'array', 'default'=>array())
				),
				"Register user",
				'POST',
				true,
				false);

		self::expose_function('user.create3',
				"user/create3",
				array('name' => array ('type' => 'string'),
						'email' => array ('type' => 'string', 'default' => ''),
						'alternate_email' => array ('type' => 'string', 'default' => ''),
						'password' => array ('type' => 'string', 'required' => true),
						'account_type' => array ('type' => 'string'),
						'external_social_id' => array ('type' => 'string', 'required' => false, 'default'=>""),
						'social_additional_attributes' => array('type'=>'array', 'default'=>array()),
						'extra_param' => array ('type' => 'string', 'default' => ''),
				),
				"Register user",
				'POST',
				true,
				false);

		self::expose_function('user.create4',
				"user/create4",
				array('name' => array ('type' => 'string'),
						'email' => array ('type' => 'string', 'default' => ''),
						'alternate_email' => array ('type' => 'string', 'default' => ''),
						'password' => array ('type' => 'string', 'required' => true),
						'account_type' => array ('type' => 'string'),
						'external_social_id' => array ('type' => 'string', 'required' => false, 'default'=>""),
						'social_additional_attributes' => array('type'=>'array', 'default'=>array()),
						'extra_param' => array ('type' => 'string', 'default' => ''),
				),
				"Register user",
				'POST',
				true,
				false);

		self::expose_function('user.IsUserValidated',
				"user/isUserValidated",
				array(
						'email' => array ('type' => 'string', 'default' => ''),
				),
				"Is User Validated?",
				'POST',
				true,
				false);

		self::expose_function('user.ResendValidationEmail',
				"user/resendValidationEmail",
				array(
						'email' => array ('type' => 'string', 'default' => ''),
				),
				"Resend Validation Email",
				'POST',
				true,
				false);

		self::expose_function('user.set_account_details',
				"user/SetAccountDetails",
				array('email' => array ('type' => 'string','required' => true),
						'profile' => array ('type' => 'array'),),
				"Set account detals",
				'POST',
				true,
				true);

		self::expose_function('user.get_country_code',
				"user/getCountryCode",
				array('country_name' => array ('type' => 'string', 'required' => false),
				),
				"Get Country Code Details",
				'POST',
				true,
				false);

		self::expose_function('user.set_attributes',
				"user/SetAttributes",
				array('auth_token' => array ('type' => 'string', 'required' => true),
						'profile' => array ('type' => 'array'),),

				'Set attributes like device type and version.',
				'POST',
				true,
				true);

		self::expose_function('user.get_attributes',
				"user/GetAttributes",
				array(
						'auth_token' => array ('type' => 'string', 'required' => true),
				),
				'Get attributes like device type and version.',
				'POST',
				true,
				true);

		self::expose_function('user.get_user_account_details',
				"user/GetAccountDetails",
				array('email' => array ('type' => 'string','required' => false),
				),
				"Get user profile labels",
				'POST',
				true,
				true);

		self::expose_function('user.get_associated_robots',
				"user/GetAssociatedRobot",
				array('email' => array ('type' => 'string','required' => false),
				),
				"Get user associated robots",
				'POST',
				true,
				true);

		self::expose_function('user.update_auth_token_expiry',
				"user/UpdateAuthTokenExpiry",
				array(),
				"Update auth token expiry of User",
				'POST',
				true,
				false);

		self::expose_function('user.logout_auth_token',
				"user/LogoutAuthToken",
				array('email' => array ('type' => 'string','required' => false),
				),
				"Log out auth token of User",
				'POST',
				true,
				true);

		self::expose_function('user.disassociate_robot',
				"user/disAssociateRobot",
				array('email' => array ('type' => 'string', 'required' => true),
						'serial_number' => array ('type' => 'string', 'required' => false)
				),
				"Disassociate user from robots",
				'POST',
				true,
				false);

		self::expose_function('user.get_error_code',
				"user/getErrorCode",
				array('error_code' => array ('type' => 'string', 'required' => false),
				),
				"Error message detail from error code",
				'POST',
				true,
				false);

		self::expose_function('robot.create',
				"robot/create",
				array('serial_number' => array ('type' => 'string', 'required' => true),
						'name' => array ('type' => 'string',  'required' => false),
				),
				"Create Robot",
				'POST',
				true,
				false);

		self::expose_function('robot.create2',
				"robot/create2",
				array('serial_number' => array ('type' => 'string', 'required' => true),
						'name' => array ('type' => 'string',  'required' => false),
						'robot_type' => array ('type' => 'string', 'required' => false),
				),
				"Create Robot 2",
				'POST',
				true,
				false);

		self::expose_function('robot.is_online',
				"robot/isOnline",
				array('serial_number' => array ('type' => 'string', 'required' => true),
				),
				"check if robot is online",
				'POST',
				true,
				false);

		self::expose_function('robot.get_robot_presence_status',
				"robot/getRobotPresenceStatus",
				array('serial_number' => array ('type' => 'string', 'required' => true),
				),
				"Get Robot Presence Status",
				'POST',
				true,
				false);

		self::expose_function('robot.is_robot_online_virtual',
				"robot/isRobotOnlineVirtual",
				array('serial_number' => array ('type' => 'string', 'required' => true),
				),
				"Is Robot Online Virtual",
				'POST',
				true,
				false);

		self::expose_function('robot.ping_from_robot',
				"robot/pingFromRobot",
				array(
						'serial_number' => array ('type' => 'string', 'required' => true),
						'status' => array ('type' => 'string', 'default'=>''),
				),
				"Get Robot Presence Status",
				'POST',
				true,
				false);

		self::expose_function('robot.get_robot_type_metadata_using_type',
				"robot/getRobotTypeMetadataUsingType",
				array(
						'robot_type' => array ('type' => 'string', 'required' => true),
				),
				"Get Robot Type Metadata Using Robot Type",
				'POST',
				true,
				false);

		self::expose_function('robot.get_robot_type_metadata_using_id',
				"robot/getRobotTypeMetadataUsingId",
				array(
						'serial_number' => array ('type' => 'string', 'required' => true),
				),
				"Get Robot Type Metadata Using Robot Id",
				'POST',
				true,
				false);

		self::expose_function('robot.set_robot_configuration',
				"robot/setRobotConfiguration",
				array(
						'serial_number' => array ('type' => 'string', 'required' => true),
						'sleep_time' => array ('type' => 'string', 'required' => true),
						'wakeup_time' => array ('type' => 'string', 'required' => true),
						'config_key_value' => array ('type' => 'array', 'default' => array()),
				),
				"Set Robot Configuration",
				'POST',
				true,
				false);

		self::expose_function('robot.set_robot_configuration2',
				"robot/setRobotConfiguration2",
				array(
						'serial_number' => array ('type' => 'string', 'required' => true),
						'sleep_time' => array ('type' => 'string', 'required' => true),
						'wakeup_time' => array ('type' => 'string', 'required' => true),
						'robot_type' => array ('type' => 'string', 'required' => false),
						'config_key_value' => array ('type' => 'array', 'default' => array()),
				),
				"Set Robot Configuration 2",
				'POST',
				true,
				false);

		self::expose_function('robot.get_robot_configuration',
				"robot/getRobotConfiguration",
				array(
						'serial_number' => array ('type' => 'string', 'required' => true),
				),
				"Get Robot Configuration",
				'POST',
				true,
				false);

		self::expose_function('robot.clear_robot_association',
				"robot/ClearRobotAssociation",
				array(
						'serial_number' => array ('type' => 'string', 'required' => true),
						'email' => array ('type' => 'string', 'required' => true),
						'is_delete' => array('type' => 'string', 'required'=> true),
				),
				"Clear Robot Association",
				'POST',
				true,
				false);

		self::expose_function('robot.request_link_code',
				"robot/RequestLinkCode",
				array(
						'serial_number' => array ('type' => 'string', 'required' => true),
				),
				"request_link_code",
				'POST',
				true,
				false);

		self::expose_function('robot.initiate_link_to_robot',
				"robot/InitiateLinkToRobot",
				array(
						'email' => array ('type' => 'string', 'required' => true),
						'linking_code' => array ('type' => 'string', 'required' => true),
				),
				"Initiate Link To Robot",
				'POST',
				true,
				false);

		self::expose_function('robot.link_to_robot',
				"robot/LinkToRobot",
				array(
						'email' => array ('type' => 'string', 'required' => true),
						'linking_code' => array ('type' => 'string', 'required' => true),
				),
				"Link To Robot",
				'POST',
				true,
				false);

		self::expose_function('robot.confirm_linking',
				"robot/ConfirmLinking",
				array(
						'serial_number' => array ('type' => 'string', 'required' => true),
						'linking_code' => array ('type' => 'string', 'required' => true),
				),
				"Confirm Linking",
				'POST',
				true,
				false);

		self::expose_function('robot.reject_linking',
				"robot/RejectLinking",
				array(
						'serial_number' => array ('type' => 'string', 'required' => true),
						'linking_code' => array ('type' => 'string', 'required' => true),
				),
				"Reject Linking",
				'POST',
				true,
				false);

		self::expose_function('robot.cancel_linking',
				"robot/CancelLinking",
				array(
						'serial_number' => array ('type' => 'string', 'required' => true),
						//                                    'token' => array ('type' => 'string', 'required' => true),
				),
				"Cancel Linking",
				'POST',
				true,
				false);

		self::expose_function('robot.health_check',
				"robot/AliveRobot",
				array(
						'serial_number' => array ('type' => 'string', 'required' => true),
				),
				"Alive Robot",
				'POST',
				true,
				false);

		self::expose_function('message.send_xmpp_message_to_robot',
				"message/SendXmppMessageToRobot",
				array(
						'user_id' => array ('type' => 'string', 'required' => true),
						'serial_number' => array ('type' => 'string', 'required' => true),
						'message' => array ('type' => 'string', 'required' => true),
				),
				"send message to robot",
				'POST',
				true,
				false);

		self::expose_function('message.send_message_to_associated_users',
				"message/SendMessageToAssociatedUsers",
				array(
						'serial_number' => array ('type' => 'string', 'required' => true),
						'message_type' => array ('type' => 'string', 'required' => true),
						'message' => array ('type' => 'string', 'required' => true),
				),
				"send message to associated users",
				'POST',
				true,
				false);

		self::expose_function('message.send_xmpp_message_to_all_associated_users2',
				"message/sendXMPPMessageToAllAssociatedUsers2",
				array(
						'serial_number' => array ('type' => 'string', 'required' => true),
						'only_online' => array ('type' => 'string', 'default' => ''),
						'message' => array ('type' => 'string', 'required' => true),
				),
				"send message to associated users",
				'POST',
				true,
				false);

		self::expose_function('message.send_notification_to_given_registration_ids',
				"message/SendNotificationToGivenRegistrationIds",
				array(
						'registration_ids' => array ('type' => 'array', 'default'=>array()),
						'message' => array ('type' => 'string', 'required' => true),
				),
				"send notification to associated users",
				'POST',
				true,
				false);

		self::expose_function('message.send_notification_to_all_users_of_robot2',
				"message/SendNotificationToAllUsersOfRobot2",
				array(
						'serial_number' => array ('type' => 'array', 'required' => true),
						'message' => array ('type' => 'string', 'required' => true),
				),
				"send notification to associated users",
				'POST',
				true,
				false);

		self::expose_function('message.send_notification_to_given_emails',
				"message/SendNotificationToGivenEmails",
				array(
						'emails' => array ('type' => 'array', 'default'=>array()),
						'message' => array ('type' => 'string', 'default'=>'', 'required' => true),
				),
				"send notification to associated users",
				'POST',
				true,
				false);

		self::expose_function('message.notification_registration',
				"message/notificationRegistration",
				array(
						'user_email' => array ('type' => 'string', 'required' => true),
						'registration_id' => array ('type' => 'string', 'required' => true),
						'device_type' => array ('type' => 'string', 'required' => true),
						'application_id' => array ('type' => 'string', 'required' => false),
						'notification_server_type' => array ('type' => 'string', 'required' => false),
				),
				"Store Registration Id To Send Notification",
				'POST',
				true,
				false);

		self::expose_function('message.notification_unregistration',
				"message/notificationUnRegistration",
				array(
						'registration_id' => array ('type' => 'string', 'required' => true),
				),
				"Store Registration Id To Send Notification",
				'POST',
				true,
				false);

		self::expose_function('message.set_user_push_notification_options',
				"message/setUserPushNotificationOptions",
				array(
						'email' => array ('type' => 'string', 'required' => true),
						'json_object' => array ('type' => 'string', 'required' => true),
				),
				"Set User Push Notification Options",
				'POST',
				true,
				false);

		self::expose_function('message.get_user_push_notification_options',
				"message/getUserPushNotificationOptions",
				array(
						'email' => array ('type' => 'string', 'required' => true),
				),
				"Get User Push Notification Options",
				'POST',
				true,
				false);

		self::expose_function('robot.set_profile_details3',
				"robot/SetProfileDetails3",
				array('serial_number' => array ('type' => 'string','required' => true),
						'source_serial_number' => array ('type' => 'string','default' => ''),
						'source_smartapp_id' => array ('type' => 'string','default' => ''),
						'cause_agent_id' => array ('type' => 'string','required' => true),
						'value_extra' => array ('type' => 'string','default' => ''),
						'notification_flag' => array ('type' => 'string','default' => '1'),
						'profile' => array ('type' => 'array'),),
				"Set profile details 3",
				'POST',
				false,
				false);

		self::expose_function('robot.get_profile_details',
				"robot/GetProfileDetails",
				array(
						'serial_number' => array ('type' => 'string','required' => true),
						'key' => array ('type' => 'string','default' => ''),
				),
				"Get profile details",
				'POST',
				false,
				false);

		self::expose_function('robot.get_profile_details2',
				"robot/GetProfileDetails2",
				array(
						'serial_number' => array ('type' => 'string','required' => true),
						'key' => array ('type' => 'string','default' => ''),
				),
				"Get profile details",
				'POST',
				false,
				false);

		self::expose_function('robot.delete_robot_profile_key2',
				"robot/DeleteRobotProfileKey2",
				array(
						'serial_number' => array ('type' => 'string','required' => true),
						'key' => array ('type' => 'string','required' => true),
						'cause_agent_id' => array ('type' => 'string','required' => true),
						'source_serial_number' => array ('type' => 'string','default' => ''),
						'source_smartapp_id' => array ('type' => 'string','default' => ''),
						'notification_flag' => array ('type' => 'string','default' => '1'),
				),
				"Delete robot profile key 2",
				'POST',
				false,
				false);

		self::expose_function('robot.get_details',
				"robot/getDetails",
				array('serial_number' => array ('type' => 'string', 'required' => true)),
				"Pass on a robot serial number and return robot details",
				'POST',
				true,
				false);

		self::expose_function('robot.delete',
				"robot/delete",
				array('serial_number' => array ('type' => 'string', 'required' => true)),
				"Pass on a robot serial number to delete robot",
				'POST',
				true,
				false);

		self::expose_function('robot.get_associated_users',
				"robot/GetAssociatedUser",
				array('serial_number' => array ('type' => 'string', 'required' => true)),
				"Pass on a robot serial number and return array of associated users",
				'POST',
				true,
				false);

		self::expose_function('robot.set_user',
				"robot/setUsers",
				array('email' => array ('type' => 'string', 'required' => true),
						'serial_number' => array ('type' => 'string', 'required' => true),),
				"User robot setting",
				'POST',
				true,
				false);

		self::expose_function('robot.disassociate_user',
				"robot/disAssociateUser",
				array('serial_number' => array ('type' => 'string', 'required' => true),
						'email' => array ('type' => 'string', 'required' => false)),
				"Disassociate robot from users",
				'POST',
				true,
				false);

		self::expose_function("user.login", "user/login", array(
				'username' => array ('type' => 'string'),
				'password' => array ('type' => 'string', 'required' => true, 'default' => "9999"),),
				"test login expose", "POST", true, false);

		self::expose_function('robotschedule.post_data',
				"robotSchedule/PostData",
				array('serial_number' => array ('type' => 'string', 'required' => true),
						'schedule_type' => array ('type' => 'string', 'required' => true),
						'xml_data' => array ('type' => 'string', 'required' => false),
						'blob_data' => array ('type' => 'string', 'required' => false),
						'encoded_blob_data' => array ('type' => 'string', 'required' => false)),

				"Pass on a robot serial number, schedule_type, xml data, blob data",
				'POST',
				true,
				false);

		self::expose_function('robotschedule.get_schedules',
				"robotSchedule/GetSchedules",
				array('serial_number' => array ('type' => 'string', 'required' => true),
				),
				"Pass on a robot serial number",
				'POST',
				true,
				false);

		self::expose_function('robotschedule.get_data',
				"robotSchedule/GetData",
				array('robot_schedule_id' => array ('type' => 'string','required' => true),
				),
				"Pass on a robot schedule id ",
				'POST',
				true,
				false);

		self::expose_function('robotschedule.update_data',
				"robotSchedule/UpdateData",
				array('robot_schedule_id' => array ('type' => 'string', 'required' => true),
						'schedule_type' => array ('type' => 'string','required' => false),
						'xml_data_version' => array ('type' => 'string', 'required' => false),
						'xml_data' => array ('type' => 'string', 'required' => false),
						'blob_data_version' => array ('type' => 'string', 'required' => false),
						'blob_data' => array ('type' => 'string', 'required' => false),
						'encoded_blob_data' => array ('type' => 'string', 'required' => false)),

				"Pass on a robot robot schedule id,schedule type,  xml data version, xml data, blob data version, blob data",
				'POST',
				true,
				false);

		self::expose_function('robotschedule.delete_data',
				"robotSchedule/DeleteScheduleData",
				array('robot_schedule_id' => array ('type' => 'string','required' => true),
				),
				"Pass on a robot schedule id ",
				'POST',
				true,
				false);

		self::expose_function('robotschedule.get_schedule_based_on_type',
				"robotSchedule/getScheduleBasedOnType",
				array(
						'robot_serial_number' => array ('type' => 'string','required' => true),
						'schedule_type' => array ('type' => 'string','required' => true),
				),
				"Get Schedule Based On Type",
				'POST',
				true,
				false);

		self::expose_function('robot.post_custom_data',
				"robotCustom/PostData",
				array('serial_number' => array ('type' => 'string', 'required' => true),
						'encoded_blob_data' => array ('type' => 'array','required' => false),
						'blob_data' => array ('type' => 'array','required' => false),
				),
				"Set robot custom data",
				'POST',
				true,
				false);

		self::expose_function('robot.get_customs',
				"robotCustom/GetCustoms",
				array('serial_number' => array ('type' => 'string', 'required' => true),
				),
				"Pass on a robot serial number",
				'POST',
				true,
				false);

		self::expose_function('robot.get_custom_data',
				"robotCustom/GetData",
				array('robot_custom_id' => array ('type' => 'string', 'required' => true),
				),
				"Pass on a robot custom id ",
				'POST',
				true,
				false);

		self::expose_function('robot.update_custom_data',
				"robotCustom/UpdateData",
				array('robot_custom_id' => array ('type' => 'string', 'required' => true),
						'blob_data_version' => array ('type' => 'string', 'required' => false),
						'encoded_blob_data' => array ('type' => 'array','required' => false),
						'blob_data' => array ('type' => 'array','required' => false),
				),
				"Pass on a robot custom id, data version, encoded_blob_data, blob_data",
				'POST',
				true,
				false);

		self::expose_function('robot.delete_custom_data',
				"robotCustom/DeleteData",
				array('robot_custom_id' => array ('type' => 'string', 'required' => true),
				),
				"Delete robot custom data",
				'POST',
				true,
				false);

		self::expose_function('user.creatuserthree',
				"user/creatuserthree",
				array('user_name' => array ('type' => 'string', 'required' => false), 'email' => array('type'=> 'string', 'required' => false)
				),
				"User creation method",
				'POST',
				true,
				false);

		// Get parameter variables
		$method = Yii::app()->request->getParam('method', '');

		// this will throw an exception if authentication fails
		self::authenticate_method($method);
		self::execute_method($method);
	}

	/**
	 * It will first check for expose of methods then check for method callable
	 * @param string $method The method
	 */
	protected function execute_method($method){
		// method must be exposed
		if (!isset($this->API_METHODS[$method])) {
			$msg = self::yii_api_echo('APIException:MethodCallNotImplemented', array($method));
			self::terminate(-1, $msg, APIConstant::METHOD_CALL_NOT_IMPLEMENTED);
			//throw new APIException($msg);
		}

		// function must be callable
		if (!(isset($this->API_METHODS[$method]["function"]))) {

			$msg = self::yii_api_echo('APIException:FunctionDoesNotExist', array($method));
			self::terminate(-1, $msg, APIConstant::METHOD_CALL_NOT_IMPLEMENTED);
			//throw new APIException($msg);
		}

		// check http call method
		if (strcmp(self::get_call_method(), $this->API_METHODS[$method]["call_method"]) != 0) {
			$msg = self::yii_api_echo('CallException:InvalidCallMethod', array($method,
					$this->API_METHODS[$method]["call_method"]));
			self::terminate(-1, $msg, APIConstant::METHOD_CALL_NOT_IMPLEMENTED);
			//throw new CallException($msg);
		}

		$parameters = self::get_parameters_for_method($method);

		if (self::verify_parameters($method, $parameters) == false) {
			// if verify_parameters fails, it throws exception which is not caught here
		}

		//IF EVERTHING IS FINE THEN IT FORWARD TO THE ACTUAL CONTROLLER
		CController::forward("/api/".$this->API_METHODS[$method]['function']);
	}

	/**
	 * This function analyses all expected parameters for a given method
	 *
	 * This function sanitizes the input parameters and returns them in
	 * an associated array.
	 *
	 * @param string $method The method
	 *
	 * @return array containing parameters as key => value
	 * @access protected
	 */
	protected function get_parameters_for_method($method) {
		$sanitised = array();

		// if there are parameters, sanitize them
		if (isset($this->API_METHODS[$method]['parameters'])) {
			foreach ($this->API_METHODS[$method]['parameters'] as $k => $v) {
				$param = self::get_input($k); // Make things go through the sanitiser
				if ($param !== '' && $param !== null) {
					$sanitised[$k] = $param;
				} else {
					// parameter wasn't passed so check for default
					if (isset($v['default'])) {
						$sanitised[$k] = $v['default'];
						$_REQUEST[$k] = $v['default'];
					}
				}
			}
		}

		return $sanitised;
	}

	/**
	 * Get some input from variables passed submitted through GET or POST.
	 *
	 * If using any data obtained from get_input() in a web page, please be aware that
	 * it is a possible vector for a reflected XSS attack. If you are expecting an
	 * integer, cast it to an int. If it is a string, escape quotes.
	 *
	 * @param string $variable      The variable name we want.
	 * @param mixed  $default       A default value for the variable if it is not found.
	 *
	 * @return mixed
	 */
	protected function get_input($variable, $default = NULL) {
		$result = $default;

		if (isset($_REQUEST[$variable])) {
			if (is_array($_REQUEST[$variable])) {
				$result = $_REQUEST[$variable];
			} else {
				$result = trim($_REQUEST[$variable]);
			}
		}

		return $result;
	}

	/**
	 * Verify that the required parameters are present
	 *
	 * @param string $method     Method name
	 * @param array  $parameters List of expected parameters
	 *
	 * @return true on success or exception
	 * @throws APIException
	 * @access protected
	 */
	protected function verify_parameters($method, $parameters) {
		// are there any parameters for this method
		if (!(isset($this->API_METHODS[$method]["parameters"]))) {
			return true; // no so return
		}

		// check that the parameters were registered correctly and all required ones are there
		foreach ($this->API_METHODS[$method]['parameters'] as $key => $value) {
			// this tests the expose structure: must be array to describe parameter and type must be defined
			if (!is_array($value) || !isset($value['type'])) {

				$msg = self::yii_api_echo('APIException:InvalidParameter', array($key, $method));
				self::terminate(-1, $msg, APIConstant::PARAMETER_MISSING);
				//throw new APIException($msg);
			}

			// Check that the variable is present in the request if required
			if ($value['required'] && !array_key_exists($key, $parameters)) {
				$msg = self::yii_api_echo('APIException:MissingParameterInMethod', array($key, $method));
				self::terminate(-1, $msg, APIConstant::PARAMETER_MISSING);
				//throw new APIException($msg);
			}
		}

		return true;
	}

	/**
	 * Get the request method.
	 *
	 * @return string HTTP request method
	 * @access protected
	 */
	protected function get_call_method() {
		return $_SERVER['REQUEST_METHOD'];
	}

	/**
	 * Check that the method call has the proper API and user authentication
	 *
	 * @param string $method The api name that was exposed
	 *
	 * @return true or throws an exception
	 * @throws APIException
	 * @access protected
	 */
	protected function authenticate_method($method) {

		// method must be exposed
		if (!isset($this->API_METHODS[$method])) {
			$msg = self::yii_api_echo('APIException:MethodCallNotImplemented', array($method));
			self::terminate(-1, $msg, APIConstant::METHOD_CALL_NOT_IMPLEMENTED);
			//throw new APIException($msg);
		}

		// check API authentication if required
		if ($this->API_METHODS[$method]["require_api_auth"] == true) {
			if (AppCore::authenticate_api_key() == false) {
				$msg = self::yii_api_echo('APIException:APIAuthenticationFailed');
				self::terminate(-1, $msg, APIConstant::API_KEY_MISSING_OR_INCORRECT);
				//throw new APIException($msg);
			}
		}

		// check if user authentication is required
		if ($this->API_METHODS[$method]["require_user_auth"] == true) {
			if (AppCore::authenticate_user_token() == false) {
				$msg = self::yii_api_echo('APIException:UserAuthenticationFailed');
				self::terminate(-1, $msg, APIConstant::AUTHENTICATION_FAILED);
				//throw new APIException($msg);
				//throw new APIException($user_pam->getFailureMessage());
			}
		}

		return true;
	}

	/**
	 * Expose a function as a services api call.
	 *
	 * Limitations: Currently cannot expose functions which expect objects.
	 * It also cannot handle arrays of bools or arrays of arrays.
	 * Also, input will be filtered to protect against XSS attacks through the API.
	 *
	 * @param string $method            The api name to expose - for example "myapi.dosomething"
	 * @param string $function          Your function callback.
	 * @param array  $parameters        (optional) List of parameters in the same order as in
	 *                                  your function. Default values may be set for parameters which
	 *                                  allow REST api users flexibility in what parameters are passed.
	 *                                  Generally, optional parameters should be after required
	 *                                  parameters.
	 *
	 *                                  This array should be in the format
	 *                                    "variable" = array (
	 *                                  					type => 'int' | 'bool' | 'float' | 'string' | 'array'
	 *                                  					required => true (default) | false
	 *                                  					default => value (optional)
	 *                                  	 )
	 * @param string $description       (optional) human readable description of the function.
	 * @param string $call_method       (optional) Define what http method must be used for
	 *                                  this function. Default: GET
	 * @param bool   $require_api_auth  (optional) (default is false) Does this method
	 *                                  require API authorization? (example: API key)
	 * @param bool   $require_user_auth (optional) (default is false) Does this method
	 *                                  require user authorization?
	 *
	 * @return bool
	 */
	protected  function expose_function($method, $function, array $parameters = NULL, $description = "",
	$call_method = "GET", $require_api_auth = false, $require_user_auth = false) {

		if (($method == "") || ($function == "")) {
			$msg = self::yii_api_echo('InvalidParameterException:APIMethodOrFunctionNotSet');
			self::terminate(-1, $msg, '');
			//throw new InvalidParameterException($msg);
		}

		// does not check whether this method has already been exposed - good idea?
		$this->API_METHODS[$method] = array();

		$this->API_METHODS[$method]["description"] = $description;

		// does not check whether callable - done in execute_method()
		$this->API_METHODS[$method]["function"] = $function;

		if ($parameters != NULL) {
			if (!is_array($parameters)) {
				$msg = self::yii_api_echo('InvalidParameterException:APIParametersArrayStructure', array($method));
				throw new InvalidParameterException($msg);
			}

			// catch common mistake of not setting up param array correctly
			$first = current($parameters);
			if (!is_array($first)) {
				$msg = self::yii_api_echo('InvalidParameterException:APIParametersArrayStructure', array($method));
				throw new InvalidParameterException($msg);
			}
		}

		if ($parameters != NULL) {
			// ensure the required flag is set correctly in default case for each parameter
			foreach ($parameters as $key => $value) {
				// check if 'required' was specified - if not, make it true
				if (!array_key_exists('required', $value)) {
					$parameters[$key]['required'] = true;
				}
			}
			$this->API_METHODS[$method]["parameters"] = $parameters;
		}

		$call_method = strtoupper($call_method);
		switch ($call_method) {
			case 'POST' :
				$this->API_METHODS[$method]["call_method"] = 'POST';
				break;
			case 'GET' :
				$this->API_METHODS[$method]["call_method"] = 'GET';
				break;
			default :
				$msg = self::yii_api_echo('InvalidParameterException:UnrecognisedHttpMethod',
				array($call_method, $method));

				throw new InvalidParameterException($msg);
		}

		$this->API_METHODS[$method]["require_api_auth"] = $require_api_auth;

		$this->API_METHODS[$method]["require_user_auth"] = $require_user_auth;
		return true;
	}

	/**
	 * Unregister an API method
	 *
	 * @param string $method The api name that was exposed
	 *
	 * @return void
	 */
	function unexpose_function($method) {

		if (isset($this->API_METHODS[$method])) {
			unset($this->API_METHODS[$method]);
		}
	}

}
