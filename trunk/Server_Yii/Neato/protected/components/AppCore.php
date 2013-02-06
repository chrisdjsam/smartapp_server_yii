<?php

/**
 * Common helper functions used across Neato Application
 *
 */
class AppCore {

	/**
	 * Authenticate Api Key
	 * @return boolean
	 */

	public static function authenticate_api_key(){
		$response = false;
		$api_key = Yii::app()->request->getParam('api_key', false);
		if($api_key){
			$apiUserModel = ApiUser::model()->findByAttributes(array('api_key' => $api_key));
			if($apiUserModel != null){
				$response = true;
			}
		}
		return $response;
	}

	/**
	 * Create Auth Token for User
	 * @param int $user_id
	 * @return boolean
	 */
	public static function create_user_auth_token($user_id){
		$user_auth_token = sha1(microtime() . getmypid() . ApiUser::model()->count());
		$ts = time();
		$user_auth_tken_valid_till = Yii::app()->params['user-auth-token-valid-till'];
		$duration = $ts + 3600*24*$user_auth_tken_valid_till;

		$api_key = $_REQUEST['api_key'];
		$site_id = self::get_site_id();
		$api_user_model = ApiUser::model()->findByAttributes(array('api_key'=>$api_key));
		if(!is_null($api_user_model)){
			$site_id = $api_user_model->id_site;
		}

		$user_api_sessions = new UsersApiSession();
		$user_api_sessions->id_user = $user_id;
		$user_api_sessions->id_site = $site_id;
		$user_api_sessions->token = $user_auth_token;
		$user_api_sessions->expires = $duration;
		if ($user_api_sessions->save()){
			return $user_auth_token;
		}

		return false;
	}

	/**
	 * Authenticate user tokens
	 * @return boolean
	 */
	public static function authenticate_user_token(){
		$user_auth_token = Yii::app()->request->getParam('auth_token', '');
		if ($user_auth_token){
			$user_api_session = UsersApiSession::model()->findByAttributes(array('token' =>$user_auth_token));
			if ($user_api_session != null) {
				$user_expire_time = $user_api_session->expires;
				$ts = time();
				if ($user_expire_time >= $ts){
					return true;
				}
				return false;
			}
		}
		return false;
	}

	/**
	 *
	 * Authenticate user tokens for provided user id
	 * @param int $user_id
	 * @return boolean
	 */
	public static function authenticate_user_token_with_user($user_id){
		$user_auth_token = Yii::app()->request->getParam('auth_token', '');
		if ($user_auth_token){
			$user_api_session = UsersApiSession::model()->findByAttributes(array('token' =>$user_auth_token, 'id_user' => $user_id));
			if ($user_api_session != null) {
				$user_expire_time = $user_api_session->expires;
				$ts = time();
				if ($user_expire_time >= $ts){
					$user_auth_tken_valid_till = Yii::app()->params['user-auth-token-valid-till'];
					$duration = $ts + 3600*24*$user_auth_tken_valid_till;
					$user_api_session->expires = $duration;
					$user_api_session->save();
					return true;
				}
				return false;
			}
			return false;
		}
		return false;
	}

	/**
	 *
	 * return connected users
	 * @return array of chat IDs of all online users and robots.
	 */
	public static function getOnlineUsers(){
		$online_users_array = array();
		if(Yii::app()->params['isjabbersetup']){
			$cmd = "sudo ejabberdctl connected_users";
			$output = shell_exec($cmd);
			$output = strval($output);
			$online_users = array();
			$online_users = array_filter(explode("\n", $output));
			for($i=0; $i<count($online_users); $i++){
				$online_user_str = $online_users[$i];
				$pos = strpos($online_user_str, '/');
				if($pos){
					$online_user_str = substr($online_user_str,0, $pos);
				}
				$online_users_array[] = $online_user_str;
			}
		}else{
			$online_users_array = explode(',', Yii::app()->params['dummy-online-users']);
		}
		return $online_users_array;
	}


	/**
	 *
	 * send message to jabber.
	 *
	 */
	public static function send_chat_message($from, $to, $message){
		if(Yii::app()->params['isjabbersetup']){
			$message = escapeshellarg($message);
			$cmd = "sudo ejabberdctl send-message-chat ". $from . " " . $to . " " . $message;
			$output = shell_exec($cmd);
			$output = strval($output);
			return true;
		}else{
			return false;
		}
		
	}


	/**
	 *
	 * Create chat id and chat password for robot
	 * @return array
	 */
	public static function create_chat_user_for_robot(){
		$chat_details = array();
		$ts=time();

		$ejabberd_node = Yii::app()->params['ejabberdhost'];
		$chat_user = $ts."_robot";

		$chat_id = $chat_user . '@' . $ejabberd_node;
		$chat_pwd = $ts."_robot";

		$chat_details['jabber_status'] = true;
		$chat_details['chat_id'] = $chat_id;
		$chat_details['chat_pwd'] = $chat_pwd;

		if(Yii::app()->params['isjabbersetup']){
			$jabberRegisterString = 'sudo ejabberdctl register '.$chat_user.' '.$ejabberd_node.' '.$chat_pwd.' 2>&1';
			exec($jabberRegisterString, $output, $status);

			$success_string = strtolower("successfully registered");
			$message_string = isset($output[0])? $output[0] : '';
			$message_string = strtolower($message_string);

			if(strpos($message_string, $success_string) == -1){
				$chat_details['jabber_status'] = false;
			}
		}
		return $chat_details;
	}

	/**
	 *
	 * Create chat id and chat password for user
	 * @return array
	 */
	public static function create_chat_user_for_user(){
		$chat_details = array();
		$ts=time();

		$ejabberd_node = Yii::app()->params['ejabberdhost'];
		$chat_user = $ts."_user";

		$chat_id = $chat_user . '@' . $ejabberd_node;
		$chat_pwd = $ts."_user";

		$chat_details['jabber_status'] = true;
		$chat_details['chat_id'] = $chat_id;
		$chat_details['chat_pwd'] = $chat_pwd;

		if(Yii::app()->params['isjabbersetup']){
			$jabberRegisterString = 'sudo ejabberdctl register '.$chat_user.' '.$ejabberd_node.' '.$chat_pwd.' 2>&1';
			exec($jabberRegisterString, $output, $status);

			$success_string = strtolower("successfully registered");
			$message_string = isset($output[0])? $output[0] : '';
			$message_string = strtolower($message_string);

			if(strpos($message_string, $success_string) == -1){
				$chat_details['jabber_status'] = false;
			}
		}
		return $chat_details;
	}

	/**
	 *
	 * Delete chat user
	 * @return array
	 */
	public static function delete_chat_user($chat_user){
		$chat_details = array();
		$ejabberd_node = Yii::app()->params['ejabberdhost'];
		$chat_user = str_replace('@' . $ejabberd_node,"",$chat_user);

		$chat_details['jabber_status'] = true;
		if(Yii::app()->params['isjabbersetup']){
			$jabberRegisterString = 'sudo ejabberdctl unregister '.$chat_user.' '.$ejabberd_node. ' 2>&1';
			exec($jabberRegisterString, $output, $status);
		}
		return $chat_details;
	}

	/**
	 * Given a message key, returns an appropriately translated full-text string
	 *
	 * @param string $message_key The short message code
	 * @param array  $args        An array of arguments to pass through vsprintf().
	 * @param string $language    Optionally, the standard language code
	 *                            (defaults to site/user default, then English)
	 *
	 * @return string Either the translated string, the English string,
	 * or the original language string.
	 */
	public static function yii_echo($message_key, $args = array(), $language = "ln"){

		$english = array(
				/**
				 * Sites
		*/

				'item:site' => 'Sites',

				/**
				 * Sessions
		*/

				'login' => "Log In",
				'signuptext' => "<p>Create an account and register your Neato now.</p><p> We'll send you a FREE filter.</p>",
				'newusersignup' => "Register",
				'orconnectwith' => "Or connect with ",
				'loginok' => "You have been logged in.",
				'loginerror' => "We couldn't log you in. Please check your credentials and try again.",
				'login:empty' => "Email and password are required.",
				'login:baduser' => "Unable to load your user account.",
				'auth:nopams' => "Internal error. No user authentication method installed.",

				'logout' => "Log Out",
				'logoutok' => "You have been logged out.",
				'logouterror' => "We couldn't log you out. Please try again.",

				'loggedinrequired' => "You must be logged in to view that page.",
				'adminrequired' => "You must be an administrator to view that page.",
				'membershiprequired' => "You must be a member of this group to view that page.",


				/**
				 * Errors
		*/
				'exception:title' => "Fatal Error.",
				'exception:contact_admin' => 'An unrecoverable error has occurred and has been logged. Contact the site administrator with the following information:',

				'actionundefined' => "The requested action (%s) was not defined in the system.",
				'actionnotfound' => "The action file for %s was not found.",
				'actionloggedout' => "Sorry, you cannot perform this action while logged out.",
				'actionunauthorized' => 'You are unauthorized to perform this action',

				'InstallationException:SiteNotInstalled' => 'Unable to handle this request. This site '
				. ' is not configured or the database is down.',
				'InstallationException:MissingLibrary' => 'Could not load %s',
				'InstallationException:CannotLoadSettings' => 'Yii could not load the settings file. It does not exist or there is a file permissions issue.',

				'SecurityException:Codeblock' => "Denied access to execute privileged code block",
				'DatabaseException:WrongCredentials' => "Yii couldn't connect to the database using the given credentials. Check the settings file.",
				'DatabaseException:NoConnect' => "Yii couldn't select the database '%s', please check that the database is created and you have access to it.",
				'SecurityException:FunctionDenied' => "Access to privileged function '%s' is denied.",
				'DatabaseException:DBSetupIssues' => "There were a number of issues: ",
				'DatabaseException:ScriptNotFound' => "Yii couldn't find the requested database script at %s.",
				'DatabaseException:InvalidQuery' => "Invalid query",
				'DatabaseException:InvalidDBLink' => "Connection to database was lost.",

				'IOException:FailedToLoadGUID' => "Failed to load new %s from GUID:%d",
				'InvalidParameterException:NonYiiObject' => "Passing a non-YiiObject to an YiiObject constructor!",
				'InvalidParameterException:UnrecognisedValue' => "Unrecognised value passed to constuctor.",

				'InvalidClassException:NotValidYiiStar' => "GUID:%d is not a valid %s",

				'PluginException:MisconfiguredPlugin' => "%s (guid: %s) is a misconfigured plugin. It has been disabled. Please search the Yii wiki for possible causes (http://docs.Yii.org/wiki/).",
				'PluginException:CannotStart' => '%s (guid: %s) cannot start and has been deactivated.  Reason: %s',
				'PluginException:InvalidID' => "%s is an invalid plugin ID.",
				'PluginException:InvalidPath' => "%s is an invalid plugin path.",
				'PluginException:InvalidManifest' => 'Invalid manifest file for plugin %s',
				'PluginException:InvalidPlugin' => '%s is not a valid plugin.',
				'PluginException:InvalidPlugin:Details' => '%s is not a valid plugin: %s',
				'PluginException:NullInstantiated' => 'YiiPlugin cannot be null instantiated. You must pass a GUID, a plugin ID, or a full path.',

				'YiiPlugin:MissingID' => 'Missing plugin ID (guid %s)',
				'YiiPlugin:NoPluginPackagePackage' => 'Missing YiiPluginPackage for plugin ID %s (guid %s)',

				'YiiPluginPackage:InvalidPlugin:MissingFile' => 'The required file "%s" is missing.',
				'YiiPluginPackage:InvalidPlugin:InvalidDependency' => 'Its manifest contains an invalid dependency type "%s".',
				'YiiPluginPackage:InvalidPlugin:InvalidProvides' => 'Its manifest contains an invalid provides type "%s".',
				'YiiPluginPackage:InvalidPlugin:CircularDep' => 'There is an invalid %s dependency "%s" in plugin %s.  Plugins cannot conflict with or require something they provide!',

				'YiiPlugin:Exception:CannotIncludeFile' => 'Cannot include %s for plugin %s (guid: %s) at %s.',
				'YiiPlugin:Exception:CannotRegisterViews' => 'Cannot open views dir for plugin %s (guid: %s) at %s.',
				'YiiPlugin:Exception:CannotRegisterLanguages' => 'Cannot register languages for plugin %s (guid: %s) at %s.',
				'YiiPlugin:Exception:NoID' => 'No ID for plugin guid %s!',

				'PluginException:ParserError' => 'Error parsing manifest with API version %s in plugin %s.',
				'PluginException:NoAvailableParser' => 'Cannot find a parser for manifest API version %s in plugin %s.',
				'PluginException:ParserErrorMissingRequiredAttribute' => "Missing required '%s' attribute in manifest for plugin %s.",

				'YiiPlugin:Dependencies:Requires' => 'Requires',
				'YiiPlugin:Dependencies:Suggests' => 'Suggests',
				'YiiPlugin:Dependencies:Conflicts' => 'Conflicts',
				'YiiPlugin:Dependencies:Conflicted' => 'Conflicted',
				'YiiPlugin:Dependencies:Provides' => 'Provides',
				'YiiPlugin:Dependencies:Priority' => 'Priority',

				'YiiPlugin:Dependencies:Yii' => 'Yii version',
				'YiiPlugin:Dependencies:PhpExtension' => 'PHP extension: %s',
				'YiiPlugin:Dependencies:PhpIni' => 'PHP ini setting: %s',
				'YiiPlugin:Dependencies:Plugin' => 'Plugin: %s',
				'YiiPlugin:Dependencies:Priority:After' => 'After %s',
				'YiiPlugin:Dependencies:Priority:Before' => 'Before %s',
				'YiiPlugin:Dependencies:Priority:Uninstalled' => '%s is not installed',
				'YiiPlugin:Dependencies:Suggests:Unsatisfied' => 'Missing',

				'YiiPlugin:InvalidAndDeactivated' => '%s is an invalid plugin and has been deactivated.',

				'InvalidParameterException:NonYiiUser' => "Passing a non-YiiUser to an YiiUser constructor!",

				'InvalidParameterException:NonYiiSite' => "Passing a non-YiiSite to an YiiSite constructor!",

				'InvalidParameterException:NonYiiGroup' => "Passing a non-YiiGroup to an YiiGroup constructor!",

				'IOException:UnableToSaveNew' => "Unable to save new %s",

				'InvalidParameterException:GUIDNotForExport' => "GUID has not been specified during export, this should never happen.",
				'InvalidParameterException:NonArrayReturnValue' => "Entity serialisation function passed a non-array returnvalue parameter",

				'ConfigurationException:NoCachePath' => "Cache path set to nothing!",
				'IOException:NotDirectory' => "%s is not a directory.",

				'IOException:BaseEntitySaveFailed' => "Unable to save new object's base entity information!",
				'InvalidParameterException:UnexpectedODDClass' => "import() passed an unexpected ODD class",
				'InvalidParameterException:EntityTypeNotSet' => "Entity type must be set.",

				'ClassException:ClassnameNotClass' => "%s is not a %s.",
				'ClassNotFoundException:MissingClass' => "Class '%s' was not found, missing plugin?",
				'InstallationException:TypeNotSupported' => "Type %s is not supported. This indicates an error in your installation, most likely caused by an incomplete upgrade.",

				'ImportException:ImportFailed' => "Could not import element %d",
				'ImportException:ProblemSaving' => "There was a problem saving %s",
				'ImportException:NoGUID' => "New entity created but has no GUID, this should not happen.",

				'ImportException:GUIDNotFound' => "Entity '%d' could not be found.",
				'ImportException:ProblemUpdatingMeta' => "There was a problem updating '%s' on entity '%d'",

				'ExportException:NoSuchEntity' => "No such entity GUID:%d",

				'ImportException:NoODDElements' => "No OpenDD elements found in import data, import failed.",
				'ImportException:NotAllImported' => "Not all elements were imported.",

				'InvalidParameterException:UnrecognisedFileMode' => "Unrecognised file mode '%s'",
				'InvalidParameterException:MissingOwner' => "File %s (file guid:%d) (owner guid:%d) is missing an owner!",
				'IOException:CouldNotMake' => "Could not make %s",
				'IOException:MissingFileName' => "You must specify a name before opening a file.",
				'ClassNotFoundException:NotFoundNotSavedWithFile' => "Unable to load filestore class %s for file %u",
				'NotificationException:NoNotificationMethod' => "No notification method specified.",
				'NotificationException:NoHandlerFound' => "No handler found for '%s' or it was not callable.",
				'NotificationException:ErrorNotifyingGuid' => "There was an error while notifying %d",
				'NotificationException:NoEmailAddress' => "Could not get the email address for GUID:%d",
				'NotificationException:MissingParameter' => "Missing a required parameter, '%s'",

				'DatabaseException:WhereSetNonQuery' => "Where set contains non WhereQueryComponent",
				'DatabaseException:SelectFieldsMissing' => "Fields missing on a select style query",
				'DatabaseException:UnspecifiedQueryType' => "Unrecognised or unspecified query type.",
				'DatabaseException:NoTablesSpecified' => "No tables specified for query.",
				'DatabaseException:NoACL' => "No access control was provided on query",

				'InvalidParameterException:NoEntityFound' => "No entity found, it either doesn't exist or you don't have access to it.",

				'InvalidParameterException:GUIDNotFound' => "GUID:%s could not be found, or you can not access it.",
				'InvalidParameterException:IdNotExistForGUID' => "Sorry, '%s' does not exist for guid:%d",
				'InvalidParameterException:CanNotExportType' => "Sorry, I don't know how to export '%s'",
				'InvalidParameterException:NoDataFound' => "Could not find any data.",
				'InvalidParameterException:DoesNotBelong' => "Does not belong to entity.",
				'InvalidParameterException:DoesNotBelongOrRefer' => "Does not belong to entity or refer to entity.",
				'InvalidParameterException:MissingParameter' => "Missing parameter, you need to provide a GUID.",
				'InvalidParameterException:LibraryNotRegistered' => '%s is not a registered library',
				'InvalidParameterException:LibraryNotFound' => 'Could not load the %s library from %s',

				'APIException:ApiResultUnknown' => "API Result is of an unknown type, this should never happen.",
				'ConfigurationException:NoSiteID' => "No site ID has been specified.",
				'SecurityException:APIAccessDenied' => "Sorry, API access has been disabled by the administrator.",
				'SecurityException:NoAuthMethods' => "No authentication methods were found that could authenticate this API request.",
				'SecurityException:ForwardFailedToRedirect' => 'Redirect could not be issued due to headers already being sent. Halting execution for security. Search http://docs.Yii.org/ for more information.',
				'InvalidParameterException:APIMethodOrFunctionNotSet' => "Method or function not set in call in expose_method()",
				'InvalidParameterException:APIParametersArrayStructure' => "Parameters array structure is incorrect for call to expose method '%s'",
				'InvalidParameterException:UnrecognisedHttpMethod' => "Unrecognised http method %s for api method '%s'",
				'APIException:MissingParameterInMethod' => "Missing parameter %s in method %s",
				'APIException:ParameterNotArray' => "%s does not appear to be an array.",
				'APIException:UnrecognisedTypeCast' => "Unrecognised type in cast %s for variable '%s' in method '%s'",
				'APIException:InvalidParameter' => "Invalid parameter found for '%s' in method '%s'.",
				'APIException:FunctionParseError' => "%s(%s) has a parsing error.",
				'APIException:FunctionNoReturn' => "%s(%s) returned no value.",
				'APIException:APIAuthenticationFailed' => "Method call failed the API Authentication",
				'APIException:UserAuthenticationFailed' => "Method call failed the User Authentication",
				'SecurityException:AuthTokenExpired' => "Authentication token either missing, invalid or expired.",
				'CallException:InvalidCallMethod' => "%s must be called using '%s'",
				'APIException:MethodCallNotImplemented' => "Method call '%s' has not been implemented.",
				'APIException:FunctionDoesNotExist' => "Function for method '%s' is not callable",
				'APIException:AlgorithmNotSupported' => "Algorithm '%s' is not supported or has been disabled.",
				'ConfigurationException:CacheDirNotSet' => "Cache directory 'cache_path' not set.",
				'APIException:NotGetOrPost' => "Request method must be GET or POST",
				'APIException:MissingAPIKey' => "Missing API key",
				'APIException:BadAPIKey' => "Bad API key",
				'APIException:MissingHmac' => "Missing X-Yii-hmac header",
				'APIException:MissingHmacAlgo' => "Missing X-Yii-hmac-algo header",
				'APIException:MissingTime' => "Missing X-Yii-time header",
				'APIException:MissingNonce' => "Missing X-Yii-nonce header",
				'APIException:TemporalDrift' => "X-Yii-time is too far in the past or future. Epoch fail.",
				'APIException:NoQueryString' => "No data on the query string",
				'APIException:MissingPOSTHash' => "Missing X-Yii-posthash header",
				'APIException:MissingPOSTAlgo' => "Missing X-Yii-posthash_algo header",
				'APIException:MissingContentType' => "Missing content type for post data",
				'SecurityException:InvalidPostHash' => "POST data hash is invalid - Expected %s but got %s.",
				'SecurityException:DupePacket' => "Packet signature already seen.",
				'SecurityException:InvalidAPIKey' => "Invalid or missing API Key.",
				'NotImplementedException:CallMethodNotImplemented' => "Call method '%s' is currently not supported.",

				'NotImplementedException:XMLRPCMethodNotImplemented' => "XML-RPC method call '%s' not implemented.",
				'InvalidParameterException:UnexpectedReturnFormat' => "Call to method '%s' returned an unexpected result.",
				'CallException:NotRPCCall' => "Call does not appear to be a valid XML-RPC call",

				'PluginException:NoPluginName' => "The plugin name could not be found",

				'SecurityException:authenticationfailed' => "User could not be authenticated",

				'CronException:unknownperiod' => '%s is not a recognised period.',

				'SecurityException:deletedisablecurrentsite' => 'You can not delete or disable the site you are currently viewing!',

				'RegistrationException:EmptyPassword' => 'The password fields cannot be empty',
				'RegistrationException:PasswordMismatch' => 'Passwords must match',
				'LoginException:BannedUser' => 'You have been banned from this site and cannot log in',
				'LoginException:UsernameFailure' => 'We could not log you in. Please check your email and password.',
				'LoginException:PasswordFailure' => 'We could not log you in. Please check your email and password.',
				'LoginException:AccountLocked' => 'Your account has been locked for too many log in failures.',
				'LoginException:ChangePasswordFailure' => 'Failed current password check.',

				'deprecatedfunction' => 'Warning: This code uses the deprecated function \'%s\' and is not compatible with this version of Yii',

				'pageownerunavailable' => 'Warning: The page owner %d is not accessible!',
				'viewfailure' => 'There was an internal failure in the view %s',
				'changebookmark' => 'Please change your bookmark for this page',
				'noaccess' => 'You need to login to view this content or the content has been removed or you do not have permission to view it.',
				'error:missing_data' => 'There was some data missing in your request',

				'error:default' => 'Oops...something went wrong.',
				'error:404' => 'Sorry. We could not find the page that you requested.',

				/**
				 * API
	 */
				'system.api.list' => "List all available API calls on the system.",
				'auth.gettoken' => "This API call lets a user obtain a user authentication token which can be used for authenticating future API calls. Pass it as the parameter auth_token",

				/**
				 * User details
	 */

				'name' => "Name",
				'email' => "Email",
				'username' => "Username",
				'loginusername' => "Email",
				'password' => "Password",
				'passwordagain' => "Confirm Password",
				'admin_option' => "Make this user an admin?",

				/**
				 * Access
	 */

				'PRIVATE' => "Private",
				'LOGGED_IN' => "Logged in users",
				'PUBLIC' => "Public",
				'access:friends:label' => "Friends",
				'access' => "Access",
				'access:limited:label' => "Limited",
				'access:help' => "The access level",

				/**
				 * robot
	 */
				'addrobot:ok' => "You have successfully added a robot having serial number: <b> %s </b>.",
				'addrobotexist:ok' => "The Serial number <b> %s </b> is already registered.",
				'editrobot:ok' => "You have successfully updated a robot having serial number: <b> %s </b>.",
				'editrobotexist:ok' => "The Serial number <b> %s </b> is already registered.",
				'deleterobot:ok' => "You have successfully deleted a robot.",

				/**
				 * user
	 */
				'registeruser:ok' => "<b> %s </b> you have successfully registered.",
				'adduser:ok' => "You have successfully added a user named <b> %s </b>.",
				'edituser:ok' => "You have successfully updated a user named <b> %s </b>.",
				'deleteuser:ok' => "You have successfully deleted a user <b> %s </b>.",

				/**
				 *  Forgot password email
	 */
				'forgotpassword_subject' => "Neato-Robotics forgot password",
				'forgotpassword_message' => "<html><body>

				<div style='border: 1px solid #E3E3E3; padding:10px;'>

				<div style='width: auto; background-color: #3BB9FF; height: 20px; color: #ffffff; padding-left:10px; padding-top: 5px;'>
				<b>Welcome to Neato-Robotics</b></div>

				<br>HI %s,<br><br>

				Your email id is : %s<br><br>
				Your password is : %s<br><br>
				You can login  by clicking <a href='%s'>here</a>

				<br><br>

				Thank & Regards,

				<br><br>

				<b>Team Neato-Robotics</b>

				</div>

				</body>

				</html>",
				/**
				 *  Reset/Change password email
	 			*/
				'change_password_subject' => "Neato-Robotics change password",
				'resetpassword_subject' => "Neato-Robotics reset password",
				'changepassword_message' => "<html><body>

				<div style='border: 1px solid #E3E3E3; padding:10px;'>

				<div style='width: auto; background-color: #3BB9FF; height: 20px; color: #ffffff; padding-left:10px; padding-top: 5px;'>
				<b>Welcome to Neato-Robotics</b></div>

				<br>HI %s,<br><br>

				Your email id is : %s<br><br>
				Your new password is : %s<br><br>
				You can login  by clicking <a href='%s'>here</a>

				<br><br>

				Thank & Regards,

				<br><br>

				<b>Team Neato-Robotics</b>

				</div>

				</body>

				</html>",

				/**
				 *  New user email
	 */
				'welocome_subject' => "Welcome to Neato-Robotics",
				'welcome_message' => "<html><body>

				<div style='border: 1px solid #E3E3E3; padding:10px;'>

				<div style='width: auto; background-color: #3BB9FF; height: 20px; color: #ffffff; padding-left:10px; padding-top: 5px;'>
				<b>Welcome to Neato-Robotics</b></div>

				<br>HI %s,<br><br>
				Thanks for registering with Neato-Robotics.<br><br>

				Your email id is : %s<br><br>
				Your password is : %s<br><br>
				You can login  by clicking <a href='%s'>here</a>

				<br><br>

				Thank & Regards,

				<br><br>

				<b>Team Neato-Robotics</b>

				</div>

				</body>

				</html>",

		);

		if (isset($english[$message_key])) {
			$string = $english[$message_key];
		} else {
			$string = $message_key;
		}

		// only pass through if we have arguments to allow backward compatibility
		// with manual sprintf() calls.
		if ($args) {
			$string = vsprintf($string, $args);
		}

		return $string;
	}

	/**
	 *
	 * Store API call deatils
	 * @param  int $status
	 * @param array() $result
	 * @param array() $request_type
	 */
	public static function ws_log_details($status = 1, $result = array(), $request_type = 'post'){
		try{
			//check for enable web service logging flag
			if(Yii::app()->params['enablewebservicelogging']){
				$remote_address = isset($_SERVER['REMOTE_ADDR'])? $_SERVER['REMOTE_ADDR'] : 'not found';
				//$_REQUEST;
				if(!isset($_REQUEST['method'])){
					return false;
				}
				$method_name = $_REQUEST['method'];
				$api_key = $_REQUEST['api_key'];
				$response_type = $_REQUEST['response_type'];
				$handler_name = $_REQUEST['handler'];

				//unset extra data
				$request_data = $_REQUEST;
				unset($request_data['method']);
				unset($request_data['api_key']);
				unset($request_data['request']);
				unset($request_data['handler']);

				$serialized_request_data = serialize($request_data);
				$serialized_response_data = serialize($result);
				//start of code to store all $_server info
				$can_log_server = 1;
				if($can_log_server){
					$serialized_request_and_server_data['request'] = $_REQUEST;
					$serialized_request_and_server_data['server'] = $_SERVER;
					$serialized_request_data = serialize($serialized_request_and_server_data);
				}
				//end of code to store all $_server info

				$site_id = self::get_site_id();
				$api_user_model = ApiUser::model()->findByAttributes(array('api_key'=>$api_key));
				if(!is_null($api_user_model)){
					$site_id = $api_user_model->id_site;
				}


				$ws_logging_model = new WsLogging();
				$ws_logging_model->id_site = $site_id;
				$ws_logging_model->remote_address = $remote_address;
				$ws_logging_model->method_name = $method_name;
				$ws_logging_model->api_key = $api_key;
				$ws_logging_model->response_type = $response_type;
				$ws_logging_model->handler_name = $handler_name;
				$ws_logging_model->request_type = $request_type;
				$ws_logging_model->request_data = $serialized_request_data;
				$ws_logging_model->response_data = $serialized_response_data;
				$ws_logging_model->status = $status;

				$ws_logging_model->save();
			}
		} catch (Exception $e) {
			//do nothing
		}
	}

	/**
	 * Get site id (It is used by API calls)
	 * @return int
	 */
	public static function get_site_id(){
		return 1;
	}

	/**
	 * Check for map xml data.
	 * @param string $xml_data
	 * @return boolean
	 */
	public static function validate_map_xml_data($xml_data){
		return true;
	}

	/**
	 * Check for atlas xml data.
	 * @param string $xml_data
	 * @return boolean
	 */
	public static function validate_atlas_xml_data($xml_data){
		return true;
	}

	/**
	 * Check for robot map blob data.
	 * @param string $blob_data
	 * @return boolean
	 */
	public static function validate_map_blob_data($blob_data){
		return true;
	}

	/**
	 * Check for robot schedule xml data.
	 * @param string $xml_data
	 * @return boolean
	 */
	public static function validate_schedule_xml_data($xml_data){
		return true;
	}

	/**
	 * Check for robot schedule blob data.
	 * @param string $blob_data
	 * @return boolean
	 */
	public static function validate_schedule_blob_data($blob_data){
		return true;
	}

	/**
	 * Delete all data for provided robot map ids
	 * @param mixed $robot_map_id_arr
	 */
	public static function delete_robot_map_data($robot_map_id_arr){
		foreach ($robot_map_id_arr as $robot_map_id){
			$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
			$uploads_dir_for_robot_map = Yii::app()->getBasePath().$back . Yii::app()->params['robot-data-directory-name']. DIRECTORY_SEPARATOR . $robot_map_id;
			AppHelper::deleteDirectoryRecursively($uploads_dir_for_robot_map);
		}
	}

	/**
	 * Delete all data for provided robot schedule ids
	 * @param unknown $robot_schedule_id_arr
	 */
	public static function delete_robot_schedule_data($robot_schedule_id_arr){
		foreach ($robot_schedule_id_arr as $robot_schedule_id){
			$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
			$uploads_dir_for_robot_schedule = Yii::app()->getBasePath().$back . Yii::app()->params['robot-schedule_data-directory-name']. DIRECTORY_SEPARATOR . $robot_schedule_id;
			AppHelper::deleteDirectoryRecursively($uploads_dir_for_robot_schedule);
		}
	}

	/**
	 * Delete all atlas data for provided robot id.
	 * @param unknown $id_robot
	 */
	public static function delete_robot_atlas_data($id_robot){

		$back = DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
		$uploads_dir_for_robot = Yii::app()->getBasePath().$back . Yii::app()->params['robot-atlas-data-directory-name']. DIRECTORY_SEPARATOR . $id_robot;
		AppHelper::deleteDirectoryRecursively($uploads_dir_for_robot);

	}



}

?>
